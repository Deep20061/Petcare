<?php
    ini_set ('default_charset','utf-8');
    include('server.php');
    $email =$_POST['email'];
    $consulta = "select * from utilizadores where email = '$email'";
    $resultado = pg_query($conn,$consulta);
    $n_registros=pg_num_rows($resultado);
    if ($n_registros == 0) {
        echo "O utilizador não existe";}
    else{
    echo "O utilizador existe";}
  ?>