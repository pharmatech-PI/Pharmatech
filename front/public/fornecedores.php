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
    <?php include('../src/components/sidebar.inc.php'); ?>

    <main>
    <div class="fornecedor-group">
        <span class="fornecedor-title">Fornecedores</span>
        <button class="btn">+ Novo Fornecedor</button>
    </div>

    <div class="fornecedor-container">
        <div class="fornecedor-search">
            <img src="assets/icons/search.svg" alt="buscar">
            <input type="text" placeholder="Buscar Fornecedor ou CNPJ...">
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
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Fabricante</td>
                    <td>PharmaBrasil Ltda</td>
                    <td><strong>PharmaBR</strong></td>
                    <td>12.38.678/0001-01</td>
                    <td>São Paulo - SP</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Distribuidor</td>
                    <td>MedDistrubi S.A</td>
                    <td><strong>MedDIST</strong></td>
                    <td>98.765.432/001-02</td>
                    <td>Rio de Janeiro - RJ</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Fabricante</td>
                    <td>BioSaúde Indústria</td>
                    <td><strong>BioSaúde</strong></td>
                    <td>11.222.333/001-03</td>
                    <td>Belo Horizonte - MG</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Distribuidor</td>
                    <td>Saúde Total Logistica</td>
                    <td><strong>SaudeLog</strong></td>
                    <td>44.555.666/001-04</td>
                    <td>Curitiba - PR</td>
                </tr>
            </tbody>
        </table>

        <div class="fornecedor-pagination">
            <button class="pagination-btn">Anterior</button>
            <span class="pagination-current">1</span>
            <button class="pagination-btn">Próximo</button>
        </div>
    </div>
</main>
    
</body>
</html>