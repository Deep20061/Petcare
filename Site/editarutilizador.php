<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Pet Care - Editar Dados do Utilizador</title>

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
    </style>
</head>

<body>
<?php
ob_start();
include('php/server.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";

// Fetch user data
$sql = "SELECT nome, email FROM utilizadores WHERE códigoutilizador = $user_id";
$result = pg_query($conn, $sql);
if (!$result || pg_num_rows($result) == 0) {
    echo "Utilizador não encontrado.";
    exit();
}
$user = pg_fetch_assoc($result);

// Handle form submission for user data update
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_animal'])) {
    if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
        // Handle AJAX request for user update
        header('Content-Type: application/json');
        $nome = isset($_POST['nome']) ? pg_escape_string($conn, $_POST['nome']) : '';
        $email = isset($_POST['email']) ? pg_escape_string($conn, $_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

        if ($password !== $password_confirm) {
            echo json_encode(['success' => false, 'message' => 'As palavras-passe não coincidem.']);
            exit();
        } else {
            if (!empty($password)) {
                // Update with password
                $sql_update = "UPDATE utilizadores SET nome = '$nome', email = '$email', passeword = '$password' WHERE códigoutilizador = $user_id";
            } else {
                // Update without password
                $sql_update = "UPDATE utilizadores SET nome = '$nome', email = '$email' WHERE códigoutilizador = $user_id";
            }
            $update_result = pg_query($conn, $sql_update);
            if ($update_result) {
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso.', 'updatedUser' => ['nome' => $nome, 'email' => $email]]);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar os dados.']);
                exit();
            }
        }
    } else {
        // Non-AJAX fallback (existing code)
        $nome = isset($_POST['nome']) ? pg_escape_string($conn, $_POST['nome']) : '';
        $email = isset($_POST['email']) ? pg_escape_string($conn, $_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

        if ($password !== $password_confirm) {
            $message = "As palavras-passe não coincidem.";
        } else {
            if (!empty($password)) {
                // Update with password
                $sql_update = "UPDATE utilizadores SET nome = '$nome', email = '$email', passeword = '$password' WHERE códigoutilizador = $user_id";
            } else {
                // Update without password
                $sql_update = "UPDATE utilizadores SET nome = '$nome', email = '$email' WHERE códigoutilizador = $user_id";
            }
            $update_result = pg_query($conn, $sql_update);
            if ($update_result) {
                $message = "Dados atualizados com sucesso.";
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
            } else {
                $message = "Erro ao atualizar os dados.";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_animal'])) {
    if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
        header('Content-Type: application/json');
        $animal_id = intval($_POST['animal_id']);
        $delete_reason = isset($_POST['delete_reason']) ? pg_escape_string($conn, $_POST['delete_reason']) : '';

        // Fetch animal data for insertion into animais_eliminados
        $sql_animal_data = "SELECT codanimal, nome, pertence, c_raca, nascimento FROM animais WHERE codanimal = $animal_id";
        $result_animal_data = pg_query($conn, $sql_animal_data);
        $animal_data = null;
        if ($result_animal_data && pg_num_rows($result_animal_data) > 0) {
            $animal_data = pg_fetch_assoc($result_animal_data);
        }

        if ($animal_data) {
            // Insert into animais_eliminados with reason
            $codanimal = $animal_data['codanimal'];
            $nome = pg_escape_string($conn, $animal_data['nome']);
            $pertence = intval($animal_data['pertence']);
            $c_raca = pg_escape_string($conn, $animal_data['c_raca']);
            $nascimento = $animal_data['nascimento']; // Assuming date format is compatible

            $insert_eliminado = "INSERT INTO animais_eliminados (codanimal, nome, pertence, c_raca, nascimento, causa_eli) VALUES ($codanimal, '$nome', $pertence, '$c_raca', '$nascimento', '$delete_reason')";
            pg_query($conn, $insert_eliminado);
        }

        // Delete vaccines related to the animal
        $delete_vacinas = "DELETE FROM vacinasfeitas WHERE c_animal = $animal_id";
        pg_query($conn, $delete_vacinas);

        // Get animal image path
        $sql_img = "SELECT img FROM animais WHERE codanimal = $animal_id";
        $result_img = pg_query($conn, $sql_img);
        $animal_img = "";
        if ($result_img && pg_num_rows($result_img) > 0) {
            $row_img = pg_fetch_assoc($result_img);
            $animal_img = $row_img['img'];
        }

        // Delete the animal record
        $delete_animal = "DELETE FROM animais WHERE codanimal = $animal_id";
        $result_delete = pg_query($conn, $delete_animal);

        // Delete the image file if exists
        if (!empty($animal_img) && file_exists($animal_img)) {
            unlink($animal_img);
        }

        if ($result_delete) {
            echo json_encode(['success' => true, 'message' => 'Animal eliminado com sucesso.', 'deletedAnimalId' => $animal_id]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao eliminar o animal.']);
            exit();
        }
    } else {
        // Non-AJAX fallback (existing code)
        $animal_id = intval($_POST['animal_id']);
        $delete_reason = isset($_POST['delete_reason']) ? pg_escape_string($conn, $_POST['delete_reason']) : '';

        // Fetch animal data for insertion into animais_eliminados
        $sql_animal_data = "SELECT codanimal, nome, pertence, c_raca, nascimento FROM animais WHERE codanimal = $animal_id";
        $result_animal_data = pg_query($conn, $sql_animal_data);
        $animal_data = null;
        if ($result_animal_data && pg_num_rows($result_animal_data) > 0) {
            $animal_data = pg_fetch_assoc($result_animal_data);
        }

        if ($animal_data) {
            // Insert into animais_eliminados with reason
            $codanimal = $animal_data['codanimal'];
            $nome = pg_escape_string($conn, $animal_data['nome']);
            $pertence = intval($animal_data['pertence']);
            $c_raca = pg_escape_string($conn, $animal_data['c_raca']);
            $nascimento = $animal_data['nascimento']; // Assuming date format is compatible

            $insert_eliminado = "INSERT INTO animais_eliminados (codanimal, nome, pertence, c_raca, nascimento, causa_eli) VALUES ($codanimal, '$nome', $pertence, '$c_raca', '$nascimento', '$delete_reason')";
            pg_query($conn, $insert_eliminado);
        }

        // Delete vaccines related to the animal
        $delete_vacinas = "DELETE FROM vacinasfeitas WHERE c_animal = $animal_id";
        pg_query($conn, $delete_vacinas);

        // Get animal image path
        $sql_img = "SELECT img FROM animais WHERE codanimal = $animal_id";
        $result_img = pg_query($conn, $sql_img);
        $animal_img = "";
        if ($result_img && pg_num_rows($result_img) > 0) {
            $row_img = pg_fetch_assoc($result_img);
            $animal_img = $row_img['img'];
        }

        // Delete the animal record
        $delete_animal = "DELETE FROM animais WHERE codanimal = $animal_id";
        $result_delete = pg_query($conn, $delete_animal);

        // Delete the image file if exists
        if (!empty($animal_img) && file_exists($animal_img)) {
            unlink($animal_img);
        }

        if ($result_delete) {
            $message = "Animal eliminado com sucesso.";
        } else {
            $message = "Erro ao eliminar o animal.";
        }
    }
}

// Fetch user's animals with additional fields for deletion reason
$sql_animals = "SELECT codanimal, nome, pertence, c_raca, nascimento FROM animais WHERE pertence = $user_id";
$result_animals = pg_query($conn, $sql_animals);
$animals = [];
if ($result_animals && pg_num_rows($result_animals) > 0) {
    while ($row = pg_fetch_assoc($result_animals)) {
        $animals[] = $row;
    }
}
?>

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

<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <main class="main-content">
        <div class="container-fluid">
            <h2>Editar Dados do Utilizador</h2>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
<form id="userUpdateForm" class="needs-validation" novalidate>
    <div class="form-group mb-3">
        <label for="nome">Nome:</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
        <div class="invalid-feedback">Por favor, insira o seu nome.</div>
    </div>
    <div class="form-group mb-3">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <div class="invalid-feedback">Por favor, insira um email válido.</div>
    </div>
    <div class="form-group mb-3">
        <label for="password">Nova Palavra-Passe (deixe em branco para manter a atual):</label>
        <input type="password" class="form-control" id="password" name="password" minlength="6">
        <div class="invalid-feedback">A palavra-passe deve ter pelo menos 6 caracteres.</div>
    </div>
    <div class="form-group mb-3">
        <label for="password_confirm">Confirmar Nova Palavra-Passe:</label>
        <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6">
        <div class="invalid-feedback">As palavras-passe devem coincidir.</div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<div id="updateMessage" class="mt-3"></div>

            <hr>

            <h3>Animais do Utilizador</h3>
            <?php if (count($animals) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($animals as $animal): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($animal['nome']); ?>
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar-animal" data-animal='<?php echo json_encode($animal); ?>'>Eliminar</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form id="deleteAnimalForm" method="POST" style="display:none;">
                    <input type="hidden" name="animal_id" id="animal_id" value="">
                    <input type="hidden" name="delete_reason" id="delete_reason" value="">
                    <input type="hidden" name="delete_animal" value="1">
                </form>
            <?php else: ?>
                <p>Não há animais registados.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<hr>

<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h3>Eliminar Conta de Utilizador</h3>
    <form method="POST" onsubmit="return confirm('Tem certeza que deseja eliminar a sua conta? Esta ação não pode ser desfeita. Todos os seus animais e dados serão removidos.');">
        <input type="hidden" name="delete_user" value="1">
        <button type="submit" class="btn btn-danger">Eliminar Conta</button>
    </form>
</div>
 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    echo "<div>Delete user block entered</div>";
    // Begin transaction
    pg_query($conn, "BEGIN");

    // Fetch all animal images to delete files
    $sql_imgs = "SELECT img FROM animais WHERE pertence = $user_id";
    $result_imgs = pg_query($conn, $sql_imgs);
    if (!$result_imgs) {
        echo "<div>Error fetching animal images: " . htmlspecialchars(pg_last_error($conn)) . "</div>";
    }
    $images_to_delete = [];
    if ($result_imgs && pg_num_rows($result_imgs) > 0) {
        while ($row_img = pg_fetch_assoc($result_imgs)) {
            if (!empty($row_img['img'])) {
                $images_to_delete[] = $row_img['img'];
            }
        }
    }

    // Delete vaccines related to the user's animals
    $delete_vacinas = "DELETE FROM vacinasfeitas WHERE c_animal IN (SELECT codanimal FROM animais WHERE pertence = $user_id)";
    $res_vacinas = pg_query($conn, $delete_vacinas);
    if (!$res_vacinas) {
        echo "<div>Error deleting vaccines: " . htmlspecialchars(pg_last_error($conn)) . "</div>";
    }

    // Delete animals
    $delete_animais = "DELETE FROM animais WHERE pertence = $user_id";
    $res_animais = pg_query($conn, $delete_animais);
    if (!$res_animais) {
        echo "<div>Error deleting animals: " . htmlspecialchars(pg_last_error($conn)) . "</div>";
    }

    // Delete user
    $delete_user = "DELETE FROM utilizadores WHERE códigoutilizador = $user_id";
    $res_user = pg_query($conn, $delete_user);
    if (!$res_user) {
        echo "<div>Error deleting user: " . htmlspecialchars(pg_last_error($conn)) . "</div>";
    }

    if ($res_vacinas && $res_animais && $res_user) {
        // Commit transaction
        pg_query($conn, "COMMIT");

        // Delete image files
        foreach ($images_to_delete as $img_path) {
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }

        // Destroy session and redirect
        session_destroy();
            if (!headers_sent()) {
                header("Location: login.php");
                exit();
            } else {
                echo '<script type="text/javascript">';
                echo 'window.location.href="login.php";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=login.php" />';
                echo '</noscript>';
                exit();
            }
    } else {
        // Rollback on error
        pg_query($conn, "ROLLBACK");
        $error = pg_last_error($conn);
        echo "<div class='alert alert-danger'>Erro ao eliminar a conta: " . htmlspecialchars($error) . "</div>";
    }
}
?>
 <button class="menu-toggle-right" id="menuToggleRight">
        <i class="fas fa-bars"></i>
    </button>
<nav class="sidebar-right" id="sidebarRight">
        <div class="text-center mb-4">
            <h4 class="text-white"><i class="fas fa-paw"></i> Menu Animais PetCare</h4>
        </div>
        
        <a href="animals.php" ><i class="fas fa-dog"></i> Meus Animais</a>
        <a href="registeranimal.php"><i class="fas fa-calendar-check"></i> Registrar Animal</a>
        <a href="alimentaçao.php"><i class="fas fa-file-medical"></i> Ver dietas</a>
        <a href="editarutilizador.php"class="active"><i class="fas fa-cog"></i> Definições</a>
        
        <div style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
            <a href="logout.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Fechar Sessão
            </a>
        </div>
    </nav> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap form validation
    (function() {
        'use strict';
        var form = document.getElementById('userUpdateForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            var password = document.getElementById('password').value;
            var password_confirm = document.getElementById('password_confirm').value;
            if (password !== password_confirm) {
                alert('As palavras-passe não coincidem.');
                return;
            }

            // Prepare form data
            var formData = new FormData(form);
            formData.append('ajax', '1'); // flag to indicate AJAX request

            // Send AJAX request
            fetch('editarutilizador.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var messageDiv = document.getElementById('updateMessage');
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    // Optionally update form fields with returned data
                    if (data.updatedUser) {
                        document.getElementById('nome').value = data.updatedUser.nome;
                        document.getElementById('email').value = data.updatedUser.email;
                    }
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => {
                var messageDiv = document.getElementById('updateMessage');
                messageDiv.innerHTML = '<div class="alert alert-danger">Erro na comunicação com o servidor.</div>';
            });
        });
    })();

    // Handle animal deletion button click
    document.querySelectorAll('.btn-eliminar-animal').forEach(button => {
        button.addEventListener('click', function() {
            const animal = JSON.parse(this.getAttribute('data-animal'));
            // Set animal info in modal
            document.getElementById('modalAnimalName').textContent = animal.nome;
            document.getElementById('animal_id').value = animal.codanimal;
            document.getElementById('delete_reason').value = '';
            // Show modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteAnimalModal'));
            deleteModal.show();
        });
    });

    // Handle modal confirm button click for animal deletion
    document.getElementById('confirmDeleteAnimal').addEventListener('click', function() {
        const reasonInput = document.getElementById('modalDeleteReason');
        const reason = reasonInput.value.trim();
        if (reason === '') {
            reasonInput.classList.add('is-invalid');
            reasonInput.focus();
        } else {
            reasonInput.classList.remove('is-invalid');
            // Prepare form data for AJAX
            var formData = new FormData(document.getElementById('deleteAnimalForm'));
            formData.append('ajax', '1'); // flag to indicate AJAX request

            // Send AJAX request to delete animal
            fetch('editarutilizador.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove animal from list
                    var animalList = document.querySelector('.list-group');
                    var animalItems = animalList.querySelectorAll('li');
                    animalItems.forEach(item => {
                        if (item.querySelector('.btn-eliminar-animal').getAttribute('data-animal').includes('"codanimal":' + data.deletedAnimalId)) {
                            item.remove();
                        }
                    });
                    // Hide modal
                    var deleteModalEl = document.getElementById('deleteAnimalModal');
                    var deleteModal = bootstrap.Modal.getInstance(deleteModalEl);
                    deleteModal.hide();

                    // Clear reason input
                    reasonInput.value = '';

                    // Show success message
                    var messageDiv = document.getElementById('updateMessage');
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';

                    // If no animals left, show no animals message
                    if (animalList.children.length === 0) {
                        animalList.innerHTML = '<p>Não há animais registados.</p>';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Erro na comunicação com o servidor.');
            });
        }
    });
});
</script>
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

window.addEventListener('DOMContentLoaded', function() {
    // Handle animal deletion button click
    document.querySelectorAll('.btn-eliminar-animal').forEach(button => {
        button.addEventListener('click', function() {
            const animal = JSON.parse(this.getAttribute('data-animal'));
            // Set animal info in modal
            document.getElementById('modalAnimalName').textContent = animal.nome;
            document.getElementById('animal_id').value = animal.codanimal;
            document.getElementById('delete_reason').value = '';
            // Show modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteAnimalModal'));
            deleteModal.show();
        });
    });

    // Handle modal confirm button click
    document.getElementById('confirmDeleteAnimal').addEventListener('click', function() {
        const reasonInput = document.getElementById('modalDeleteReason');
        const reason = reasonInput.value.trim();
        if (reason === '') {
            reasonInput.classList.add('is-invalid');
            reasonInput.focus();
        } else {
            reasonInput.classList.remove('is-invalid');
            document.getElementById('delete_reason').value = reason;
            document.getElementById('deleteAnimalForm').submit();
        }
    });
});
    </script>

    <!-- Adicione esta modal antes do fechamento do </body> -->
<!-- Modal para confirmar eliminação de animal -->
<div class="modal fade" id="deleteAnimalModal" tabindex="-1" aria-labelledby="deleteAnimalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnimalModalLabel">Confirmar Eliminação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja eliminar o animal <strong id="modalAnimalName"></strong>?</p>
                <div class="form-group">
                    <label for="modalDeleteReason">Motivo da eliminação:</label>
                    <textarea class="form-control" id="modalDeleteReason" rows="3" placeholder="Descreva o motivo da eliminação..." required></textarea>
                    <div class="invalid-feedback">Por favor, insira o motivo da eliminação.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAnimal">Confirmar Eliminação</button>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS Bundle CDN (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>