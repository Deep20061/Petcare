<head>
    <meta charset="utf-8">
    <title>Pet Care - Aprende o que é melhor para o teu animal</title>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Flaticon Font -->
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .login {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 200px);
            margin-bottom: 2rem;
            padding: 2rem 0;
        }
        
        .login .bg-primary {
            width: 100%;
            max-width: 800px; /* Aumentei de 500px para 600px */
            margin: 2rem auto;
            padding: 3rem 2rem; /* Aumentei o padding vertical de py-5 para mais */
        }
        
        .login .bg-primary form {
            padding: 2rem 0; /* Aumentei o padding do form */
        }
        
        .form-group {
            margin-bottom: 1.5rem; /* Mais espaço entre os campos */
        }
        
        .form-control {
            padding: 1.2rem; /* Campos de input maiores */
            font-size: 1.1rem;
        }
        
        .btn {
            padding: 1rem 2rem; /* Botão maior */
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row py-3 px-lg-5">
            <div class="col-lg-4">
                <a href="index.html" class="navbar-brand d-none d-lg-block">
                    <h1 class="m-0 display-5 text-capitalize"><span class="text-primary">Pet</span>Care</h1>
                </a>
            </div>
            <div class="col-lg-8 text-center text-lg-right">
                    <img width="20%" src="img/02 Cabecalho-principal-ESVV-24-25.png" alt="Image" >
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <div class="main-content">
        <div class="login">
            <div class="bg-primary py-5 px-4 px-sm-5">
                <form class="py-5" method='post'>
                        <h1 class="textlogin display-5 text-capitalize">Criar Conta</h1><p></p>
                    <div class="form-group">
                        <input type="text" id="Nome" name="Nome" class="form-control border-0 p-4" placeholder="Nome Completo" required="required" />
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="Email" class="form-control border-0 p-4" placeholder="Email" required="required" />
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control border-0 p-4" placeholder="Palavra-Passe" required="required" />
                    </div>
                   
                    <div>
                        <button class="btn btn-dark btn-block border-0 py-3">Criar</button>
                    </div>
                    <p></p> 
                    <p class="topxuxa">Já tens conta? <a  href="login.php" class="topxuxa2">Faz login</a></p>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        ini_set ('default_charset','utf-8');
                        include('php/server.php');
                        $nome = $_POST['Nome'];
                        $email =$_POST['Email'];
                        $password =$_POST['password'];
                        #select com email e se não for igual vai criar com insert into

                        $consulta = "select * from utilizadores where email = '$email'";
                        $resultado = pg_query($conn,$consulta);
                        $n_registros=pg_num_rows($resultado);
                        if ($n_registros == 1) {
                            echo "<p class='topxuxa'>Este email já está registado, tente de novo </p>";}
                        else{
                            $dados = array(
                                'nome'=> $nome,
                                'email' => $email,
                                'passeword' => $password
                            );
                            pg_insert($conn,'utilizadores',$dados);
                            $resultado = pg_query($conn,$consulta);
                            $n_registros=pg_num_rows($resultado);
                            if ($n_registros == 1) {echo "<p class='topxuxa'>Conta criada com sucesso!</p>";}
                                else{echo "<p class='topxuxa'>Erro ao criar conta</p>";}
                        }
                    }
                        ?>
                </form>
            </div>
        </div>
    </div>
    
  <!-- Footer -->
<?php include('php/footer.php'); ?>
    <!-- Footer End -->

</body>
</html>
