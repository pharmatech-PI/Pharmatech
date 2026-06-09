<?php
// 1. Garante que o Model do Produto está carregado para podermos usar o método do sininho
require_once __DIR__ . '../../../../back/app/models/produto.php';

// 2. Instancia o model usando a conexão $pdo que já vem da página principal
$produtoModelNotificacoes = new Produto($pdo);

// 3. Busca as últimas 5 linhas salvas na tabela de histórico
$notificacoes = $produtoModelNotificacoes->listarUltimasNotificacoes(5);
?>

<header>
    <img id="toggle-sidebar" src="assets/icons/sidebar.svg" alt="siderbar icon">
    <div class="icons-right">
        
        <div class="notification-wrapper">
            <img id="btn-notificacao" src="assets/icons/Alarm.svg" alt="icone de alarme" style="cursor: pointer;">

            <?php if (!empty($notificacoes)): ?>
                <span class="notification-badge"></span>
            <?php endif; ?>
            
            <div id="dropdown-notificacao" class="notification-dropdown">
                <div class="notification-header">
                    <h4>Últimas Movimentações</h4>
                </div>
                <ul class="notification-list">
                    
                    <?php if (empty($notificacoes)): ?>
                        <li style="text-align: center; color: #888; padding: 15px;">
                            Nenhuma movimentação recente.
                        </li>
                    <?php else: ?>
                        <?php foreach ($notificacoes as $noti): ?>
                            <li>
                                <span class="noti-user"><?= htmlspecialchars($noti['usuario_nome']) ?></span> 
                                
                                <?php 
                                // Adapta a frase de exibição de acordo com o tipo de ação
                                if ($noti['acao'] === 'entrada') {
                                    echo 'deu entrada em <br>';
                                    echo '<b>' . htmlspecialchars($noti['quantidade']) . 'x ' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                } elseif ($noti['acao'] === 'saida') {
                                    echo 'registrou saída de <br>';
                                    echo '<b>' . htmlspecialchars($noti['quantidade']) . 'x ' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                } elseif ($noti['acao'] === 'cadastro_produto') {
                                    echo 'cadastrou o produto <br>';
                                    echo '<b>' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                } elseif ($noti['acao'] === 'cadastro_fornecedor') {
                                    echo 'cadastrou o fornecedor <br>';
                                    echo '<b>' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                }
                                ?>
                                
                                <span class="noti-time">
                                    <?= date('d/m, H:i', strtotime($noti['data_criacao'])) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
        <img src="assets/icons/dark-mode.svg" alt="icone de dark mode">
        <img src="assets/icons/Profile.svg" alt="icone de perfil">
    </div>
</header>