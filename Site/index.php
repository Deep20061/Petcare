<?php
include('php/server.php');
?>
<!DOCTYPE html>
<html lang="en">

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
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row py-3 px-lg-5">
            <div class="col-lg-4">
                <a href="index.html" class="navbar-brand d-none d-lg-block">
                    <h1 class="m-0 display-5 text-capitalize"><span class="text-primary">Pet</span>Care</span></h1>
                </a>
            </div>
            <div class="col-lg-8 text-center text-lg-right">
                    <img width="20%" src="img/02 Cabecalho-principal-ESVV-24-25.png" alt="Image" >
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-lg-5">
            <a href="" class="navbar-brand d-block d-lg-none">
                <h1 class="m-0 display-5 text-capitalize font-italic text-white"><span class="text-primary">Pet</span>Care</span></h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Início</a>
                    <a href="about.php" class="nav-item nav-link">Sobre</a>
                    <a href="service.php" class="nav-item nav-link">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link ">Raças</a>
                    <a href="booking.php" class="nav-item nav-link ">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Outras Páginas</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="capa_camera.php" class="dropdown-item">Capa para Micro Câmera</a>
                            <a href="blog_single.php" class="dropdown-item">Texto Informativos</a>
                        </div>
                    </div>
                </div>
                <?php if(isset($_SESSION['email'])): ?>
                    <a href="login.php" class="btn btn-lg btn-primary px-3 d-block d-lg-none">Ver Seus Animais</a>
                    <a href="login.php" class="btn btn-lg btn-primary px-3 d-none d-lg-block">Ver Seus Animais</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-lg btn-primary px-3 d-block d-lg-none">Login</a>
                    <a href="login.php" class="btn btn-lg btn-primary px-3 d-none d-lg-block">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100 img-fluid" src="img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h1 class="display-3 text-white mb-3"><span class="text-primary">Pet</span>Care</h1>
                            <h5 class="text-white mb-3 d-none d-sm-block">O site que te vai por a ver sorrisos na cara dos seus animais</h5>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image" >
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h1 class="display-3 text-white mb-3"><span class="text-primary">Pet</span>Care</h1>
                            <h5 class="text-white mb-3 d-none d-sm-block">Aprende a por os teus animais mais apegados a ti</h5>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100 " src="img/carousel-3.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h1 class="display-3 text-white mb-3"><span class="text-primary">Pet</span>Care</h1>
                            <h5 class="text-white mb-3 d-none d-sm-block">Duo nonumy et dolor tempor no et. Diam sit diam sit diam erat</h5>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#header-carousel" data-bs-slide="prev">
                <div class="btn btn-primary rounded" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon mb-n2"></span>
                </div>
            </a>
            <a class="carousel-control-next" href="#header-carousel" data-bs-slide="next">
                <div class="btn btn-primary rounded" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon mb-n2"></span>
                </div>
            </a>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Booking Start -->
    <div class="container-fluid bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="bg-primary py-5 px-4 px-sm-5">
                        <video width="350" height="500" loop autoplay muted>
                            <source src="video/Videofarrusco.mp4" type="video/mp4">
                            Seu navegador não suporta a tag de vídeo.
                        </video>
                    </div>
                </div>
                <div class="col-lg-7 py-5 py-lg-0 px-3 px-lg-5">
                    <br><h1 class="display-4 mb-4">Propósito da Pet<span class="text-primary">Care</span></h1>
                    <p>Com este site, poderás aprender a melhor cuidar dos teus animais, atrávez das seguintes funções:</p>
                    <div class="row py-2">
                        <div class="col-sm-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="flaticon-house font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <h5 class="text-truncate m-0">Melhore a sua casa</h5>
                                </div>
                                <p>A nossa plataforma dá dicas para que o possas adaptar a tua casa para o teu animal.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="flaticon-food font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <h5 class="text-truncate m-0">Alimentação</h5>
                                </div>
                                <p>Atravéz da nossa plataforma, poderás aprender o que os teus animais mais gostam.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="flaticon-grooming font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <h5 class="text-truncate m-0">Cuido do teu animal</h5>
                                </div>
                                <p class="m-0">Ajuda os teus animais a aprenderem a cuidar de si</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="flaticon-toy font-weight-normal text-secondary m-0 mr-3"></h1>
                                    <h5 class="text-truncate m-0">Comportamento</h5>
                                </div>
                                <p class="m-0">Aprende o Comportamento dos teus animais e ve o quanto eles gostam de ti</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- About Start -->
    <div class="container py-5">
        <div class="row py-5">
            <div class="col-lg-7 pb-5 pb-lg-0 px-3 px-lg-5">
                <h1 class="display-4 mb-4"><span class="text-primary">Plataformas</span> e <span class="text-secondary">Dispositivos</span></h1>
                <h5 class="text-muted mb-3">Para podermos otímizar o cuido dos nossos pequeninos, a nossa comunidade desenvolveu uma aplicação e um dispositivo. </h5>
                <p class="mb-4">Estes todos meios têm um propósito, podermos prevenir doenças e mortes assidentais, não queremos fazer com os nosso animais sofram desnecessariamente, porque com poucas e pequenas mudanças, podemos prevenir acidentes gigantescos. A nossa comunidade têm:</p>
                <ul class="list-inline">
                    <li><h5><i class="fa fa-check-double text-secondary mr-3"></i>Inteligência Artificial</h5></li>
                    <li><h5><i class="fa fa-check-double text-secondary mr-3"></i>Contactos de Emergência</h5></li>
                    <li><h5><i class="fa fa-check-double text-secondary mr-3"></i>Comunidade online 24/7</h5></li>
                </ul>
            </div>
            <div class="col-lg-5">
                <div class="row px-3">
                    <div class="col-12 p-0">
                        <img class="img-fluid w-100" src="img/about-1.jpg" alt="">
                    </div>
                    <div class="col-6 p-0">
                        <img class="img-fluid w-100" src="img/about-2.jpg" alt="">
                    </div>
                    <div class="col-6 p-0">
                        <img class="img-fluid w-100" src="img/about-3.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Features Start -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <img class="img-fluid w-100" src="img/feature.jpg" alt="">
            </div>
            <div class="col-lg-7 py-5 py-lg-0 px-3 px-lg-5">
                <h4 class="text-secondary mb-3">Quer se juntar á nossa comunidade?</h4>
                <h1 class="display-4 mb-4"><span class="text-primary">Aceitamos </span>toda a ajuda na <span class="text-primary">Pet</span>Care</h1>
                <p class="mb-4">A PetCare é uma plataforma de comunidade aberta, onde qualquer um é livre a se juntar a esta causa, quer saber como pode contribuir na nossa comunidade?</p>
                <div class="row py-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="flaticon-cat font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Tirar fotos aos animais</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-4">
                            <h1 class="flaticon-doctor font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Escritor de artigos</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <h1 class="flaticon-care font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Espalhar para vets</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <h1 class="flaticon-dog font-weight-normal text-secondary m-0 mr-3"></h1>
                            <h5 class="text-truncate m-0">Partilhar a PetCare</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->
    <!-- Blog Start -->
    <div class="container pt-5">
        <div class="d-flex flex-column text-center mb-5">
            <h4 class="text-secondary mb-3">Pet Explica</h4>
            <h1 class="display-4 m-0"><span class="text-primary">Textos Informativos </span>atualizados</h1>
        </div>
        <div class="row pb-3">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 mb-2">
                    <img class="card-img-top" src="img/blog-1.jpg" alt="">
                    <div class="card-body bg-light p-4">
                        <h4 class="card-title text-truncate">Papel dos Animais</h4>
                        <div class="d-flex mb-3">
                           <small class="mr-2"><i class="fa fa-user text-muted"></i> Tati Rodrigues</small>
                            <small class="mr-2"><i class="fa fa-folder text-muted"></i> Utilizador</small>
                        </div>
                        <p>Ao longo dos tempos, o ser humano sempre contou com a presença de animais, acabando por criar uma ligação com algumas espécies.</p>
                        <a class="font-weight-bold" href="blog_papeldosanimais.php">Ler Mais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog End -->


 <!-- Footer -->
<?php include('php/footer.php'); ?>


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>