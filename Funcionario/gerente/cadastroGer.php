<?php
    session_start();
    if(isset($_POST['submit']))
    {
        include_once('../config.php');
       $nomeGer = $_POST['nome'];  
       $cidadeGer = $_POST['cidade'];
       $bairroGer = $_POST['bairro'];
       $ruaGer = $_POST['rua'];
       $residenciaGer = $_POST['num'];
       $loginGer = $_POST['login'];
       $senhaGer = password_hash($_POST['senha'], PASSWORD_BCRYPT);

       $result = mysqli_query($conexao, "INSERT INTO TbGerente
       VALUES ('', '$nomeGer', '$cidadeGer', '$bairroGer', '$ruaGer', '$residenciaGer', '$loginGer', '$senhaGer' )");

       header('Location: ../sistema.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styleCadaa.css">
</head>
<body>
        <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php" >
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
            </svg>
        </a>
        
        <h1>Cadastrar Gerente</h1>

    <div class="box">
        <form action="/BDExpresso/Funcionario/gerente/cadastroGer.php" method="POST">
            <fieldset>
                <div class="input-container">
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" class="inputuser" required>
                        </div>

                        <div class="col">
                            <label class="inputlabel" for="cidade">Cidade:</label>
                            <input type="text" name="cidade" id="cidade" class="inputuser" required>
                        </div>
                        
                        <div class="col">
                            <label class="inputlabel" for="bairro">Bairro:</label>
                            <input type="text" name="bairro" id="bairro" class="inputuser" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-rua">
                            <label class="inputlabel" for="rua">Rua:</label>
                            <input type="text" name="rua" id="rua" class="inputrua" required>
                        </div>
                        <div class="col-num">
                            <label class="inputlabel" for="num">Nº</label>
                            <input type="text" name="num" id="num" class="inputnum" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="login">Login:</label>
                            <input type="text" name="login" id="login" class="inputuser" required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="senha">Senha:</label>
                            <input type="password" name="senha" id="senha" class="inputuser" required>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="submit">Cadastrar</button>
                </fieldset>
            </form>
        </div>
    </body>
</html>
