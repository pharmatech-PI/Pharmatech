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
    <main>
        <section class="cadastro">
               <div class="logo-cadastro">
                <img src="assets/Logo-cadastro.svg" alt="logo Pharmatech">
            </div>
            <p>Crie sua conta</p>
          <form action="processa_login.php" method="POST" class="form-cadastro"> 
         
            <div class="input-wrapper">
                <label for="name"></label>
                <input type="text" name="name" id="name" placeholder="Nome Completo" required>
            </div>
              <div class="input-wrapper">
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
              <div class="input-wrapper">
                <label for="pass"></label>
                <input type="password" name="pass" id="pass" placeholder="Senha" required>
            </div>
              <div class="input-wrapper">
                <label for="confirm-pass"></label>
                <input type="password" name="confirm-pass" id="confirm-pass" placeholder="Confirmar Senha" required>
            </div>

           <button type="submit" class="btn">Cadastrar</button>
          </form>
          <p class="paragrafo-entrar">Já tem uma conta? <a href="login.php">Entrar</a></p>
        </section>
    </main>
     <script type="module" src="/js/main.js"></script>
</body>
</html>