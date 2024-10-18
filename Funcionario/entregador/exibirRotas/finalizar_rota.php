<?php
session_start(); // Inicia a sessão
include_once $_SERVER['DOCUMENT_ROOT'].'/BDExpresso/Funcionario/config.php';

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

$codigoRota = isset($_POST['codigoRota']) ? $_POST['codigoRota'] : null;

// Verifica se o código da rota foi enviado
if ($codigoRota === null) {
    echo "erro_rota_invalida";
    exit;
}

// Consulta para verificar se há pacotes na rota que não tiveram a entrega registrada
$sqlVerificar = "
    SELECT COUNT(*) AS PacotesNaoRegistrados
    FROM TBPacote p
    JOIN TbEntrega e ON p.CdEntrega = e.Cd_Entrega
    WHERE e.CdRotas = ? AND (p.FoiEntrega IS NULL OR p.FoiEntrega = '')
";

// Prepara e executa a consulta
$stmt = $conexao->prepare($sqlVerificar);
$stmt->bind_param("i", $codigoRota);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Verifica se há pacotes sem registro de entrega
if ($row['PacotesNaoRegistrados'] > 0) {
    echo "erro_pacotes_nao_registrados";
} else {
    // Se todos os pacotes tiverem registro de entrega, permita finalizar a rota
    echo "rota_finalizada";
}

$stmt->close();
mysqli_close($conexao);
?>
