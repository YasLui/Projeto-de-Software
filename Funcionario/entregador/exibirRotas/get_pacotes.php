<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdExpressoR";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$codigoRota = intval($_GET['codigoRota']);
$sqlPacotes = "
    SELECT 
        p.Cd_Pacote,
        p.NmEmpresaParceira,
        p.NmBairro,
        p.NmCidade,
        p.NuResidencia
    FROM 
        TbEntrega e
    JOIN 
        TBPacote p ON e.Cd_Entrega = p.CdEntrega
    WHERE 
        e.CdRotas = $codigoRota
";

$resultPacotes = $conn->query($sqlPacotes);

if ($resultPacotes->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Código do Pacote</th>
                <th>Empresa Parceira</th>
                <th>Bairro</th>
                <th>Cidade</th>
                <th>Residência</th>
            </tr>";
    while($row = $resultPacotes->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Cd_Pacote']}</td>
                <td>{$row['NmEmpresaParceira']}</td>
                <td>{$row['NmBairro']}</td>
                <td>{$row['NmCidade']}</td>
                <td>{$row['NuResidencia']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Nenhum pacote encontrado.";
}

$conn->close();
?>
