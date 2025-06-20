<!DOCTYPE html>
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
     <style> 
        body {
            font-family: 'Nunito', sans-serif;
            background-color:rgb(236, 236, 236);
        }
        .alimentar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .alimentar-table th,
        .alimentar-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .alimentar-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .alimentar-table tr:hover {
            background-color: #f5f5f5;
        }
        .status-gosta {
            color: #28a745;
            font-weight: bold;
        }
        .status-nao-gosta {
            color: #dc3545;
            font-weight: bold;
        }
        .status-neutro {
            color: #6c757d;
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

<?php 
include('php/server.php');
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$animal_id = $_GET['id'];
$sql = "SELECT nome, c_raca, nascimento, castrado, img FROM animais WHERE codanimal = $animal_id";
$result = pg_query($conn, $sql);
$animal = pg_fetch_array($result);

if (!$animal) {
    echo '<div style="display:flex; justify-content:center; align-items:center; height:80vh; font-family: \'Nunito\', sans-serif; font-size: 1.5rem; text-align:center; flex-direction: column;">';
    echo 'Não há animais registrados.<br>';
    echo '<a href="registeranimal.php" style="margin-top: 1rem; font-size: 1.2rem; color: #007bff; text-decoration: underline;">Deseja inserir um?</a>';
    echo '</div>';
    exit();
}

$animal_id = intval($animal_id);

// Get animal race
$sql_raca = "SELECT c_raca FROM animais WHERE codanimal = $animal_id";
$result_raca = pg_query($conn, $sql_raca);
$row_raca = pg_fetch_array($result_raca);
$c_raca = $row_raca['c_raca'];

// Get animal type from race
$sql_tipo = "SELECT c_tipo FROM racas WHERE c_raca = $c_raca";
$result_tipo = pg_query($conn, $sql_tipo);
$row_tipo = pg_fetch_array($result_tipo);
$c_tipo = $row_tipo['c_tipo'];

// Query vaccines taken by animal filtered by vaccine type
$sql2 = "SELECT vacinasfeitas.doses as doses, datavacina, vacinas.nome AS nome
        FROM vacinasfeitas
        JOIN vacinas ON vacinas.c_vacina = vacinasfeitas.c_vacina
        WHERE vacinasfeitas.c_animal = $animal_id
        ORDER BY vacinas.nome;";
$result2 = pg_query($conn, $sql2);
if (!$result2) {
    echo "Erro na consulta de vacinas: " . pg_last_error($conn);
}
// Removed premature fetch to avoid skipping first vaccine row
// $animal2 = pg_fetch_array($result2);

// CÁLCULO DA IDADE CORRIGIDO
$nascimento = new DateTime($animal['nascimento']);
$hoje = new DateTime();

if ($nascimento > $hoje) {
    $idade = "Data de nascimento inválida";
} else {
    $intervalo = $hoje->diff($nascimento);
    $idadey = $intervalo->y;
    $idadem = $intervalo->m;
    
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
}

// Restante das consultas...
$sql2 = "SELECT nome, c_tipo, definicao FROM racas WHERE c_raca = $animal[c_raca]";
$result2 = pg_query($conn, $sql2);
$animal_raca = pg_fetch_array($result2);

$sql3 = "SELECT tipo as nome FROM tipo WHERE c_tipo = $animal_raca[c_tipo]";
$result3 = pg_query($conn, $sql3);
$animal_tipo = pg_fetch_array($result3);

if (!isset($animal_id) || !is_numeric($animal_id)) {
    echo "<p>ID do animal inválido.</p>";
    exit();
}

$sql_alimentar = "SELECT a.n_alimento as alimento, ga.gosta 
                  FROM preferencias_alimentares ga 
                  JOIN alimentos a ON ga.id_alimento = a.c_alimento 
                  WHERE ga.id_animal = $animal_id 
                  ORDER BY a.n_alimento";
$result_alimentar = pg_query($conn, $sql_alimentar);

$animal = [
    'id' => $_GET['id'],
    'nome' => $animal['nome'],
    'especie' => $animal_tipo['nome'],
    'raca' => $animal_raca['nome'],
    'sobre' => $animal_raca['definicao'],
    'castrado' => $animal['castrado'],
    'imagem' => $animal['img'],
    // Removed 'vacinas' to avoid undefined variable warning
    //'vacinas'=> $animal2,
];
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <header class="profile-header">
        <?php  
        $consulta_imagem = "SELECT id FROM imagens WHERE id = ".$animal['imagem']." LIMIT 1";
        $resultado_imagem = pg_query($conn, $consulta_imagem);
        $imagem = pg_fetch_array($resultado_imagem);
        $tem_imagem = (pg_num_rows($resultado_imagem) > 0);
        ?>
        <div class="cover-photo">
            <img src="<?php echo $tem_imagem ? 'php/exibir_imagem.php?id='.$imagem['id'] : 'img/team-1.jpg' ?>" class="profile-photo">
        </div>
        
        <div class="profile-inf">
            <h1><?php echo htmlspecialchars($animal['nome']); ?></h1>
            <p><?php echo htmlspecialchars($animal['especie']); ?> - <?php echo htmlspecialchars($animal['raca']); ?></p>
            <p>Idade: <?php echo $idade; ?></p>
            <?php
            // Calculate animal age in animal years using a simple multiplier
            $animal_age_years = $idadey + ($idadem / 12);
            $multiplier = 1; // default multiplier

            // Define multipliers for different animal types
            $animal_type_lower = strtolower($animal['especie']);
            if (strpos($animal_type_lower, 'cão') !== false || strpos($animal_type_lower, 'dog') !== false) {
                $multiplier = 7;
            } elseif (strpos($animal_type_lower, 'gato') !== false || strpos($animal_type_lower, 'cat') !== false) {
                $multiplier = 6; // example multiplier for cats
            } else {
                $multiplier = 1; // default multiplier for other animals
            }

            $animal_age = round($animal_age_years * $multiplier, 1);
            ?>
            <p>Idade Animal: <?php echo $animal_age . ' anos'; ?></p>
            <a href="<?php echo ("editardados.php?id=$animal_id");?>">Editar Dados</a>
        </div>
    </header>
    
    <div class="profile-content">
        <main class="main-content">
            <section class="about-section">
                <h2 class="section-title">Sobre a Raça</h2>
                <p><?php echo htmlspecialchars($animal['sobre']); ?></p>
            </section>
            <section class="about-section">
                <h2 class="section-title">Gostos Alimentares do seu Animal</h2>
                <?php if ($result_alimentar && pg_num_rows($result_alimentar) > 0): ?>
                    <table class="alimentar-table">
                        <thead>
                            <tr>
                                <th>Alimento</th>
                                <th>Preferência</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($gosto = pg_fetch_array($result_alimentar)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($gosto['alimento']); ?></td>
                                    <td>
                                        <?php 
                                        $gosta = $gosto['gosta'];
                                        $classe_status = '';
                                        $texto_preferencia = '';
                                        
                                        if ($gosta === 't' || $gosta === true || $gosta === '1') {
                                            $classe_status = 'status-gosta';
                                            $texto_preferencia = 'Gosta';
                                        } else {
                                            $classe_status = 'status-nao-gosta';
                                            $texto_preferencia = 'Não Gosta';
                                        }
                                        ?>
                                        <span class="<?php echo $classe_status; ?>">
                                            <?php echo $texto_preferencia; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhum gosto alimentar registrado ainda.</p>
                <?php endif; ?>
                <p style="margin-top: 15px;">
                    <a href="<?php echo ("editargalimentar.php?id=$animal_id");?>">Editar Gostos Alimentares</a>
                </p>
            </section>
        </main>
        
        <aside class="sidebar">
            <section class="sidebar-section">
                <h2 class="section-title">Sobre</h2>
                <p>Castrado: <?php echo ($animal['castrado'] == 't' || $animal['castrado'] === true || $animal['castrado'] == '1') ? 'Sim' : 'Não'; ?></p>

            </section>
            
            <section class="sidebar-section">
                <h2 class="section-title">Vacinas</h2>

                <ul class="list-unstyled">
                    <?php
if ($result2 && pg_num_rows($result2) > 0) {
                        while ($vacina = pg_fetch_array($result2)) {
                            echo "<li>" . htmlspecialchars($vacina['nome']) . " - Doses: " . htmlspecialchars($vacina['doses']) . " - Data: " . htmlspecialchars($vacina['datavacina']) . "</li>";
                        }
                    } else {
                        echo "<li>Nenhuma vacina registrada.</li>";
                    }
                    ?>
                </ul>
                <a href="<?php echo ("vacinas.php?id=$animal_id");?>">Editar Vacinas</a>
            </section>
            
        </aside>
    </div>
</div>
   <!-- Menu Lateral Direito -->
   <button id="menuToggleRight" class="btn btn-primary" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
       <i class="fas fa-bars"></i>
   </button>
    <nav class="sidebar-right" id="sidebarRight">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-paw"></i> Menu Animais PetCare</h4>
        </div>
        
        <a href="animals.php" class="active"><i class="fas fa-dog"></i> Meus Animais</a>
        <a href="registeranimal.php"><i class="fas fa-calendar-check"></i> Registrar Animal</a>
        <a href="#"><i class="fas fa-file-medical"></i> Ver dietas</a>
        <a href="editarutilizador.php"><i class="fas fa-cog"></i> Definições</a>
        
        <div style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
            <a href="logout.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Fechar Sessão
            </a>
        </div>
    </nav> 

<!-- Footer -->
<?php include('php/footer.php'); ?>
</body>
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
</html>
