<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pacote</title>
    <link rel="stylesheet" href="stylessss.css">
    <style>
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
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .btn {
            margin: 5px;
        }

        /* Estilo comum para inputs e select */
        .input-container .col input, 
        .input-container .col select {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Inclui padding e border no tamanho total */
        }

        .input-container .row {
            display: flex;
            flex-wrap: wrap; /* Permite que as colunas se ajustem em diferentes tamanhos de tela */
            margin-bottom: 15px; /* Espaço entre as linhas */
        }

        .input-container .col {
            flex: 1; /* Faz as colunas ocuparem o mesmo espaço */
            min-width: 220px; /* Largura mínima das colunas */
            margin-right: 10px; /* Espaço entre colunas */
        }

        .input-container .col:last-child {
            margin-right: 0; /* Remove margem direita da última coluna */
        }

        .submit {
            background-color: #4CAF50; /* Verde */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit:hover {
            background-color: #45a049; /* Verde mais escuro */
        }
    </style>
</head>
<body>
    <h1>Cadastro de Pacote</h1>
    <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>
    </a>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="modal" id="errorModal">
            <div class="modal-content">
                <h2>Erro</h2>
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
                <button class="btn" id="closeModal">OK</button>
            </div>
        </div>
    <?php endif; ?>
       
    <form action="processa_pacote.php" method="post">
        <fieldset>
            <div class="input-container">
                <div class="row">
                    <div class="col">
                        <label for="CdEntrega">Código da Entrega:</label>
                        <select id="CdEntrega" name="CdEntrega" required onchange="preencherBairro()">
                            <option value="">Selecione uma entrega</option>
                            <?php
                            // Conectando ao banco de dados para obter as entregas
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "bdExpressoR";
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Conexão falhou: " . $conn->connect_error);
                            }

                            $query = "SELECT Cd_Entrega, DsBairro FROM TbEntrega INNER JOIN TbRotas ON TbEntrega.CdRotas = TbRotas.CdRotas";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['Cd_Entrega']}' data-bairro='{$row['DsBairro']}'>{$row['Cd_Entrega']} - {$row['DsBairro']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="NmBairro">Bairro:</label>
                        <input type="text" id="NmBairro" name="NmBairro" required readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="NmEmpresaParceira">Empresa Parceira:</label>
                        <input type="text" id="NmEmpresaParceira" name="NmEmpresaParceira" required>
                    </div>
                    <div class="col">
                        <label for="NmCidade">Cidade:</label>
                        <input type="text" id="NmCidade" name="NmCidade" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="NmRua">Rua:</label>
                        <input type="text" id="NmRua" name="NmRua" required>
                    </div>
                    <div class="col">
                        <label for="NuResidencia">Número da Residência:</label>
                        <input type="number" id="NuResidencia" name="NuResidencia" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="QtTentativas">Quantidade de Tentativas:</label>
                        <input type="number" id="QtTentativas" name="QtTentativas" required>
                    </div>
                    <div class="col">
                        <label for="HrChegadaPacote">Hora de Chegada do Pacote:</label>
                        <input type="time" id="HrChegadaPacote" name="HrChegadaPacote" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="submit">Cadastrar</button>
        </fieldset>
    </form>

    <script>
        // Exibir o modal se houver um erro
        <?php if (isset($_GET['error'])): ?>
            document.getElementById('errorModal').style.display = 'block';
        <?php endif; ?>

        document.getElementById('closeModal').onclick = function() {
            document.getElementById('errorModal').style.display = 'none';
        }

        function preencherBairro() {
            var select = document.getElementById('CdEntrega');
            var selectedOption = select.options[select.selectedIndex];
            var bairro = selectedOption.getAttribute('data-bairro');

            // Preenche o campo de bairro com o valor correspondente
            document.getElementById('NmBairro').value = bairro ? bairro : '';
        }
    </script>
</body>

</html>
