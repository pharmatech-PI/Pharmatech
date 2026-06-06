<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    
    // Trazendo os Models para buscar os dados do banco
    require_once __DIR__ . '/../../back/app/models/produto.php';
    require_once __DIR__ . '/../../back/app/models/fornecedor.php';

    // Puxando as listas para preencher as caixas de seleção
    $produtoModel = new Produto($pdo);
    $listaProdutos = $produtoModel->listar();

    $fornecedorModel = new Fornecedor($pdo);
    $listaFornecedores = $fornecedorModel->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmatech</title>
    <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
    <div class="layout-container">
        <?php include_once __DIR__ . "/../src/components/sidebar.inc.php"; ?>
        <div class="main-wrapper">
            <?php include_once __DIR__ . "/../src/components/header.inc.php"; ?>
            <main class="content-area">
                <div class="mercadoria-group">
                    <div class="mercadoria-items">
                        <img src="assets/icons/Box.svg" alt="imagem de uma caixa">
                        <span class="mercadoria-title">Entrada de Mercadorias</span>
                    </div>
                    <span class="mercadoria-description">Registre a entrada de mercadoria no estoque!</span>
                </div>

                <div class="mercadoria-container-alinhamento">
                    <div class="mercadoria-container">
                        <h1>Nova Entrada</h1>
                        
                        <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=registrar_entrada" method="POST">
                            <div class="container-grid">
                                
                                <div class="grid-item">
                                    <span>Produto</span>
                                    <select name="produto_id" required>
                                        <option value="" disabled selected>Selecione o produto</option>
                                        <?php foreach ($listaProdutos as $p): ?>
                                            <option value="<?= htmlspecialchars($p['id']) ?>">
                                                <?= htmlspecialchars($p['nome']) ?> (<?= htmlspecialchars($p['sku']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="grid-item">
                                    <span>Quantidade</span>
                                    <input type="number" name="quantidade" placeholder="0" min="1" required> 
                                </div>

                                <div class="grid-item">
                                    <span>Nota Fiscal</span>
                                    <input type="text" name="nota_fiscal" placeholder="NF-00000" required>
                                </div>

                                <div class="grid-item">
                                    <span>Lote</span>
                                    <input type="text" name="lote" placeholder="LT-0000-000"> 
                                </div>

                                <div class="grid-item grid-item--full">
                                    <span>Fornecedor</span>
                                    <select name="fornecedor_id" required>
                                        <option value="" disabled selected>Selecione o fornecedor</option>
                                        <?php foreach ($listaFornecedores as $f): ?>
                                            <option value="<?= htmlspecialchars($f['id']) ?>">
                                                <?= htmlspecialchars($f['nome_fantasia']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>            
                            </div>

                            <div class="mercadoria-footer">
                                <button type="submit" class="btn">+ Registrar entrada</button>
                            </div>
                        </form>
                    </div>
                </div>  
            </main>
        </div>
    </div>
    <script type="module" src="/js/main.js"></script>
</body>
</html>