<?php
// Conectar ao banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$dbname = "bdExpressoR";

$conexao = mysqli_connect($servidor, $usuario, $senha, $dbname);

if (!$conexao) {
    die("Erro de conexão: " . mysqli_connect_error());
}

// Inicializa variáveis para armazenar mensagens e dados
$msg = "";
$login = $novaSenha = $confirmarSenha = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se as chaves existem antes de acessar
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $novaSenha = isset($_POST['nova_senha']) ? $_POST['nova_senha'] : '';
    $confirmarSenha = isset($_POST['confirmar_senha']) ? $_POST['confirmar_senha'] : '';

    // Verifica se as senhas coincidem
    if ($novaSenha !== $confirmarSenha) {
        $msg = "As senhas não coincidem.";
    } else {
        // Verifica se o login existe nas tabelas TbGerente ou TbEntregador
        $sqlGerente = "SELECT DsLogin FROM TbGerente WHERE DsLogin = ?";
        $sqlEntregador = "SELECT DsLogin FROM TbEntregador WHERE DsLogin = ?";

        $stmtGerente = $conexao->prepare($sqlGerente);
        $stmtGerente->bind_param("s", $login);
        $stmtGerente->execute();
        $resultGerente = $stmtGerente->get_result();

        $stmtEntregador = $conexao->prepare($sqlEntregador);
        $stmtEntregador->bind_param("s", $login);
        $stmtEntregador->execute();
        $resultEntregador = $stmtEntregador->get_result();

        if ($resultGerente->num_rows > 0 || $resultEntregador->num_rows > 0) {
            // Atualiza a senha na tabela correta
            if ($resultGerente->num_rows > 0) {
                $updateSql = "UPDATE TbGerente SET DsSenha = ? WHERE DsLogin = ?";
                $stmtUpdate = $conexao->prepare($updateSql);
                $novaSenhaHashed = password_hash($novaSenha, PASSWORD_DEFAULT);
                $stmtUpdate->bind_param("ss", $novaSenhaHashed, $login);
                $stmtUpdate->execute();
            } elseif ($resultEntregador->num_rows > 0) {
                $updateSql = "UPDATE TbEntregador SET DsSenha = ? WHERE DsLogin = ?";
                $stmtUpdate = $conexao->prepare($updateSql);
                $novaSenhaHashed = password_hash($novaSenha, PASSWORD_DEFAULT);
                $stmtUpdate->bind_param("ss", $novaSenhaHashed, $login);
                $stmtUpdate->execute();
            }

            $msg = "Sua senha foi alterada com sucesso.";
        } else {
            $msg = "Login não encontrado.";
        }

        // Fecha a conexão
        $stmtGerente->close();
        $stmtEntregador->close();
    }
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
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
            width: 100%;
            margin-bottom: 10px;
        }
        .enviar {
            background-color: #f0861e;
            border: none;
            padding: 15px;
            border-radius: 15px;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
        }
        .enviar:hover {
            background-color: #ff8a1e;
        }
        .msg {
            color: #ff8a1e;
            text-align: center;
        }
    </style>
</head>
<body>
    <div>
        <h1>Redefinir Senha</h1>
        <form action="" method="POST">
            <input type="text" name="login" placeholder="Login" required value="<?php echo htmlspecialchars($login); ?>">
            <input type="password" name="nova_senha" placeholder="Nova Senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
            <input class="enviar" type="submit" name="submit" value="Alterar Senha">
        </form>
        <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
    </div>
</body>
</html>
