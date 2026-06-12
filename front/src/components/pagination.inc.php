<?php
    $busca_query = '';
    if (!empty($_GET['busca'])) {
        $busca_query = '&busca=' . urlencode(trim($_GET['busca']));
    }
?>

<div class="pagination">
    
    <?php if (isset($pagina_atual) && $pagina_atual > 1): ?>
        <a href="?pagina=<?= $pagina_atual - 1 ?><?= $busca_query ?>" class="pagination-btn" style="text-decoration: none; text-align: center;">Anterior</a>
    <?php else: ?>
        <button class="pagination-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Anterior</button>
    <?php endif; ?>

    <span class="pagination-current">
        <?= isset($pagina_atual) ? $pagina_atual : 1 ?> 
        <?= isset($total_paginas) ? "de " . $total_paginas : "" ?>
    </span>

    <?php if (isset($pagina_atual) && isset($total_paginas) && $pagina_atual < $total_paginas): ?>
        <a href="?pagina=<?= $pagina_atual + 1 ?><?= $busca_query ?>" class="pagination-btn" style="text-decoration: none; text-align: center;">Próximo</a>
    <?php else: ?>
        <button class="pagination-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Próximo</button>
    <?php endif; ?>

</div>