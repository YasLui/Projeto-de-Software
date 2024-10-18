<?php
session_start();
include_once('config.php'); // Inclua o arquivo de configuração para conectar ao banco de dados

// Variáveis iniciais para os campos do formulário
$nome = $cidade = $bairro = $rua = $residencia = $login = $senha = $n_do_CNH = '';
$codigo = '';

// Verifica se o código do entregador foi fornecido
if (!empty($_GET['CdEntregador'])) {
    $codigo = intval($_GET['CdEntregador']); // Assegura que o código é um inteiro

    // Seleciona os dados do entregador
    $sqlSelect = "SELECT * FROM TbEntregador WHERE CdEntregador=$codigo";
    $result = $conexao->query($sqlSelect);

    if ($result->num_rows > 0) {
        // Preenche as variáveis com os dados do entregador
        $user_data = $result->fetch_assoc();
        $nome = $user_data['NmEntregador'];
        $cidade = $user_data['NmCidade'];
        $bairro = $user_data['NmBairro'];
        $rua = $user_data['NmRua'];
        $residencia = $user_data['NuResidencia'];
        $login = $user_data['DsLogin'];
        $senha = $user_data['DsSenha'];
        $n_do_CNH = $user_data['NuCNH'];
    } else {
        // Redireciona se o entregador não for encontrado
        header('Location: sistema.php');
        exit;
    }
}

// Processa o formulário quando enviado
if (isset($_POST['submit'])) {
    $codigo = intval($_POST['codigo']); // Assegura que o código é um inteiro
    $nome = $_POST['nome'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $residencia = $_POST['num'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $n_do_CNH = $_POST['n_do_CNH'];

    // Preparar a consulta para atualização
    $sqlUpdate = "UPDATE TbEntregador SET
        NmEntregador=?,
        NmCidade=?,
        NmBairro=?,
        NmRua=?,
        NuResidencia=?,
        DsLogin=?,
        DsSenha=?,
        NuCNH=?
        WHERE CdEntregador=?";

    // Usar prepared statements para evitar SQL Injection
    if ($stmt = $conexao->prepare($sqlUpdate)) {
        $stmt->bind_param('ssssssssi', $nome, $cidade, $bairro, $rua, $residencia, $login, $senha, $n_do_CNH, $codigo);

        // Executar a consulta
        if ($stmt->execute()) {
            header('Location: sistema.php');
            exit;
        } else {
            echo "Erro ao atualizar o registro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conexao->error;
    }
}   

$conexao->close();
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styleEdit.css">
</head>
<body>      
    <h1>editar cadastro</h1>

    <div class="box">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <fieldset>
                <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($codigo); ?>">
                <div class="input-container">
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" class="inputuser" value="<?php echo htmlspecialchars($nome); ?>"  required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="cidade">Cidade:</label>
                            <input type="text" name="cidade" id="cidade" class="inputuser" value="<?php echo htmlspecialchars($cidade); ?>"  required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="bairro">Bairro:</label>
                            <input type="text" name="bairro" id="bairro" class="inputuser" value="<?php echo htmlspecialchars($bairro); ?>"  required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-rua">
                            <label class="inputlabel" for="rua">Rua:</label>
                            <input type="text" name="rua" id="rua" class="inputrua" value="<?php echo htmlspecialchars($rua); ?>"  required>
                        </div>
                        <div class="col-num">
                            <label class="inputlabel" for="num">Nº</label>
                            <input type="text" name="num" id="num" class="inputnum" value="<?php echo htmlspecialchars($residencia); ?>"  required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="login">Login:</label>
                            <input type="text" name="login" id="login" class="inputuser" value="<?php echo htmlspecialchars($login); ?>"  required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="senha">Senha:</label>
                            <input type="password" name="senha" id="senha" class="inputuser" value="<?php echo htmlspecialchars($senha); ?>"  required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="n_do_CNH">Nº da CNH:</label>
                            <input type="text" name="n_do_CNH" id="n_do_CNH" class="inputuser" value="<?php echo htmlspecialchars($n_do_CNH); ?>"   required>
                        </div>
                    </div>
                    
                    <button type="submit" name="submit" class="submit">atualizar</button>
                </fieldset>
            </form>
        </div>
    </body>
</html>
