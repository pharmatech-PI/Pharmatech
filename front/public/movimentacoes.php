<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    require_once __DIR__ . '/../../back/app/models/movimentacao.php';

    $movimentacaoModel = new Movimentacao($pdo);

    
    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_atual < 1) {
        $pagina_atual = 1;
    }

    $itens_por_pagina = 10; 

    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    $total_itens = $movimentacaoModel->contarTotal();

    $total_paginas = ceil($total_itens / $itens_por_pagina);

    $listaMovimentacoes = $movimentacaoModel->listarPaginado($itens_por_pagina, $offset);

    $resumo = $movimentacaoModel->obterResumo();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmatech</title>
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
                    <h1>Histórico de movimentações</h1>
                </header>
                <section class="cards card-movimentacao">
                    <div class="card">
                        <div class="card-flex">
                            <p>Entradas</p>
                        </div>
                        <span><?= $resumo['Entrada'] ?></span> 
                    </div>
                    <div class="card">
                        <div class="card-flex">
                            <p>Saídas</p>
                        </div>
                        <span><?= $resumo['Saída'] ?></span>
                    </div>
                    <div class="card">
                        <div class="card-flex">
                            <p>Vencimento</p>
                        </div>
                        <span><?= $resumo['Vencimento'] ?></span>
                    </div>
                </section>

                <section class="produtos">
                    <div class="produtos-container">
                        <div class="table-header">
                            <div class="input-search">
                            <img src="assets/icons/search.svg" alt="buscar">
                            <input type="text" id="busca-movimentacao" placeholder="Buscar Produtos ou Lote...">
                        </div>
                        </div>
        
                        <table class="table-movimentacao">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Produto</th>
                                    <th>Lote</th>
                                    <th>Validade</th>
                                    <th>Qtd.</th>
                                    <th>antes</th>
                                    <th>depois</th>
                                </tr>
                            </thead>

                            <tbody>
    <?php if (empty($listaMovimentacoes)): ?>
        <tr>
            <td colspan="10" style="text-align: center;">Nenhuma movimentação registrada.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($listaMovimentacoes as $mov): ?>
            <tr>
                <td><?= htmlspecialchars($mov['id']) ?></td>
                
                <td><?= date('d/m/Y', strtotime($mov['data'])) ?></td>
                
                <td>
                    <?php if (strtolower($mov['tipo']) === 'entrada'): ?>
                        <span class="status-badge green"><?= htmlspecialchars($mov['tipo']) ?></span>
                    <?php else: ?>
                        <span class="status-badge yellow"><?= htmlspecialchars($mov['tipo']) ?></span>
                    <?php endif; ?>
                </td>
                
                <td><?= htmlspecialchars($mov['produto_nome']) ?></td>         
                
                <td><?= htmlspecialchars($mov['lote']) ?></td>  

                <td>
                    <?php 
                    if (!empty($mov['validade']) && $mov['validade'] !== '0000-00-00') {
                        
                        $hoje = strtotime(date('Y-m-d'));
                        $dataValidade = strtotime($mov['validade']);
                        $limite30Dias = strtotime('+30 days');

                        $dataFormatada = date('d/m/Y', $dataValidade);

                        if ($dataValidade < $hoje) {
                            echo "<span style='color: #dc3545; font-weight: 700;'>{$dataFormatada} 🔴</span>";
                        } elseif ($dataValidade <= $limite30Dias) {
                            echo "<span style='color: #fd7e14; font-weight: 700;'>{$dataFormatada} 🟠</span>";
                        } else {
                            echo "<span>{$dataFormatada}</span>";
                        }

                    } else {
                        echo "-"; 
                    }
                    ?>
                </td>

                <td><?= htmlspecialchars($mov['quantidade']) ?></td>
                <td><?= htmlspecialchars($mov['qtd_antes']) ?></td>
                <td><?= htmlspecialchars($mov['qtd_depois']) ?></td>
                
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
                            
                        </table>
                        <?php include_once __DIR__ . "/../src/components/pagination.inc.php"; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script type="module" src="./js/main.js"></script>
</body>
</html>