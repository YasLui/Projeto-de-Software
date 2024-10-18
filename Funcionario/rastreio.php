<?php
// Conexão com o banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$dbname = "bdExpressoR";

$conexao = mysqli_connect($servidor, $usuario, $senha, $dbname);

if ($conexao->connect_errno) {
    echo "Erro na conexão: " . $conexao->connect_error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rastreamento de Encomendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333; /* Fundo escuro */
            color: #fff; /* Cor do texto */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 20px;
            background-color: #444; /* Fundo do cabeçalho */
            color: #fff; /* Cor do texto no cabeçalho */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #fff; /* Cor do texto da logo */
            margin-left: 10px;
        }

        header img {
            width: 50px;
            height: auto;
        }

        .container {
            text-align: center;
            max-width: 600px;
            margin: 100px auto 0;
        }

        h1 {
            color: #D94F04;
            font-size: 36px;
        }

        .search-box {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-box input[type="text"] {
            width: 300px;
            padding: 15px;
            border-radius: 8px 0 0 8px;
            border: 2px solid #ccc;
            font-size: 16px;
        }

        .search-box button {
            padding: 15px 30px;
            background-color: #666; /* Fundo do botão */
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-size: 16px;
        }

        .search-box button:hover {
            background-color: #555; /* Fundo do botão ao passar o mouse */
        }

        .timeline {
            margin-top: 40px;
            width: 100%;
            max-width: 600px;
        }

        .status-section {
            border: 2px solid #00a859;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #555; /* Fundo das seções de status */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .status-header {
            font-size: 18px;
            color: #00a859;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .status-detail {
            color: #fff; /* Cor do texto das seções de status */
            margin: 5px 0;
        }

        footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #fff; /* Cor do texto do rodapé */
        }
        
    </style>
</head>
<body>
    <header class="header">
        <a href="/BDExpresso/Principal/Index/index.Html">
            <img src="/BDExpresso/imgExpresso.png" alt="Logo">
        </a>
        <div class="logo">Expreesso Rosario</div>
        <div class="logo">Melhor Rastreio</div>
    </header>

    <div class="container">
        <h1>Rastreie suas encomendas gratuitamente</h1>
        <div class="search-box">
            <form method="POST" action="">
                <input type="text" name="trackingNumber" placeholder="Código de rastreamento">
                <button type="submit">RASTREAR</button>
            </form>
        </div>

        <div class="timeline">
            <?php
            // Verifica se o número de rastreamento foi enviado
            if (isset($_POST['trackingNumber']) && !empty($_POST['trackingNumber'])) {
                $trackingNumber = $_POST['trackingNumber'];

                // Consulta para buscar a linha do tempo do pacote
                $sql = "SELECT e.DtEntrega, e.HrChegada, e.HrSaida, p.HrEntrega, p.Status, p.QtTentativas, p.NmCidade, p.NmBairro, p.NmRua, p.NuResidencia, p.HrChegadaPacote
                        FROM TBPacote p
                        JOIN TbEntrega e ON p.CdEntrega = e.Cd_Entrega
                        WHERE p.Cd_Pacote = ?
                        ORDER BY e.DtEntrega, p.HrChegadaPacote";

                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("s", $trackingNumber);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Variáveis para armazenar dados de status
                    $statusData = [
                        'No Centro Logístico' => [],
                        'Saiu para Entrega' => [],
                        'Entregue' => [],
                        'Tentativa de Entrega' => [],
                    ];

                    // Coletando os dados em arrays separados
                    while ($row = $result->fetch_assoc()) {
                        $statusData[$row['Status']][] = $row;
                    }

                    // Exibindo as seções separadas
                    foreach ($statusData as $status => $events) {
                        if (!empty($events)) {
                            echo "<div class='status-section'>";
                            echo "<div class='status-header'>Status: $status</div>";

                            foreach ($events as $event) {
                                if ($status == 'No Centro Logístico') {
                                    echo "<div class='status-detail'>O pacote chegou ao centro logístico às " . $event['HrChegadaPacote'] . ".</div>";
                                } elseif ($status == 'Saiu para Entrega') {
                                    echo "<div class='status-detail'>O pacote chegou ao centro logístico às " . $event['HrChegadaPacote'] . ".</div>";
                                    echo "<div class='status-detail'>O pacote saiu para entrega às " . $event['HrSaida'] . ".</div>";
                                    echo "<div class='status-detail'>Destino: " . $event['NmRua'] . ", " . $event['NuResidencia'] . " - " . $event['NmBairro'] . ", " . $event['NmCidade'] . ".</div>";
                                } elseif ($status == 'Entregue') {
                                    echo "<div class='status-detail'>O pacote chegou ao centro logístico às " . $event['HrChegadaPacote'] . ".</div>";
                                    echo "<div class='status-detail'>O pacote saiu para entrega às " . $event['HrSaida'] . ".</div>";
                                    echo "<div class='status-detail'>Destino: " . $event['NmRua'] . ", " . $event['NuResidencia'] . " - " . $event['NmBairro'] . ", " . $event['NmCidade'] . ".</div>";
                                    echo "<div class='status-detail'>O pacote foi entregue no dia " .  $event['DtEntrega'] . " às " . $event['HrEntrega'] . ".</div>";
                                } elseif ($status == 'Tentativa de Entrega') {
                                    echo "<div class='status-detail'>O pacote chegou ao centro logístico às " . $event['HrChegadaPacote'] . ".</div>";
                                    echo "<div class='status-detail'>O pacote saiu para entrega às " . $event['HrSaida'] . ".</div>";
                                    echo "<div class='status-detail'>Houve uma tentativa de entrega às " . $event['HrEntrega'] . ".</div>";
                                    echo "<div class='status-detail'>Tentativas de entrega: " . $event['QtTentativas'] . "</div>";
                                }
                            }
                            echo "</div>"; // fecha status-section
                        }
                    }
                } else {
                    echo "<p>Pacote não encontrado para o código de rastreamento fornecido.</p>";
                }

                $stmt->close();
            } else {
                echo "<p>Por favor, insira um número de rastreamento válido.</p>";
            }

            $conexao->close();
            ?>
        </div>
    </div>

    <footer>
        &copy; 2024 Melhor Rastreio - Todos os direitos reservados.
    </footer>
</body>
</html>
