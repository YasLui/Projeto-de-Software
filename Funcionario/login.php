<?php
session_start();
include_once('config.php');

if (isset($_POST['submit'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $user_type = $_POST['user_type'];

    // Escolher a tabela com base no tipo de usuário
    if ($user_type == 'entregador') {
        $table = 'TbEntregador';
        $id_column = 'CdEntregador';
        $name_column = 'NmEntregador';
    } else if ($user_type == 'gerente') {
        $table = 'TbGerente';
        $id_column = 'CdGerente';
        $name_column = 'NmGerente';
    } else {
        $_SESSION['error_message'] = "Tipo de usuário inválido.";
        header('Location: login.php');
        exit();
    }

    // Verificar o login
    $stmt = $conexao->prepare("SELECT * FROM $table WHERE DsLogin = ? LIMIT 1");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($senha, $user['DsSenha'])) {
            // Sucesso no login
            $_SESSION['user_id'] = $user[$id_column];
            $_SESSION['user_name'] = $user[$name_column];
            $_SESSION['user_type'] = $user_type;

            // Redirecionar com base no tipo de usuário
            if ($user_type == 'entregador') {
                header('Location: /BDExpresso/Funcionario/entregador/index.html');
            } else if ($user_type == 'gerente') {
                header('Location: /BDExpresso/Funcionario/gerente/principal.php');
            }
            exit();
        } else {
            $_SESSION['error_message'] = "Senha incorreta.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Usuário não encontrado.";
        header('Location: login.php');
        exit();
    }

    $stmt->close();
    $conexao->close();

    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        background-image: url(/BDExpresso/6871864.jpg);
        margin-top: 10px;
    }

    div {
        border: 2px solid;
        border-color: rgba(0, 0, 0, 0.4);
        background-color: rgba(0, 0, 0, 0.4);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 80px;
        border-radius: 15px;
        color: white;
    }

    input {
        padding: 15px;
        border: none;
        outline: none;
        font-size: 15px;
    }

    .enviar {
        background-color: #f0861e;
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 15px;
        font-size: 15px;
        cursor: pointer;
    }

    .enviar:hover {
        background-color: #ff8a1e;
    }

    .voltar {
        padding: 10px;
        color: #ffffff;
    }

    .voltar:hover {
        width: 100%;
    }

    .reset-link {
        color: #ffffff;
        text-decoration: none;
        display: block;
        margin-top: 10px;
        text-align: center;
    }

    .reset-link:hover {
        text-decoration: underline;
    }

    /* Estilização do Modal */
    .modal-content {
        background-color: rgba(0, 0, 0, 0.8); /* Fundo do modal */
        border: none; /* Remove a borda padrão */
        border-radius: 15px; /* Bordas arredondadas */
        color: #ffffff; /* Cor do texto */
    }

    .modal-header {
        border-bottom: none; /* Remove a borda inferior do cabeçalho */
    }

    .modal-footer {
        border-top: none; /* Remove a borda superior do rodapé */
    }

    .close {
        color: #ffffff; /* Cor do botão de fechar */
        opacity: 0.8; /* Opacidade do botão de fechar */
    }

    .close:hover,
    .close:focus {
        opacity: 1; /* Aumenta a opacidade ao passar o mouse */
    }

    </style>
</head>
<body>
    <a class="voltar" href="/BDExpresso/Principal/Index/index.Html">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
        </svg>
    </a>
    <div>
        <h1>Login</h1>
        <form action="teslogin.php" method="POST">
            <input type="text" name="login" placeholder="Login" required>
            <br><br>
            <input type="password" name="senha" placeholder="Senha" required>
            <br><br>
            <label for="user_type">Tipo de Usuário:</label>
            <select name="user_type" id="user_type" required>
                <option value="entregador">Entregador</option>
                <option value="gerente">Gerente</option>
            </select>
            <br><br>
            <input class="enviar" type="submit" name="submit" value="Enviar">
        </form>
        <a class="reset-link" href="esqueceu_senha.php">Esqueceu a senha?</a>
    </div>



</body>
</html>