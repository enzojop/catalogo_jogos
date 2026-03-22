<?php
session_start();

function verificarAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] !== 'admin') {
        header('Location: ../pages/login.php?erro=acesso_negado');
        exit;
    }
}

function fazerLogout() {
    session_destroy();
    header('Location: ../pages/login.php?logout=1');
    exit;
}
