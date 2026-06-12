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
    <style>
        body, input, button, label, h1, p, a {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
 <main>
    <section class="login-container">
        <div class="logo">
            <img src="assets/logo-pharmatech.svg" alt="Logo Pharmatech">
        </div> 

        <form action="/PHARMATECH_PROJETO/Pharmatech/back/public/index.php?acao=login" method="POST" class="forms-login">
            <div class="form-container">
            <h1>Pharmatech</h1>
            <p>Onde cada miligrama conta</p>

            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'conta_inativa'): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb; font-size: 14px; font-weight: 500;">
                    ⚠️ <strong>Acesso Negado:</strong> Sua conta foi inativada. Procure o administrador do sistema.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'credenciais_invalidas'): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb; font-size: 14px; font-weight: 500;">
                    ❌ <strong>Usuário ou senha inválidas.</strong>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] === 'sem_cadastro'): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb; font-size: 14px; font-weight: 500;">
                    ❌ <strong>Usuário não tem cadastro.</strong>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb; font-size: 14px; font-weight: 500;">
                    ✅ <strong>Cadastro realizado com sucesso!</strong> Faça login para continuar.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'erro'): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb; font-size: 14px; font-weight: 500;">
                    ❌ <strong>Erro ao realizar cadastro.</strong> Tente novamente.
                </div>
            <?php endif; ?>

            <div class="input-wrapper">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="E-mail" required>
            </div>

            <div class="input-wrapper">
                <label for="pass">Senha</label>
                <input type="password" id="pass" name="pass" placeholder="Senha" required>
            </div>

            <div class="checkbox-wrapper">
                <div class="checkbox">
                    <label class="custom-checkbox">
                        <input name="lembrar" type="checkbox">
                        <span class="checkmark"></span>
                        Lembrar de mim
                    </label>
                </div>

                <a class="link-cadastro" href="http://localhost/PHARMATECH_PROJETO/Pharmatech/front/public/index.php">Efetuar Cadastro</a>
            </div>

            <button type="submit" class="btn">Entrar</button>
            </div>
        </form>
    </section>
</main>
 <script type="module" src="/js/main.js"></script>
</body>
</html>