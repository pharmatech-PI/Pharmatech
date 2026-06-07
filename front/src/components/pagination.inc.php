<!DOCTYPE html>
<html lang="en">
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
    <div class="pagination">
    
    <?php if (isset($pagina_atual) && $pagina_atual > 1): ?>
        <a href="?pagina=<?= $pagina_atual - 1 ?>" class="pagination-btn" style="text-decoration: none; text-align: center;">Anterior</a>
    <?php else: ?>
        <button class="pagination-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Anterior</button>
    <?php endif; ?>

    <span class="pagination-current">
        <?= isset($pagina_atual) ? $pagina_atual : 1 ?> 
        <?= isset($total_paginas) ? "de " . $total_paginas : "" ?>
    </span>

    <?php if (isset($pagina_atual) && isset($total_paginas) && $pagina_atual < $total_paginas): ?>
        <a href="?pagina=<?= $pagina_atual + 1 ?>" class="pagination-btn" style="text-decoration: none; text-align: center;">Próximo</a>
    <?php else: ?>
        <button class="pagination-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Próximo</button>
    <?php endif; ?>

</div>
</body>
</html>
