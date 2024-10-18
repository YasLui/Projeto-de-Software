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

// Recupera os dados do formulário
$NmEmpresaParceira = $_POST['NmEmpresaParceira'];
$NmCidade = $_POST['NmCidade'];
$NmBairro = $_POST['NmBairro'];
$NmRua = $_POST['NmRua'];
$NuResidencia = $_POST['NuResidencia'];
$QtTentativas = $_POST['QtTentativas'];
$CdEntrega = $_POST['CdEntrega'];
$HrChegadaPacote = $_POST['HrChegadaPacote']; // Adiciona ":00" para os segundos

// Inicializa a variável de erro
$errorMessage = "";

// Recupera o bairro associado à entrega
$result = $conn->query("SELECT DsBairro FROM TbRotas WHERE CdRotas = (SELECT CdRotas FROM TbEntrega WHERE Cd_Entrega = '$CdEntrega')");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bairroRota = $row['DsBairro'];

    // Verifica se os bairros correspondem
    if ($bairroRota === $NmBairro) {
        inserirPacote($conn, $NmEmpresaParceira, $NmCidade, $NmBairro, $NmRua, $NuResidencia, $QtTentativas, $CdEntrega, $HrChegadaPacote);
    } else {
        $errorMessage = "O bairro do pacote não corresponde ao bairro da rota!";
    }
} else {
    $errorMessage = "O código da entrega não foi encontrado!";
}

function inserirPacote($conn, $NmEmpresaParceira, $NmCidade, $NmBairro, $NmRua, $NuResidencia, $QtTentativas, $CdEntrega, $HrChegadaPacote) {
    // Mensagem de status
    $status = "Seu pacote chegou ao centro logístico"; 

    // Prepara e vincula
    $stmt = $conn->prepare("INSERT INTO TBPacote (NmEmpresaParceira, NmCidade, NmBairro, NmRua, NuResidencia, QtTentativas, CdEntrega, HrChegadaPacote, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissis", $NmEmpresaParceira, $NmCidade, $NmBairro, $NmRua, $NuResidencia, $QtTentativas, $CdEntrega, $HrChegadaPacote, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Novo pacote cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar o pacote: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pacote</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Modal de Erro -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if (!empty($errorMessage)) {
                    echo htmlspecialchars($errorMessage);
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if (!empty($errorMessage)): ?>
        $('#errorModal').modal('show');
    <?php endif; ?>
});
</script>

</body>
</html>
