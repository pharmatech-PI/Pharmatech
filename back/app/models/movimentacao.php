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
            // Usamos a sua correção crucial da sessão para garantir o nome do usuário ativo
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            // 1. Seleciona o estoque atual E o nome do produto para a notificação
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

            // 2. Registra na tabela interna de movimentações completas
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

            // 3. Atualiza o estoque do produto
            $stmtUpdate = $this->pdo->prepare("UPDATE produto SET estoque = ? WHERE id = ?");
            $stmtUpdate->execute([$qtd_depois, $produto_id]);

            // =================================================================
            // 🔥 NOVO: ALIMENTA O SININHO DE NOTIFICAÇÕES (ENTRADA)
            // =================================================================
            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'entrada', ?, ?)
            ");
            $stmtHist->execute([$usuario_logado, $nome_produto, $quantidade]);
            // =================================================================

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

            // 1. Verifica o estoque atual E o nome do produto
            $stmtProduto = $this->pdo->prepare("SELECT nome, estoque FROM produto WHERE id = ?");
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            // 2. Trava de Segurança
            if (!$produto || $produto['estoque'] < $quantidade) {
                $this->pdo->rollBack();
                return false; 
            }

            $nome_produto = $produto['nome'];
            $qtd_antes = (int)$produto['estoque'];
            $qtd_depois = $qtd_antes - $quantidade; 

            // 3. Registra a movimentação no histórico detalhado
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

            // 4. Atualiza o produto com o novo estoque reduzido
            $stmtUpdate = $this->pdo->prepare("UPDATE produto SET estoque = ? WHERE id = ?");
            $stmtUpdate->execute([$qtd_depois, $produto_id]);

            // =================================================================
            // 🔥 NOVO: ALIMENTA O SININHO DE NOTIFICAÇÕES (SAÍDA)
            // =================================================================
            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'saida', ?, ?)
            ");
            $stmtHist->execute([$usuario_logado, $nome_produto, $quantidade]);
            // =================================================================

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


     public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM movimentacao"; 
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }


    public function listarPaginado($limite, $offset) {
        try {
            $limite_seguro = (int) $limite;
            $offset_seguro = (int) $offset;

            $sql = "SELECT m.*, p.nome AS produto_nome 
                FROM movimentacao m 
                INNER JOIN produto p ON m.produto_id = p.id 
                ORDER BY m.id DESC LIMIT {$limite_seguro} OFFSET {$offset_seguro}";

            $stmt = $this->pdo->query($sql);
            
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