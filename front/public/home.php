<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    
    require_once __DIR__ . '/../../back/app/models/dashboard.php';

    $dashboardModel = new Dashboard($pdo);
    
    $metricas = $dashboardModel->obterMetricas();
    $produtosRecentes = $dashboardModel->listarProdutosRecentes();


    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuario_id = $_SESSION['usuario_id'] ?? 0;

    $stmt = $pdo->prepare("SELECT nome_completo, email, telefone FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmatech - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <div class="layout-container">
        <?php include_once __DIR__ . "/../src/components/sidebar.inc.php"; ?>
        <div class="main-wrapper">
            <?php include_once __DIR__ . "/../src/components/header.inc.php"; ?>
            <main class="content-area">
                <header>
                    <?php
                        $nome_usuario = $dadosUsuario['nome_completo'] ?? 'Usuario';
                    ?>
                    <h1>Seja bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>!</h1>
                </header>
                
                <section class="cards">
                    <div class="card">
                        <div class="card-flex">
                            <p>Total de Produtos</p>
                            <img src="assets/icons/resetar.svg" alt="icone de resetar">
                        </div>
                        <span><?= $metricas['total_produtos'] ?></span>
                    </div>
                    
                    <div class="card">
                        <div class="card-flex">
                            <p>Estoque Baixo</p>
                            <img src="assets/icons/alta-prioridade.svg" alt="icone de alta prioridade">
                        </div>
                        <span><?= $metricas['estoque_baixo'] ?></span>
                    </div>
                    
                    <div class="card">
                        <div class="card-flex">
                            <p>Sem Estoque</p>
                            <img src="assets/icons/alta-prioridade.svg" alt="icone de alta prioridade">
                        </div>
                        <span style="color: #dc3545;"><?= $metricas['sem_estoque'] ?></span>
                    </div>
                    
                    <div class="card">
                        <div class="card-flex">
                            <p>Vencendo em 20 dias</p>
                            <img src="assets/icons/livro-marcado.svg" alt="icone de bookmark">
                        </div>
                        <span><?= $metricas['vencendo_20'] ?></span>
                    </div>
                    
                    <div class="card">
                        <div class="card-flex">
                            <p>Vencendo em 90 dias</p>
                            <img src="assets/icons/perigo.svg" alt="icone de perigo">
                        </div>
                        <span><?= $metricas['vencendo_90'] ?></span>
                    </div>
                </section>

                <section class="produtos">
                    <div class="produtos-container">
                        <div class="table-header">
                            <h2>Produtos Recentes</h2>
                        </div>
        
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>SKU</th>
                                    <th>Estoque</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($produtosRecentes)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center;">Nenhum produto cadastrado recentemente.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($produtosRecentes as $prod): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($prod['nome']) ?></td>
                                            <td><?= htmlspecialchars($prod['sku']) ?></td>
                                            <td><?= htmlspecialchars($prod['estoque']) ?></td>
                                            <td>
                                                <?php if ($prod['estoque'] == 0): ?>
                                                    <span class="status-badge red">Sem estoque</span>
                                                <?php elseif ($prod['estoque'] <= 20): ?>
                                                    <span class="status-badge yellow">Estoque baixo</span>
                                                <?php else: ?>
                                                    <span class="status-badge green">Em estoque</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script type="module" src="./js/main.js"></script>

    
</body>
</html>