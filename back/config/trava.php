<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['lembrar_usuario'])) {
    
    require_once __DIR__ . '/config.php'; 

    $token = $_COOKIE['lembrar_usuario'];

    $stmt = $pdo->prepare("SELECT id, nome_completo FROM usuario WHERE token_lembrar = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome_completo'];
    }
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /PHARMATECH_PROJETO/Pharmatech/front/public/login.php?erro=autenticacao');
    exit;
}
?>