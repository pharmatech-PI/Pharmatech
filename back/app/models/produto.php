<?php
class Produto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar($nome, $sku, $preco, $estoque, $status, $usuario_id, $categoria_id, $fornecedores) {
        
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("SELECT id FROM produto WHERE sku = ?");
            $stmt->execute([$sku]);
            if ($stmt->rowCount() > 0) {
                $this->pdo->rollBack();
                return 'sku_duplicado';
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO produto (nome, sku, preco, estoque, status, usuario_id, categoria_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nome, $sku, $preco, $estoque, $status, $usuario_id, $categoria_id]);
            
            $produto_id = $this->pdo->lastInsertId();

            if (!empty($fornecedores) && is_array($fornecedores)) {
                $stmtAssoc = $this->pdo->prepare("INSERT INTO fornecedor_produto (fornecedor_id, produto_id) VALUES (?, ?)");
                
                foreach ($fornecedores as $fornecedor_id) {
                    $stmtAssoc->execute([$fornecedor_id, $produto_id]);
                }
            }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function listar() {
    $sql = "SELECT produto.*, categoria.nome AS categoria_nome 
            FROM produto 
            INNER JOIN categoria ON produto.categoria_id = categoria.id 
            ORDER BY produto.id ASC";

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>