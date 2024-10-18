<?php
// Verifica se nenhuma sessão está ativa antes de iniciar uma nova
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servidor = "localhost";
$usuario = "root";
$senha = "";
$dbname = "bdExpressoR";

$conexao = mysqli_connect($servidor, $usuario, $senha, $dbname);

if ($conexao->connect_errno) {
    echo "Erro ao conectar ao banco de dados: " . $conexao->connect_error;
}
?>
