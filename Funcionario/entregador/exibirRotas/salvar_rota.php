<?php
session_start(); // Inicia a sessão

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigoRota'])) {
    $_SESSION['rotaEscolhida'] = intval($_POST['codigoRota']); // Armazena a rota escolhida
}
?>
