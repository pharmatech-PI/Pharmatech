<?php
class Fornecedor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar($polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                    session_start();
            }

            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("SELECT id FROM fornecedor WHERE cnpj = ?");
            $stmt->execute([$cnpj]);
            if ($stmt->rowCount() > 0) {
                $this->pdo->rollBack();
                return false;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO fornecedor (polo, razao_social, nome_fantasia, cnpj, localidade) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade]);

            $stmtHist = $this->pdo->prepare("INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade)
            VALUES (?, 'cadastro_fornecedor', ?, 0)");

            $stmtHist->execute([$usuario_logado, $nomeFantasia]);

            $this->pdo->commit();

            return true;
        }  catch (Exception $e) {
            $this->pdo->rollBack(); 

            echo "<h1>Erro no banco: " . $e->getMessage() . "</h1>";
            
            die();
        }   
    }

    public function contarTotal($busca = '') {
        if (!empty($busca)) {
            $sql = "SELECT COUNT(*) as total FROM fornecedor WHERE razao_social LIKE :busca OR cnpj LIKE :busca";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':busca', '%' . $busca . '%');
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT COUNT(*) as total FROM fornecedor"; 
            $stmt = $this->pdo->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return (int) $resultado['total'];
    }

    public function listarPaginado($limite, $offset, $busca = '') {
        try {
            $limite_seguro = (int) $limite;
            $offset_seguro = (int) $offset;

            if (!empty($busca)) {
                $sql = "SELECT * FROM fornecedor 
                        WHERE razao_social LIKE :busca OR cnpj LIKE :busca 
                        ORDER BY id ASC 
                        LIMIT :limite OFFSET :offset";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':busca', '%' . $busca . '%');
                $stmt->bindValue(':limite', $limite_seguro, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset_seguro, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $sql = "SELECT * FROM fornecedor 
                        ORDER BY id ASC 
                        LIMIT :limite OFFSET :offset";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':limite', $limite_seguro, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset_seguro, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            die("Erro detectado: " . $e->getMessage());
        }
    }

    public function atualizar($id, $polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                UPDATE fornecedor 
                SET polo = ?, razao_social = ?, nome_fantasia = ?, cnpj = ?, localidade = ? 
                WHERE id = ?
            ");
            $stmt->execute([$polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade, $id]);

            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'edicao_fornecedor', ?, 0)
            ");
            $stmtHist->execute([$usuario_logado, $nomeFantasia]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function excluir($id) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuario_logado = $_SESSION['usuario_nome'] ?? 'Administrador';

            $this->pdo->beginTransaction();

            $stmtNome = $this->pdo->prepare("SELECT nome_fantasia FROM fornecedor WHERE id = ?");
            $stmtNome->execute([$id]);
            $fornecedor = $stmtNome->fetch(PDO::FETCH_ASSOC);
            $nomeFornecedor = $fornecedor ? $fornecedor['nome_fantasia'] : 'Desconhecido';

            $stmtDel = $this->pdo->prepare("UPDATE fornecedor SET status = 'Inativo' WHERE id = ?");
            $stmtDel->execute([$id]);

            $stmtHist = $this->pdo->prepare("
                INSERT INTO historico_movimentacao (usuario_nome, acao, produto_nome, quantidade) 
                VALUES (?, 'inativacao_fornecedor', ?, 0)
            ");
            $stmtHist->execute([$usuario_logado, $nomeFornecedor]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }


    public function buscarPorProduto($produto_id) {

        $sql = "SELECT f.id, f.nome_fantasia, f.cnpj 
                FROM fornecedor f
                INNER JOIN fornecedor_produto fp ON f.id = fp.fornecedor_id
                WHERE fp.produto_id = ? AND f.status = 'Ativo'";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$produto_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarAtivos() {
        $sql = "SELECT * FROM fornecedor WHERE status = 'Ativo' ORDER BY nome_fantasia ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>