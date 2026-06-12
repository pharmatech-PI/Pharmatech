<?php
class Movimentacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function entrada($produto_id, $quantidade, $validade, $nota_fiscal, $lote, $fornecedor_id, $usuario_id) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmtProduto = $this->pdo->prepare("SELECT nome, estoque FROM produto WHERE id = ?");
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            if (!$produto) {
                $this->pdo->rollBack();
                return false;
            }

            $nome_produto = $produto['nome'];
            $qtd_antes = (int)$produto['estoque'];
            $qtd_depois = $qtd_antes + $quantidade;

            $stmtMov = $this->pdo->prepare("
                INSERT INTO movimentacao 
                (tipo, nota_fiscal, lote, quantidade, validade, qtd_antes, qtd_depois, produto_id, usuario_id, fornecedor_id) 
                VALUES ('Entrada', ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmtMov->execute([
                $nota_fiscal, 
                $lote, 
                $quantidade,
                $validade, 
                $qtd_antes, 
                $qtd_depois, 
                $produto_id, 
                $usuario_id, 
                $fornecedor_id
            ]);

            $stmtUpdate = $this->pdo->prepare("UPDATE produto SET estoque = ? WHERE id = ?");
            $stmtUpdate->execute([$qtd_depois, $produto_id]);


            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'entrada', ?, ?)
            ");
            $stmtHist->execute([$usuario_logado, $nome_produto, $quantidade]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }


    public function saida($produto_id, $quantidade, $motivo, $lote, $usuario_id) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmtProduto = $this->pdo->prepare("SELECT nome, estoque FROM produto WHERE id = ?");
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            if (!$produto || $produto['estoque'] < $quantidade) {
                $this->pdo->rollBack();
                return false; 
            }

            $nome_produto = $produto['nome'];
            $qtd_antes = (int)$produto['estoque'];
            $qtd_depois = $qtd_antes - $quantidade; 

            $stmtMov = $this->pdo->prepare("
                INSERT INTO movimentacao 
                (tipo, nota_fiscal, lote, quantidade, validade, qtd_antes, qtd_depois, produto_id, usuario_id, fornecedor_id) 
                VALUES ('Saída', ?, ?, ?, NULL, ?, ?, ?, ?, NULL)
            ");
            
            $stmtMov->execute([
                $motivo, 
                $lote, 
                $quantidade,
                $qtd_antes, 
                $qtd_depois, 
                $produto_id, 
                $usuario_id
            ]);

            $stmtUpdate = $this->pdo->prepare("UPDATE produto SET estoque = ? WHERE id = ?");
            $stmtUpdate->execute([$qtd_depois, $produto_id]);

            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'saida', ?, ?)
            ");
            $stmtHist->execute([$usuario_logado, $nome_produto, $quantidade]);

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


     public function contarTotal($busca = '') {
        $sql = "SELECT COUNT(*) as total FROM movimentacao m INNER JOIN produto p ON m.produto_id = p.id WHERE p.nome LIKE :busca OR m.lote LIKE :busca";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':busca', "%$busca%");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }


    public function listarPaginado($limite, $offset, $busca = '') {
        try {
            $limite_seguro = (int) $limite;
            $offset_seguro = (int) $offset;

            $sql = "SELECT m.*, p.nome AS produto_nome 
                FROM movimentacao m 
                INNER JOIN produto p ON m.produto_id = p.id 
                WHERE p.nome LIKE :busca OR m.lote LIKE :busca 
                ORDER BY m.id DESC LIMIT {$limite_seguro} OFFSET {$offset_seguro}";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':busca', "%$busca%");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            die("Erro detectado: " . $e->getMessage());
        }
    }


    public function obterResumo() {
        $stmt = $this->pdo->query("SELECT tipo, COUNT(*) as total FROM movimentacao GROUP BY tipo");
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resumo = [
            'Entrada' => 0,
            'Saída' => 0,
            'Vencimento' => 0 
        ];

        foreach ($resultados as $linha) {
            $tipoFormatado = ucfirst(strtolower($linha['tipo'])); 
            
            if (isset($resumo[$tipoFormatado])) {
                $resumo[$tipoFormatado] = $linha['total'];
            }
        }

        $stmtVenc = $this->pdo->query("
            SELECT COUNT(*) as total 
            FROM movimentacao 
            WHERE tipo = 'Entrada' 
            AND validade IS NOT NULL 
            AND validade < CURDATE() 
        ");
        
        $vencidos = $stmtVenc->fetch(PDO::FETCH_ASSOC);
        $resumo['Vencimento'] = $vencidos['total'] ?? 0;

        return $resumo;
    }
}
?>