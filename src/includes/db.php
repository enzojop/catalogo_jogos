<?php

try {
    $db = new mysqli('localhost', 'root', '', 'bd_catalogo');
} catch (Exception $e) {
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}

$db->query("SET NAMES 'utf8'");
$db->query("SET character_set_connection=utf8");
$db->query("SET character_set_client=utf8");
$db->query("SET character_set_results=utf8");

// $busca = $db->query("SELECT * FROM generos");
// if(!$busca) {
//     echo "Erro na consulta: " . $db->error;
// } else {
//     while($reg = $busca->fetch_object()) {
//         print_r($reg);  
//     }
// }
