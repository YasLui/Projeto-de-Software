<?php
session_start();
include_once('config.php');

$errorMessage = ""; // Variável para armazenar mensagens de erro

if (isset($_POST['submit'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $user_type = $_POST['user_type'];

    // Escolher a tabela com base no tipo de usuário
    if ($user_type == 'entregador') {
        $table = 'TbEntregador';
        $id_column = 'CdEntregador'; // Nome da coluna ID para entregadores
        $name_column = 'NmEntregador'; // Nome da coluna do nome para entregadores
    } else if ($user_type == 'gerente') {
        $table = 'TbGerente';
        $id_column = 'CdGerente'; // Nome da coluna ID para gerentes
        $name_column = 'NmGerente'; // Nome da coluna do nome para gerentes
    } else {
        die("Tipo de usuário inválido.");
    }

    // Verificar o login
    $stmt = $conexao->prepare("SELECT * FROM $table WHERE DsLogin = ? LIMIT 1");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar a senha usando password_verify
        if (password_verify($senha, $user['DsSenha'])) {
            // Sucesso no login
            $_SESSION['user_id'] = $user[$id_column];
            $_SESSION['user_name'] = $user[$name_column]; // Armazenar o nome do usuário na sessão
            $_SESSION['user_type'] = $user_type;

            // Redirecionar com base no tipo de usuário
            if ($user_type == 'entregador') {
                header('Location: /BDExpresso/Funcionario/entregador/index.html'); // Página para entregadores
            } else if ($user_type == 'gerente') {
                header('Location: /BDExpresso/Funcionario/gerente/principal.php'); // Página para gerentes
            }
            exit();
        } else {
            $errorMessage = "Senha incorreta.";    
        }
    } else {
        $errorMessage = "Usuário não encontrado.";
    }

    $stmt->close();
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                // Exibe a mensagem de erro no modal se houver uma
                if (!empty($errorMessage)) {
                    echo htmlspecialchars($errorMessage);
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeButton">Fechar</button>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Exibe o modal se houver uma mensagem de erro
    <?php if (!empty($errorMessage)): ?>
        $('#errorModal').modal('show');
    <?php endif; ?> 

    // Redirecionar ao fechar o modal
    $('#closeButton').on('click', function() {
        window.location.href = 'login.php'; // Altere 'login.php' para o caminho correto do seu arquivo de login
    });
});
</script>


</body>
</html>
