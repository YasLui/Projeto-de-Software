<?php
include_once('config.php'); // Inclua o arquivo de configuração para conectar ao banco de dados

// Recupera os veículos cadastrados
$veiculos = mysqli_query($conexao, "SELECT v.CdEntregador, e.NmEntregador, v.NuPlaca, v.DsModelo, v.DsAno 
                                    FROM TbVeiculo v 
                                    JOIN TbEntregador e ON v.CdEntregador = e.CdEntregador 
                                    ORDER BY e.NmEntregador ASC");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Veículos Cadastrados</title>
    <link rel="stylesheet" href="veiculo.css"> <!-- Reaproveitando o CSS existente -->
</head>
<body>
    <a href="/BDExpresso/gerente/principal.php">Voltar</a>
    <div class="box">
        <h2>Veículos Cadastrados</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Código do Entregador</th>
                    <th>Nome do Entregador</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Ano</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($veiculos) > 0) {
                    while ($row = mysqli_fetch_assoc($veiculos)) {
                        echo "<tr>";
                        echo "<td>" . $row['CdEntregador'] . "</td>";
                        echo "<td>" . $row['NmEntregador'] . "</td>";
                        echo "<td>" . $row['NuPlaca'] . "</td>";
                        echo "<td>" . $row['DsModelo'] . "</td>";
                        echo "<td>" . $row['DsAno'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum veículo cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
