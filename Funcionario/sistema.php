<?php
// Inclua o arquivo de configuração
include_once('config.php');

// Recuperar parâmetros de pesquisa
$typeFilter = isset($_GET['type']) ? mysqli_real_escape_string($conexao, $_GET['type']) : '';
$nameFilter = isset($_GET['name']) ? mysqli_real_escape_string($conexao, $_GET['name']) : '';
$cityFilter = isset($_GET['city']) ? mysqli_real_escape_string($conexao, $_GET['city']) : '';

// Consultas     para obter dados das duas tabelas com filtragem
$sqlGerentes = "SELECT CdGerente as id, NmGerente as nome, NmCidade as cidade, NmBairro as bairro, NmRua as rua, NuResidencia as numero, DsLogin as login, 'Gerente' as tipo FROM TbGerente WHERE NmGerente LIKE '%$nameFilter%' AND NmCidade LIKE '%$cityFilter%'";
$sqlEntregadores = "SELECT CdEntregador as id, NmEntregador as nome, NmCidade as cidade, NmBairro as bairro, NmRua as rua, NuResidencia as numero, DsLogin as login, NuCNH as Cnh, 'Entregador' as tipo FROM TbEntregador WHERE NmEntregador LIKE '%$nameFilter%' AND NmCidade LIKE '%$cityFilter%'";

// Adicionar filtro de tipo
if ($typeFilter === 'Gerente') {
    $sqlGerentes .= " AND 'Gerente' = 'Gerente'";
    $sqlEntregadores .= " AND 1=0"; // Ignorar entregadores
} elseif ($typeFilter === 'Entregador') {
    $sqlGerentes .= " AND 1=0"; // Ignorar gerentes
    $sqlEntregadores .= " AND 'Entregador' = 'Entregador'";
    }

// Execute as consultas
$resultGerentes = mysqli_query($conexao, $sqlGerentes);
$resultEntregadores = mysqli_query($conexao, $sqlEntregadores);

// Verifique se há erros nas consultas
if (!$resultGerentes || !$resultEntregadores) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

// Combine os resultados
$funcionarios = [];
while ($row = mysqli_fetch_assoc($resultGerentes)) {
    $funcionarios[] = $row;
}
while ($row = mysqli_fetch_assoc($resultEntregadores)) {
    $funcionarios[] = $row;
}

// Feche a conexão
mysqli_close($conexao);
?>  



<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistema</title>
    <link rel="stylesheet" href="styleSistemass.css">
</head>
<body>
    <a class="voltar" href="/BDExpresso/Funcionario/gerente/principal.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
        </svg>
    </a>
    <h1>Acessou o sistema</h1>

    <div class="box-search">
        <select id="type">
            <option value="">Todos</option>
            <option value="Gerente">Gerente</option>
            <option value="Entregador">Entregador</option>
        </select>
        <input type="text" placeholder="Nome" id="name" />
        <input type="text" placeholder="Cidade" id="city" />
        <button onclick="searchData()" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
    </div>

    <div class="button-container">
        <button onclick="window.location.href='/BDExpresso/Funcionario/gerente/cadastroGer.php'">Adicionar Gerente</button>
        <button onclick="window.location.href='/BDExpresso/Funcionario/entregador/cadastro.php'">Adicionar Entregador</button>
    </div>

    <div class="divTable">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">Bairro</th>
                    <th scope="col">Rua</th>
                    <th scope="col">Nº Resid</th>
                    <th scope="col">Login</th>
                    <th scope="col">Cnh</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">...</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($funcionarios as $funcionario) {
                        echo "<tr>";
                        echo "<td>{$funcionario['id']}</td>";
                        echo "<td>{$funcionario['nome']}</td>";
                        echo "<td>{$funcionario['cidade']}</td>";
                        echo "<td>{$funcionario['bairro']}</td>";
                        echo "<td>{$funcionario['rua']}</td>";
                        echo "<td>{$funcionario['numero']}</td>";
                        echo "<td>{$funcionario['login']}</td>";

                        // Verifique se o índice 'Cnh' existe antes de acessá-lo
                        echo "<td>" . (isset($funcionario['Cnh']) ? $funcionario['Cnh'] : '-') . "</td>";
                        echo "<td>{$funcionario['tipo']}</td>";                  
                        echo "<td class='teste'>";

                        // Verifique o tipo de funcionário para determinar o link de edição
                        if ($funcionario['tipo'] === 'Gerente') {
                            echo "<a class='lapis' href='/BDExpresso/Funcionario/gerente/editGER.php?CdGerente={$funcionario['id']}'>
                                <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='25' height='25' viewBox='0 0 48 48'>
                                    <path fill='#e57373' d='M42.6,9.1l-3.7-3.7c-0.6-0.6-1.5-0.6-2,0l-1.7,1.7l5.7,5.7l1.7-1.7C43.1,10.5,43.1,9.6,42.6,9.1'></path>
                                    <path fill='#ff9800' d='M38,15.6L12.6,41.1l-5.7-5.7L32.4,10L38,15.6z'></path>
                                    <path fill='#b0bec5' d='M32.4,10l2.8-2.8l5.7,5.7L38,15.6L32.4,10z'></path>
                                    <path fill='#ffc107' d='M6.9,35.4L5,43l7.6-1.9L6.9,35.4z'></path>
                                    <path fill='#37474f' d='M6,39.2L5,43l3.8-1L6,39.2z'></path>
                                </svg>
                            </a>";
                        } else {
                            echo "<a class='lapis' href='editEntregador.php?CdEntregador={$funcionario['id']}'>
                                <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='25' height='25' viewBox='0 0 48 48'>
                                    <path fill='#e57373' d='M42.6,9.1l-3.7-3.7c-0.6-0.6-1.5-0.6-2,0l-1.7,1.7l5.7,5.7l1.7-1.7C43.1,10.5,43.1,9.6,42.6,9.1'></path>
                                    <path fill='#ff9800' d='M38,15.6L12.6,41.1l-5.7-5.7L32.4,10L38,15.6z'></path>
                                    <path fill='#b0bec5' d='M32.4,10l2.8-2.8l5.7,5.7L38,15.6L32.4,10z'></path>
                                    <path fill='#ffc107' d='M6.9,35.4L5,43l7.6-1.9L6.9,35.4z'></path>
                                    <path fill='#37474f' d='M6,39.2L5,43l3.8-1L6,39.2z'></path>
                                </svg>
                            </a>";
                        }
                        
                        echo "<a class='' href='delete.php?Cd" . ($funcionario['tipo'] === 'Gerente' ? "Gerente" : "Entregador") . "={$funcionario['id']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='28' height='28' viewBox='0 0 100 100'>
                                <path fill='#f37e98' d='M25,30l3.645,47.383C28.845,79.988,31.017,82,33.63,82h32.74c2.613,0,4.785-2.012,4.985-4.617L75,30'></path>
                                <path fill='#f15b6c' d='M65 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S65 36.35 65 38zM53 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S53 36.35 53 38zM41 38v35c0 1.65-1.35 3-3 3s-3-1.35-3-3V38c0-1.65 1.35-3 3-3S41 36.35 41 38zM77 24h-4l-1.835-3.058C70.442 19.737 69.14 19 67.735 19h-35.47c-1.405 0-2.707.737-3.43 1.942L27 24h-4c-1.657 0-3 1.343-3 3s1.343 3 3 3h54c1.657 0 3-1.343 3-3S78.657 24 77 24z'></path>
                                <path fill='#1f212b' d='M66.37 83H33.63c-3.116 0-5.744-2.434-5.982-5.54l-3.645-47.383 1.994-.154 3.645 47.384C29.801 79.378 31.553 81 33.63 81H66.37c2.077 0 3.829-1.622 3.988-3.692l3.645-47.385 1.994.154-3.645 47.384C72.113 80.566 69.485 83 66.37 83zM56 20c-.552 0-1-.447-1-1v-3c0-.552-.449-1-1-1h-8c-.551 0-1 .448-1 1v3c0 .553-.448 1-1 1s-1-.447-1-1v-3c0-1.654 1.346-3 3-3h8c1.654 0 3 1.346 3 3v3C57 19.553 56.552 20 56 20z'></path>
                                <path fill='#1f212b' d='M77,31H23c-2.206,0-4-1.794-4-4s1.794-4,4-4h3.434l1.543-2.572C28.875,18.931,30.518,18,32.265,18h35.471c1.747,0,3.389,0.931,4.287,2.428L73.566,23H77c2.206,0,4,1.794,4,4S79.206,31,77,31z M23,25c-1.103,0-2,0.897-2,2s0.897,2,2,2h54c1.103,0,2-0.897,2-2s-0.897-2-2-2h-4c-0.351,0-0.677-0.185-0.857-0.485l-1.835-3.058C69.769,20.559,68.783,20,67.735,20H32.265c-1.048,0-2.033,0.559-2.572,1.457l-1.835,3.058C27.677,24.815,27.351,25,27,25H23z'></path>
                                <path fill='#1f212b' d='M61.5 25h-36c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h36c.276 0 .5.224.5.5S61.776 25 61.5 25zM73.5 25h-5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5S73.776 25 73.5 25zM66.5 25h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S66.776 25 66.5 25zM50 76c-1.654 0-3-1.346-3-3V38c0-1.654 1.346-3 3-3s3 1.346 3 3v25.5c0 .276-.224.5-.5.5S52 63.776 52 63.5V38c0-1.103-.897-2-2-2s-2 .897-2 2v35c0 1.103.897 2 2 2s2-.897 2-2v-3.5c0-.276.224-.5.5-.5s.5.224.5.5V73C53 74.654 51.654 76 50 76zM62 76c-1.654 0-3-1.346-3-3V47.5c0-.276.224-.5.5-.5s.5.224.5.5V73c0 1.103.897 2 2 2s2-.897 2-2V38c0-1.103-.897-2-2-2s-2 .897-2 2v1.5c0 .276-.224.5-.5.5S59 39.776 59 39.5V38c0-1.654 1.346-3 3-3s3 1.346 3 3v35C65 74.654 63.654 76 62 76z'></path>
                                <path fill='#1f212b' d='M59.5 45c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C60 44.776 59.776 45 59.5 45zM38 76c-1.654 0-3-1.346-3-3V38c0-1.654 1.346-3 3-3s3 1.346 3 3v35C41 74.654 39.654 76 38 76zM38 36c-1.103 0-2 .897-2 2v35c0 1.103.897 2 2 2s2-.897 2-2V38C40 36.897 39.103 36 38 36z'></path>
                            </svg>
                        </a>";                        
                        
                        echo "</td>";
                        echo "</tr>";   
                    }
                ?>
            </tbody>
        </table>
    </div>    
</body> 
<script>
    function searchData() {
        var type = document.getElementById('type').value;
        var name = document.getElementById('name').value;
        var city = document.getElementById('city').value;

        // Construa a URL de pesquisa com parâmetros
        var query = 'sistema.php?type=' + encodeURIComponent(type) + '&name=' + encodeURIComponent(name) + '&city=' + encodeURIComponent(city);

        // Redireciona para a página com os parâmetros de pesquisa
        window.location.href = query;
    }

    var search = document.getElementById('name');
    search.addEventListener("keydown", function(event){
        if(event.key === "Enter") {
            searchData();
        }
    });
</script>
</html>
