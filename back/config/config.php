<?php

define('URL_FRONT', 'http://localhost/Pharmatech/front/src/styles/pages');  
define('URL_BACK', 'http://localhost/Pharmatech/back/public/index.php');  

function conectarBanco() {
    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../../database/database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("PRAGMA foreign_keys = ON");
        return $db;
    } catch (PDOException $e) {
        die('Erro ao conectar no banco: ' . $e->getMessage());
    }
}