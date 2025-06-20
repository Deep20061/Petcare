<!DOCTYPE html>
<html lang="pt">
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style> 
        body {
            font-family: 'Nunito', sans-serif;
            background-color:rgb(236, 236, 236);
        }
        
        /* Estilos melhorados para notificações */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }
        
        .alert-success-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 12px;
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            border-left: 5px solid #ffffff;
            animation: slideInRight 0.5s ease-out;
        }
        
        .alert-error-custom {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            border: none;
            border-radius: 12px;
            color: white;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
            border-left: 5px solid #ffffff;
            animation: slideInRight 0.5s ease-out;
        }
        
        .alert-icon {
            font-size: 1.5rem;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .alert-content {
            display: flex;
            align-items: center;
        }
        
        .alert-text {
            flex: 1;
        }
        
        .alert-title {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .alert-message {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .btn-close-custom {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            opacity: 0.8;
            margin-left: 15px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        
        .btn-close-custom:hover {
            opacity: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .alert-fade-out {
            animation: fadeOut 0.5s ease-in forwards;
        }
    </style>
</head>

<body>
    <?php
    include('php/server.php');
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }
    if (!isset($_GET['id'])) {
        exit();
    }
    $animal_id = $_GET['id'];

    // Variáveis para controlar notificações
    $show_success = false;
    $show_error = false;
    $error_message = "";
    
    // Primeiro, obter os dados do animal
$sql = "SELECT nome, c_raca, nascimento, castrado::boolean, img as imagem, \"Peso\" FROM animais WHERE codanimal = $animal_id";
$result = pg_query($conn, $sql);
$animal = pg_fetch_array($result);

// Convert castrado boolean to 'Sim' or 'Não' for form select
$castrado_text = ($animal['castrado'] === true || $animal['castrado'] === 't' || $animal['castrado'] === 'true' || $animal['castrado'] === 1) ? 'Sim' : 'Não';
    
    // Depois, obter o tipo da raça em uma consulta separada
    $sql_tipo = "SELECT c_tipo, nome, c_raca  FROM racas WHERE c_raca = '" . $animal['c_raca'] . "'";
    $result_tipo = pg_query($conn, $sql_tipo);
    $row_tipo = pg_fetch_assoc($result_tipo);
    $tipo_id = $row_tipo['c_tipo'];

    $sql_tipo = "SELECT c_tipo, nome, c_raca  FROM racas WHERE c_tipo = $tipo_id";
    $resultado_tipo = pg_query($conn, $sql_tipo);
   
    // Determinar o tipo de animal baseado no c_tipo
    $tipo_nome = "";
    $tipo_texto = "";
    
    // Fetch the tipo string from tipo table
    $sql_tipo_nome = "SELECT tipo FROM tipo WHERE c_tipo = $tipo_id";
    $result_tipo_nome = pg_query($conn, $sql_tipo_nome);
    if ($result_tipo_nome && pg_num_rows($result_tipo_nome) > 0) {
        $row_tipo_nome = pg_fetch_assoc($result_tipo_nome);
        $tipo_texto = $row_tipo_nome['tipo'];
    }
    
    // Map tipo_texto to tipo_nome for select option values (lowercase)
    switch(strtolower($tipo_texto)) {
        case 'cão': $tipo_nome = "cao"; break;
        case 'gato': $tipo_nome = "gato"; break;
        case 'peixe': $tipo_nome = "peixe"; break;
        case 'ave': $tipo_nome = "ave"; break;
        default: $tipo_nome = "outro"; break;
    }

    // Processar AJAX apenas se for uma requisição AJAX
    if (isset($_POST['tipo-animal']) && isset($_POST['is-ajax'])) {
        header('Content-Type: text/html');
        $opcao = $_POST['tipo-animal'];
        
        // Consulta ao banco de dados para obter raças
        $tipo = 0;
        switch($opcao) {
            case 'cao': $tipo = 1; break;
            case 'gato': $tipo = 2; break;
            case 'peixe': $tipo = 3; break;
            case 'ave': $tipo = 4; break;
            case 'outro': $tipo = 0; break;
            default: $tipo = 0; break;
        }
        
        if ($tipo > 0) {
            $sql = "SELECT nome FROM racas WHERE c_tipo = $tipo";
            $resultado = pg_query($conn, $sql);
            
            if ($resultado && pg_num_rows($resultado) > 0) {
                echo "<option value=''>-- Escolha --</option>";
                while ($row = pg_fetch_assoc($resultado)) {

                    $selected = ($row['nome'] == $animal['c_raca']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row['nome']) . '" ' . $selected . '>' . htmlspecialchars($row['nome']) . '</option>';
                }
            } else {
                echo '<option value="">-- Nenhuma raça encontrada --</option>';
            }
        } else {
            echo '<option value="">-- Nenhuma raça disponível --</option>';
        }
        exit;
    }

    // Processar o envio do formulário para salvar os dados atualizados
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['is-ajax'])) {
        // Validar e sanitizar os dados recebidos
        $nome = pg_escape_string($conn, $_POST['nome']);
        $tipo_animal = $_POST['tipo-animal'];
        $raca_nome = pg_escape_string($conn, $_POST['raça-animal']);
        $nascimento = $_POST['nascimento'];
        $castrado = $_POST['castrado'] === 'Sim' ? 'TRUE' : 'FALSE';
        $peso = floatval($_POST['peso']);

        // Validações básicas
        if (empty($nome) || empty($raca_nome) || empty($nascimento) || $peso <= 0) {
            $show_error = true;
            $error_message = "Por favor, preencha todos os campos obrigatórios corretamente.";
        } else {
            // Obter o c_raca correspondente ao nome da raça
            $sql_raca = "SELECT c_raca FROM racas WHERE nome = '$raca_nome'";
            $result_raca = pg_query($conn, $sql_raca);
            if ($result_raca && pg_num_rows($result_raca) > 0) {
                $row_raca = pg_fetch_assoc($result_raca);
                $c_raca = $row_raca['c_raca'];
            } else {
                $c_raca = null;
                $show_error = true;
                $error_message = "Raça selecionada não é válida.";
            }

            if (!$show_error) {
                // Inicializar variável para imagem
                $img_id = null;

                // Verificar se um novo arquivo de imagem foi enviado
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $imagem_tmp = $_FILES['imagem']['tmp_name'];
                    $imagem_nome = pg_escape_string($conn, $_FILES['imagem']['name']);
                    $imagem_tipo = pg_escape_string($conn, $_FILES['imagem']['type']);
                    
                    // Validar tipo de arquivo
                    $tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!in_array($imagem_tipo, $tipos_permitidos)) {
                        $show_error = true;
                        $error_message = "Tipo de arquivo não permitido. Use apenas JPEG, PNG ou GIF.";
                    } else {
                        $imagem_dados = pg_escape_bytea($conn, file_get_contents($imagem_tmp));

                        // Inserir a imagem na tabela imagens
                        $sql_img = "INSERT INTO imagens (nome, tipo, dados, data_upload) VALUES ('$imagem_nome', '$imagem_tipo', '$imagem_dados', NOW()) RETURNING id";
                        $result_img = pg_query($conn, $sql_img);
                        if ($result_img && pg_num_rows($result_img) > 0) {
                            $row_img = pg_fetch_assoc($result_img);
                            $img_id = $row_img['id'];
                        } else {
                            $show_error = true;
                            $error_message = "Erro ao fazer upload da imagem.";
                        }
                    }
                }

                if (!$show_error) {
                    // Construir a query de atualização
                    $sql_update = "UPDATE animais SET nome = '$nome', c_raca = ";
                    $sql_update .= $c_raca !== null ? $c_raca : 'NULL';
                    $sql_update .= ", nascimento = '$nascimento', castrado = $castrado, \"Peso\" = $peso";

                    if ($img_id !== null) {
                        $sql_update .= ", img = $img_id";
                    }

                    $sql_update .= " WHERE codanimal = $animal_id";

                    // Executar a query de atualização
                    $result_update = pg_query($conn, $sql_update);

                    if ($result_update) {
                        // Redirecionar para a mesma página para evitar reenvio do formulário
                        header("Location: editardados.php?id=$animal_id&success=1");
                        exit();
                    } else {
                        $show_error = true;
                        $error_message = "Erro interno do servidor. Tente novamente mais tarde.";
                    }
                }
            }
        }
    }

    // Verificar se deve mostrar notificação de sucesso
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        $show_success = true;
    }
    ?>
    
    <!-- Container de Notificações -->
    <div class="notification-container">
        <?php if ($show_success): ?>
        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
            <div class="alert-content">
                <i class="fas fa-check-circle alert-icon"></i>
                <div class="alert-text">
                    <div class="alert-title">Sucesso!</div>
                    <div class="alert-message">Os dados do animal foram atualizados com sucesso.</div>
                </div>
                <button type="button" class="btn-close-custom" onclick="closeAlert(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($show_error): ?>
        <div class="alert alert-error-custom alert-dismissible fade show" role="alert">
            <div class="alert-content">
                <i class="fas fa-exclamation-triangle alert-icon"></i>
                <div class="alert-text">
                    <div class="alert-title">Erro!</div>
                    <div class="alert-message"><?php echo htmlspecialchars($error_message); ?></div>
                </div>
                <button type="button" class="btn-close-custom" onclick="closeAlert(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
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
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <main class="main-content">
            <div class="container-fluid">
                <h2>Editar Dados do Animal</h2>
                <form action="editardados.php?id=<?php echo $animal_id; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="animal_id" value="<?php echo $animal_id; ?>">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($animal['nome']); ?>" required> 
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="tipo-animal" class="form-label">Tipo de Animal</label>
                                <select class="form-control" id="tipo-animal" name="tipo-animal" required disabled >
                                <option value="cao"   <?php if ($tipo_nome == 'cao')   echo 'selected'; ?>> Cão</option>
                                <option value="gato"  <?php if ($tipo_nome == 'gato')  echo 'selected'; ?>> Gato</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="raça-animal" class="form-label">Raça</label>
                                <select class="form-control" id="raça-animal" name="raça-animal" required >
                                    <?php
                                    while ($raça = pg_fetch_array($resultado_tipo)) {
                                        echo '<option value="' . htmlspecialchars($raça['nome']) . '"';
                                        if ($raça['c_raca'] == $animal['c_raca']) {
                                            echo ' selected';
                                        }
                                        echo '>' . htmlspecialchars($raça['nome']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nascimento">Data de Nascimento:</label>
                        <input type="date" class="form-control" id="nascimento" name="nascimento" value="<?php echo $animal['nascimento']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="castrado">Castrado:</label>
                        <select class="form-control" id="castrado" name="castrado" required>
                        <option value="Sim" <?php if ($castrado_text == 'Sim') echo 'selected'; ?>>Sim</option>
                        <option value="Não" <?php if ($castrado_text == 'Não') echo 'selected'; ?>>Não</option>
                        </select> 
                    </div>
                    <div class="form-group">
                        <label for="peso">Peso (kg):</label>
                        <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="<?php echo htmlspecialchars($animal['Peso']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="imagem">Imagem:</label>
                        <input type="file" class="form-control-file" id="imagem" name="imagem" accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small class="form-text text-muted">Formatos aceitos: JPEG, PNG, GIF (máx. 5MB)</small>
                        <?php if (!empty($animal['img'])): ?>
                            <?php  
                            $consulta_imagem = "SELECT id FROM imagens WHERE id = ".$animal['imagem']." LIMIT 1";
                            $resultado_imagem = pg_query($conn, $consulta_imagem);
                            $imagem = pg_fetch_array($resultado_imagem);
                            $tem_imagem = (pg_num_rows($resultado_imagem) > 0);
                            ?>
                        <div class="mt-2">
                            <p>Imagem atual:</p>
                            <img src="<?php echo $tem_imagem ? 'php/exibir_imagem.php?id='.$imagem['id'] : 'img/team-1.jpg' ?>" alt="Imagem do animal" style="max-width: 200px; max-height: 200px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Salvar</button>
                        <a class="btn btn-secondary" <?php echo "href='coiso.php?id=$animal_id'" ?> > <i class="fas fa-arrow-left"></i>Voltar</a>
                    </div>
                </form>
            </div>
        </main>  
    </div>
 <?php include('php/footer.php'); ?>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Função para fechar alertas
        function closeAlert(button) {
            const alert = button.closest('.alert');
            alert.classList.add('alert-fade-out');
            setTimeout(() => {
                alert.remove();
            }, 500);
        }

        // Auto-fechar notificações após 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.classList.add('alert-fade-out');
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }
                }, 5000);
            });
        });

        // AJAX para carregar raças
        $(document).ready(function() {
            $('#tipo-animal').change(function() {
                var tipoAnimal = $(this).val();
                if (tipoAnimal) {
                    $.ajax({
                        type: 'POST',
                        url: 'editardados.php',
                        data: { 'tipo-animal': tipoAnimal, 'is-ajax': true },
                        success: function(response) {
                            $('#raça-animal').html(response);
                        },
                        error: function() {
                            $('#raça-animal').html('<option value="">Erro ao carregar raças</option>');
                        }
                    });
                } else {
                    $('#raça-animal').html('<option value="">-- Escolha a raça --</option>');
                }
            });
        });

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

        // Validação de arquivo de imagem
        document.getElementById('imagem').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!allowedTypes.includes(file.type)) {
                    alert('Tipo de arquivo não permitido. Use apenas JPEG, PNG ou GIF.');
                    e.target.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('Arquivo muito grande. O tamanho máximo é 5MB.');
                    e.target.value = '';
                    return;
                }
            }
        });
    </script>
</body>
</html>
