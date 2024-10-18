<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <style>
        /* Estilos simples para o formulário */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Redefinir Senha</h2>
        <form action="redefinir_senha.php" method="POST">
            <label for="login">Informe seu login </label>
            <input type="text" name="login" placeholder="Digite seu login" required>
            <input type="submit" value="Redefinir Senha">
        </form>
    </div>
</body>
</html>
