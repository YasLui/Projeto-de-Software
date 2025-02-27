<?php
// welcome.php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header('Location: login.php'); // Redirecionar para a página de login se não estiver autenticado
    exit();
}

// Obter o nome do usuário da sessão
$user_name = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="stylePrincipal.css">
  <title>Expresso Rosário</title>

</head>
<body>
  <div class="header">
  <div class="menu">
  <div class="welcome-message">
  Bem-vindo, <?php echo htmlspecialchars($user_name); ?>!
  </div>  
    
    <div class="input">
      <button class="value">
        <svg
          viewBox="0 0 16 16"
          xmlns="http://www.w3.org/2000/svg"
          data-name="Layer 2"
        >
          <path
            fill="#7D8590"
            d="m1.5 13v1a.5.5 0 0 0 .3379.4731 18.9718 18.9718 0 0 0 6.1621 1.0269 18.9629 18.9629 0 0 0 6.1621-1.0269.5.5 0 0 0 .3379-.4731v-1a6.5083 6.5083 0 0 0 -4.461-6.1676 3.5 3.5 0 1 0 -4.078 0 6.5083 6.5083 0 0 0 -4.461 6.1676zm4-9a2.5 2.5 0 1 1 2.5 2.5 2.5026 2.5026 0 0 1 -2.5-2.5zm2.5 3.5a5.5066 5.5066 0 0 1 5.5 5.5v.6392a18.08 18.08 0 0 1 -11 0v-.6392a5.5066 5.5066 0 0 1 5.5-5.5z"
          ></path>
        </svg>
        Perfil
      </button>
      <div class="paste-button">
        <button class="button">Cadastro &nbsp; ▼</button>
        <div class="dropdown-content">
          <a class="cad" href="/BDExpresso/Funcionario/gerente/cadastroGer.php">Gerente</a>
          <a class="cad" href="/BDExpresso/Funcionario/entregador/cadastro.php">Entregador</a>
          <a class="cad" href="/BDExpresso/cadastros/veiculo/cadastroVeiculo.php">Veículos</a>
          <a class="cad" href="/BDExpresso/cadastros/pacotes/index.php">Pacotes</a>
          <a class="cad" href="/BDExpresso/cadastros/rota/cadastroRotas.php">Rotas</a>
          <a class="cad" href="/BDExpresso/cadastros/entrega/cadastroEntrega.php">Entrega</a>
        </div>
      </div>

      <div class="paste-button">
        <button class="button">Controle &nbsp; ▼</button>
        <div class="dropdown-content">
          <a class="cad" href="/BDExpresso/Funcionario/sistema.php">funcionários</a>
          <a class="cad" href="#">entregas</a>
        </div>
      </div>

      <button class="value">
        <svg id="svg8" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
          <g id="layer1" transform="translate(-33.022 -30.617)">
            <path
              fill="#7D8590"
              id="path26276"
              d="m49.021 31.617c-2.673 0-4.861 2.188-4.861 4.861 0 1.606.798 3.081 1.873 3.834h-7.896c-1.7 0-3.098 1.401-3.098 3.1s1.399 3.098 3.098 3.098h4.377l.223 2.641s-1.764 8.565-1.764 8.566c-.438 1.642.55 3.355 2.191 3.795s3.327-.494 3.799-2.191l2.059-5.189 2.059 5.189c.44 1.643 2.157 2.631 3.799 2.191s2.63-2.153 2.191-3.795l-1.764-8.566.223-2.641h4.377c1.699 0 3.098-1.399 3.098-3.098s-1.397-3.1-3.098-3.1h-7.928c1.102-.771 1.904-2.228 1.904-3.834 0-2.672-2.189-4.861-4.862-4.861zm0 2c1.592 0 2.861 1.27 2.861 2.861 0 1.169-.705 2.214-1.789 2.652-.501.203-.75.767-.563 1.273l.463 1.254c.145.393.519.654.938.654h8.975c.626 0 1.098.473 1.098 1.1s-.471 1.098-1.098 1.098h-5.297c-.52 0-.952.398-.996.916l-.311 3.701c-.008.096-.002.191.018.285 0 0 1.813 8.802 1.816 8.82.162.604-.173 1.186-.777 1.348s-1.184-.173-1.346-.777c-.01-.037-3.063-7.76-3.063-7.76-.334-.842-1.525-.842-1.859 0 0 0-3.052 7.723-3.063 7.76-.162.604-.741.939-1.346.777s-.939-.743-.777-1.348c.004-.019 1.816-8.82 1.816-8.82.02-.094.025-.189.018-.285l-.311-3.701c-.044-.518-.477-.916-.996-.916h-5.297c-.627 0-1.098-.471-1.098-1.098s.472-1.1 1.098-1.1h8.975c.419 0 .793-.262.938-.654l.463-1.254c.188-.507-.062-1.07-.563-1.273-1.084-.438-1.789-1.483-1.789-2.652.001-1.591 1.271-2.861 2.862-2.861z"
            ></path>
          </g>
        </svg>
        Acessibilidade
      </button>

      <button class="value">
        <svg fill="none" viewBox="0 0 24 25" xmlns="http://www.w3.org/2000/svg">
          <path
            clip-rule="evenodd"
            d="m11.9572 4.31201c-3.35401 0-6.00906 2.59741-6.00906 5.67742v3.29037c0 .1986-.05916.3927-.16992.5576l-1.62529 2.4193-.01077.0157c-.18701.2673-.16653.5113-.07001.6868.10031.1825.31959.3528.67282.3528h14.52603c.2546 0 .5013-.1515.6391-.3968.1315-.2343.1117-.4475-.0118-.6093-.0065-.0085-.0129-.0171-.0191-.0258l-1.7269-2.4194c-.121-.1695-.186-.3726-.186-.5809v-3.29037c0-1.54561-.6851-3.023-1.7072-4.00431-1.1617-1.01594-2.6545-1.67311-4.3019-1.67311zm-8.00906 5.67742c0-4.27483 3.64294-7.67742 8.00906-7.67742 2.2055 0 4.1606.88547 5.6378 2.18455.01.00877.0198.01774.0294.02691 1.408 1.34136 2.3419 3.34131 2.3419 5.46596v2.97007l1.5325 2.1471c.6775.8999.6054 1.9859.1552 2.7877-.4464.795-1.3171 1.4177-2.383 1.4177h-14.52603c-2.16218 0-3.55087-2.302-2.24739-4.1777l1.45056-2.1593zm4.05187 11.32257c0-.5523.44772-1 1-1h5.99999c.5523 0 1 .4477 1 1s-.4477 1-1 1h-5.99999c-.55228 0-1-.4477-1-1z"
            fill="#7D8590"
            fill-rule="evenodd"
          ></path>
        </svg>
        Notficação
      </button>
     
        
      </button>

      <!--Termino da classe input-->
    </div>

    <!--Termino da classe menu-->
  </div>



  <button class="cta" onclick="window.location.href='/BDExpresso/Principal/Index/index.html'">
                <span class="hover-underline-animation"> Sair </span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                    width="16" height="16" fill="#d5d5d2" 
                    class="bi bi-arrow-bar-left" 
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd" 
                    d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5"/>
                </svg>
            </button>
</div>

  <div class="content">
  </div>
</body>
</html>