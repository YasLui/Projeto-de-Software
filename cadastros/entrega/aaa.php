<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/BDExpresso/Funcionario/config.php';

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verifica se o formulário de entrega foi enviado
if (isset($_POST['submit'])) {
    $cdEntrega = mysqli_real_escape_string($conexao, $_POST['CdEntrega']);
    $cdRotas = mysqli_real_escape_string($conexao, $_POST['cdRotas']);
    $dtEntrega = mysqli_real_escape_string($conexao, $_POST['dtEntrega']);

    $sqlInsert = "INSERT INTO TbEntrega (Cd_Entrega, CdRotas, DtEntrega) 
                  VALUES ('$cdEntrega', '$cdRotas', '$dtEntrega')";

    if (mysqli_query($conexao, $sqlInsert)) {
        $_SESSION['msg'] = "Entrega cadastrada com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao cadastrar entrega: " . mysqli_error($conexao);
    }
}
$delivererCode = isset($_POST['delivererCode']) ? $_POST['delivererCode'] : null;

// Verifica se o formulário de horários foi enviado
if (isset($_POST['submitHorario'])) {
    $cdEntrega = mysqli_real_escape_string($conexao, $_POST['cdEntrega']);
    $hrSaida = mysqli_real_escape_string($conexao, $_POST['hrSaida']);
    $hrChegada = mysqli_real_escape_string($conexao, $_POST['hrChegada']);

    // Atualizar a entrega com os horários
    $sqlUpdate = "UPDATE TbEntrega SET HrSaida = '$hrSaida', HrChegada = '$hrChegada', CdEntregador = '$delivererCode' WHERE Cd_Entrega = '$cdEntrega'";

    if (mysqli_query($conexao, $sqlUpdate)) {
        $_SESSION['msg'] = "Horários cadastrados com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao cadastrar horários: " . mysqli_error($conexao);
    }
}

// Filtragem
$filterDate = isset($_POST['filterDate']) ? mysqli_real_escape_string($conexao, $_POST['filterDate']) : '';
$filterDelivered = isset($_POST['filterDelivered']) ? mysqli_real_escape_string($conexao, $_POST['filterDelivered']) : '';
$filterRoute = isset($_POST['filterRoute']) ? mysqli_real_escape_string($conexao, $_POST['filterRoute']) : '';

// Consulta para obter as rotas cadastradas
$sqlRotas = "SELECT CdRotas, DsBairro, DsCidade FROM TbRotas";
$resultRotas = $conexao->query($sqlRotas);

// Consulta para obter as entregas registradas
$sqlEntregas = "SELECT Cd_Entrega, HrSaida, HrChegada, DtEntrega, CdRotas, CdEntregador FROM TbEntrega";
$resultEntregas = $conexao->query($sqlEntregas);

// Consulta para obter as entregas registradas com filtros
$sqlEntregas = "SELECT Cd_Entrega, HrSaida, HrChegada, DtEntrega, CdRotas, CdEntregador FROM TbEntrega WHERE 1=1";
if ($filterDate) {
    $sqlEntregas .= " AND DtEntrega = '$filterDate'";
}
if ($filterDelivered) {
    $sqlEntregas .= " AND CdEntregador IS " . ($filterDelivered === 'yes' ? 'NOT NULL' : 'NULL');
}
if ($filterRoute) {
    $sqlEntregas .= " AND CdRotas = '$filterRoute'";
}

$resultEntregas = $conexao->query($sqlEntregas);
?>

<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Entrega</title>
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

#btnImprimir {
    color: #ffffff; /* Cor do texto */
    background-color: #d9534f; /* Vermelho */
    padding: 10px 15px; /* Espaçamento interno */
    border-radius: 4px; /* Bordas arredondadas */
    display: inline-flex; /* Para centralizar o conteúdo do SVG */
    align-items: center; /* Centraliza verticalmente */
    transition: background-color 0.3s ease; /* Efeito de transição */
}

#btnImprimir {
    background-color: #c9302c; /* Vermelho escuro ao passar o mouse */
}

#btnImprimir {
    margin-right: 5px; /* Espaçamento entre o ícone e o texto */
}

.lixo{
    background-color: transparent;
    border: none;
}



    </style>
</head>
<body>

<h1>Cadastrar Entrega</h1>

<a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>
    </a>

<!-- Exibir mensagem de sucesso ou erro -->
<?php
if (isset($_SESSION['msg'])) {
    $msgClass = strpos($_SESSION['msg'], 'Erro') === false ? 'msg' : 'msg error';
    echo "<p class='$msgClass'>{$_SESSION['msg']}</p>";
    unset($_SESSION['msg']);
}
?>


 <div class="box">
        <form action="" method="POST">
            <fieldset>
                <div class="input-container">
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="CdEntrega">Código da Entrega:</label>
                            <input type="number" id="CdEntrega" name="CdEntrega" class="inputuser" required>
                        </div>
                        <div class="col">
                            <label class="inputlabel" for="cdRotas">Código da Rota:</label>
                            <select name="cdRotas" id="cdRotas" class="inputuser" required>
                                <option value="">Selecione uma rota</option>
                                <?php
                                if ($resultRotas->num_rows > 0) {
                                    while($row = $resultRotas->fetch_assoc()) {
                                        echo "<option value='{$row['CdRotas']}'>{$row['CdRotas']} - {$row['DsBairro']}, {$row['DsCidade']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="inputlabel" for="dtEntrega">Data de Entrega:</label>
                            <input type="date" name="dtEntrega" id="dtEntrega" class="inputuser" required>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="submit">Cadastrar Entrega</button>
                </div>''
            </fieldset>
        </form>
    </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Atualize sua tabela de controle -->
<div class="box">
    <input type="button" value="Criar PDF" id="btnImprimir" onclick="CriaPDF()" />

    <h2>Controle de Entregas</h2>
    <table id="tabela">
    <thead>
        <tr>
            <th>Código da Entrega</th>
            <th>Hora de Saída</th>
            <th>Hora de Chegada</th>
            <th>Data de Entrega</th>
            <th>Código da Rota</th>
            <th>Código do Entregador</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($resultEntregas->num_rows > 0) {
        while ($row = $resultEntregas->fetch_assoc()) {
            echo "<tr id='linha-{$row['Cd_Entrega']}'>
                    <td>{$row['Cd_Entrega']}</td>
                    <td>{$row['HrSaida']}</td>
                    <td>{$row['HrChegada']}</td>
                    <td>{$row['DtEntrega']}</td>
                    <td>{$row['CdRotas']}</td>
                    <td>{$row['CdEntregador']}</td>
                    <td>
                        <button class='btn-cadastrar-horario' data-id='{$row['Cd_Entrega']}'>Cadastrar Horário</button>
                        <form action='' method='POST' style='display:inline;' onsubmit='return confirmDelete(this);'>
                            <input type='hidden' name='cdEntrega' value='{$row['Cd_Entrega']}'>
                            <button type='submit' name='delete' class='lixo'>
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
                </tr>";
        }
    }
    ?>
    </tbody>
</table>
        </tbody>
    </table>
</div>


<div id="modalHorario" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Cadastrar Horário</h2>
        <form action="" method="POST">
            <input type="hidden" name="cdEntrega" id="modalCdEntrega">
            
            <!-- Seleção do entregador -->
            <label for="delivererCode">Código do Entregador:</label>
            <select name="delivererCode" id="delivererCode" required>
                <option value="">Selecione um entregador</option>
                <?php
                // Conectar ao banco de dados
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "bdExpressoR";
                
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar a conexão
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                // Buscar entregadores cadastrados
                $queryEntregadores = "SELECT CdEntregador, NmEntregador FROM TbEntregador";
                $resultEntregadores = $conn->query($queryEntregadores);

                // Exibir os entregadores no select
                if ($resultEntregadores->num_rows > 0) {
                    while($row = $resultEntregadores->fetch_assoc()) {
                        echo "<option value='{$row['CdEntregador']}'>{$row['CdEntregador']} - {$row['NmEntregador']}</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum entregador cadastrado</option>";
                }

                // Fechar a conexão
                $conn->close();
                ?>
            </select>
            <br><br>
            
            <!-- Hora de saída -->
            <label for="hrSaida">Hora de Saída:</label>
            <input type="time" name="hrSaida" id="hrSaida" required>
            <br><br>
            
            <!-- Hora de chegada -->
            <label for="hrChegada">Hora de Chegada:</label>
            <input type="time" name="hrChegada" id="hrChegada" required>
            <br><br>
            
            <!-- Botão de cadastro -->
            <button type="submit" name="submitHorario">Cadastrar Horários</button>
        </form>
    </div>
</div>



<!-- Formulário de Filtro -->
<div class="box">
    <h2>Filtrar Entregas</h2>
    <form action="" method="POST">
        <div class="input-container">
            <div class="row">
                <div class="col">
                    <label for="filterDate">Data de Entrega:</label>
                    <input type="date" name="filterDate" id="filterDate" value="<?= htmlspecialchars($filterDate) ?>">
                </div>
                <div class="col">
                    <label for="filterDelivered">Entregue:</label>
                    <select name="filterDelivered" id="filterDelivered">
                        <option value="">Todos</option>
                        <option value="yes" <?= $filterDelivered === 'yes' ? 'selected' : '' ?>>Sim</option>
                        <option value="no" <?= $filterDelivered === 'no' ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>
                <div class="col">
                    <label for="filterRoute">Código da Rota:</label>
                    <select name="filterRoute" id="filterRoute">
                        <option value="">Todas</option>
                        <?php while($row = $resultRotas->fetch_assoc()): ?>
                            <option value="<?= $row['CdRotas'] ?>" <?= $filterRoute === $row['CdRotas'] ? 'selected' : '' ?>><?= $row['CdRotas'] ?> - <?= $row['DsBairro'] ?>, <?= $row['DsCidade'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="submit">Filtrar</button>
        </div>
    </form>
</div>

<div class="box">
    <h2>Controle de Entregas</h2>
    <table id="tabela">
        <thead>
            <tr>
                <th>Código da Entrega</th>
                <th>Hora de Saída</th>
                <th>Hora de Chegada</th>
                <th>Data de Entrega</th>
                <th>Código da Rota</th>
                <th>Código do Entregador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultEntregas->num_rows > 0): ?>
            <?php while ($row = $resultEntregas->fetch_assoc()): ?>
                <tr id='linha-<?= $row['Cd_Entrega'] ?>'>
                    <td><?= $row['Cd_Entrega'] ?></td>
                    <td><?= $row['HrSaida'] ?></td>
                    <td><?= $row['HrChegada'] ?></td>
                    <td><?= $row['DtEntrega'] ?></td>
                    <td><?= $row['CdRotas'] ?></td>
                    <td><?= $row['CdEntregador'] ?></td>
                    <td>
                        <button class='btn-cadastrar-horario' data-id='<?= $row['Cd_Entrega'] ?>'>Cadastrar Horário</button>
                        <form action='' method='POST' style='display:inline;' onsubmit='return confirmDelete(this);'>
                            <input type='hidden' name='cdEntrega' value='<?= $row['Cd_Entrega'] ?>'>
                            <button type='submit' name='delete' class='lixo'>Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Nenhuma entrega encontrada.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
        // JavaScript para abrir e fechar o modal
var modal = document.getElementById("modalHorario");
var span = document.getElementsByClassName("close")[0];
var buttons = document.getElementsByClassName("btn-cadastrar-horario");

// Abre o modal ao clicar no botão
Array.from(buttons).forEach(function(button) {
    button.onclick = function() {
        var cdEntrega = this.getAttribute("data-id");
        document.getElementById("modalCdEntrega").value = cdEntrega;
        modal.style.display = "block";
    }
});

// Fecha o modal ao clicar no X
span.onclick = function() {
    modal.style.display = "none";
}

// Fecha o modal se clicar fora da janela
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function confirmDelete(form) {
    if (confirm("Tem certeza que deseja excluir esta entrega?")) {
        // Aqui você pode adicionar lógica para remover a linha após a resposta do servidor
        // O 'id' da linha é baseado no código da entrega
        const entregaId = form.elements['cdEntrega'].value;
        const linha = document.getElementById('linha-' + entregaId);
        
        // Aqui, você pode usar AJAX para enviar a solicitação de exclusão
        // Para fins de demonstração, vamos apenas remover a linha após o envio
        // Comente a linha abaixo se quiser esperar a resposta do servidor
        linha.remove(); 

        return true; // Permitir que o formulário seja enviado
    }
    return false; // Cancelar o envio do formulário
}


function CriaPDF() {
    // Cria o cabeçalho
    var header = document.createElement('header');
    header.className = 'header';
    header.style.display = 'flex';
    header.style.justifyContent = 'space-between';
    header.style.alignItems = 'center';
    header.style.width = '100%';
    header.style.padding = '20px';
    header.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';

    header.innerHTML = `
        <div class="logo" style="font-size: 20px; font-weight: bold; color: black; margin-right: 10px;">Expresso Rosario</div>
        <img src="/BDExpresso/imgExpresso.png" alt="Logo" style="width: 50px; height: auto; margin-right: 30px;">
    `;
    
    // Clona a tabela
    var table = document.getElementById('tabela').cloneNode(true);
    
    // Remove a coluna de Ações
    var headers = table.getElementsByTagName('th');
    for (var i = 0; i < headers.length; i++) {
        if (headers[i].innerText === 'Ações') {
            headers[i].parentNode.removeChild(headers[i]);
            break;
        }
    }
    var rows = table.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            rows[i].removeChild(cells[cells.length - 1]); // Remove a última célula que corresponde à coluna de Ações
        }
    }

    // Remove cores de fundo e define cor preta para as letras
    table.style.backgroundColor = "transparent"; // Remove a cor de fundo da tabela
    table.style.color = "black"; // Define a cor do texto como preta

    // Centraliza o conteúdo da tabela
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        for (var j = 0; j < cells.length; j++) {
            cells[j].style.textAlign = 'center'; // Centraliza o texto em cada célula
            cells[j].style.backgroundColor = "transparent"; // Remove a cor de fundo das células
            cells[j].style.color = "black"; // Define a cor do texto das células como preta
        }
    }

    // Cria o rodapé com a data e hora
    var footer = document.createElement('div');
    footer.style.textAlign = 'center';
    footer.style.marginTop = '20px';
    footer.style.fontSize = '12px';
    footer.style.color = 'black';
    
    // Obtém a data e hora atual
    var currentDate = new Date();
    var formattedDate = currentDate.toLocaleString('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    
    footer.innerHTML = `Gerado em: ${formattedDate}`;

    // Cria um novo elemento para combinar cabeçalho, tabela e rodapé
    var element = document.createElement('div');
    element.appendChild(header);
    element.appendChild(table);
    element.appendChild(footer);

    var opt = {
        margin:       1,
        filename:     'controle_entregas.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Gera o PDF e inicia o download
    html2pdf().from(element).set(opt).save();
}


    
        // JavaScript para abrir e fechar o modal
        var modal = document.getElementById("modalHorario");
        var span = document.getElementsByClassName("close")[0];
        var buttons = document.getElementsByClassName("btn-cadastrar-horario");

        // Abre o modal ao clicar no botão
        Array.from(buttons).forEach(function(button) {
            button.onclick = function() {
                var cdEntrega = this.getAttribute("data-id");
                document.getElementById("modalCdEntrega").value = cdEntrega;
                modal.style.display = "block";
            }
        });

        // Fecha o modal ao clicar no X
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Fecha o modal se clicar fora da janela
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>       
</body>
</html>
