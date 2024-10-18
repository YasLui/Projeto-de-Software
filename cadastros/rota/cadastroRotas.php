<?php
session_start();
include_once('config.php');

// Inserir rota no banco de dados
if (isset($_POST['submit'])) {
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];

    $result = mysqli_query($conexao, "INSERT INTO TbRotas ( DsCidade, DsBairro) VALUES ('$cidade', '$bairro')");
}

// Buscar rotas no banco de dados
$result = mysqli_query($conexao, "SELECT * FROM TbRotas");

// Verifica se o formulário de exclusão foi enviado
if (isset($_POST['delete'])) {
    $cdRota = mysqli_real_escape_string($conexao, $_POST['cdRota']);
    
    $sqlDelete = "DELETE FROM TbRotas WHERE CdRotas = '$cdRota'";
    
    if (mysqli_query($conexao, $sqlDelete)) {
        $_SESSION['msg'] = "Rota excluída com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao excluir rota: " . mysqli_error($conexao);
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Entrega</title>
    <link rel="stylesheet" href="estiloEntrega.css">
    <style>
        body {
    background-image: url('papel.avif');
    background-size: cover; /* Cobre toda a tela */
    background-color: #121212; 
    color: #e0e0e0;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

h1 {
    color: #e27d3b; /* Cor do título */
    text-align: center;
    margin-top: 20px; /* Espaço no topo */
}

a {
    text-decoration: none;
    display: inline-block;  
    margin: 20px;
}

.voltar {
    color: #ffffff; /* Cor do link voltar */
}

.voltar:hover {
    transform: scale(1.1); /* Efeito de zoom ao passar o mouse */
}

.box {
    width: 90%; /* Manter um pouco de espaço nas laterais */
    padding: 20px;
    background-color: rgba(30, 30, 30, 0.8); /* Fundo escuro e semitransparente */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Sombra suave */
    margin: 20px auto; /* Centraliza a caixa */
    border-radius: 8px; /* Bordas arredondadas */
}

form {
    display: flex;
    flex-direction: column;
}

fieldset {
    color: #ccc;
    border: none;
    padding: 0;
}

.input-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
}

.row {
    display: flex;
    width: 100%;
    margin-bottom: 15px;
}

.col {
    flex: 1;
    margin-right: 10px;
}

.col:last-child {
    margin-right: 0; /* Remove o margin-right do último elemento */
}

.inputlabel {
    display: block;
    margin-bottom: 5px;
    color: #ccc; /* Cor dos rótulos */
}

input[type="text"],
input[type="number"],
input[type="date"],
input[type="time"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #555; /* Borda cinza escura */
    border-radius: 4px; /* Bordas arredondadas */
    box-sizing: border-box;
    background-color: #222; /* Fundo escuro */
    color: #fff; /* Texto claro */
    font-size: 15px;
}

button.submit {
    margin-left: auto; /* Alinha o botão à direita */
    display: block;
    padding: 10px 32px;
    border: none;
    color: white;
    background-color: #F28705; /* Fundo do botão */
    border-radius: 4px; /* Bordas arredondadas */
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button.submit:hover {
    background-color: #bd6800; /* Cor ao passar o mouse */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #333; /* Fundo da tabela */
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #e27d3b; /* Borda inferior com cor */
}

th {
    background-color: #595857; /* Fundo escuro */
    color: white;
}

.btn-cadastrar-horario {
    background-color: #8C8C8B; /* Cor do botão */
    border: none;
    cursor: pointer;
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.btn-cadastrar-horario:hover {
    background-color: #595857; /* Cor ao passar o mouse */
}

/* Estilos do modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #0D0F0E; /* Cor do fundo do modal */
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
    color: white;
    border-radius: 8px; /* Bordas arredondadas */
}

.close {
    color: #aaaaaa; /* Cor do botão de fechar */
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #ffffff; /* Muda a cor ao passar o mouse */
    text-decoration: none;
    cursor: pointer;
}
.btn-excluir {
    background-color: transparent; /* Fundo transparente */
    border: none; /* Remove borda */
    cursor: pointer; /* Muda o cursor para pointer */
    color: #f37e98; /* Cor do ícone */
    transition: color 0.3s ease; /* Transição suave para a cor */
}

.btn-excluir:hover {
    color: #f15b6c; /* Cor ao passar o mouse */
}

    </style>
</head>
<body>
    <h1>Cadastrar Rota</h1>

    <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>
    </a>

    <div class="box">
        <form action="cadastroRotas.php" method="POST">
            <fieldset>
                <div class="input-container">
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="bairro">Bairro:</label>
                            <input type="text" id="bairro" name="bairro" class="inputuser" placeholder="Entre com o Bairro" required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="cidade">Cidade:</label>
                            <input type="text" id="cidade" name="cidade" class="inputuser" placeholder="Entre com a Cidade" required>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="submit">Cadastrar Rota</button>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- Tabela de controle -->
    <div class="box">
        <h2>Controle de Rotas</h2>
        <table>
            <thead>
                <tr>
                    <th>Rota</th>
                    <th>Cidade</th>
                    <th>Bairro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['CdRotas']; ?></td>
                    <td><?php echo $row['DsCidade']; ?></td>
                    <td><?php echo $row['DsBairro']; ?></td>
                    <td>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="cdRota" value="<?php echo $row['CdRotas']; ?>">
                            <button type="submit" name="delete" onclick='return confirm("Tem certeza que deseja excluir esta rota?");' class="btn-excluir">
                                <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='28' height='28' viewBox='0 0 100 100'>
                                    <path fill='#f37e98' d='M25,30l3.645,47.383C28.845,79.988,31.017,82,33.63,82h32.74c2.613,0,4.785-2.012,4.985-4.617L75,30'></path>
                                    <path fill='#f15b6c' d='M65 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S65 36.35 65 38zM53 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S53 36.35 53 38zM41 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S41 36.35 41 38zM77 24h-4l-1.835-3.058C70.442 19.737 69.14 19 67.735 19h-35.47c-1.405 0-2.707.737-3.43 1.942L27 24h-4c-1.657 0-3 1.343-3 3s1.343 3 3 3h54c1.657 0 3-1.343 3-3S78.657 24 77 24z'></path>
                                    <path fill='#1f212b' d='M66.37 83H33.63c-3.116 0-5.744-2.434-5.982-5.54l-3.645-47.383 1.994-.154 3.645 47.384C29.801 79.378 31.553 81 33.63 81H66.37c2.077 0 3.829-1.622 3.988-3.692l3.645-47.385 1.994.154-3.645 47.384C72.113 80.566 69.485 83 66.37 83zM56 20c-.552 0-1-.447-1-1v-3c0-.552-.449-1-1-1h-8c-.551 0-1 .448-1 1v3c0 .553-.448 1-1 1s-1-.447-1-1v-3c0-1.654 1.346-3 3-3h8c1.654 0 3 1.346 3 3v3C57 19.553 56.552 20 56 20z'></path>
                                    <path fill='#1f212b' d='M77,31H23c-2.206,0-4-1.794-4-4s1.794-4,4-4h3.434l1.543-2.572C28.875,18.931,30.518,18,32.265,18h35.471c1.747,0,3.389,0.931,4.287,2.428L73.566,23H77c2.206,0,4,1.794,4,4S79.206,31,77,31z M23,25c-1.103,0-2,0.897-2,2s0.897,2,2,2h54c1.103,0,2-0.897,2-2s-0.897-2-2-2h-4c-0.351,0-0.677-0.185-0.857-0.485l-1.835-3.058C69.769,20.559,68.783,20,67.735,20H32.265c-1.048,0-2.033,0.559-2.572,1.457l-1.835,3.058C27.677,24.815,27.351,25,27,25H23z'></path>
                                    <path fill='#1f212b' d='M61.5 25h-36c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h36c.276 0 .5.224.5.5S61.776 25 61.5 25zM73.5 25h-5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5S73.776 25 73.5 25zM66.5 25h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S66.776 25 66.5 25zM50 76c-1.654 0-3-1.346-3-3V38c0-1.654 1.346-3 3-3s3 1.346 3 3v25.5c0 .276-.224.5-.5.5S52 63.776 52 63.5V38c0-1.103-.897-2-2-2s-2 .897-2 2v35c0 1.103.897 2 2 2s2-.897 2-2v-3.5c0-.276.224-.5.5-.5s.5.224.5.5V73C53 74.654 51.654 76 50 76zM62 76c-1.654 0-3-1.346-3-3V47.5c0-.276.224-.5.5-.5s.5.224.5.5V73c0 1.103.897 2 2 2s2-.897 2-2V38c0-1.103-.897-2-2-2s-2 .897-2 2v1.5c0 .276-.224.5-.5.5S59 39.776 59 39.5V38c0-1.654 1.346-3 3-3s3 1.346 3 3v35C65 74.654 63.654 76 62 76z'></path>
                                    <path fill='#1f212b' d='M59.5 45c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C60 44.776 59.776 45 59.5 45zM38 76c-1.654 0-3-1.346-3-3V38c0-1.654 1.346-3 3-3s3 1.346 3 3v35C41 74.654 39.654 76 38 76zM38 36c-1.103 0-2 .897-2 2v35c0 1.103.897 2 2 2s2-.897 2-2V38C40 36.897 39.103 36 38 36z'></path>
                                </svg>
                            </button>

                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

        </table>
    </div>
    
</body>
</html>
