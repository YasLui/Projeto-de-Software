<?php
include_once('config.php'); // Inclua o arquivo de configuração para conectar ao banco de dados

// Recupera os entregadores cadastrados
$entregadores = mysqli_query($conexao, "SELECT CdEntregador, NmEntregador FROM TbEntregador ORDER BY NmEntregador ASC");

if (isset($_POST['submit'])) {
    // Receber dados do formulário
    $cdEntregador = intval($_POST['cdEntregador']); // Assegura que o código é um inteiro
    $nuPlaca = $_POST['nuPlaca'];
    $dsModelo = $_POST['dsModelo'];
    $dsAno = isset($_POST['dsAno']) ? intval($_POST['dsAno']) : null; // Verifica se o ano foi fornecido

    // Preparar a consulta para inserção
    $sqlInsert = "INSERT INTO TbVeiculo (CdEntregador, NuPlaca, DsModelo, DsAno) VALUES (?, ?, ?, ?)";

    // Usar prepared statements para evitar SQL Injection
    if ($stmt = $conexao->prepare($sqlInsert)) {
        $stmt->bind_param('issi', $cdEntregador, $nuPlaca, $dsModelo, $dsAno);

        // Executar a consulta
        if ($stmt->execute()) {
            echo "Veículo cadastrado com sucesso!";
            header('Location: /BDExpresso/cadastros/veiculo/cadastroVeiculo.php'); 
            exit;
        } else {
            echo "Erro ao cadastrar o veículo: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conexao->error;
    }

    $conexao->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Veículo</title>
    <link rel="stylesheet" href="veiculooo.css">
</head>
<body>
    
    <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>
    </a>

    <div class="box">
        <form action="cadastroVeiculo.php" method="POST">
            <fieldset>
                <legend><b>Cadastro de Veículo</b></legend>

                <div class="inputbox">
                    <label class="inputlabel" for="cdEntregador">Código do Entregador:</label>
                    <select name="cdEntregador" id="cdEntregador" class="inputuser" required>
                        <option value="">Selecione um entregador</option>
                        <?php
                        // Preenche o select com os códigos e nomes dos entregadores
                        while ($row = mysqli_fetch_assoc($entregadores)) {
                            echo "<option value='" . $row['CdEntregador'] . "'>" . $row['NmEntregador'] . " (ID: " . $row['CdEntregador'] . ")</option>";
                        }
                        ?>
                    </select>
                </div>

                <br><br>

                <div class="inputbox">
                    <label class="inputlabel" for="nuPlaca">Placa:</label>
                    <input type="text" name="nuPlaca" id="nuPlaca" class="inputuser" required>
                </div>

                <br><br>

                <div class="inputbox">
                    <label class="inputlabel" for="dsModelo">Modelo:</label>
                    <input type="text" name="dsModelo" id="dsModelo" class="inputuser">
                </div>

                <br><br>

                <div class="inputbox">
                    <label class="inputlabel" for="dsAno">Ano:</label>
                    <input type="number" name="dsAno" id="dsAno" class="inputuser">
                </div>

                <br><br>

                <button type="submit" name="submit" class="submit">Cadastrar Veículo</button>
            </fieldset>
        </form>
    </div>
</body>
</html>
