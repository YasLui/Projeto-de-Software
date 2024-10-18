<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/BDExpresso/Funcionario/config.php';

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

$cdPacote = isset($_POST['cdPacote']) ? $_POST['cdPacote'] : null;
$hrEntrega = isset($_POST['hrEntrega']) ? $_POST['hrEntrega'] : null;
$nmRecebeu = isset($_POST['nmRecebeu']) ? $_POST['nmRecebeu'] : null;
$foiEntrega = isset($_POST['foiEntrega']) ? $_POST['foiEntrega'] : null;

// Verifica se todos os dados obrigatórios foram enviados
if ($cdPacote === null || $hrEntrega === null || $foiEntrega === null) {
    echo "entrega_nao_registrada"; // Dados faltando
    exit;
}

// Atualiza os dados da entrega no banco de dados
$sqlUpdate = "
    UPDATE TBPacote
    SET HrEntrega = ?, NmRecebeu = ?, FoiEntrega = ?
    WHERE Cd_Pacote = ?
";

$stmt = $conexao->prepare($sqlUpdate);
$stmt->bind_param("sssi", $hrEntrega, $nmRecebeu, $foiEntrega, $cdPacote);

if ($stmt->execute()) {
    echo "entrega_registrada"; // Sucesso
} else {
    echo "entrega_nao_registrada"; // Falha
}

$stmt->close();
mysqli_close($conexao);
?>
