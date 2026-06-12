<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    require_once __DIR__ . '/../../back/config/config.php';
    require_once __DIR__ . '/../../back/app/models/usuario.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuarioModel = new Usuario($pdo);
    $usuario_id = $_SESSION['usuario_id'] ?? 0;

    $stmt = $pdo->prepare("SELECT nome_completo, email, telefone FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $listaUsuarios = [];
    if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin') {
        $listaUsuarios = $usuarioModel->listarTodos();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmatech - Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
    <div class="layout-container">
        <?php include_once __DIR__ . "/../src/components/sidebar.inc.php"; ?>
        
        <div class="main-wrapper">
            <main class="main-content">
                
                <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=atualizar_perfil" method="POST">
                    <section class="card-section">
                        <?php if (isset($_GET['sucesso'])): ?>
                            <div class="alert alert-success">Perfil atualizado com sucesso!</div>
                        <?php endif; ?>
                        
                        <header class="personal-information">
                            <img src="assets/icons/Profile.svg" alt="icone de perfil">
                            <div class="information-group">
                                <h1>Informações Pessoais</h1>
                                <span>Atualize seus dados do perfil</span>
                            </div>
                        </header>

                        <div class="profile-picture-section">
                            <div class="profile-circle">
                                <?php
                                    $nome_usuario = $dadosUsuario['nome_completo'] ?? 'Usuario';
                                    $primeira_letra = mb_strtoupper(mb_substr($nome_usuario, 0, 1));
                                ?>
                                <span><?= htmlspecialchars($primeira_letra) ?></span>
                            </div>
                      
                            <span class="name-user">
                                <?= htmlspecialchars($nome_usuario) ?>
                            </span>                       
                        </div>

                        <div class="container-grid-profile">
                            <div class="grid-item-profile">
                                <label>Nome</label>
                                <input name="nome_completo" type="text" value="<?= htmlspecialchars($nome_usuario) ?>">
                            </div>

                            <div class="grid-item-profile">
                                <label>Email</label>
                                <input name="email" type="email" value="<?= htmlspecialchars($dadosUsuario['email'] ?? '') ?>">
                            </div>

                            <div class="grid-item-profile grid-item-profile--full">
                                <label>Telefone</label>
                                <input name="telefone" type="text" value="<?= htmlspecialchars($dadosUsuario['telefone'] ?? '') ?>">
                            </div>
                        </div>
                    </section>

                    <section class="card-section">
                        <header class="personal-information">
                            <img src="assets/icons/Profile.svg" alt="icone de segurança">
                            <div class="information-group">
                                <h1>Segurança</h1>
                                <span>Altere sua senha de acesso</span>
                            </div>
                        </header>

                        <div class="container-grid-profile">
                            <div class="grid-item-profile grid-item-profile--full">
                                <label>Senha atual</label>
                                <input name="senha" type="password" placeholder="********">
                            </div>

                            <div class="grid-item-profile">
                                <label>Nova Senha</label>
                                <input name="nova_senha" type="password" placeholder="********">
                            </div>

                            <div class="grid-item-profile">
                                <label>Confirmar nova senha</label>
                                <input name="confirmar_senha" type="password" placeholder="********">
                            </div>
                        </div>
                    </section>

                    <footer class="profile-footer">
                        <button type="button" class="btn-picture">CANCELAR</button> 
                        <button type="submit" class="btn">SALVAR ALTERAÇÕES</button>
                    </footer>
                </form>
                  
                <?php if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'permissao_alterada'): ?>
                        <div class="alert alert-success">Permissões atualizadas com sucesso!</div>
                    <?php endif; ?>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'auto_rebaixamento'): ?>
                        <div class="alert alert-danger">Você não pode alterar suas próprias permissões de acesso ou inativar sua conta.</div>
                    <?php endif; ?>

                      <?php if (isset($_GET['erro']) && $_GET['erro'] === 'super_admin_protegido'): ?>
                        <div class="alert alert-danger">Erro: O perfil do Administrador Principal não pode ser alterado ou inativado!</div>
                    <?php endif; ?>

                    <section class="card-section team-section">
                        <header class="personal-information">
                            <div class="information-group">
                                <h1>Gerenciamento de Equipe</h1>
                                <span>Controle os níveis de acesso e inative funcionários</span>
                            </div>
                        </header>

                        <div class="team-table-wrapper">
                            <table class="team-table">
                                <thead>
                                    <tr>
                                        <th>Funcionário</th>
                                        <th>E-mail</th>
                                        <th>Cargo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listaUsuarios)): ?>
                                        <tr>
                                            <td colspan="5" class="table-empty">Nenhum funcionário encontrado.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($listaUsuarios as $user): ?>
                                            <?php
                                                $iniciais = mb_strtoupper(mb_substr($user['nome_completo'], 0, 1));
                                                $isAdmin   = $user['nivel_acesso'] === 'admin';
                                                $isInativo = isset($user['status']) && $user['status'] === 'Inativo';
                                                $isSelf    = $user['id'] === $_SESSION['usuario_id'];
                                                $baseUrl   = '/PHARMATECH_PROJETO/Pharmatech/back/public/index.php';
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="user-cell">
                                                        <div class="user-avatar"><?= htmlspecialchars($iniciais) ?></div>
                                                        <span class="user-name"><?= htmlspecialchars($user['nome_completo']) ?></span>
                                                    </div>
                                                </td>

                                                <td><?= htmlspecialchars($user['email']) ?></td>

                                                <td>
                                                    <?php if ($user['id'] == 36): ?>
                                                        <span class="badge super-admin">Super Admin</span>
                                                    <?php elseif ($isAdmin): ?>
                                                        <span class="badge badge-admin">Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-common">Comum</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if ($isInativo): ?>
                                                        <span class="badge badge-inactive">Inativo</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-active">Ativo</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <div class="actions-cell">
    
                                                    <?php if ($user['id'] == 36): ?>
                                                        <span style="font-size: 13px; color: #6c757d; font-style: italic; font-weight: 600;">Não pode ser modificado</span>
                                                    <?php else: ?>
                                                        
                                                        <?php if ($isAdmin): ?>
                                                            <a href="<?= $baseUrl ?>?acao=alterar_permissao_usuario&id=<?= $user['id'] ?>&nivel=comum"
                                                            class="action-btn action-btn-demote"
                                                            onclick="return confirm('Deseja retirar os poderes de Admin deste funcionário?');">
                                                                Retirar Admin
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= $baseUrl ?>?acao=alterar_permissao_usuario&id=<?= $user['id'] ?>&nivel=admin"
                                                            class="action-btn action-btn-promote"
                                                            onclick="return confirm('Promover este funcionário a Administrador?');">
                                                                Dar Admin
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if ($isInativo): ?>
                                                            <a href="<?= $baseUrl ?>?acao=alterar_status_usuario&id=<?= $user['id'] ?>&status=Ativo"
                                                            class="action-btn action-btn-activate"
                                                            onclick="return confirm('Deseja liberar o acesso deste funcionário novamente?');">
                                                                Ativar
                                                            </a>
                                                        <?php elseif (!$isSelf): ?>
                                                            <a href="<?= $baseUrl ?>?acao=alterar_status_usuario&id=<?= $user['id'] ?>&status=Inativo"
                                                            class="action-btn action-btn-deactivate"
                                                            onclick="return confirm('Inativar este usuário? Ele perderá acesso ao sistema.');">
                                                                Inativar
                                                            </a>
                                                        <?php endif; ?>

                                                    <?php endif; ?>

                                                </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endif; ?>
                
            </main>
        </div> 
    </div>
</body>
</html>