<?php
session_start();
if (isset($_POST['submit'])) {
    include_once('../config.php');
    
    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $residencia = $_POST['num'];
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografar a senha
    $CNH = $_POST['n_do_CNH'];

    // Inserir na tabela TbEntregador
    $result = mysqli_query($conexao, "INSERT INTO TbEntregador (NmEntregador, NmCidade, NmBairro, NmRua, NuResidencia, DsLogin, DsSenha, NuCNH) VALUES ('$nome', '$cidade', '$bairro', '$rua', '$residencia', '$login', '$senha', '$CNH')");

    if ($result) {
        // Define uma mensagem de sucesso na sessão
        $_SESSION['mensagem_sucesso'] = "Entregador cadastrado com sucesso!";
    } else {
        // Define uma mensagem de erro na sessão
        $_SESSION['mensagem_erro'] = "Erro ao cadastrar o entregador. Tente novamente.";
    }

    header('Location: cadastro.php');
    exit(); // Certifique-se de que o script seja encerrado após o redirecionamento
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Entregador</title>
    <link rel="stylesheet" href="styleCadast.css">
</head>

<body>

    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['mensagem_sucesso'])) {
            echo "<div class='mensagem sucesso'>" . $_SESSION['mensagem_sucesso'] . "</div>";
            unset($_SESSION['mensagem_sucesso']); // Remove a mensagem após exibi-la
        } elseif (isset($_SESSION['mensagem_erro'])) {
            echo "<div class='mensagem erro'>" . $_SESSION['mensagem_erro'] . "</div>";
            unset($_SESSION['mensagem_erro']); // Remove a mensagem após exibi-la
        }
    ?>


    <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
        </svg>
    </a>

    <h1>Cadastrar Entregador</h1>

    <div class="box">
    <form action="cadastro.php" method="POST">
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
                    
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="n_do_CNH">Nº da CNH:</label>
                            <input type="text" name="n_do_CNH" id="n_do_CNH" class="inputuser" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="submit" class="submit">Cadastrar</button>
                        <!-- Botão para abrir o modal -->
                    <button id="btnCadastrarVeiculo" class="aaaaaa">Cadastrar Veículo</button>
                </fieldset>
            </form>
    </div>

    

    <!-- Modal -->
    <div id="modalVeiculo" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <iframe src="/BDExpresso/cadastros/veiculo/cadastroVeiculo.php" width="100%" height="500px" style="border:none;"></iframe>
        </div>
    </div>

    <script>
        // JavaScript para abrir e fechar o modal
        var modal = document.getElementById("modalVeiculo");
        var btn = document.getElementById("btnCadastrarVeiculo");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>