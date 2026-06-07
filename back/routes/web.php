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
        

    default:
        echo "<h1>Acesso negado ou rota não encontrada.</h1>";
        break;
}
?>