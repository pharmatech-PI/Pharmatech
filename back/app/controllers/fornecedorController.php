<?php
require_once __DIR__ . '/../models/fornecedor.php';

class FornecedorController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $polo        = trim($_POST['polo'] ?? '');
            $razaoSocial = trim($_POST['razao_social'] ?? '');
            $nomeFantasia= trim($_POST['nome_fantasia'] ?? '');
            $cnpj        = trim($_POST['cnpj'] ?? '');
            $localidade  = trim($_POST['localidade'] ?? '');

            if (empty($polo) || empty($razaoSocial) || empty($cnpj) || empty($localidade)) {
                die("Erro: Preencha todos os campos obrigatórios.");
            }

            $fornecedorModel = new Fornecedor($this->pdo);
            $cadastrou = $fornecedorModel->cadastrar($polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade);

            if ($cadastrou) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?sucesso=1");
                exit;
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?erro=cnpj_duplicado");
                exit;
            }

        } else {
            echo "Acesso inválido.";
        }
    }


    public function atualizar_fornecedor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id           = (int) ($_POST['id'] ?? 0);
            $polo         = trim($_POST['polo'] ?? '');
            $razaoSocial  = trim($_POST['razao_social'] ?? '');
            $nomeFantasia = trim($_POST['nome_fantasia'] ?? '');
            $cnpj         = trim($_POST['cnpj'] ?? '');
            $localidade   = trim($_POST['localidade'] ?? '');

            if ($id === 0 || empty($nomeFantasia) || empty($cnpj)) {
                die("Erro: Dados obrigatórios não preenchidos.");
            }

            require_once __DIR__ . '/../models/fornecedor.php';
            $fornecedorModel = new Fornecedor($this->pdo);
            
            $atualizou = $fornecedorModel->atualizar($id, $polo, $razaoSocial, $nomeFantasia, $cnpj, $localidade);

            if ($atualizou) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?status=editado");
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?status=erro_editar");
            }
            exit();
        }
    }

    public function excluir() {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            
            require_once __DIR__ . '/../models/fornecedor.php';
            $fornecedorModel = new Fornecedor($this->pdo);
            
            $inativou = $fornecedorModel->excluir($id);

            if ($inativou) {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?status=inativado");
            } else {
                header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/fornecedores.php?status=erro_inativar");
            }
            exit();
        }
    }





}
?>