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
                        <span>600</span>
                    </div>
                    <div class="card">
                        <div class="card-flex">
                            <p>Saídas</p>
                        </div>
                        <span>80</span>
                    </div>
                    <div class="card">
                        <div class="card-flex">
                            <p>Ajustes</p>
                        </div>
                        <span>15</span>
                    </div>
                    <div class="card">
                        <div class="card-flex">
                            <p>Vencimento</p>
                        </div>
                        <span>3</span>
                    </div>
                   
                </section>

                <section class="produtos">
                    <div class="produtos-container">
                        <div class="table-header">
                            <div class="input-search">
                            <img src="assets/icons/search.svg" alt="buscar">
                            <input type="text" placeholder="Buscar Produtos ou Lote...">
                        </div>
                        </div>
        
                        <table>
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
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2026-01-16</td>
                                    <td><span class="status-badge green">Entrada</span></td>
                                    <td>Amoxicilina 500mg</td>
                                    <td>LT-2026-001</td>
                                    <td>2027-06</td>
                                    <td>200</td>
                                    <td>200</td>
                                    <td>200</td>
                                    <td>Ver</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                      <td>2</td>
                                    <td>2026-01-16</td>
                                    <td><span class="status-badge yellow">Saída</span></td>
                                    <td>Dipirona sódica 1g</td>
                                    <td>LT-2025-001</td>
                                    <td>2027-06</td>
                                    <td>30</td>
                                    <td>42</td>
                                    <td>42</td>
                                    <td>Ver</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>3</td>
                                    <td>2026-01-16</td>
                                    <td><span class="status-badge green">Entrada</span></td>
                                    <td>Omeprazol 20mg</td>
                                    <td>LT-2026-001</td>
                                    <td>2027-06</td>
                                    <td>15</td>
                                    <td>15</td>
                                    <td>15</td>
                                    <td>Ver</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>2026-01-16</td>
                                    <td><span class="status-badge green">Entrada</span></td>
                                    <td>Metformina 850mg</td>
                                    <td>LT-2026-001</td>
                                    <td>2027-06</td>
                                    <td>100</td>
                                    <td>100</td>
                                    <td>130</td>
                                    <td>Ver</td>
                                </tr>
                            </tbody>
                        </table>
                        <?php include_once __DIR__ . "/../src/components/pagination.inc.php"; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script type="module" src="/js/main.js"></script>
</body>
</html>