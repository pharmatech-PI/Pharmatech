<?php
require_once __DIR__ . '/../app/controllers/authController.php';
require_once __DIR__ . '/../app/controllers/fornecedorController.php'; 
require_once __DIR__ . '/../app/controllers/produtoController.php';
require_once __DIR__ . '/../app/controllers/movimentacaoController.php';
require_once __DIR__ . '/../app/controllers/atualizarController.php';


$acao = $_GET['acao'] ?? '';

switch ($acao) {
    case 'registrar':
        $auth = new AuthController($pdo);
        $auth->registrar();
        break;

    case 'login':
        $auth = new AuthController($pdo);
        $auth->login();
        break;

    case 'cadastrar_fornecedor':                        
        $fornecedor = new FornecedorController($pdo);
        $fornecedor->cadastrar();
        break;

    case 'cadastrar_produto':
        $produto = new produtoController($pdo);
        $produto->cadastrar();
        break;
    
    case 'registrar_entrada':                        
        $movimentacao = new MovimentacaoController($pdo);
        $movimentacao->registrarEntrada();
        break;

    case 'registrar_saida':
        $movimentacaoController = new MovimentacaoController($pdo);
        $movimentacaoController->registrarSaida();
        break;

    case 'atualizar_perfil':
        $atualizarPerfil = new AtualizarController($pdo);
        $atualizarPerfil->atualizar();
        break;

    case 'excluir_produto':
        $produto = new produtoController($pdo);
        $produto->excluir();
        break;

    case 'atualizar_produto':
        $produto = new produtoController($pdo);
        $produto->atualizar_produto();
        break;

    case 'excluir_fornecedor':
        $fornecedorController = new FornecedorController($pdo);
        $fornecedorController->excluir();
        break;

    case 'atualizar_fornecedor':
        $fornecedorController = new FornecedorController($pdo);
        $fornecedorController->atualizar_fornecedor(); // Este método criamos no seu FornecedorController
        break;

    case 'buscar_fornecedores_ajax':
        $produto_id = (int)($_GET['produto_id'] ?? 0);
        
        require_once __DIR__ . '/../app/models/fornecedor.php';
        $fornecedorModel = new Fornecedor($pdo);
        $fornecedores = $fornecedorModel->buscarPorProduto($produto_id);

        header('Content-Type: application/json');
        
        echo json_encode($fornecedores);
        
        exit();
        break;

    case 'alterar_permissao_usuario':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== 'admin') {
            die("Acesso negado.");
        }

        $usuario_id = (int)($_GET['id'] ?? 0);
        $novo_nivel = trim($_GET['nivel'] ?? '');

        if ($usuario_id === (int)$_SESSION['usuario_id']) {
            header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?erro=auto_rebaixamento");
            exit();
        }

        require_once __DIR__ . '/../app/models/usuario.php';
        $usuarioModel = new Usuario($pdo);
        $alterou = $usuarioModel->alterarNivelAcesso($usuario_id, $novo_nivel);

        if ($alterou) {
            header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?status=permissao_alterada");
        } else {
            header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/configuracoes.php?status=erro_permissao");
        }
        exit();
        break;

    case 'alterar_status_usuario':
        require_once __DIR__ . '/../app/controllers/authController.php';
        $usuarioController = new authController($pdo);
        $alterouStatus = $usuarioController->alterar_status();
        break;   


    default:
        echo "<h1>Acesso negado ou rota não encontrada.</h1>";
        break;
}
?>