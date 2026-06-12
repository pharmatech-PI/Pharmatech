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
                 
            if (isset($usuario['status']) && $usuario['status'] === 'Inativo') {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/login.php?erro=conta_inativa");
                exit; 
            }
       
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome_completo'];

                $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

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


    public function alterar_status() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
            die("Acesso negado.");
        }

        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id_alvo = (int) $_GET['id'];
            $novo_status = $_GET['status']; 

            if ($id_alvo === $_SESSION['usuario_id'] && $novo_status === 'Inativo') {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?erro=auto_rebaixamento");
                exit();
            }

            $sql = "UPDATE usuario SET status = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$novo_status, $id_alvo]);

            header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?status=permissao_alterada");
            exit();
        }
    }

    public function alterar_permissao() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
            die("Acesso negado.");
        }

        if (isset($_GET['id']) && isset($_GET['nivel'])) {
            $id_alvo = (int) $_GET['id'];
            $novo_nivel = $_GET['nivel']; 

            if ($id_alvo === $_SESSION['usuario_id'] && $novo_nivel === 'comum') {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/perfil.php?erro=auto_rebaixamento");
                exit();
            }

            $sql = "UPDATE usuario SET nivel_acesso = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$novo_nivel, $id_alvo]);

            header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/perfil.php?status=permissao_alterada");
            exit();
        }
    }

}
?>