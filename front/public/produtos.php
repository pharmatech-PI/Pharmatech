<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    require_once __DIR__ . '/../../back/app/models/fornecedor.php';
    require_once __DIR__ . '/../../back/app/models/produto.php';



    $produtoModel = new Produto($pdo);

    
    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_atual < 1) {
        $pagina_atual = 1;
    }

    $itens_por_pagina = 10; 


    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    
    $total_itens = $produtoModel->contarTotal();

    $total_paginas = ceil($total_itens / $itens_por_pagina);

    $listaProdutos = $produtoModel->listarPaginado($itens_por_pagina, $offset);

    $fornecedorModel = new Fornecedor($pdo);
    $listaFornecedores = $fornecedorModel->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
    <div class="layout-container">
        <?php include_once __DIR__ . "/../src/components/sidebar.inc.php"; ?>
        <div class="main-wrapper">
            <?php include_once __DIR__ . "/../src/components/header.inc.php"; ?>
            <main class="content-area">
                <div class="fornecedor-group">
                    <span class="fornecedor-title">Produtos</span>
                    <button class="btn" data-modal="abrir">+ Novo Produto</button>
                </div>

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
                <div id="alert-message" class="alert alert-success">
                    Produto cadastrado com sucesso!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] == 'sku_duplicado'): ?>
                <div class="alert alert-error">
                    Já existe um produto cadastrado com este SKU.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] == 'falha_cadastro'): ?>
                <div class="alert alert-error">
                    Ocorreu um erro ao cadastrar o produto.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'editado'): ?>
                <div class="alert alert-success">
                    Produto atualizado com sucesso!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'inativado'): ?>
                <div class="alert alert-success">
                    Produto inativado com sucesso!
                </div>
            <?php endif; ?>

                <section class="produtos">
                 <div class="produtos-container">
                    <div class="table-header">
                        <div class="input-search">
                            <img src="assets/icons/search.svg" alt="buscar">
                            <input type="text" id="busca-produto" placeholder="Buscar Produtos ou SKU...">
                        </div>
                    </div>

                    <table class="produto-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>SKU</th>
                                <th>Categoria</th>
                                <th>Estoque</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ajustes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listaProdutos)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">Nenhum produto cadastrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($listaProdutos as $produto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($produto['id']) ?></td>
                                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                                        <td><?= htmlspecialchars($produto['sku']) ?></td>
                                        
                                        <td><?= htmlspecialchars($produto['categoria_nome']) ?></td>
                                        
                                        <td><?= htmlspecialchars($produto['estoque']) ?></td>
                                        
                                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                                        
                                        <td>
                                            <?php if (strtolower($produto['status']) === 'ativo'): ?>
                                                <span class="status-badge green">Ativo</span>
                                            <?php else: ?>
                                                <span class="status-badge red">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <div style="display: flex; aling-items: center; gap: 5px;">


                                                <?php if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>
                                               <img src="./assets/icons/pencil.svg" alt="icone de lapis" style="cursor: pointer;" 
                                                    class="btn-editar-produto"
                                                    data-id="<?= htmlspecialchars($produto['id']) ?>"
                                                    data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                                                    data-sku="<?= htmlspecialchars($produto['sku']) ?>"
                                                    data-preco="<?= htmlspecialchars($produto['preco']) ?>"
                                                    data-estoque="<?= htmlspecialchars($produto['estoque']) ?>"
                                                    data-status="<?= htmlspecialchars($produto['status']) ?>"
                                                    data-categoria="<?= htmlspecialchars($produto['categoria_id']) ?>">         

                                                <a href="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=excluir_produto&id=<?= $produto['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir o produto <?= htmlspecialchars($produto['nome']) ?>?');">
                                                    <img src="./assets/icons/lixeira.svg" style="width: 20px; height: 20px;" alt="icone de lixeira" style="cursor: pointer;">
                                                </a>   
                                                <?php else: ?>
                                                    <span style="color: #999; font-size: 12px; font-style: italic;">Sem permissão</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
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
    </div>

    <section class="modal-container" data-modal="container">
      <div class="modal">
        <button data-modal="fechar" class="fechar">X</button>
        <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=cadastrar_produto" method="POST">
        <div class="input-modal">
    <div class="input-wrapper">
        <label for="name">Nome</label>
        <input type="text" name="nome" id="name" placeholder="Ex: Amoxicilina 500mg" required/>
    </div>
    
    <div class="input-wrapper">
        <label for="sku">SKU</label>
        <input type="text" name="sku" id="sku" placeholder="ex: AMX-500" required/>
    </div>
    
    <div class="input-wrapper">
        <label for="categoria">ID da Categoria</label>
        <input type="number" name="categoria_id" id="categoria" placeholder="Ex: 1" required/>
    </div>
    
    <div class="input-wrapper">
        <label for="preco">Preço</label>
        <input type="text" name="preco" id="preco" placeholder="12,90" required/>
    </div>
    
    <div class="input-wrapper">
        <label for="estoque">Estoque inicial</label>
        <input type="number" name="estoque" id="estoque" placeholder="0" required/>
    </div>
    
    <div class="input-wrapper">
        <label for="status">Status</label>
        <input type="text" name="status" id="status" placeholder="Ativo" value="Ativo"/>
    </div>

    <div class="input-wrapper input-modal-fornecedor">
        <label for="fornecedores">Fornecedores do Produto (Segure CTRL para marcar vários)</label>
        
        <select name="fornecedores[]" id="fornecedores" multiple required>
            
            <?php foreach ($listaFornecedores as $f): ?>
                <option value="<?= htmlspecialchars($f['id']) ?>">
                    <?= htmlspecialchars($f['nome_fantasia']) ?> (<?= htmlspecialchars($f['cnpj']) ?>)
                </option>
            <?php endforeach; ?>
            
        </select>
    </div>
</div>

    <div class="btn-modal">
        <button class="pagination-btn" type="button" data-modal="fechar">Cancelar</button>
         <button class="btn" type="submit">Salvar Agora</button>
    </div>
        
    </form>
      </div>
</section>


        <section class="modal-container" id="modal-editar" style="display: none;">
        <div class="modal">
            <button id="btn-fechar-editar" class="fechar">X</button>
            
            <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=atualizar_produto" method="POST">
                <input type="hidden" name="id" id="edit_id">

                <div class="input-modal">
                    <div class="input-wrapper">
                        <label for="edit_nome">Nome</label>
                        <input type="text" name="nome" id="edit_nome" required/>
                    </div>
                    
                    <div class="input-wrapper">
                        <label for="edit_sku">SKU</label>
                        <input type="text" name="sku" id="edit_sku" required/>
                    </div>
                    
                    <div class="input-wrapper">
                        <label for="edit_categoria">ID da Categoria</label>
                        <input type="number" name="categoria_id" id="edit_categoria" required/>
                    </div>
                    
                    <div class="input-wrapper">
                        <label for="edit_preco">Preço</label>
                        <input type="text" name="preco" id="edit_preco" required/>
                    </div>
                    
                    <div class="input-wrapper">
                        <label for="edit_estoque">Estoque</label>
                        <input type="number" name="estoque" id="edit_estoque" required/>
                    </div>
                    
                    <div class="input-wrapper">
                        <label for="edit_status">Status</label>
                        <input type="text" name="status" id="edit_status" required/>
                    </div>

                    <div class="input-wrapper input-modal-fornecedor">
                        <label for="edit_fornecedores">Fornecedores (Segure CTRL para marcar vários)</label>
                        <select name="fornecedores[]" id="edit_fornecedores" multiple required>
                            <?php foreach ($listaFornecedores as $f): ?>
                                <option value="<?= htmlspecialchars($f['id']) ?>">
                                    <?= htmlspecialchars($f['nome_fantasia']) ?> (<?= htmlspecialchars($f['cnpj']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="btn-modal">
                    <button class="pagination-btn" type="button" id="btn-cancelar-editar">Cancelar</button>
                    <button class="btn" type="submit">Salvar agora</button>
                </div>
            </form>
        </div>
        </section>

        <script type="module" src="./js/main.js"></script>
    </body>
</html>