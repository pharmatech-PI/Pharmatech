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
                        <div class="container-grid">
                            <div class="grid-item">
                                <span>Produto</span>
                                <input type="text" placeholder="Nome do produto">
                            </div>

                            <div class="grid-item">
                                <span>Quantidade</span>
                                <input type="number" placeholder="0"> 
                            </div>

                            <div class="grid-item">
                                <span>Nota Fiscal</span>
                                <input type="text" placeholder="NF-00000">
                            </div>

                            <div class="grid-item">
                                <span>Lote</span>
                                <input type="text" placeholder="LT-0000-000"> 
                            </div>

                            <div class="grid-item grid-item--full">
                                <span>Fornecedor</span>
                                <select>
                                    <option value="">Selecione o fornecedor</option>
                                </select>
                            </div>            
                        </div>

                        <div class="mercadoria-footer">
                             <button class="btn">+ Registrar entrada</button>
                        </div>
                    </div>
                </div>  
            </main>
        </div>
    </div>
</body>
</html>