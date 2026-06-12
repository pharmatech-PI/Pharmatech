<?php
require_once __DIR__ . '../../../../back/app/models/produto.php';

$produtoModelNotificacoes = new Produto($pdo);

$notificacoes = $produtoModelNotificacoes->listarUltimasNotificacoes(10);

if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuario_id = $_SESSION['usuario_id'] ?? 0;

    $stmt = $pdo->prepare("SELECT nome_completo, email, telefone FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
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
                                elseif ($noti['acao'] === 'exclusao_produto') {
                                    echo 'excluiu o produto <br>';
                                    echo '<b>' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                }
                                elseif ($noti['acao'] === 'edicao_produto') {
                                    echo 'editou o produto <br>';
                                    echo '<b>' . htmlspecialchars($noti['produto_nome']) . '</b>';
                                }
                                elseif ($noti['acao'] === 'inativacao_produto') {
                                    echo 'desativou o produto <br>';
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
        


        <a href="/PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php" class="profile-picture">
            <div class="profile-circle secondary">
                <?php
                    $nome_usuario = $dadosUsuario['nome_completo'] ?? 'Usuario';

                    $primeira_letra = mb_strtoupper(mb_substr($nome_usuario, 0, 1));
                    ?>
                    <span><?= htmlspecialchars($primeira_letra) ?></span>
            </div>
        </a>
    </div>
</header>