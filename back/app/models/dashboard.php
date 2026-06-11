<?php
class Dashboard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obterMetricas() {
        $stmtTotal = $this->pdo->query("SELECT COUNT(*) as total FROM produto WHERE status = 'Ativo'");
        $totalProdutos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        $stmtBaixo = $this->pdo->query("SELECT COUNT(*) as total FROM produto WHERE estoque > 0 AND estoque <= 20 AND status = 'Ativo'");
        $estoqueBaixo = $stmtBaixo->fetch(PDO::FETCH_ASSOC)['total'];

        $stmtZerado = $this->pdo->query("SELECT COUNT(*) as total FROM produto WHERE estoque = 0 AND status = 'Ativo'");
        $semEstoque = $stmtZerado->fetch(PDO::FETCH_ASSOC)['total'];

        $stmtVenc20 = $this->pdo->query("
            SELECT COUNT(*) as total 
            FROM movimentacao 
            WHERE tipo = 'Entrada' AND validade IS NOT NULL 
            AND validade >= CURDATE() AND validade <= DATE_ADD(CURDATE(), INTERVAL 20 DAY)
        ");
        $vencendo20 = $stmtVenc20->fetch(PDO::FETCH_ASSOC)['total'];

        $stmtVenc90 = $this->pdo->query("
            SELECT COUNT(*) as total 
            FROM movimentacao 
            WHERE tipo = 'Entrada' AND validade IS NOT NULL 
            AND validade > DATE_ADD(CURDATE(), INTERVAL 20 DAY) 
            AND validade <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
        ");
        $vencendo90 = $stmtVenc90->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'total_produtos' => $totalProdutos,
            'estoque_baixo'  => $estoqueBaixo,
            'sem_estoque'    => $semEstoque,
            'vencendo_20'    => $vencendo20,
            'vencendo_90'    => $vencendo90
        ];
    }

    public function listarProdutosRecentes() {
        $sql = "SELECT nome, sku, estoque FROM produto WHERE status = 'Ativo' ORDER BY id DESC LIMIT 5";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>