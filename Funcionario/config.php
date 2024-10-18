<?php

    $servidor="localhost";
    $usuario="root";
    $senha="";
    $dbname="bdExpressoR";

    $conexao =  mysqli_connect($servidor,$usuario,$senha,$dbname);

    if ($conexao->connect_errno){
        echo"Erro";
    }
?>