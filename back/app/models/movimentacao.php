<?php
class Movimentacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function entrada($produto_id, $quantidade, $nota_fiscal, $lote, $fornecedor_id, $usuario_id) {
        try {
            $this->pdo->beginTransaction();

            $stmtProduto = $this->pdo->prepare("SELECT estoque FROM produto WHERE id = ?");
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            if (!$produto) {
                $this->pdo->rollBack();
                return false;
            }

            $qtd_antes = (int)$produto['estoque'];
            $qtd_depois = $qtd_antes + $quantidade;

            $stmtMov = $this->pdo->prepare("
                INSERT INTO movimentacao 
                (tipo, nota_fiscal, lote, quantidade, qtd_antes, qtd_depois, produto_id, usuario_id, fornecedor_id) 
                VALUES ('Entrada', ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmtMov->execute([
                $nota_fiscal, 
                $lote, 
                $quantidade, 
                $qtd_antes, 
                $qtd_depois, 
                $produto_id, 
                $usuario_id, 
                $fornecedor_id
            ]);

            $stmtUpdate = $this->pdo->prepare("UPDATE produto SET estoque = ? WHERE id = ?");
            $stmtUpdate->execute([$qtd_depois, $produto_id]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }


    public function listar() {
        $sql = "SELECT m.*, p.nome AS produto_nome 
                FROM movimentacao m 
                INNER JOIN produto p ON m.produto_id = p.id 
                ORDER BY m.id DESC"; 
                
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function obterResumo() {
        $stmt = $this->pdo->query("SELECT tipo, COUNT(*) as total FROM movimentacao GROUP BY tipo");
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resumo = [
            'Entrada' => 0,
            'Saída' => 0
            'Vencimento' => 0 
        ];

        foreach ($resultados as $linha) {
            $tipoFormatado = ucfirst(strtolower($linha['tipo'])); 
            
            if (isset($resumo[$tipoFormatado])) {
                $resumo[$tipoFormatado] = $linha['total'];
            }
        }

        return $resumo;
    }

}
?>