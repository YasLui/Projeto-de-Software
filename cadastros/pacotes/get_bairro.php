<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdExpressoR";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recupera o código da entrega da query string
$CdEntrega = intval($_GET['CdEntrega']);

// Busca o bairro correspondente ao código da entrega
$sql = "SELECT DsBairro FROM TbRotas WHERE CdRotas = (SELECT CdRotas FROM TbEntrega WHERE Cd_Entrega = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $CdEntrega);
$stmt->execute();
$result = $stmt->get_result();

$bairro = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bairro = $row['DsBairro'];
}

// Retorna o resultado como JSON
echo json_encode(['bairro' => $bairro]);

$stmt->close();
$conn->close();
?>
