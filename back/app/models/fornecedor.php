<?php
class Fornecedor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar($polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade) {
        $stmt = $this->pdo->prepare("SELECT id FROM fornecedor WHERE cnpj = ?");
        $stmt->execute([$cnpj]);
        if ($stmt->rowCount() > 0) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO fornecedor (polo, razao_social, nome_fantasia, cnpj, localidade) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade]);
    }


    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM fornecedor"; 
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    public function listarPaginado($limite, $offset) {
        try {
            $limite_seguro = (int) $limite;
            $offset_seguro = (int) $offset;

            $sql = "SELECT *
                    FROM fornecedor 
                    ORDER BY id ASC 
                    LIMIT {$limite_seguro} OFFSET {$offset_seguro}";

            $stmt = $this->pdo->query($sql);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            die("Erro detectado: " . $e->getMessage());
        }
    }


    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM fornecedor ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>