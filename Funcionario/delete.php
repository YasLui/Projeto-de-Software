<?php
// Inclua o arquivo de configuração
include_once('config.php');

// Verifique o tipo de funcionário e recupere o parâmetro de exclusão
if (isset($_GET['CdGerente'])) {
    $id = intval($_GET['CdGerente']);
    $tipo = 'Gerente';
} elseif (isset($_GET['CdEntregador'])) {
    $id = intval($_GET['CdEntregador']);
    $tipo = 'Entregador';
} else {
    die("Nenhum ID fornecido.");
}

// Prepare a consulta de exclusão com base no tipo
if ($tipo === 'Gerente') {
    $sqlDelete = "DELETE FROM TbGerente WHERE CdGerente=?";
} else {
    $sqlDelete = "DELETE FROM TbEntregador WHERE CdEntregador=?";
}

if ($stmt = $conexao->prepare($sqlDelete)) {
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        // Redireciona após exclusão
        header('Location: sistema.php');
        exit;
    } else {
        echo "Erro ao excluir o registro: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conexao->error;
}

$conexao->close();
?>
