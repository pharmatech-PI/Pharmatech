<?php
require_once __DIR__ . '/../models/movimentacao.php';

class MovimentacaoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registrarEntrada() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $produto_id    = (int)($_POST['produto_id'] ?? 0);
            $quantidade    = (int)($_POST['quantidade'] ?? 0);
            $nota_fiscal   = trim($_POST['nota_fiscal'] ?? '');
            $lote          = trim($_POST['lote'] ?? '');
            $fornecedor_id = (int)($_POST['fornecedor_id'] ?? 0);
            $validade = !empty($_POST['validade']) ? trim($_POST['validade']) : null;
            
            $usuario_id = 1; 

            if ($produto_id === 0 || $quantidade <= 0 || empty($nota_fiscal)) {
                die("Erro: Produto, Quantidade (maior que zero) e Nota Fiscal são obrigatórios.");
            }

            $movimentacaoModel = new Movimentacao($this->pdo);
            $resultado = $movimentacaoModel->entrada($produto_id, $quantidade, $validade, $nota_fiscal, $lote, $fornecedor_id, $usuario_id);

            if ($resultado === true) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/entrada.php?sucesso=1");
                exit;
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/entrada.php?erro=falha_entrada");
                exit;
            }

        } else {
            echo "Acesso inválido.";
        }
    }


    public function registrarSaida() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $produto_id = (int)($_POST['produto_id'] ?? 0);
            $quantidade = (int)($_POST['quantidade'] ?? 0);
            $motivo     = trim($_POST['motivo'] ?? '');
            $lote       = trim($_POST['lote'] ?? '');
            
            $usuario_id = 1; 

            if ($produto_id === 0 || $quantidade <= 0) {
                die("Erro: Produto e Quantidade são obrigatórios e devem ser maiores que zero.");
            }

            $movimentacaoModel = new Movimentacao($this->pdo);
            
            $resultado = $movimentacaoModel->saida($produto_id, $quantidade, $motivo, $lote, $usuario_id);

            if ($resultado === true) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/saidas.php?sucesso=1");
                exit;
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/saidas.php?erro=estoque_insuficiente");
                exit;
            }

        } else {
            echo "Acesso inválido.";
        }
    }
}
?>