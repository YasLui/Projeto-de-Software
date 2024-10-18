<?php
if (!empty($_GET['CdRotas'])) {
    include_once('config.php');

    $id = $_GET['CdRotas'];

    // Prevenir injeção de SQL
    $id = intval($id);

    // Verifica se o registro existe antes de deletar
    $sqlSelect = "SELECT * FROM TbRotas WHERE CdRotas = ?";
    $stmt = $conexao->prepare($sqlSelect);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Registro encontrado, então pode deletar
        $sqlDelete = "DELETE FROM TbRotas WHERE CdRotas = ?";
        $stmtDelete = $conexao->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id);
        $stmtDelete->execute();
    }

    $stmt->close();
    $stmtDelete->close();
    $conexao->close();
}

header('Location: /BDExpresso/rota/sistemaRota.php');
?>
