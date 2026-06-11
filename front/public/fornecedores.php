<?php 
    require_once __DIR__ . '/../../back/config/trava.php';
    require_once __DIR__ . '/../../back/config/config.php';
    require_once __DIR__ . '/../../back/app/models/fornecedor.php';


    $fornecedorModel = new Fornecedor($pdo);

    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_atual < 1) {
        $pagina_atual = 1;
    }

    $itens_por_pagina = 10; 


    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    
    $total_itens = $fornecedorModel->contarTotal();

    $total_paginas = ceil($total_itens / $itens_por_pagina);


    $fornecedores = $fornecedorModel->listarPaginado($itens_por_pagina, $offset);
?>

<!DOCTYPE html>
<html lang="en">
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
                    <span class="fornecedor-title">Fornecedores</span>
                    <button class="btn" data-modal="abrir">+ Novo Fornecedor</button>
                </div>
                <div class="fornecedor-container-alinhamento">
                    <div class="fornecedor-container">
                        <div class="input-search">
                            <img src="assets/icons/search.svg" alt="buscar">
                            <input type="text" id="busca-fornecedor" placeholder="Buscar Fornecedor ou CNPJ...">
                        </div>

                        <table class="fornecedor-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Polo</th>
                                    <th>Razão Social</th>
                                    <th>Nome Fantasia</th>
                                    <th>CNPJ</th>
                                    <th>Localidade</th>
                                    <th>Status</th>
                                    <th>Ajustes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fornecedores)): ?>
                                    <tr>
                                        <td colspan="8" style="text-align:center">Nenhum fornecedor cadastrado.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($fornecedores as $f): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($f['id']) ?></td>
                                            <td><?= htmlspecialchars($f['polo']) ?></td>
                                            <td><?= htmlspecialchars($f['razao_social']) ?></td>
                                            <td><strong><?= htmlspecialchars($f['nome_fantasia']) ?></strong></td>
                                            <td><?= htmlspecialchars($f['cnpj']) ?></td>
                                            <td><?= htmlspecialchars($f['localidade']) ?></td>

                                            <td>
                                                <?php if (strtolower($f['status']) === 'ativo'): ?>
                                                    <span class="status-badge green">Ativo</span>
                                                <?php else: ?>
                                                    <span class="status-badge red">Inativo</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <div style="display: flex; aling-items: center; gap: 5px;">
                                                <img src="./assets/icons/pencil.svg" alt="Editar" class="btn-editar-fornecedor" style="cursor: pointer;"
                                                    data-id="<?= htmlspecialchars($f['id']) ?>"
                                                    data-polo="<?= htmlspecialchars($f['polo']) ?>"
                                                    data-razao="<?= htmlspecialchars($f['razao_social']) ?>"
                                                    data-fantasia="<?= htmlspecialchars($f['nome_fantasia']) ?>"
                                                    data-cnpj="<?= htmlspecialchars($f['cnpj']) ?>"
                                                    data-localidade="<?= htmlspecialchars($f['localidade']) ?>">        

                                                    <a href="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=excluir_fornecedor&id=<?= $f['id'] ?>" onclick="return confirm('Tem certeza que deseja inativar este fornecedor?');">
                                                        <img src="./assets/icons/lixeira.svg" style="width: 20px; height: 20px;" alt="Inativar">
                                                    </a> 
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    <?php include_once __DIR__ . "/../src/components/pagination.inc.php"; ?>
                </div>
            </main>
        </div>
    </div>

<section class="modal-container" data-modal="container">
      <div class="modal">
        <button data-modal="fechar" class="fechar">X</button>
        <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=cadastrar_fornecedor" method="POST">
        <div class="input-modal">
            <div class="input-wrapper">
                <label for="name">Razão Social</label>
                <input type="text" name="razao_social" for="name" id="name" placeholder="Ex: Pharma Brasil Ltda"/>
            </div>
            <div class="input-wrapper">
                <label for="fantasia">Nome Fantasia</label>
                <input  name="nome_fantasia" type="text" for="fantasia" id="fantasia" placeholder="Ex: PharmaBR"/>
            </div>
               <div class="input-wrapper">
                <label for="cnpj">CNPJ</label>
                <input name="cnpj" type="text" for="cnpj" id="cnpj" placeholder="00.000.000/00000"/>
            </div>
               <div class="input-wrapper">
                <label for="polo">Polo</label>
                <input name="polo" type="text" for="polo" id="polo" placeholder="Selecione"/>
            </div>
            <div class="input-wrapper">
                <label for="localidade">localidade</label>
                <input name="localidade" type="text" for="localidade" id="localidade" placeholder="Ex: São Paulo - SP"/>
            </div>
            <div class="input-wrapper">
                <label for="status">status</label>
                <input type="text" for="status" id="status" placeholder="Ativo"/>
            </div>
            <div class="input-wrapper input-modal-fornecedor">
                <label for="email">E-mail de contato</label>
                <input type="email" for="email" id="email" placeholder="contato@empresa.com"/>
                <label for="phone">Telefone</label>
                <input type="phone" for="phone" id="phone" placeholder="(00) 00000-0000"/>
            </div>
        </div>
          <div class="btn-modal">
            <button class="pagination-btn" type="button">Cancelar</button>
            <button class="btn" type="submit">Salvar</button>
          </div>
        
        </form>
      </div>
</section>


                                                    
<section class="modal-container" id="modal-editar-fornecedor" style="display: none;">
  <div class="modal">
    <button id="btn-fechar-editar-forn" class="fechar">X</button>
    
    <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=atualizar_fornecedor" method="POST">
        <input type="hidden" name="id" id="edit_forn_id">

        <div class="input-modal">
            <div class="input-wrapper">
                <label for="edit_forn_razao">Razão Social</label>
                <input type="text" name="razao_social" id="edit_forn_razao" required/>
            </div>
            <div class="input-wrapper">
                <label for="edit_forn_fantasia">Nome Fantasia</label>
                <input type="text" name="nome_fantasia" id="edit_forn_fantasia" required/>
            </div>
            <div class="input-wrapper">
                <label for="edit_forn_cnpj">CNPJ</label>
                <input type="text" name="cnpj" id="edit_forn_cnpj" required/>
            </div>
            <div class="input-wrapper">
                <label for="edit_forn_polo">Polo</label>
                <input type="text" name="polo" id="edit_forn_polo"/>
            </div>
            <div class="input-wrapper">
                <label for="edit_forn_localidade">Localidade</label>
                <input type="text" name="localidade" id="edit_forn_localidade"/>
            </div>
        </div>
        
        <div class="btn-modal">
            <button class="pagination-btn" type="button" id="btn-cancelar-editar-forn">Cancelar</button>
            <button class="btn" type="submit">Atualizar</button>
        </div>
    </form>
  </div>
</section>


<script type="module" src="./js/main.js"></script>
</html>