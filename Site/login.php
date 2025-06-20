<?php
include('php/server.php');

// Verificar se já está logado
if (isset($_SESSION['email'])) {
    header("Location: animals.php");
    exit();
} 

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ini_set('default_charset','utf-8');
    $email = $_POST['email'];
    $password = $_POST['password'];
    $consulta = "select * from utilizadores where email = '$email' and passeword = '$password'";
    $resultado = pg_query($conn, $consulta);
    $tabela = pg_fetch_array($resultado);
    $n_registros = pg_num_rows($resultado);
    
    if ($n_registros == 0) {
        $erro_login = "Palavra passe ou email estão incorretos, por favor tente de novo.";
    } else {
        $nome = $tabela['nome'];
        $id = $tabela['códigoutilizador'];
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        header("Location: animals.php");
        exit();
    }
}
?>
<html>
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

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
            max-width: 800px;
            margin: 2rem auto;
            padding: 3rem 2rem;
        }
        
        .login .bg-primary form {
            padding: 2rem 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            padding: 1.5rem; /* increased padding to match create.php */
            font-size: 1.1rem;
        }
        
        .btn {
            padding: 1rem 2rem; /* keep horizontal padding */
            padding-top: 1rem;
            padding-bottom: 1rem; /* adjust vertical padding to py-3 equivalent */
            font-size: 1.1rem;
        }

        /* Add media query for mobile responsiveness */
        @media (max-width: 576px) {
            .login .bg-primary {
                max-width: 320px; /* smaller max width on mobile */
                padding: 2rem 1rem; /* reduce padding on smaller screens */
                margin: 1rem auto;
            }
            .form-control {
                padding: 1.2rem; /* slightly smaller inputs on mobile */
            }
            .btn {
                padding: 0.8rem 1.5rem;
                font-size: 1rem;
            }
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
                <h1 class="textlogin display-5 text-capitalize text-center mb-4">Iniciar sessão</h1>
                <div class="form-group mb-3">
                    <input type="email" id="email" name="email" class="form-control border-0 p-3" placeholder="Email" required="required" />
                </div>
                <div class="form-group mb-4">
                    <input type="password" id="password" name="password" class="form-control border-0 p-3" placeholder="Palavra-Passe" required="required" />
                </div>
                <div>
                    <button class="btn btn-dark btn-block border-0 py-3 w-100" type="submit">Entrar</button>
                </div>
               <p></p> 
                <p class="topxuxa text-center mt-4">Não tens conta? <a href="create.php" class="topxuxa2">Regista-te</a></p>
                <?php
                // Exibir mensagem de erro se houver
                if (isset($erro_login)) {
                    echo "<p class='topxuxa text-center'>$erro_login</p>";
                }
                ?>
            </form>
        </div>
    </div>
    </div>
<!-- Footer -->
<?php include('php/footer.php'); ?>

</div>
</html>
