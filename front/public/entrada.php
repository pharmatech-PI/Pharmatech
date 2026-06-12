<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    
    require_once __DIR__ . '/../../back/app/models/produto.php';
    require_once __DIR__ . '/../../back/app/models/fornecedor.php';

    $produtoModel = new Produto($pdo);
    $listaProdutos = $produtoModel->listarAtivos();

    // DICA: Não precisamos mais listar todos os fornecedores aqui no topo!
    // O JavaScript vai buscar apenas os fornecedores certos direto no banco.
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
                                    <select name="produto_id" id="produto_select" required>
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
                                    <span>Validade do Lote</span>
                                    <input type="date" name="validade" required> 
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
                                    <select name="fornecedor_id" id="fornecedor_select" required>
                                        <option value="" disabled selected>Selecione um produto primeiro</option>
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
    <script type="module" src="./js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectProduto = document.getElementById('produto_select');
            const selectFornecedor = document.getElementById('fornecedor_select');

            if (selectProduto && selectFornecedor) {
                selectProduto.addEventListener('change', async function() {
                    const produtoId = this.value;

                    // Mostra um aviso de carregamento enquanto busca no banco
                    selectFornecedor.innerHTML = '<option value="" disabled selected>Carregando fornecedores...</option>';

                    if (!produtoId) {
                        selectFornecedor.innerHTML = '<option value="" disabled selected>Selecione um produto primeiro</option>';
                        return;
                    }

                    try {
                        // Faz a requisição na rota invisível que criamos
                        const resposta = await fetch(`/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=buscar_fornecedores_ajax&produto_id=${produtoId}`);
                        const fornecedores = await resposta.json();

                        // Limpa o select para colocar os novos dados
                        selectFornecedor.innerHTML = '<option value="" disabled selected>Selecione o fornecedor</option>';

                        // Se o produto não tiver fornecedor cadastrado
                        if (fornecedores.length === 0) {
                            selectFornecedor.innerHTML = '<option value="" disabled selected>Nenhum fornecedor vinculado a este produto</option>';
                            return;
                        }

                        // Preenche os fornecedores magicamente
                        fornecedores.forEach(f => {
                            const option = document.createElement('option');
                            option.value = f.id;
                            option.textContent = `${f.nome_fantasia} (${f.cnpj})`;
                            selectFornecedor.appendChild(option);
                        });

                    } catch (erro) {
                        console.error("Erro ao buscar fornecedores:", erro);
                        selectFornecedor.innerHTML = '<option value="" disabled selected>Erro ao carregar</option>';
                    }
                });
            }
        });
    </script>
</body>
</html>