<?php
include('php/server.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PetCare - Papel dos Animais</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Pet blog about the role of animals" name="description">

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
                    <h1 class="m-0 display-5 text-capitalize"><span class="text-primary">Pet</span>Care</h1>
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
                <h1 class="m-0 display-5 text-capitalize font-italic text-white"><span class="text-primary">Pet</span>Care</h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Início</a>
                    <a href="about.php" class="nav-item nav-link">Sobre</a>
                    <a href="service.php" class="nav-item nav-link">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link">Raças</a>
                    <a href="booking.php" class="nav-item nav-link">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Outras Páginas</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="capa_camera.php" class="dropdown-item">Capa para Micro Câmera</a>
                            <a href="blog_single.php" class="dropdown-item active">Texto Informativos</a>
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

    <!-- Blog Single Start -->
    <div class="container pt-5">
        <div class="d-flex flex-column text-center mb-5 pt-5">
            <h4 class="text-secondary mb-3">Pet Info</h4>
            <h1 class="display-4 m-0"><span class="text-primary">Papel</span> dos Animais</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="bg-light p-4 rounded">
                    <p>Ao longo dos tempos, o ser humano sempre contou com a presença de animais, acabando por criar uma ligação com algumas espécies. Essa convivência levou certos animais a habituarem-se à companhia humana, tornando-se, assim, animais domésticos. Acredita-se que os primeiros a serem domesticados foram os cães, descendentes dos lobos. Esta relação entre humanos e cães existe há mais de 30 mil anos, sendo um dos primeiros exemplos de interação entre espécies diferentes com benefícios mútuos.</p>
                    <div class="text-center my-4">
                        <img src="img/blog-1.jpg" alt="Animais e Humanos" class="img-fluid rounded" />
                        <p class="text-muted">Animais e humanos convivendo ao longo do tempo</p>
                    </div>
                    <p>Para além da companhia, os animais domésticos têm demonstrado ser uma fonte de bem-estar para os seres humanos, proporcionando não só afeto, mas também benefícios físicos e emocionais, como a redução do stress e o aumento da atividade física.</p>
                    <p>A relação entre os seres humanos e os animais domésticos vai além da simples convivência. Desde os primórdios, os cães desempenharam um papel crucial, não só como companheiros, mas também como auxiliares na caça, na proteção e até mesmo no pastoreio, estes sempre nos trouxeram bastantes benefícios. Contudo, ao longo dos anos esta relação evoluiu, e os cães passaram integrantes das famílias, oferecendo afeto, segurança e companhia incondicional. Agora também conhecidos como os melhores amigos do homem.</p>
                    <div class="text-center my-4">
                        <img src="img/blog-2.jpg" alt="Cães e Família" class="img-fluid rounded" />
                        <p class="text-muted">Animais como membros da família</p>
                    </div>
                    <p>Mas não foram só cães, outros animais também se adaptaram ao estilo de vida humano, como os gatos, os cavalos e até os animais de estimação mais exóticos. Cada um desses animais trouxe benefícios distintos à sociedade. Por exemplo, os gatos têm sido aliados na luta contra roedores, enquanto os cavalos desempenharam um papel fundamental no transporte e na agricultura, antes da mecanização.</p>
                    <p>Os animais de estimação têm um impacto significativo na melhoria do bem-estar dos seres humanos, oferecendo benefícios tanto físicos como emocionais. A presença de um animal em casa pode reduzir os níveis de stress e ansiedade, promovendo assim um ambiente relaxante e agradável. A interação diária com animais, como cães e gatos, proporciona uma sensação de afeto e companheirismo que contribui para a redução da solidão, especialmente em pessoas que vivem sozinhas ou em situações de isolamento social além de ajudar muito pessoas em situação de depressão ou outros problemas associados ao foro mental.</p>
                    <div class="text-center my-4">
                        <img src="img/blog-3.jpg" alt="Benefícios dos Animais" class="img-fluid rounded" />
                        <p class="text-muted">Benefícios físicos e emocionais dos animais</p>
                    </div>
                    <p>Estudos científicos demonstram que os animais de estimação têm um efeito positivo na saúde mental. O simples ato de acariciar um cão ou um gato pode liberar endorfinas e oxitocina, hormonas associadas ao prazer e ao vínculo emocional, ajudando a melhorar o humor e a reduzir os sintomas de depressão, além de contribuírem para fins terapêuticos como a terapia assistida por animais, em que cães e outros animais ajudam no tratamento de doenças como o autismo e o Alzheimer.</p>
                    <p>Para além disso, a presença de um animal de estimação pode ajudar a aumentar a autoestima, já que muitos donos experienciam uma sensação de responsabilidade e propósito ao cuidar do seu animal. Além do apoio emocional, os nossos animais de estimação também desempenham um papel importante na promoção da atividade física. Como por exemplo os cães e os seus passeios diários exigidos pela necessidade de exercício físico não só beneficiam a saúde do animal, como também incentivam os donos a manterem-se mais ativos. Este tipo de interação contribui para a redução do risco de doenças cardiovasculares, ao aumentar a frequência cardíaca e melhorar a circulação sanguínea.</p>
                    <div class="text-center my-4">
                        <img src="img/blog-4.jpg" alt="Cães-guia e Assistência" class="img-fluid rounded" />
                        <p class="text-muted">Cães-guia e cães de assistência</p>
                    </div>
                    <p>Para além dos benefícios emocionais e físicos, existem animais de estimação que desempenham funções fundamentais na vida de muitas pessoas. É o caso dos cães-guia, que ajudam pessoas com deficiência visual a deslocarem-se com maior segurança e autonomia. Estes animais são treinados desde muito cedo para responder a comandos específicos e lidar com diversas situações do dia a dia, sendo verdadeiros companheiros e facilitando assim aos seus donos uma vida mais independente.</p>
                    <p>Também os cães de assistência e socorristas têm um papel essencial no bem-estar humano. Existem cães treinados para auxiliar pessoas com epilepsia, autismo ou outras condições de saúde, alertando para crises iminentes ou proporcionando conforto em momentos de ansiedade. Já os cães de busca e salvamento são utilizados em operações de resgate, como em desastres naturais ou desaparecimentos, demonstrando um nível de inteligência, sensibilidade e dedicação que os torna indispensáveis em muitas missões humanitárias. Estes exemplos mostram que a relação entre humanos e animais de estimação vai muito além do afeto. Os animais podem ser verdadeiros parceiros na promoção da qualidade de vida, ajudando a suprir necessidades físicas, emocionais e sociais.</p>
                    <p>Em suma, os animais de estimação desempenham um papel fundamental no bem-estar dos seres humanos. Para além da companhia e do afeto que proporcionam, contribuem positivamente para a saúde física e mental, ajudando a reduzir o stress, a solidão e promovendo um estilo de vida mais ativo. Animais como os cães-guia, de assistência ou de resgate demonstram ainda a sua importância em contextos mais exigentes, sendo verdadeiros aliados na superação de dificuldades e no apoio a pessoas com necessidades especiais. A convivência com os animais de estimação revela-se, assim, uma relação de benefício mútuo que deve ser valorizada e respeitada.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Single End -->

    <div class="container text-center my-4">
        <a href="blog_single.php" class="btn btn-primary btn-lg">Voltar</a>
    </div>

   <!-- Footer -->
<?php include('php/footer.php'); ?>
    <!-- Footer End -->

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
