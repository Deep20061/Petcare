<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Care - Registrar Animal</title>

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Customized Bootstrap Stylesheet -->
     
    <link href="css/style.css" rel="stylesheet">
    
</head>
<body>
    <?php
    include('php/server.php');
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    // Definir variáveis de sessão
    $email = $_SESSION['email'];
    $passe = $_SESSION['password'];
    $id = $_SESSION['id'];
    
    // Parte para carregar raças via AJAX
    if (isset($_POST['tipo-animal']) && !isset($_POST['form-submitted'])) {
        header('Content-Type: text/html');
        $opcao = $_POST['tipo-animal'];
        
        // Consulta ao banco de dados para obter raças
        $tipo = 0;
        switch($opcao) {
            case 'cao': $tipo = 1; break;
            case 'gato': $tipo = 2; break;
            case 'peixe': $tipo = 3; break;
            case 'Ave': $tipo = 4; break;
        }
        
        $sql = "SELECT nome FROM racas WHERE c_tipo = $tipo";
        $resultado = pg_query($conn, $sql);
        
        if ($resultado && pg_num_rows($resultado) > 0) {
            echo '<option value="">-- Escolha --</option>';
            while ($row = pg_fetch_assoc($resultado)) {
                echo '<option value="' . htmlspecialchars($row['nome']) . '">' . htmlspecialchars($row['nome']) . '</option>';
            }
        } else {
            echo '<option value="">-- Nenhuma raça encontrada --</option>';
        }
        exit;
    }

    // Processar o formulário de registro
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form-submitted'])) {
        $nome = pg_escape_string($conn, $_POST['nome-animal']);
        $age = pg_escape_string($conn, $_POST['data-animal']);
        $tipo = pg_escape_string($conn, $_POST['tipo-animal']);
        $race = pg_escape_string($conn, $_POST['raça-animal']);
        
        // Validação básica
        if (!empty($nome) && !empty($age) && !empty($tipo) && !empty($race)) {
            // Obter código da raça
            $sql_raca = "SELECT c_raca FROM racas WHERE nome = $1";
            $result_raca = pg_prepare($conn, "get_raca", $sql_raca);
            $result_raca = pg_execute($conn, "get_raca", array($race));
            
            if ($result_raca && pg_num_rows($result_raca) > 0) {
                $row_raca = pg_fetch_assoc($result_raca);
                $c_raca = $row_raca['c_raca'];
                
                // Inserir no banco de dados
                $imagem=0;
                $data = date("Y-m-d", strtotime($age));  
                $sql = "INSERT INTO animais (nome, pertence, c_raca, nascimento, img) VALUES ('$nome', $id, $c_raca, '$data',$imagem)";
                $result = pg_query($conn, $sql);
                
                if ($result) {
                    echo '<script> swal("Animal registrado com sucesso!"); window.location.href = "animals.php";</script>';
                    exit();
                } else {
                    $error = pg_last_error($conn);
                    echo '<script> swal("Erro ao registrar animal: ' . addslashes($error) . '");</script>';
                }
            } else {
                echo '<script> swal("Raça não encontrada.");</script>';
            }
        } else {
            echo '<script> swal("Por favor, preencha todos os campos.");</script>';
        }
    }
    ?>

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
     
    <!-- Conteúdo Principal -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container p-4 p-md-5">
                    <div class="text-center mb-5">
                        <h1 class="display-4 mb-3">Registrar Novo <span class="text-primary">Animal</span></h1>
                        <p class="lead text-muted">Preencha os campos abaixo para registrar um novo animal.</p>
                    </div>
                    
                    <form method="post" action="" class="needs-validation" novalidate id="animal-form">
                        <input type="hidden" name="form-submitted" value="1">
                        
                        <div class="form-group mb-4">
                            <label for="nome-animal" class="form-label">Nome do Animal</label>
                            <input type="text" id="nome-animal" name="nome-animal" class="form-control form-control-custom" placeholder="Ex: Rex" required autocomplete="off">
                            <div class="invalid-feedback">Por favor, insira o nome do animal.</div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="data-animal" class="form-label">Data de Nascimento</label>
                            <input type="date" id="data-animal" name="data-animal" class="form-control form-control-custom" required autocomplete="off">
                            <div class="invalid-feedback">Por favor, selecione a data de nascimento.</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="tipo-animal" class="form-label">Tipo de Animal</label>
                                    <select id="tipo-animal" name="tipo-animal" class="form-control form-control-custom" required autocomplete="off">
                                        <option value="">Selecione o tipo</option>
                                        <option value="cao">Cão</option>
                                        <option value="gato">Gato</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione o tipo de animal.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="raça-animal" class="form-label">Raça</label>
                                    <select id="raça-animal" name="raça-animal" class="form-control form-control-custom" required disabled autocomplete="off">
                                        <option value="">Selecione primeiro o tipo</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione a raça do animal.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg px-4 py-3">
                                Registrar Animal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Lateral Direito -->
    <button class="menu-toggle-right" id="menuToggleRight">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Menu Lateral Direito -->
<nav class="sidebar-right" id="sidebarRight">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-paw"></i> Menu Animais PetCare</h4>
        </div>
        
        <a href="animals.php" ><i class="fas fa-dog"></i> Meus Animais</a>
        <a href="registeranimal.php"class="active"><i class="fas fa-calendar-check"></i> Registrar Animal</a>
        <a href="alimentaçao.php   "><i class="fas fa-file-medical"></i> Ver dietas</a>
        <a href="editarutilizador.php"><i class="fas fa-cog"></i> Definições</a>
        
        <div style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
            <a href="logout.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Fechar Sessão
            </a>
        </div>
    </nav> 

    <!-- Footer -->
  <?php include('php/footer.php'); ?>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
    <script>
        // Validação do formulário
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        
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

        // AJAX para carregar raças
        $(document).ready(function() {
            $('#tipo-animal').change(function() {
                var valor = $(this).val();
                var $segundoSelect = $('#raça-animal');

                if (valor) { 
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: { 
                            'tipo-animal': valor,
                            'is-ajax': 1
                        },
                        success: function(resposta) {
                            $segundoSelect.html(resposta).prop('disabled', false);
                        },
                        error: function(xhr, status, erro) {
                            console.error("Erro AJAX:", status, erro);
                            $segundoSelect.html(
                                '<option value="">-- Erro ao carregar raças --</option>'
                            ).prop('disabled', false);
                        }
                    });
                } else {
                    $segundoSelect.html(
                        '<option value="">Selecione primeiro o tipo</option>'
                    ).prop('disabled', true);
                }
            });
        });
    </script>
</body>
</html>