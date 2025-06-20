

<?php
include('php/notifications.php');
ini_set('memory_limit', '128M'); // Aumenta o limite de memória para 128MB
//         $id = $_SESSION['id'];

include('php/server.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['id'];
$nome_usuario = $_SESSION['nome'];

// Buscar todos os animais do utilizador
$consulta_animais = "SELECT * FROM animais WHERE pertence = $user_id ORDER BY nome LIMIT 100";
$resultado_animais = pg_query($conn, $consulta_animais);
$animais = [];
if ($resultado_animais === false) {
    error_log("Query failed: " . pg_last_error($conn));
    $animais = [];
} else {
    while ($row = pg_fetch_array($resultado_animais)) {
        $animais[] = $row;
    }
}

// Se foi selecionado um animal específico
$animal_selecionado = null;
$dietas = [];
if (isset($_GET['animal_id']) && is_numeric($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    
    // Verificar se o animal pertence ao utilizador
$consulta_animal = "SELECT \"Peso\", animais.nome as nome, nascimento, castrado, racas.nome as raca, racas.nome as especie, racas.c_tipo FROM animais INNER JOIN racas ON animais.c_raca = racas.c_raca WHERE codanimal = $animal_id AND pertence = $user_id";
    $resultado_animal = pg_query($conn, $consulta_animal);
    
    if (pg_num_rows($resultado_animal) > 0) {
        $animal_selecionado = pg_fetch_array($resultado_animal);
        
        // Calcular idade do animal em meses
        $nascimento = new DateTime($animal_selecionado['nascimento']);
        $hoje = new DateTime();

        $intervalo = $hoje->diff($nascimento);
        $idadey = $intervalo->y;
        $idadem = $intervalo->m;

        $idade_meses = $idadey * 12 + $idadem;
    
        if ($idadey == 0 && $idadem == 0) {
            $idade = "Recém-nascido";
        } elseif ($idadey == 0) {
            $idade = $idadem . ' ' . ($idadem == 1 ? 'mês' : 'meses');
        } elseif ($idadem == 0) {
            $idade = $idadey . ' ' . ($idadey == 1 ? 'ano' : 'anos');
        } else {
            $idade = $idadey . ' ' . ($idadey == 1 ? 'ano' : 'anos') . ' e ' . 
                     $idadem . ' ' . ($idadem == 1 ? 'mês' : 'meses');
        }
        
$castrado = $animal_selecionado['castrado'];
$peso = $animal_selecionado['Peso'];
$racao_necessaria = '';

if ($idade_meses < 6) {
    $racao_necessaria = "Ração Puppy Premium - para filhotes até 6 meses";
} elseif ($idade_meses < 12) {
    $racao_necessaria = "Ração Junior Growth - para filhotes de 6 a 12 meses";
} else {
    if ($castrado == 't' || $castrado === true || $castrado == '1') {
        if ($peso < 5) {
            $racao_necessaria = "Ração Adult Castrado Balance - para adultos castrados de pequeno porte";
        } elseif ($peso < 20) {
            $racao_necessaria = "Ração Adult Castrado Balance - para adultos castrados de porte médio";
        } else {
            $racao_necessaria = "Ração Adult Castrado Balance - para adultos castrados de grande porte";
        }
    } else {
        if ($peso < 5) {
            $racao_necessaria = "Ração Adult Active - para adultos não castrados de pequeno porte";
        } elseif ($peso < 20) {
            $racao_necessaria = "Ração Adult Active - para adultos não castrados de porte médio";
        } else {
            $racao_necessaria = "Ração Adult Active - para adultos não castrados de grande porte";
        }
    }
}
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Alimentação - Pet Care</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    <link href="css/notifications.css" rel="stylesheet">

    <style>
        .btn-primary-custom {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
            box-shadow: 0 6px 20px rgba(33, 136, 56, 0.6);
            transform: translateY(-3px);
        }
        .dieta-card {
            border-left: 6px solid #28a745;
            margin-bottom: 25px;
            padding-left: 15px;
            background-color: #f9fdf9;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
            transition: box-shadow 0.3s ease;
        }
        .dieta-card:hover {
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .select-animal {
            margin-bottom: 40px;
        }
        .animal-info {
            background: linear-gradient(135deg, #28a745 0%, #19692c 100%);
            color: white;
            border-radius: 15px;
            padding: 30px 25px;
            margin-bottom: 40px;
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
            transition: box-shadow 0.3s ease;
        }
        .animal-info:hover {
            box-shadow: 0 12px 30px rgba(40, 167, 69, 0.6);
        }
        .animal-info h3 {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .animal-info p {
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <?php renderNotifications(); ?>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row py-3 px-lg-5">
            <div class="col-lg-4">
                <a href="animals.php" class="navbar-brand d-none d-lg-block">
                    <h1 class="m-0 display-5 text-capitalize"><span class="text-primary">Pet</span>Care</h1>
                </a>
            </div>
            <div class="col-lg-8 text-center text-lg-right">
                <img width="20%" src="img/02 Cabecalho-principal-ESVV-24-25.png" alt="Image">
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="profile-content d-flex">
                <main class="main-content flex-grow-1">
                    <div class="mb-5">
                        <h1 class="mb-4">Alimentação dos Animais</h1>
                        
                        <!-- Select Box para escolher animal -->
                        <div class="select-animal">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Selecione um Animal</h5>
                                        <form method="GET" action="alimentaçao.php">
                                            <div class="form-group">
                                                <select name="animal_id" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- Escolha um animal --</option>
                                                <?php if ($animais): ?>
                                                <?php foreach ($animais as $animal): ?>
                                                <option value="<?php echo $animal['codanimal']; ?>" 
                                                <?php echo (isset($_GET['animal_id']) && $_GET['animal_id'] == $animal['codanimal']) ? 'selected' : ''; ?> >
                                                <?php echo htmlspecialchars($animal['nome']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php if ($animal_selecionado): ?>
                            <!-- Informações do Animal -->
                            <div class="animal-info">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <?php if ($animal_selecionado['c_tipo'] == 1): ?>
                                            <i class="flaticon-dog fa-3x"></i>
                                        <?php else: ?>
                                            <i class="flaticon-cat fa-3x"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-10">
                                        <h3><?php echo htmlspecialchars($animal_selecionado['nome']); ?></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- <p class="mb-1"><strong>Espécie:</strong> <?php echo htmlspecialchars($animal_selecionado['espécie']); ?></p> -->
                                                <p class="mb-1"><strong>Raça:</strong> <?php echo htmlspecialchars($animal_selecionado['raca']); ?></p>
                                                <p class="mb-1"><strong>Castrado:</strong> <?php echo ($animal_selecionado['castrado'] == 't' || $animal_selecionado['castrado'] === true || $animal_selecionado['castrado'] == '1') ? 'Sim' : 'Não'; ?></p>
                                            </div>
<div class="col-md-6">
    <p class="mb-1"><strong>Idade:</strong> <?php echo htmlspecialchars($idade); ?></p>
    <p class="mb-1"><strong>Peso:</strong> 
        <?php 
            if (is_null($animal_selecionado['Peso']) || $animal_selecionado['Peso'] === '') {
                echo "Ainda não inseriu o peso, abra a pagína do seu animal e depois vá para 'Editar dados' e depois adicione o peso.";
            } else {
                echo htmlspecialchars($animal_selecionado['Peso']) . " kg";
            }
        ?>
    </p>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($racao_necessaria)): ?>
                            <div class="mb-5">
                                <h3 class="mb-4">Recomendação de Alimentação</h3>
                                <p><?php echo htmlspecialchars($racao_necessaria); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- Dietas do Animal -->
                        <?php if (!empty($racao_necessaria)): ?>
                        <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$animal_selecionado && $animais && count($animais) == 0): ?>
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                <h5>Nenhum animal registrado</h5>
                                <p>Você ainda não tem animais registrados no sistema.</p>
                                <a href="registeranimal.php" class="btn btn-primary-custom">Registrar Animal</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </main>

                <aside class="sidebar">
                    <div class="mb-5">
                        <div class="bg-primary text-center" style="padding: 30px;">
                            <h4 class="text-white">Dicas de Alimentação</h4>
                        </div>
                        <div class="bg-secondary text-center" style="padding: 30px;">
                            <h5 class="text-white mb-3">Cuidados Importantes</h5>
                            <ul class="list-unstyled text-white text-left">
                                <li class="mb-2"><i class="fas fa-check text-primary mr-2"></i>Mantenha horários regulares</li>
                                <li class="mb-2"><i class="fas fa-check text-primary mr-2"></i>Água sempre disponível</li>
                                <li class="mb-2"><i class="fas fa-check text-primary mr-2"></i>Ração adequada à idade</li>
                                <li class="mb-2"><i class="fas fa-check text-primary mr-2"></i>Evite comida humana</li>
                                <li class="mb-2"><i class="fas fa-check text-primary mr-2"></i>Consulte o veterinário</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <!-- Content End -->

      <button class="menu-toggle-right" id="menuToggleRight">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Menu Lateral Direito -->
    <nav class="sidebar-right" id="sidebarRight">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-paw"></i> Menu Animais PetCare</h4>
        </div>
        
        <a href="animals.php" ><i class="fas fa-dog"></i> Meus Animais</a>
        <a href="registeranimal.php"><i class="fas fa-calendar-check"></i> Registrar Animal</a>
        <a href="alimentaçao.php" class="active"><i class="fas fa-file-medical"></i> Ver dietas</a>
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Sistema de Notificações JS -->
<script src="js/notifications.js"></script>


    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        // Smooth scroll para o topo
        document.addEventListener('DOMContentLoaded', function() {
            const backToTop = document.querySelector('.back-to-top');
            
            if (backToTop) {
                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Mostrar/esconder botão back to top baseado no scroll
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 100) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            });
        });

        // Auto-submit do formulário quando selecionar animal
        function changeAnimal() {
            document.getElementById('animalForm').submit();
        }
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
    <?php if (!empty($racao_necessaria)): ?>
    <?php endif; ?>
</body>
<?php include('php/footer.php'); ?>
</html>

