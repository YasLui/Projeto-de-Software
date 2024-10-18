<?php
session_start();
include_once('config.php'); // Inclua o arquivo de configuração para conectar ao banco de dados

// Variáveis iniciais para os campos do formulário
$bairroRota = $cidadeRota = '';
$codigoRota = '';

// Verifica se o código da rota foi fornecido
if (!empty($_GET['CdRotas'])) {
    $codigoRota = intval($_GET['CdRotas']); // Assegura que o código é um inteiro

    // Seleciona os dados da rota
    $sqlSelect = "SELECT * FROM TbRotas WHERE CdRotas=$codigoRota";
    $result = $conexao->query($sqlSelect);

    if ($result->num_rows > 0) {
        // Preenche as variáveis com os dados da rota
        $rota_data = $result->fetch_assoc();
        $bairroRota = $rota_data['DsBairro'];
        $cidadeRota = $rota_data['DsCidade'];
    } else {
        // Redireciona se a rota não for encontrada
        header('Location: /BDExpresso/rota/sistemaRota.php');
        exit;
    }
}

// Processa o formulário quando enviado
if (isset($_POST['submit'])) {
    $codigoRota = intval($_POST['codigo']); // Assegura que o código é um inteiro
    $bairroRota = $_POST['bairro'];
    $cidadeRota = $_POST['cidade'];

    // Preparar a consulta para atualização
    $sqlUpdate = "UPDATE TbRotas SET
        DsBairro=?,
        DsCidade=?
        WHERE CdRotas=?";

    // Usar prepared statements para evitar SQL Injection
    if ($stmt = $conexao->prepare($sqlUpdate)) {
        $stmt->bind_param('ssi', $bairroRota, $cidadeRota, $codigoRota);

        // Executar a consulta
        if ($stmt->execute()) {
            echo "Registro atualizado com sucesso!";
            header('Location: /BDExpresso/rota/sistemaRota.php');
            exit;
        } else {
            echo "Erro ao atualizar o registro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conexao->error;
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rota</title>
    <link rel="stylesheet" href="styleRota.css">
</head>
<body>
    <a href="/BDExpresso/rota/sistemaRota.php">Voltar</a>
    <div class="box">
        <form action="editaRota.php" method="POST">
            <fieldset>
                <legend><b>Editar Rota</b></legend>
                <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($codigoRota); ?>">

                <div class="inputbox">
                    <label class="inputlabel" for="bairro">Bairro:</label>
                    <input type="text" name="bairro" id="bairro" class="inputuser" value="<?php echo htmlspecialchars($bairroRota); ?>" required>
                </div>

                <br><br>

                <div>
                    <label class="inputlabel" for="cidade">Cidade:</label>
                    <input type="text" name="cidade" id="cidade" class="inputuser" value="<?php echo htmlspecialchars($cidadeRota); ?>" required>
                </div>

                <br><br>

                <button type="submit" name="submit" class="submit">Confirmar edição</button>
            </fieldset>
        </form>
    </div>
</body>
</html>
