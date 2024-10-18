<?php 
session_start(); // Inicia a sessão
include_once $_SERVER['DOCUMENT_ROOT'].'/BDExpresso/Funcionario/config.php';

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

$codigoRota = isset($_SESSION['rotaEscolhida']) ? $_SESSION['rotaEscolhida'] : null;

// Verifica se uma rota foi escolhida
if ($codigoRota === null) {
    die("Nenhuma rota escolhida.");
}

// Consulta para obter pacotes da rota selecionada
$sqlPacotes = "
    SELECT 
        p.Cd_Pacote,
        p.NmEmpresaParceira,
        p.NmBairro,
        p.NmCidade,
        p.NuResidencia,
        p.QtTentativas,
        p.HrEntrega,
        p.NmRecebeu,
        p.FoiEntrega,
        p.status -- Adiciona a coluna de status
    FROM 
        TBPacote p
    JOIN 
        TbEntrega e ON p.CdEntrega = e.Cd_Entrega
    WHERE 
        e.CdRotas = ?
";

// Prepara a consulta
$stmt = $conexao->prepare($sqlPacotes);
$stmt->bind_param("i", $codigoRota); // vincula o código da rota
$stmt->execute();
$resultPacotes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacotes da Rota</title>
    <link rel="stylesheet" href="entregaa.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #121212; /* Fundo escuro */
    color: #e0e0e0; /* Texto claro */
    padding: 20px;
}

h1 {
    text-align: center;
    color: #e0e0e0;
}

.packages-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.package-box {
    border: 1px solid #444; /* Borda mais escura */
    background-color: #1e1e1e; /* Fundo dos pacotes */
    border-radius: 10px;
    padding: 20px;
    width: 300px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s, box-shadow 0.3s;
}

.package-box:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
}

.package-title {
    font-size: 20px;
    font-weight: bold;
    color: #0d6efd; /* Azul claro */
    margin-bottom: 10px;
}

.package-box div {
    margin-bottom: 8px;
    color: #ccc; /* Texto claro */
}

.toggle-button {
    color: #0d6efd;
    cursor: pointer;
    margin-top: 10px;
    display: inline-block;
}

.form-container {
    display: none;
    margin-top: 10px;
}

.form-field {
    margin-bottom: 10px;
}

.form-field label {
    display: block;
    margin-bottom: 5px;
    color: #e0e0e0; /* Texto claro */
}

.form-field input {
    width: 100%;
    padding: 8px;
    border: 1px solid #444; /* Borda mais escura */
    border-radius: 5px;
    background-color: #333; /* Fundo do input */
    color: #e0e0e0; /* Texto do input */
}

.submit-button {
    padding: 10px 20px;
    background-color: #0d6efd; /* Azul claro */
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.submit-button:hover {
    background-color: #0a58ca; /* Azul escuro */
}

.finalize-button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #28a745; /* Verde claro */
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.finalize-button:hover {
    background-color: #218838; /* Verde escuro */
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #0d6efd; /* Azul claro */
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.voltar {
    padding: 10px;
    color: #ffffff;
}a
.voltar:hover {
    width: 100%;
}
    </style>
</head>
<body>

<h1>Pacotes da Rota Selecionada</h1>

<!-- Botão para marcar todos os pacotes como "Saiu para Entrega" -->
<button class="submit-button" onclick="atualizarTodosPacotes()">Marcar Todos como Saiu para Entrega</button>

<div class="packages-container">
    <?php
    if ($resultPacotes->num_rows > 0) {
        while ($row = $resultPacotes->fetch_assoc()) {
            echo "<div class='package-box'>
                    <div class='package-title'>Pacote: {$row['Cd_Pacote']}</div>
                    <div>Empresa Parceira: {$row['NmEmpresaParceira']}</div>
                    <div>Bairro: {$row['NmBairro']}</div>
                    <div>Cidade: {$row['NmCidade']}</div>
                    <div>Residência: {$row['NuResidencia']}</div>
                    <div>Qt Tentativas: {$row['QtTentativas']}</div>
                    <div id='statusPacote-{$row['Cd_Pacote']}' class='status'>Status: {$row['status']}</div> <!-- Status do pacote -->
                    
                    <span class='toggle-button' onclick='toggleForm(this)'>Mostrar Formulário &#9660;</span>
                    <div class='form-container' style='display:none;'>
                        <form onsubmit='registerDelivery(event, {$row['Cd_Pacote']})'>
                            <input type='hidden' name='cdPacote' value='{$row['Cd_Pacote']}'>
                            <div class='form-field'>
                                <label for='hrEntrega'>Horário de Entrega (Tentativa):</label>
                                <input type='time' name='hrEntrega' value='" . ($row['HrEntrega'] ?? '') . "' " . (isset($row['HrEntrega']) ? "disabled" : "") . " required>
                            </div>
                            <div class='form-field'>
                                <label for='nmRecebeu'>Nome de quem recebeu:</label>
                                <input type='text' name='nmRecebeu' value='" . ($row['NmRecebeu'] ?? '') . "' " . (isset($row['NmRecebeu']) ? "disabled" : "") . " >
                            </div>
                            <div class='form-field'>
                                <label for='foiEntrega'>Foi entregue?</label>
                                <select name='foiEntrega' required>
                                    <option value='' disabled selected>Selecione</option>
                                    <option value='Sim' " . ($row['FoiEntrega'] === 'Sim' ? "selected" : "") . ">Sim</option>
                                    <option value='Não' " . ($row['FoiEntrega'] === 'Não' ? "selected" : "") . ">Não</option>
                                </select>
                            </div>
                            <button type='submit' class='submit-button' disabled>Registrar Entrega</button>
                        </form>
                    </div>
                  </div>";
        }
    } else {
        echo "<div>Nenhum pacote encontrado para esta rota.</div>";
    }
    ?>
</div>

<!-- Botão para finalizar a rota -->
<button class="finalize-button" onclick="finalizarRota()">Finalizar Rota</button>
<a href="exibir.php">Voltar para as rotas</a>

<script>
function atualizarTodosPacotes() {
    fetch(`atualiza_status_todos.php?codigoRota=<?php echo $codigoRota; ?>`)
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Exibe a mensagem de sucesso ou erro
            
            // Atualiza a exibição do status na página
            data.pacotes.forEach(pacote => {
                const statusElement = document.getElementById(`statusPacote-${pacote.Cd_Pacote}`);
                if (statusElement) {
                    statusElement.innerText += "; Seu pacote saiu para a entrega"; // Mantém o status anterior e adiciona o novo
                }
            });
        })
        .catch(error => console.error('Erro:', error));
}

function toggleForm(button) {
    const formContainer = button.nextElementSibling; // O próximo elemento é o form-container
    if (formContainer.style.display === "none" || formContainer.style.display === "") {
        formContainer.style.display = "block";
        button.innerHTML = "Esconder Formulário &#9650;"; // Símbolo de subir
    } else {
        formContainer.style.display = "none";
        button.innerHTML = "Mostrar Formulário &#9660;"; // Símbolo de descer
    }
}

function registerDelivery(event, cdPacote) {
    event.preventDefault(); // Impede o recarregamento da página

    const form = event.target; // O formulário que disparou o evento
    const formData = new FormData(form); // Cria um objeto FormData com os dados do formulário

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "processar_entrega.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.responseText.trim(); // Remove espaços em branco

            if (response === 'entrega_registrada') {
                alert("Entrega registrada com sucesso.");
            } else if (response === 'entrega_nao_registrada') {
                alert("Falha ao registrar a entrega.");
            } else {
                alert("Ocorreu um erro ao tentar registrar a entrega.");
            }

            form.reset(); // Limpa os campos do formulário após o envio (opcional)
        }
    };
    xhr.send(formData); // Envia os dados do formulário
}

function finalizarRota() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "finalizar_rota.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const response = xhr.responseText;
            if (response === 'rota_finalizada') {
                window.location.href = "exibir.php";
            } else if (response === 'erro_pacotes_nao_registrados') {
                alert("Existem pacotes que ainda não tiveram a entrega registrada. Por favor, registre todas as entregas antes de finalizar a rota.");
            } else {
                alert("Ocorreu um erro ao tentar finalizar a rota.");
            }
        }
    };
    xhr.send("codigoRota=" + <?php echo json_encode($codigoRota); ?>);
}

// Função para verificar se os campos obrigatórios estão preenchidos
function checkFormCompletion(form) {
    const hrEntrega = form.querySelector("input[name='hrEntrega']").value;
    const foiEntrega = form.querySelector("select[name='foiEntrega']").value;
    const submitButton = form.querySelector(".submit-button");

    if (hrEntrega && foiEntrega) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

// Adiciona um event listener a todos os formulários para monitorar alterações nos campos
document.querySelectorAll("form").forEach(function(form) {
    form.addEventListener("input", function() {
        checkFormCompletion(form);  
    });
});
</script>

</body>
</html>

<?php
$stmt->close(); // Fecha a declaração
mysqli_close($conexao); // Fecha a conexão
?>
