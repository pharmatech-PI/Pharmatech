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


            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'cadastro_produto', ?, ?)
            ");
            
            session_start();
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';
            
            $stmtHist->execute([$usuario_logado, $nome, $estoque]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM produto"; 
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    public function listarPaginado($limite, $offset) {
        try {
            $limite_seguro = (int) $limite;
            $offset_seguro = (int) $offset;

            $sql = "SELECT produto.*, categoria.nome AS categoria_nome 
                    FROM produto 
                    LEFT JOIN categoria ON produto.categoria_id = categoria.id 
                    ORDER BY produto.id ASC 
                    LIMIT {$limite_seguro} OFFSET {$offset_seguro}";

            $stmt = $this->pdo->query($sql);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            die("Erro detectado: " . $e->getMessage());
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


    public function excluir($id) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmtNome = $this->pdo->prepare("SELECT nome FROM produto WHERE id = ?");
            $stmtNome->execute([$id]);
            $produto = $stmtNome->fetch(PDO::FETCH_ASSOC);
            $nomeProduto = $produto ? $produto['nome'] : 'Desconhecido';

            $stmtAssoc = $this->pdo->prepare("DELETE FROM fornecedor_produto WHERE produto_id = ?");
            $stmtAssoc->execute([$id]);

            $stmtProd = $this->pdo->prepare("DELETE FROM produto WHERE id = ?");
            $stmtProd->execute([$id]);

            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'exclusao_produto', ?, 0)
            ");
            $stmtHist->execute([$usuario_logado, $nomeProduto]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }



    public function atualizar($id, $nome, $sku, $preco, $estoque, $status, $categoria_id, $fornecedores) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                UPDATE produto 
                SET nome = ?, sku = ?, preco = ?, estoque = ?, status = ?, categoria_id = ? 
                WHERE id = ?
            ");
            $stmt->execute([$nome, $sku, $preco, $estoque, $status, $categoria_id, $id]);

            $stmtDel = $this->pdo->prepare("DELETE FROM fornecedor_produto WHERE produto_id = ?");
            $stmtDel->execute([$id]);

            if (!empty($fornecedores) && is_array($fornecedores)) {
                $stmtAssoc = $this->pdo->prepare("INSERT INTO fornecedor_produto (fornecedor_id, produto_id) VALUES (?, ?)");
                foreach ($fornecedores as $fornecedor_id) {
                    $stmtAssoc->execute([$fornecedor_id, $id]);
                }
            }

            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'edicao_produto', ?, ?)
            ");
            $stmtHist->execute([$usuario_logado, $nome, $estoque]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }



    public function listarUltimasNotificacoes($limite = 5) {
        try {
            $limite_seguro = (int) $limite;
            
            $sql = "SELECT usuario_nome, acao, produto_nome, quantidade, data_criacao 
                    FROM historico_movimentacao 
                    ORDER BY id DESC 
                    LIMIT {$limite_seguro}";
                    
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return []; 
        }
    }
}
?>