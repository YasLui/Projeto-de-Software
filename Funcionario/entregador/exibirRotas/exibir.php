<?php
session_start(); // Inicia a sessão

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdExpressoR";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter as rotas
$sqlRotas = "SELECT CdRotas, DsBairro, DsCidade FROM TbRotas";
$resultRotas = $conn->query($sqlRotas);

// Cria um array para armazenar as rotas finalizadas
if (!isset($_SESSION['rotasFinalizadas'])) {
    $_SESSION['rotasFinalizadas'] = [];
}

// Se uma rota foi confirmada, adiciona ao array de rotas finalizadas
if (isset($_SESSION['rotaEscolhida'])) {
    $codigoRotaEscolhida = $_SESSION['rotaEscolhida'];
    if (!in_array($codigoRotaEscolhida, $_SESSION['rotasFinalizadas'])) {
        $_SESSION['rotasFinalizadas'][] = $codigoRotaEscolhida;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            background-color: #121212; /* Fundo escuro */
            color: #e0e0e0; /* Texto claro */
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #e0e0e0;
        }

        .routes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .route-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 150px;
            border: 2px solid #333; /* Borda escura */
            border-radius: 10px;
            padding: 10px;
            background-color: #1e1e1e; /* Fundo dos boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            cursor: pointer; /* Cursor pointer */
        }
        .route-box:hover{
            
        }

        .route-code {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #ea883f; /* Tom pastel de laranja */
        }

        .route-details {
            font-size: 16px;
            color: #ccc; /* Texto claro */
        }

        /* Estilo para a rota selecionada */
        .route-box.selected {
            border-color: #ffa738; /* Tom pastel de laranja */
            background-color: #3b3b3b; /* Fundo escuro para seleção */
        }

        /* Estilo do modal */
        .modal {
            display: none; /* Escondido por padrão */
            position: fixed; /* Fixo na tela */
            z-index: 1; /* Em cima de outros elementos */
            left: 0;
            top: 0;
            width: 100%; /* Largura total */
            height: 100%; /* Altura total */
            overflow: auto; /* Habilita rolagem se necessário */
            background-color: rgba(0, 0, 0, 0.7); /* Fundo escuro com opacidade */
        }

        .modal-content {
            background-color: #1e1e1e; /* Fundo do modal */
            margin: 15% auto; /* Centraliza verticalmente */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Largura do modal */
            max-width: 600px; /* Largura máxima */
            border-radius: 5px; /* Bordas arredondadas */
            position: relative; /* Necessário para posicionar o botão de fechar */
        }

        .close {
            color: #aaa;
            position: absolute; /* Posiciona o botão de fechar */
            top: 10px; /* Distância do topo */
            right: 20px; /* Distância da direita */
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Tabela do modal */
        table {
            width: 100%; /* Largura da tabela */
            border-collapse: collapse; /* Colapsa bordas */
            background-color: #1e1e1e; /* Fundo escuro da tabela */
        }

        th {
            background-color: #333; /* Fundo escuro para cabeçalho */
            color: #e0e0e0; /* Texto claro para cabeçalho */
        }

        td {
            padding: 8px; /* Espaçamento */
            text-align: left; /* Alinhamento do texto */
            border: 1px solid #444; /* Bordas das células */
        }

        /* Cores das linhas alternadas */
        tr:nth-child(even) {
            background-color: #2a2a2a; /* Fundo escuro */
        }

        tr:nth-child(odd) {
            background-color: #242424; /* Fundo escuro um pouco mais claro */
        }

        .confirm-button {
            padding: 10px 20px;
            background-color: #F27405; /* Tom pastel de laranja */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block; /* Para centralizar */
            margin: 20px auto; /* Para centralizar */
        }
        .voltar {
            padding: 10px;
            color: #ffffff;
        }
        .voltar:hover {
            width: 100%;
        }
    </style>
</head>
<body>
    
<a class="voltar" href="/BDExpresso/Funcionario/entregador/index.html">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
        </svg>
    </a>

<h1>Rotas disponíveis</h1>

<div class="routes-container" id="routesContainer">
    <?php
    // Verifica se uma rota já foi escolhida
    $rotaEscolhida = isset($_SESSION['rotaEscolhida']) ? $_SESSION['rotaEscolhida'] : null;

    if ($resultRotas->num_rows > 0) {
        while($row = $resultRotas->fetch_assoc()) {
            // Verifica se a rota está na lista de rotas finalizadas
            if (!in_array($row['CdRotas'], $_SESSION['rotasFinalizadas'])) {
                echo "<div class='route-box" . ($rotaEscolhida == $row['CdRotas'] ? " selected" : "") . "' data-id='{$row['CdRotas']}' onclick='selectRoute(" . $row['CdRotas'] . ", this)'>
                        <div class='route-code'>{$row['CdRotas']}</div>
                        <div class='route-details'>{$row['DsBairro']} - {$row['DsCidade']}</div>
                      </div>";
            }
        }
    } else {
        echo "<div>Nenhuma rota encontrada.</div>";
    }
    ?>
</div>

<!-- Modal para confirmação -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Pacotes da Rota</h2>
        <div id="packageInfo"></div>
        <button class="confirm-button" onclick="confirmSelection()">Sim</button>
    </div>
</div>

<script>
let selectedRoute = null;

function selectRoute(codigoRota, element) {
    // Marca a rota como selecionada
    selectedRoute = element;
    const rotaEscolhida = document.querySelector('.route-box.selected');

    // Exibe os pacotes da rota selecionada
    fetchPacotes(codigoRota);

    if (rotaEscolhida && rotaEscolhida !== selectedRoute) {
        // Se já houver uma rota escolhida, mostra aviso
        alert("Você já confirmou uma rota. Não é possível selecionar outra.");
    } else {
        element.classList.add('selected');
    }
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

function fetchPacotes(codigoRota) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_pacotes.php?codigoRota=" + codigoRota, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("packageInfo").innerHTML = xhr.responseText;
            document.getElementById("myModal").style.display = "block";
        }
    };
    xhr.send();
}

function confirmSelection() {
    if (selectedRoute) {
        const rotaId = selectedRoute.getAttribute('data-id');

        // Envia para o servidor para processar a seleção
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "salvar_rota.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert("Rota confirmada com sucesso!");
                window.location.reload(); // Atualiza a página para refletir a seleção
            }
        };
        xhr.send("codigoRota=" + rotaId);
    } else {
        alert("Nenhuma rota selecionada.");
    }
}
    
 
</script>

</body>
</html>

<?php
$conn->close(); // Fecha a conexão
?>
