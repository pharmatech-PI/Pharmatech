<?php
    require_once __DIR__ . '/../models/atualizar.php';

    class AtualizarController {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function atualizar() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = trim($_POST['nome_completo'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $telefone = trim($_POST['telefone'] ?? '');
                
                $senha_atual = $_POST['senha'] ?? '';
                $nova_senha = $_POST['nova_senha'] ?? '';
                $confirmar_senha = $_POST['confirmar_senha'] ?? '';
                
                if (empty($nome) || empty($email) || empty($telefone)) {
                    die("Erro: Campos básicos não podem ficar vazios!");
                }

                if (!empty($nova_senha) && $nova_senha !== $confirmar_senha) {
                    die("Erro: A nova senha não bate com a confirmação!");
                }

                session_start(); 
                $usuario_id = $_SESSION['usuario_id']; 

                $atualizarModel = new Atualizar($this->pdo);
                
                $senha_hash_banco = $atualizarModel->VerificarSenha($usuario_id);

                if (!$senha_hash_banco || !password_verify($senha_atual, $senha_hash_banco)) {
                    die("Erro: Senha atual incorreta!");
                }

                $resultado = $atualizarModel->salvarPerfil($usuario_id, $nome, $email, $telefone, $nova_senha);

                if ($resultado) {
                    header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?sucesso=1");
                    exit;
                } else {
                    die("Erro ao atualizar o perfil no banco de dados.");
                }
            }
        }
    }
?>