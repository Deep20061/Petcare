<?php
include('php/server.php');
?>
<?php
// Configuração da base de dados
$host = "aws-0-eu-west-3.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.kszhqvvmlrlkvsvbpinx";
$password = "LEVufRUwFPTdywIp";

try {
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    
    if (!$conn) {
        throw new Exception("Erro na conexão com a base de dados");
    }
    
    // Buscar todos os tipos de animais
    $query_tipos = "SELECT DISTINCT c_tipo, tipo FROM tipo ORDER BY tipo";
    $resultado_tipos = pg_query($conn, $query_tipos);
    
    if (!$resultado_tipos) {
        throw new Exception("Erro ao buscar tipos de animais");
    }
    
} catch(Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PetCare - Ve as melhores raças de animais</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

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

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        .breed-table {
            margin-bottom: 3rem;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .table-header {
            background: linear-gradient(45deg, #28a745, #28a745);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .table-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .custom-table {
            margin: 0;
        }
        .custom-table th {
            background-color: #f8f9fa;
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
        }
        .custom-table td {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .custom-table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }
        .breed-name {
            font-weight: 600;
            color: #28a745;
        }
        .breed-description {
            color: #6c757d;
            line-height: 1.5;
        }
        .no-breeds {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            font-style: italic;
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
                <img width="20%" src="img/02 Cabecalho-principal-ESVV-24-25.png" alt="Image">
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
                    <a href="service.php" class="nav-item nav-link ">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link active">Raças</a>
                    <a href="booking.php" class="nav-item nav-link">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown">Outras Páginas</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="capa_camera.php" class="dropdown-item">Capa para Micro Câmera</a>
                            <a href="blog_single.php" class="dropdown-item ">Texto Informativos</a>
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
    <!-- Breeds Content Start -->
    <div class="container py-5">
        <?php
        $search = '';

        // Reset do ponteiro do resultado
        pg_result_seek($resultado_tipos, 0);
        
        $table_index = 0;
        while ($tipo = pg_fetch_array($resultado_tipos)) {
            $c_tipo = $tipo['c_tipo'];
            $nome_tipo = $tipo['tipo'];
            
            // Buscar raças para este tipo de animal sem filtro
            $query_racas = "SELECT nome, definicao FROM racas WHERE c_tipo = $1 ORDER BY nome";
            $resultado_racas = pg_query_params($conn, $query_racas, array($c_tipo));
            
            if ($resultado_racas && pg_num_rows($resultado_racas) > 0) {
        ?>
                <div class="breed-table">
                    <div class="table-container">
                        <div class="table-header">
                            <h3><i class="fas fa-paw mr-2"></i><?php echo htmlspecialchars($nome_tipo); ?></h3>
                        </div>
                        <div class="table-responsive">
                            <table id="breedTable_<?php echo $table_index; ?>" class="table custom-table breed-datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Nome da Raça</th>
                                        <th style="width: 70%;">Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($raca = pg_fetch_array($resultado_racas)) { ?>
                                        <tr>
                                            <td class="breed-name">
                                                <?php echo htmlspecialchars($raca['nome']); ?>
                                            </td>
                                            <td class="breed-description">
                                                <?php 
                                                $definicao = $raca['definicao'];
                                                if (empty($definicao) || trim($definicao) == '') {
                                                    echo '<em>Descrição não disponível</em>';
                                                } else {
                                                    echo htmlspecialchars($definicao);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        <?php
            }
            $table_index++;
        }
        
        // Verificar se não há tipos de animais
        pg_result_seek($resultado_tipos, 0);
        if (pg_num_rows($resultado_tipos) == 0) {
        ?>
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h3>Nenhum tipo de animal encontrado</h3>
                <p class="text-muted">Não há dados disponíveis na base de dados.</p>
            </div>
        <?php } ?>
    </div>
    <!-- Breeds Content End -->

    <!-- Footer Start -->
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

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            $('.breed-datatable').each(function() {
                $(this).DataTable({
                    "paging": true,
                    "pageLength": 10,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json",
                        "search": "Procurar raças:",
                        "searchPlaceholder": ""
                    }
                });
            });
        });
    </script>
</body>

</html>

<?php
// Fechar conexão
if ($conn) {
    pg_close($conn);
}
?>
