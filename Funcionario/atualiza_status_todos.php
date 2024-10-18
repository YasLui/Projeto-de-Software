<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/BDExpresso/Funcionario/config.php';

$codigoRota = $_GET['codigoRota'] ?? null;

if ($codigoRota) {
    $sqlUpdate = "UPDATE TBPacote SET StatusPacote = CONCAT(IFNULL(StatusPacote, ''), '; Seu pacote saiu para a entrega') WHERE CdEntrega IN (SELECT Cd_Entrega FROM TbEntrega WHERE CdRotas = ?)";
    
    $stmtUpdate = $conexao->prepare($sqlUpdate);
    $stmtUpdate->bind_param("i", $codigoRota);
    $stmtUpdate->execute();

    // Verifica se a atualização foi bem-sucedida
    if ($stmtUpdate->affected_rows > 0) {
        $message = "Todos os pacotes foram atualizados com sucesso.";
    } else {
        $message = "Nenhum pacote foi atualizado.";
    }

    // Obtém os pacotes atualizados para retorno
    $stmtSelect = $conexao->prepare("SELECT Cd_Pacote, StatusPacote FROM TBPacote WHERE CdEntrega IN (SELECT Cd_Entrega FROM TbEntrega WHERE CdRotas = ?)");
    $stmtSelect->bind_param("i", $codigoRota);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();
    $pacotes = $result->fetch_all(MYSQLI_ASSOC);

    // Retorna o JSON
    echo json_encode(['message' => $message, 'pacotes' => $pacotes]);
}
?>
