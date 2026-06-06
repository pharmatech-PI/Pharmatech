<?php
require_once __DIR__ . '/../models/produto.php';

class ProdutoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cadastrar() {
        // Confere se a requisição veio mesmo de um formulário (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Recebemos os dados de texto e números do formulário
            $nome         = trim($_POST['nome'] ?? '');
            $sku          = trim($_POST['sku'] ?? '');
            
            // O PHP e o banco de dados preferem ponto em vez de vírgula para centavos (ex: 12.90)
            $preco        = str_replace(',', '.', trim($_POST['preco'] ?? '0')); 
            
            $estoque      = (int)($_POST['estoque'] ?? 0);
            $status       = trim($_POST['status'] ?? 'Ativo');
            $categoria_id = (int)($_POST['categoria_id'] ?? 1); 
            $usuario_id   = 1; // Temporário. Depois podemos puxar do usuário que está logado
            
            // 2. A MÁGICA: Recebemos a lista de fornecedores que o usuário selecionou no modal
            // Usamos o "[]" no final para garantir que, se vier vazio, ele crie uma lista vazia e não dê erro
            $fornecedores = $_POST['fornecedores'] ?? []; 

            // 3. Validação básica de segurança
            if (empty($nome) || empty($sku) || empty($preco)) {
                die("Erro: Preencha todos os campos obrigatórios.");
            }

            // 4. Chamamos o Model para fazer o trabalho pesado (salvar no banco)
            $produtoModel = new Produto($this->pdo);
            $resultado = $produtoModel->cadastrar($nome, $sku, $preco, $estoque, $status, $usuario_id, $categoria_id, $fornecedores);

            // 5. Verificamos a resposta do Model e redirecionamos a tela
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
}
?>