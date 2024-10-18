

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rastreamento de Encomendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: url('caminho-para-a-imagem-de-fundo'); /* Coloque o caminho da imagem de fundo */
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 20px;
            position: absolute;
            top: 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00a859;
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
            background-color: #4a4a4a;
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            font-size: 16px;
        }

        .search-box button:hover {
            background-color: #333;
        }

        .status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            color: black; /* Cor do texto */
            font-weight: bold;
            display: block; /* Exibe a caixa de status */
        }

        footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">melhor rastreio</div>
    </header>

    <div class="container">
        <h1>Rastreie suas encomendas gratuitamente</h1>
        <div class="search-box">
            <input type="text" id="trackingNumber" placeholder="Código de rastreamento">
            <button onclick="trackPackage()">RASTREAR</button>
        </div>
        <div class="status" id="status"></div> <!-- Caixa de status -->
    </div>

    <footer>
        &copy; 2024 Melhor Rastreio - Todos os direitos reservados.
    </footer>

    <script>
        let currentStatus = '';

        function trackPackage() {
            const trackingNumber = document.getElementById('trackingNumber').value;
            const status = document.getElementById('status');

            if (trackingNumber) {
                currentStatus = 'Seu pacote chegou ao centro logístico';
                status.textContent = currentStatus;
                status.style.display = 'block'; // Exibe a caixa de status
            } else {
                status.textContent = 'Por favor, insira um número de rastreamento válido';
                status.style.display = 'block'; // Exibe a caixa de status
            }
        }
    </script>
</body>
</html>
