<?php 
    require_once __DIR__ . '/../../back/config/trava.php'; 
    
    require_once __DIR__ . '/../../back/config/config.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuario_id = $_SESSION['usuario_id'] ?? 0;

    $stmt = $pdo->prepare("SELECT nome_completo, email, telefone FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmatech</title>
    <link href="https://fonts.googleapis.com/css2?family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css" />
</head>
<body>
    <div class="layout-container">
        <?php include_once __DIR__ . "/../src/components/sidebar.inc.php"; ?>
        <div class="main-wrapper">
            <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=atualizar_perfil" method="POST">
                <main class="main-content">
                    <section class="card-section">
                        <?php if (isset($_GET['sucesso'])): ?>
                            <h2 style="color: #28a745; margin-bottom: 20px;">Perfil atualizado com sucesso!</h2>
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
                        </div>

                        <div class="container-grid-profile">
                            <div class="grid-item-profile">
                                <label>Nome</label>
                                <input name="nome_completo" type="text" placeholder="Administrador">
                            </div>

                            <div class="grid-item-profile">
                                <label>Email</label>
                                <input name="email"  type="email" placeholder="admin@pharmatech.com">
                            </div>

                            <div class="grid-item-profile grid-item-profile--full">
                                <label>Telefone</label>
                                <input name="telefone" type="text" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </section>


                    <section class="card-section">
                        <header class="personal-information">
                            <img src="assets/icons/Profile.svg" alt="icone de perfil">
                            <div class="information-group">
                                <h1>Segurança</h1>
                                <span>Altere sua senha de acesso</span>
                            </div>
                        </header>


                        <div class="container-grid-profile">
                            <div class="grid-item-profile  grid-item-profile--full">
                                <label>Senha atual</label>
                                <input name="senha" type="password" placeholder="********">
                            </div>

                            <div class="grid-item-profile">
                                <label>Nova Senha</label>
                                <input name="nova_senha" type="password" placeholder="********">
                            </div>

                            <div class="grid-item-profile">
                                <label>Confirmar nova senha</label>
                                <input name="confirmar_senha" type="text" placeholder="********">
                            </div>
                        </div>
                    </section>


                    <section class="card-section">
                        <header class="personal-information">
                            <img src="assets/icons/Profile.svg" alt="icone de perfil">
                            <div class="information-group">
                                <h1>Preferências</h1>
                                <span>Personalize sua experiência</span>
                            </div>
                        </header>

                        <div class="container-preferences">
                            <div class="profile-preference-theme">
                                <div>
                                    <h2>Modo escuro</h2>
                                    <span>Personalize sua experiência</span>
                                </div>

                                <label class="toggle-switch">
                                    <input type="checkbox" id="toggle-dark-mode">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <footer class="profile-footer">
                        <button class="btn-picture">CANCELAR</button> 
                        <button type="submit" class="btn">SALVAR ALTERAÇÕES</button>
                    </footer>
                </main>
            </form>
        </div>
    </div>
</body>
</html>