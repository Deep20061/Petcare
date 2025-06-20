<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <?php
    include('php/server.php');
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
        $email=$_SESSION['email'];
        $passe= $_SESSION['password'];
        $id = $_SESSION['id'];
    } 
    
       
    
    ?>
    <div class="container mt-5 pt-5 pb-3">
        <div class="d-flex flex-column text-center mb-5">
            <h1 class="display-4 m-0">Bem-vindo, <span class="text-primary"><?php echo ($_SESSION['nome']); ?></span>!</h1>
        </div>
        <div class="row justify-content-center">  
        <?php
        $id = $_SESSION['id'];
        $consulta = "SELECT a.*, r.nome as raca_nome 
                    FROM animais a 
                    LEFT JOIN racas r ON a.c_raca = r.c_raca 
                    WHERE a.pertence = $id
                    order by raca_nome";
        $resultado = pg_query($conn,$consulta);
$linhas = pg_num_rows($resultado);

if ($linhas == 0) {
    echo '<div style="display:flex; justify-content:center; align-items:center; height:60vh; font-family: \'Nunito\', sans-serif; font-size: 1.5rem; text-align:center; flex-direction: column;">';
    echo 'Não há animais registados.<br>';
    echo '<a href="registeranimal.php" style="margin-top: 1rem; font-size: 1.2rem; color: #28a745; text-decoration: underline;">Deseja inserir um?</a>';
    echo '</div>';
} else {
    for ($i=0; $i < $linhas; $i++) {
        $tabela = pg_fetch_array($resultado);  
        $animal_id = $tabela['img'];
        
        // Verificar se existe imagem no banco de dados
        $consulta_imagem = "SELECT id FROM imagens WHERE id = $animal_id LIMIT 1";
        $resultado_imagem = pg_query($conn, $consulta_imagem);
        $tem_imagem = (pg_num_rows($resultado_imagem) > 0);
        $ida=$tabela['codanimal'];
        
        echo ("<div class='col-lg-3 col-md-6'>
            <div class='team card position-relative overflow-hidden border-0 mb-4'>

               <div class='square-img-container' style='width: 100%; padding-top: 100%; position: relative; overflow: hidden;'>
                    <img class='card-img-top' src='" . ($tem_imagem ? "php/exibir_imagem.php?id=$animal_id" : "img/team-1.jpg") . "' 
                         alt='Imagem do animal' 
                         style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;'>
                </div>
                <div class='card-body text-center p-0'>
                    <div class='team-text d-flex flex-column justify-content-center bg-light'>
                        <h5>". $tabela['nome']."</h5>
                        <p class='text-muted mb-0'>".$tabela['raca_nome']."</p>
                    </div>
                    <div class='team-social d-flex align-items-center justify-content-center bg-dark'>
                        <a class='btn btn-outline-primary text-center mr-2 px-0' style='width: 60px; height: 36px;' href='coiso.php?id=$ida'> > </a>
                    </div>
                </div>
            </div>
        </div>");
    }
}
        ?>  
        
        </div>
    </div>
    <button class="menu-toggle-right" id="menuToggleRight">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Menu Lateral Direito -->
    <nav class="sidebar-right" id="sidebarRight">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-paw"></i> Menu Animais PetCare</h4>
        </div>
        
        <a href="animals.php" class="active"><i class="fas fa-dog"></i> Meus Animais</a>
        <a href="registeranimal.php"><i class="fas fa-calendar-check"></i> Registrar Animal</a>
        <a href=<?php echo("alimentaçao.php?id=$ida") ?>><i class="fas fa-file-medical"></i> Ver dietas</a>
        <a href="editarutilizador.php"><i class="fas fa-cog"></i> Definições</a>
        
        <div style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
            <a href="logout.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Fechar Sessão
            </a>
        </div>
    </nav> 
    <p>

    <!-- Footer Start -->
  <!-- Footer -->
<?php include('php/footer.php'); ?>

    <!-- Footer End -->
<script>
        // Controle do menu lateral direito
        document.getElementById('menuToggleRight').addEventListener('click', function() {
            document.getElementById('sidebarRight').classList.toggle('active');
            document.getElementById('menuToggleRight').classList.toggle('active');
        });
        
        // Fechar o menu ao clicar em um item
        document.querySelectorAll('#sidebarRight a').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('sidebarRight').classList.remove('active');
                document.getElementById('menuToggleRight').classList.remove('active');
            });
        });
    </script>
</body>
</html>
<style>
/* Adicione este CSS no <head> da página animals.php */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-content {
    flex: 1;
}

footer {
    margin-top: auto;
}
</style>