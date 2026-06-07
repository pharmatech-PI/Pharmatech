<?php
session_start();

if (isset($_COOKIE['lembrar_usuario'])) {
    setcookie('lembrar_usuario', '', time() - 3600, '/');
}

session_unset(); 
session_destroy(); 

header("Location: /PHARMATECH_PROJETO/Pharmatech/front/public/login.php");
exit();
?>