<?php

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar($nomeCompleto, $email, $senha) {

    $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return false; 
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO usuario (nome_completo, email, senha_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$nomeCompleto, $email, $senhaHash]);
    }

    public function buscarPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = ?");

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvarTokenLembrar($id, $token) {
    try {
        $sql = "UPDATE usuario SET token_lembrar = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$token, $id]);
    } catch (Exception $e) {
        die("Erro detectado no Model de Usuário: " . $e->getMessage());
    }
    }


    public function listarTodos() {
        $sql = "SELECT id, nome_completo, email, telefone, nivel_acesso, status FROM usuario ORDER BY id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function atualizarPermissoes($id, $nivel_acesso, $status) {
        try {
            $sql = "UPDATE usuario SET nivel_acesso = ?, status = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nivel_acesso, $status, $id]);
        } catch (Exception $e) {
            die("Erro ao atualizar permissões: " . $e->getMessage());
        }
    }

    public function alterarNivelAcesso($id, $novo_nivel) {
        try {
            if (!in_array($novo_nivel, ['admin', 'comum'])) {
                return false;
            }

            $sql = "UPDATE usuario SET nivel_acesso = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$novo_nivel, $id]);
        } catch (Exception $e) {
            die("Erro ao alterar permissão: " . $e->getMessage());
        }
    }
}
?>