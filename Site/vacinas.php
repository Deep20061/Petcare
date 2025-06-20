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

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style> 
        body {
            font-family: 'Nunito', sans-serif;
            background-color: rgb(236, 236, 236);
        }
        .overdue { 
            color: red; 
            font-weight: bold; 
        }
        
        /* Estilos para notificações flutuantes */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            pointer-events: none;
        }
        
        .notification {
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.5s ease-out;
            pointer-events: auto;
            border: none;
        }
        
        .notification-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-left: 5px solid #ffffff;
        }
        
        .notification-error {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
            border-left: 5px solid #ffffff;
        }
        
        .notification-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
            border-left: 5px solid #ffffff;
        }
        
        .notification-info {
            background: linear-gradient(135deg, #17a2b8, #007bff);
            color: white;
            border-left: 5px solid #ffffff;
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
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification.fade-out {
            animation: slideOutRight 0.5s ease-in forwards;
        }
        
        .notification .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .notification-warning .btn-close {
            filter: brightness(0);
        }
    </style>

<?php 
include('php/server.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Validar se o ID foi fornecido
if (!isset($_GET['id'])) {
    header("Location: animals.php");
    exit();
}

// Include notifications
include('php/notifications.php');

$email = $_SESSION['email'];
$passe = $_SESSION['password'];
$user_id = $_SESSION['id'];
$id = intval($_GET['id']); // Sanitizar o ID

// Validar se o animal pertence ao usuário logado
$validateOwnerQuery = "SELECT codanimal FROM animais WHERE codanimal = $id AND pertence = (SELECT códigoutilizador FROM utilizadores WHERE email = '$email')";
$validateResult = pg_query($conn, $validateOwnerQuery);

if (!$validateResult || pg_num_rows($validateResult) == 0) {
    NotificationManager::errorSession('Erro', 'Acesso negado.');
    header("Location: animals.php");
    exit();
}

// Processar registro de nova vacina - MOVIDO PARA ANTES DAS OUTRAS QUERIES
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo-animal']) && isset($_POST['data-animal'])) {
    $c_vacina = intval($_POST['tipo-animal']);
    $datavacina = pg_escape_string($conn, $_POST['data-animal']);
    
    // Validar se a data não está vazia
    if (empty($datavacina)) {
        NotificationManager::errorSession('Erro', 'Por favor, insira uma data válida.');
        header("Location: vacinas.php?id=$id");
        exit();
    }
    
    // Validar se a vacina foi selecionada
    if ($c_vacina <= 0) {
        NotificationManager::errorSession('Erro', 'Por favor, selecione uma vacina válida.');
        header("Location: vacinas.php?id=$id");
        exit();
    }

    // Check animal age
    $animalQuery = "SELECT nascimento FROM animais WHERE codanimal = $id";
    $animalResult = pg_query($conn, $animalQuery);
    
    if (!$animalResult || pg_num_rows($animalResult) == 0) {
        NotificationManager::errorSession('Erro', 'Animal não encontrado.');
        header("Location: vacinas.php?id=$id");
        exit();
    }
    
    $animal = pg_fetch_assoc($animalResult);
    $nascimento = new DateTime($animal['nascimento']);
    $today = new DateTime();
    $ageWeeks = floor($nascimento->diff($today)->days / 7);

    // Verificar se a vacina existe e obter informações
    $vaccineQuery = "SELECT doses, nome FROM vacinas WHERE c_vacina = $c_vacina";
    $vaccineResult = pg_query($conn, $vaccineQuery);
    
    if (!$vaccineResult || pg_num_rows($vaccineResult) == 0) {
        NotificationManager::errorSession('Erro', 'Vacina não encontrada.');
        header("Location: vacinas.php?id=$id");
        exit();
    }
    
    $vaccine = pg_fetch_assoc($vaccineResult);
    $maxDoses = $vaccine['doses'];
    $nomeVacina = $vaccine['nome'];
    $minAgeWeeks = 6; // Minimum age of 6 weeks as a default

    if ($ageWeeks < $minAgeWeeks) {
        NotificationManager::warningSession('Aviso', "O animal ainda não tem idade suficiente para esta vacina. Idade mínima: $minAgeWeeks semanas.");
        header("Location: vacinas.php?id=$id");
        exit();
    }

    // Verificar se já existe registro desta vacina para este animal
    $checkQuery = "SELECT doses FROM vacinasfeitas WHERE c_animal = $id AND c_vacina = $c_vacina";
    $checkResult = pg_query($conn, $checkQuery);
    
    $currentDoses = 0;
    if ($checkResult && pg_num_rows($checkResult) > 0) {
        $checkRow = pg_fetch_assoc($checkResult);
        $currentDoses = intval($checkRow['doses']);
    }

    if ($currentDoses >= $maxDoses) {
        NotificationManager::warningSession('Aviso', 'O número máximo de doses para esta vacina já foi atingido.');
        header("Location: vacinas.php?id=$id");
        exit();
    }

    // Formatar a data corretamente
    $dataFormatada = date("Y-m-d", strtotime($datavacina));
    
    // Inserir ou atualizar a vacina
    if ($currentDoses == 0) {
        // Inserir nova vacina
        $insertQuery = "INSERT INTO vacinasfeitas (c_vacina, c_animal, doses, datavacina) VALUES ($c_vacina, $id, 1, '$dataFormatada')";
        $result = pg_query($conn, $insertQuery);
    } else {
        // Atualizar doses existentes
        $newDoses = $currentDoses + 1;
        $updateQuery = "UPDATE vacinasfeitas SET doses = $newDoses, datavacina = '$dataFormatada' WHERE c_animal = $id AND c_vacina = $c_vacina";
        $result = pg_query($conn, $updateQuery);
    }
    
    if ($result) {
        $dosesText = $currentDoses == 0 ? "1ª dose" : ($currentDoses + 1) . "ª dose";
        NotificationManager::successSession('Sucesso', "Vacina \"$nomeVacina\" ($dosesText) registrada com sucesso!");
        header("Location: vacinas.php?id=$id");
        exit();
    } else {
        $error = pg_last_error($conn);
        error_log("Erro ao inserir vacina: " . $error);
        NotificationManager::errorSession('Erro', 'Erro ao registrar a vacina: ' . $error);
        header("Location: vacinas.php?id=$id");
        exit();
    }
}

// Processar eliminação de vacina
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vacinaToDelete'])) {
    $c_vacina = intval($_POST['vacinaToDelete']);
    
    // Debug: Verificar se os valores estão corretos
    error_log("Tentando eliminar vacina ID: $c_vacina para animal: $id");
    
    // Verificar se a vacina está realmente registrada para este animal
    $checkVacinaQuery = "SELECT * FROM vacinasfeitas WHERE c_animal = $id AND c_vacina = $c_vacina";
    $checkResult = pg_query($conn, $checkVacinaQuery);
    
    if ($checkResult && pg_num_rows($checkResult) > 0) {
        // Agora fazer o DELETE com os IDs específicos
        $deleteQuery = "DELETE FROM vacinasfeitas WHERE c_animal = $id AND c_vacina = $c_vacina";
        $deleteResult = pg_query($conn, $deleteQuery);
        
        if ($deleteResult) {
            $affectedRows = pg_affected_rows($deleteResult);
            if ($affectedRows > 0) {
                NotificationManager::successSession('Sucesso', "Vacina eliminada com sucesso.");
                header("Location: vacinas.php?id=$id");
                exit();
            } else {
                NotificationManager::warningSession('Aviso', 'Nenhuma vacina foi encontrada para eliminar.');
            }
        } else {
            $error = pg_last_error($conn);
            NotificationManager::errorSession('Erro', 'Erro ao eliminar a vacina: ' . $error);
        }
    } else {
        NotificationManager::warningSession('Aviso', 'Esta vacina não está registrada para este animal.');
    }
}

// Obter dados do animal
$query = "SELECT * FROM animais WHERE codanimal = $id";
$result = pg_query($conn, $query);
$tabela = pg_fetch_array($result);

if (!$tabela) {
    echo "<script>alert('Animal não encontrado.'); window.location.href = 'animals.php';</script>";
    exit();
}

$raça = $tabela['c_raca'];
$sql = "SELECT c_tipo FROM tipo WHERE c_tipo IN (SELECT c_tipo FROM racas WHERE c_raca = $raça)";
$result2 = pg_query($conn, $sql);
$tabela2 = pg_fetch_array($result2);
$tipos = $tabela2['c_tipo'];

// Obter vacinas disponíveis para o tipo do animal
$query = "SELECT * FROM vacinas WHERE tipos = '$tipos' ORDER BY nome;";
$result3 = pg_query($conn, $query);

// Obter vacinas já feitas
$sql4 = "SELECT vacinasfeitas.doses AS doses, datavacina, vacinas.nome AS Nome, vacinas.c_vacina
        FROM vacinasfeitas
        JOIN vacinas ON vacinas.c_vacina = vacinasfeitas.c_vacina
        WHERE vacinasfeitas.c_animal = $id;";
$result4 = pg_query($conn, $sql4);
$vacinasFeitas = [];
if ($result4 && pg_num_rows($result4) > 0) {
    $today = new DateTime();
    while ($row = pg_fetch_assoc($result4)) {
        $lastDoseDate = new DateTime($row['datavacina']);
        $nextDoseDate = clone $lastDoseDate;
        $nextDoseDate->modify('+1 year');
        $row['next_dose'] = $nextDoseDate->format('Y-m-d');
        $row['overdue'] = $today > $nextDoseDate ? ' (Atrasada)' : '';
        $vacinasFeitas[$row['c_vacina']] = $row;
    }
}

// Query para obter vacinas com doses
$sql5 = "SELECT vacinas.nome AS nome,
        vacinasfeitas.doses AS dose,
        vacinas.c_vacina AS vacina,
        vacinas.doses AS max_doses
        FROM vacinas
        LEFT JOIN vacinasfeitas 
        ON vacinas.c_vacina = vacinasfeitas.c_vacina
        AND vacinasfeitas.c_animal = $id 
        WHERE vacinas.tipos = $tipos
        ORDER BY vacinas.nome;";
$result5 = pg_query($conn, $sql5);
?>
</head>
<body style='color: #333;'>
    <!-- Container de Notificações Flutuantes -->
    <div class="notification-container" id="notificationContainer">
        <?php
        // Renderizar notificações de sessão como notificações flutuantes
        if (isset($_SESSION['notifications'])) {
            foreach ($_SESSION['notifications'] as $notification) {
                $typeClass = 'notification-' . $notification['type'];
                $icon = '';
                switch($notification['type']) {
                    case 'success':
                        $icon = '<i class="fas fa-check-circle me-2"></i>';
                        break;
                    case 'error':
                        $icon = '<i class="fas fa-exclamation-circle me-2"></i>';
                        break;
                    case 'warning':
                        $icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                        break;
                    case 'info':
                        $icon = '<i class="fas fa-info-circle me-2"></i>';
                        break;
                }
                
                echo "<div class='alert notification $typeClass alert-dismissible' role='alert'>
                        $icon
                        <strong>" . htmlspecialchars($notification['title']) . "</strong><br>
                        " . htmlspecialchars($notification['message']) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            }
            // Limpar notificações após exibir
            unset($_SESSION['notifications']);
        }
        ?>
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

    <div style='max-width: 1200px; margin: 0 auto; padding: 20px;'>
        <div class="profile-content">
            <main class="main-content">
                <section class="about-section">
                    <h2 class="section-title">Vacinas Registadas</h2>
                    <div class="form-group">
                    <?php if (count($vacinasFeitas) > 0) {
                            foreach ($vacinasFeitas as $vacina) {
                               echo ("<div class='about-item about-item-name'>
                        <div class='about-title'>Nome da Vacina: " . htmlspecialchars($vacina['nome']) . "</div>
                        <div class='about-period'>Última Dose da Vacina: " . htmlspecialchars($vacina['datavacina']) . "</div>
                        <div class='about-period'>Quantas doses até agora: " . htmlspecialchars($vacina['doses']) . "</div>
                        <div class='about-period'>Próxima Dose: " . htmlspecialchars($vacina['next_dose']) . 
                        ($vacina['overdue'] ? "<span class='overdue'>" . $vacina['overdue'] . "</span>" : "") . "</div>
                    </div>");
                            }
                        } else {
                            echo "<li class='skill-item'>Nenhuma vacina registrada</li>";
                        }
                      ?>
                    </div>
                    <?php if (count($vacinasFeitas) > 0) { ?>
                    <a id="openDeleteModal" class="about-text" style="text-align:right; cursor:pointer;">Eliminar Vacina</a>
                    <form method="POST" id="deleteForm">
                    <div id="deleteModal" class="modal" style="display:none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
                        <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 300px; border-radius: 5px; text-align: center;">
                            <p>Tem certeza que deseja eliminar esta vacina?</p>
<select id="vacinaSelect" name="vacinaToDelete" class="form-control mb-3" style="width: 100%; padding: 5px;">
    <option value="">Selecione a vacina</option>
    <?php
    if (count($vacinasFeitas) > 0) {
        foreach ($vacinasFeitas as $vacina) {
            // Use vaccine ID as value instead of name
            echo "<option value='" . intval($vacina['c_vacina']) . "'>" . htmlspecialchars($vacina['nome']) . "</option>";
        }
    }
    ?>
</select>
                            <button type="submit" id="confirmDelete" class="btn btn-danger">Eliminar</button>
                            <button type="button" id="cancelDelete" class="btn btn-secondary">Cancelar</button>
                        </div>
                    </div>
                    </form>
                    <?php } ?>
                </section>
                <section class="about-section">
                    <h2 class="section-title">Registar Vacinas</h2>
                    <div class="form-group">
                    <form method='POST' class="needs-validation" novalidate id="animal-form">   
                        <div class="form-group">
                            <label for="tipo-animal" class="form-label">Vacina com Devida Dose</label>
                            <select id="tipo-animal" name="tipo-animal" class="form-control form-control-custom" required autocomplete="off">
                                <option value="">Selecione a vacina</option>
                                <?php
                                if ($result5) {
                                    while ($vacinadose = pg_fetch_array($result5)) {
                                        $currentDose = $vacinadose['dose'] ? intval($vacinadose['dose']) : 0;
                                        $nextDose = $currentDose + 1;
                                        $maxDoses = intval($vacinadose['max_doses']);
                                        
                                        // Só mostrar se ainda não atingiu o máximo de doses
                                        if ($nextDose <= $maxDoses) {
                                            echo "<option value='" . htmlspecialchars($vacinadose['vacina']) . "'>" . 
                                                 htmlspecialchars($vacinadose['nome']) . " - Dose nº " . $nextDose . 
                                                 "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Por favor, selecione a vacina.</div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="data-animal" class="form-label">Data da Vacina</label>
                            <input type="date" id="data-animal" name="data-animal" class="form-control form-control-custom" required autocomplete="off" max="<?php echo date('Y-m-d'); ?>">
                            <div class="invalid-feedback">Por favor, insira a data da vacina.</div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary-custom btn-lg px-4 py-3">Registar Vacina</button>     
                        </div>
                    </form>
                    </div>
                </section>
            </main>

            <!-- Parte do lado -->
            <aside class="sidebar">
                <section class="sidebar-section">
                    <h2 class="section-title">Vacinas Existentes para o animal</h2>
                    <ul class="list-unstyled">
                        <?php
                        if ($result3) {
                            // Reset result pointer
                            $result3 = pg_query($conn, $query);
                            while ($vacina = pg_fetch_array($result3)) {
                                echo "<li>" . htmlspecialchars($vacina['nome']) . ": " . htmlspecialchars($vacina['doses']) . " Doses</li>";
                            }
                        }
                        ?>
                    </ul>
                    <p>Estas vacinas devem ser retomadas após passado um prazo de 1 ano.</p>
                    <div class="text-center mt-4">
                        <a href='<?php echo "coiso.php?id=$id"; ?>' class="btn btn-primary-custom btn-lg px-4 py-3">Voltar</a>     
                    </div>
                </section>
            </aside>
        </div>
    </div>
 <?php include('php/footer.php'); ?>
    <!-- Footer End -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const openDeleteModalLink = document.getElementById('openDeleteModal');
    const vacinaSelect = document.getElementById('vacinaSelect');
    const form = document.getElementById('animal-form');

    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.classList.add('fade-out');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    });

    // Função para mostrar notificação dinâmica
    function showNotification(type, title, message) {
        const container = document.getElementById('notificationContainer');
        const icons = {
            success: '<i class="fas fa-check-circle me-2"></i>',
            error: '<i class="fas fa-exclamation-circle me-2"></i>',
            warning: '<i class="fas fa-exclamation-triangle me-2"></i>',
            info: '<i class="fas fa-info-circle me-2"></i>'
        };
        
        const notification = document.createElement('div');
        notification.className = `alert notification notification-${type} alert-dismissible`;
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
            ${icons[type]}
            <strong>${title}</strong><br>
            ${message}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        `;
        
        container.appendChild(notification);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.classList.add('fade-out');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    }

    // Validação do formulário
    if (form) {
        form.addEventListener('submit', function(e) {
            const tipoAnimal = document.getElementById('tipo-animal');
            const dataAnimal = document.getElementById('data-animal');
            
            let isValid = true;
            
            // Validar seleção de vacina
            if (!tipoAnimal.value) {
                tipoAnimal.classList.add('is-invalid');
                isValid = false;
            } else {
                tipoAnimal.classList.remove('is-invalid');
                tipoAnimal.classList.add('is-valid');
            }
            
            // Validar data
            if (!dataAnimal.value) {
                dataAnimal.classList.add('is-invalid');
                isValid = false;
            } else {
                // Verificar se a data não é futura
                const selectedDate = new Date(dataAnimal.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate > today) {
                    dataAnimal.classList.add('is-invalid');
                    showNotification('warning', 'Data Inválida', 'A data da vacina não pode ser no futuro.');
                    isValid = false;
                } else {
                    dataAnimal.classList.remove('is-invalid');
                    dataAnimal.classList.add('is-valid');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                if (!tipoAnimal.value && !dataAnimal.value) {
                    showNotification('error', 'Campos Obrigatórios', 'Por favor, preencha todos os campos obrigatórios.');
                }
            }
            
            form.classList.add('was-validated');
        });
    }

    // Abrir modal
    if (openDeleteModalLink) {
        openDeleteModalLink.addEventListener('click', function() {
            modal.style.display = 'block';
        });
    }

    // Validar seleção antes de eliminar
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function(e) {
            if (!vacinaSelect.value) {
                e.preventDefault();
                showNotification('warning', 'Seleção Necessária', 'Por favor, selecione uma vacina para eliminar.');
                return false;
            }
            
            // Confirmar eliminação
            if (!confirm('Tem certeza que deseja eliminar a vacina "' + vacinaSelect.options[vacinaSelect.selectedIndex].text + '"?')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Cancelar eliminação
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            vacinaSelect.value = ''; // Reset selection
        });
    }

    // Fechar modal clicando fora
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            vacinaSelect.value = ''; // Reset selection
        }
    };

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
            vacinaSelect.value = ''; // Reset selection
        }
    });

    // Limpar validação quando o usuário começar a digitar/selecionar
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input',
function() {
            this.classList.remove('is-invalid');
        });
        
        input.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Melhorar a experiência do usuário com feedback visual
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
    if (submitBtn && form) {
        form.addEventListener('submit', function(e) {
            if (form.checkValidity()) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registrando...';
                submitBtn.disabled = true;
            }
        });
    }

    // Adicionar efeito hover nas notificações
    document.addEventListener('mouseenter', function(e) {
        if (e.target.classList.contains('notification')) {
            e.target.style.transform = 'translateX(-5px)';
        }
    }, true);

    document.addEventListener('mouseleave', function(e) {
        if (e.target.classList.contains('notification')) {
            e.target.style.transform = 'translateX(0)';
        }
    }, true);

    // Melhorar a acessibilidade das notificações
    notifications.forEach(notification => {
        notification.setAttribute('tabindex', '0');
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'polite');
        
        // Adicionar suporte para fechar com Enter ou Espaço
        notification.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const closeBtn = this.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        });
    });

    // Adicionar transição suave para os campos de formulário
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.style.transition = 'border-color 0.3s ease, box-shadow 0.3s ease';
        
        control.addEventListener('focus', function() {
            this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
        });
        
        control.addEventListener('blur', function() {
            if (!this.classList.contains('is-invalid')) {
                this.style.boxShadow = 'none';
            }
        });
    });

    // Adicionar validação em tempo real para o campo de data
    const dataInput = document.getElementById('data-animal');
    if (dataInput) {
        dataInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                showNotification('warning', 'Data Inválida', 'A data da vacina não pode ser no futuro.');
            } else if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }

    // Adicionar validação em tempo real para o select de vacinas
    const vacinaInput = document.getElementById('tipo-animal');
    if (vacinaInput) {
        vacinaInput.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    }

    // Função para verificar se há notificações e focar nelas para acessibilidade
    if (notifications.length > 0) {
        // Focar na primeira notificação para leitores de tela
        setTimeout(() => {
            notifications[0].focus();
        }, 100);
    }

    // Adicionar suporte para swipe em dispositivos móveis para fechar notificações
    let startX = 0;
    let currentX = 0;
    let isSwipping = false;

    document.addEventListener('touchstart', function(e) {
        if (e.target.closest('.notification')) {
            startX = e.touches[0].clientX;
            isSwipping = true;
        }
    });

    document.addEventListener('touchmove', function(e) {
        if (!isSwipping) return;
        
        currentX = e.touches[0].clientX;
        const diffX = currentX - startX;
        
        if (diffX > 0) { // Swipe para a direita
            const notification = e.target.closest('.notification');
            if (notification) {
                notification.style.transform = `translateX(${Math.min(diffX, 100)}px)`;
                notification.style.opacity = Math.max(1 - (diffX / 200), 0.3);
            }
        }
    });

    document.addEventListener('touchend', function(e) {
        if (!isSwipping) return;
        
        const diffX = currentX - startX;
        const notification = e.target.closest('.notification');
        
        if (notification) {
            if (diffX > 100) { // Swipe suficiente para fechar
                notification.classList.add('fade-out');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 500);
            } else { // Voltar à posição original
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }
        }
        
        isSwipping = false;
        startX = 0;
        currentX = 0;
    });
});

// Função global para mostrar notificações (pode ser chamada de outros scripts)
window.showVacinaNotification = function(type, title, message) {
    const container = document.getElementById('notificationContainer');
    if (!container) return;
    
    const icons = {
        success: '<i class="fas fa-check-circle me-2"></i>',
        error: '<i class="fas fa-exclamation-circle me-2"></i>',
        warning: '<i class="fas fa-exclamation-triangle me-2"></i>',
        info: '<i class="fas fa-info-circle me-2"></i>'
    };
    
    const notification = document.createElement('div');
    notification.className = `alert notification notification-${type} alert-dismissible`;
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'polite');
    notification.innerHTML = `
        ${icons[type]}
        <strong>${title}</strong><br>
        ${message}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    `;
    
    container.appendChild(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 500);
        }
    }, 5000);
};
</script>
</body>
</html>
