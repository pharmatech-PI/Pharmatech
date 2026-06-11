<?php
require_once __DIR__ . '/../models/produto.php';

class ProdutoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome         = trim($_POST['nome'] ?? '');
            $sku          = trim($_POST['sku'] ?? '');
            
            $preco        = str_replace(',', '.', trim($_POST['preco'] ?? '0')); 
            
            $estoque      = (int)($_POST['estoque'] ?? 0);
            $status       = trim($_POST['status'] ?? 'Ativo');
            $categoria_id = (int)($_POST['categoria_id'] ?? 1); 

            $usuario_id = $_SESSION['usuario_id'] ?? 1; 
            
            $fornecedores = $_POST['fornecedores'] ?? []; 

            if (empty($nome) || empty($sku) || empty($preco)) {
                die("Erro: Preencha todos os campos obrigatórios.");
            }

            $produtoModel = new Produto($this->pdo);
            $resultado = $produtoModel->cadastrar($nome, $sku, $preco, $estoque, $status, $usuario_id, $categoria_id, $fornecedores);

            if ($resultado === true) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?sucesso=1");
                exit;
            } elseif ($resultado === 'sku_duplicado') {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?erro=sku_duplicado");
                exit;
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?erro=falha_cadastro");
                exit;
            }

        } else {
            echo "Acesso inválido.";
        }
    }


    public function excluir() {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            
            require_once __DIR__ . '/../models/produto.php';
            $produtoModel = new Produto($this->pdo);
            
            $inativou = $produtoModel->excluir($id);

            if ($inativou) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?status=inativado");
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?status=erro_inativar");
            }
            exit();
        }
    }



    public function atualizar_produto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);
            
            $nome         = trim($_POST['nome'] ?? '');
            $sku          = trim($_POST['sku'] ?? '');
            $preco        = str_replace(',', '.', trim($_POST['preco'] ?? '0')); 
            $estoque      = (int) ($_POST['estoque'] ?? 0);
            $status       = trim($_POST['status'] ?? 'Ativo');
            $categoria_id = (int) ($_POST['categoria_id'] ?? 0);
            $fornecedores = $_POST['fornecedores'] ?? []; 

            if ($id === 0 || empty($nome) || empty($sku)) {
                die("Erro: Dados obrigatórios não preenchidos.");
            }

            require_once __DIR__ . '/../models/produto.php';
            $produtoModel = new Produto($this->pdo);
            
            $atualizou = $produtoModel->atualizar($id, $nome, $sku, $preco, $estoque, $status, $categoria_id, $fornecedores);

            if ($atualizou) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?status=editado");
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/produtos.php?status=erro_editar");
            }
            exit();
        }
    }
}
?>