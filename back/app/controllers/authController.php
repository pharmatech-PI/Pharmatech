<?php
require_once __DIR__ . '/../models/usuario.php';

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nome = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['pass'] ?? '';
            $senhaConfirmar = $_POST['confirm-pass'] ?? '';

            if (empty($nome) || empty($email) || empty($senha)) {
                die("Erro: Todos os campos são obrigatórios.");
            }

            if ($senha !== $senhaConfirmar) {
                die("Erro: As senhas não coincidem.");
            }

            $usuarioModel = new Usuario($this->pdo);
            $cadastrou = $usuarioModel->cadastrar($nome, $email, $senha);

            if ($cadastrou) {
                echo "<h2>Cadastro realizado com sucesso!</h2>";
                echo "<a href='/PHARMATECH_PROJETO/Pharmatech/front/public/login.php'>Clique aqui para fazer Login</a>";
            } else {
                echo "<h2>Erro: Este e-mail já está cadastrado.</h2>";
            }
        } else {
            echo "Acesso inválido. Preencha o formulário.";
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['pass'] ?? ''; 

            if (empty($email) || empty($senha)) {
                die("Erro: Preencha e-mail e senha.");
            }

            $usuarioModel = new Usuario($this->pdo);
            $usuario = $usuarioModel->buscarPorEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
                
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome_completo'];

                if (isset($_POST['lembrar'])) {
                    $token = bin2hex(random_bytes(32));

                    $usuarioModel->salvarTokenLembrar($usuario['id'], $token);

                    setcookie('lembrar_usuario', $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);
                }

                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/home.php");
                exit;

            } else {
                echo "<h2>Erro: E-mail ou senha incorretos.</h2>";
                echo "<a href='/PHARMATECH_PROJETO/Pharmatech/front/public/login.php'>Voltar</a>";
            }
        }
    }

}
?>