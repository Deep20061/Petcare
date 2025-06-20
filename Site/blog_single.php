<?php
include('php/server.php');
?>
<?php
// Handle file upload form submission
$uploadMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_submit'])) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $file = $_FILES['document'] ?? null;
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $articleName = trim($_POST['article_name'] ?? '');

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $errorCode = $file['error'] ?? 'unknown';
        $uploadMessage = '<div class="alert alert-danger">Erro no upload do ficheiro. Código do erro: ' . htmlspecialchars($errorCode) . '</div>';
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $uploadMessage = '<div class="alert alert-danger">Tipo de ficheiro não permitido. Apenas PDF e Word são aceites.</div>';
    } elseif (!$email) {
        $uploadMessage = '<div class="alert alert-danger">Por favor, insira um email válido.</div>';
    } elseif (empty($articleName)) {
        $uploadMessage = '<div class="alert alert-danger">Por favor, insira o nome do artigo.</div>';
    } else {
        $fileName = basename($file['name']);
        $targetFile = $uploadDir . $fileName;

        // To avoid overwriting, add timestamp if file exists
        if (file_exists($targetFile)) {
            $fileName = time() . '_' . $fileName;
            $targetFile = $uploadDir . $fileName;
        }

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    // Save email, article name, and uploaded file name to submissions.txt
    $submissionsFile = $uploadDir . 'submissions.txt';
    $entry = $email . '|' . $articleName . '|' . $fileName . PHP_EOL;
    file_put_contents($submissionsFile, $entry, FILE_APPEND | LOCK_EX);

    $uploadMessage = '<div class="alert alert-success">Ficheiro enviado com sucesso!</div>';
} else {
    $uploadMessage = '<div class="alert alert-danger">Erro ao guardar o ficheiro.</div>';
}
    }
}
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

    <style>
    </style>
</head>

<body>
    <?php echo $uploadMessage; ?>
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
                    <a href="index.php" class="nav-item nav-link ">Início</a>
                    <a href="about.php" class="nav-item nav-link">Sobre</a>
                    <a href="service.php" class="nav-item nav-link">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link ">Raças</a>
                    <a href="booking.php" class="nav-item nav-link ">Mapa</a>
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
     <br>
    <h1 class="display-4 mb-4 text-center">Textos Informátivos da <span class="text-primary">Pet</span>Care</h1>
    <div class="container pt-5">
        <div class="row pb-3">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 mb-2 position-relative">
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

    <div class="container mb-4 text-start">
        <button class="btn btn-primary" id="openModalBtn">Cria o teu Texto Informativo</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadModalLabel">Anexa o teu Texto Informativo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Queres ter um texto informativo de tua autoria? Anexa o teu texto informativo abaixo e completa o reto do formulário. Os nossos administradores irão ler e rever o teu documento e entrar em contacto contigo para tirarem alguma dúvida contigo.</p>
            <div class="mb-3">
              <label for="document" class="form-label">Documento (PDF ou Word)</label>
              <input class="form-control" type="file" id="document" name="document" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">O teu Email</label>
              <input class="form-control" type="email" id="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="mb-3">
              <label for="article_name" class="form-label">Nome do Texto</label>
              <input class="form-control" type="text" id="article_name" name="article_name" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="upload_submit" class="btn btn-primary">Enviar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>

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

    <script>
    // Open modal on button click
    document.getElementById('openModalBtn').addEventListener('click', function() {
        var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        uploadModal.show();
    });
    </script>
</body>

</html>
