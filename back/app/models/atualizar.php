<?php

class Atualizar {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function salvarPerfil($id, $nome, $email, $telefone, $nova_senha) {
        try {
            if (empty($nova_senha)) {
                $sql = "UPDATE usuario SET nome_completo = ?, email = ?, telefone = ? where id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$nome, $email, $telefone, $id]);
            } else {

                $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);

                $sql = "UPDATE usuario SET nome_completo = ?, email = ?, telefone = ?, senha_hash = ? where id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$nome, $email, $telefone, $senhaHash, $id]);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function VerificarSenha($id) {
        $sql = "SELECT senha_hash FROM usuario WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ? $usuario['senha_hash'] : false;
    }
}
?>