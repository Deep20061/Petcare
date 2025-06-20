<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pet Care - Editar Gostos Alimentares</title>

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
        .container-main {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .gosto-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .gosto-nome {
            font-weight: 600;
            color: #333;
        }
        .gosto-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }
        .status-gosta {
            background-color: #d4edda;
            color: #28a745;
        }
        .status-nao-gosta {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8em;
        }
        .btn-delete:hover {
            background-color: #c82333;
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

    <?php 
        include('php/server.php');
        if (!isset($_SESSION['email'])) {
            header("Location: login.php");
            exit();
        }
        
        $animal_id = $_GET['id'];
        
        // Buscar dados do animal
        $sql = "SELECT nome, c_raca, nascimento, castrado, img FROM animais WHERE codanimal = $animal_id";
        $result = pg_query($conn, $sql);
        $animal = pg_fetch_array($result);
        
        if (!$animal) {
            echo '<div class="container-main"><p class="text-danger">Animal não encontrado.</p></div>';
            exit();
        }

        // Processar formulário de adição/edição
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['adicionar_gosto'])) {
                $alimento_id = (int)$_POST['alimento_id'];
                $gosta = $_POST['gosta'] === 'sim' ? 'true' : 'false';
                
                // Verificar se já existe
                $check_sql = "SELECT * FROM preferencias_alimentares WHERE id_animal = $animal_id AND id_alimento = $alimento_id";
                $check_result = pg_query($conn, $check_sql);
                
                if (pg_num_rows($check_result) > 0) {
                    // Atualizar existente
                    $update_sql = "UPDATE preferencias_alimentares SET gosta = $gosta WHERE id_animal = $animal_id AND id_alimento = $alimento_id";
                    pg_query($conn, $update_sql);
                    $mensagem = "Gosto alimentar atualizado com sucesso!";
                } else {
                    // Inserir novo
                    $insert_sql = "INSERT INTO preferencias_alimentares (id_animal, id_alimento, gosta) VALUES ($animal_id, $alimento_id, $gosta)";
                    pg_query($conn, $insert_sql);
                    $mensagem = "Gosto alimentar adicionado com sucesso!";
                }
            }
            
            if (isset($_POST['remover_gosto'])) {
                $alimento_id = (int)$_POST['alimento_id_remover'];
                $delete_sql = "DELETE FROM preferencias_alimentares WHERE id_animal = $animal_id AND id_alimento = $alimento_id";
                pg_query($conn, $delete_sql);
                $mensagem = "Gosto alimentar removido com sucesso!";
            }
        }

        // Buscar gostos alimentares do animal com JOIN (usando a coluna 'alimento')
        $gostos_sql = "SELECT pa.id_alimento, a.n_alimento as nome_alimento, pa.gosta 
                       FROM preferencias_alimentares pa 
                       INNER JOIN alimentos a ON pa.id_alimento = a.c_alimento 
                       WHERE pa.id_animal = $animal_id 
                       ORDER BY a.n_alimento";
        $gostos_result = pg_query($conn, $gostos_sql);
        $gostos = [];
        while ($row = pg_fetch_array($gostos_result)) {
            $gostos[] = $row;
        }

        // Buscar todos os alimentos disponíveis para o dropdown (usando coluna 'alimento')
        $alimentos_sql = "SELECT c_alimento, n_alimento FROM alimentos ORDER BY n_alimento";
        $alimentos_result = pg_query($conn, $alimentos_sql);
        $alimentos_disponiveis = [];
        while ($row = pg_fetch_array($alimentos_result)) {
            $alimentos_disponiveis[] = $row;
        }
    ?>

    <div class="container-main">
        <div class="mb-4">
            <h2><i class="fas fa-utensils"></i> Gostos Alimentares - <?php echo htmlspecialchars($animal['nome']); ?></h2>
        </div>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-success"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <!-- Formulário para adicionar novo gosto -->
        <div class="form-section">
            <h4><i class="fas fa-plus"></i> Adicionar/Editar Gosto Alimentar</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <label for="alimento_id" class="form-label">Alimento:</label>
                        <select class="form-control" id="alimento_id" name="alimento_id" required>
                            <option value="">-- Selecione um alimento --</option>
                            <?php foreach ($alimentos_disponiveis as $alimento): ?>
                                <option value="<?php echo $alimento['c_alimento']; ?>">
                                    <?php echo htmlspecialchars($alimento['n_alimento']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="gosta" class="form-label">Gosta:</label>
                        <select class="form-control" id="gosta" name="gosta" required>
                            <option value="">-- Selecione --</option>
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="adicionar_gosto" class="btn-custom w-100">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de gostos alimentares -->
        <div class="form-section">
            <h4><i class="fas fa-list"></i> Gostos Alimentares Registrados</h4>
            
            <?php if (count($gostos) > 0): ?>
                <?php foreach ($gostos as $gosto): ?>
                    <div class="gosto-item">
                        <span class="gosto-nome"><?php echo htmlspecialchars($gosto['nome_alimento']); ?></span>
                        <div class="gosto-status">
                            <span class="status-badge <?php echo $gosto['gosta'] === 't' ? 'status-gosta' : 'status-nao-gosta'; ?>">
                                <?php echo $gosto['gosta'] === 't' ? 'Gosta' : 'Não Gosta'; ?>
                            </span>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover este gosto alimentar?')">
                                <input type="hidden" name="alimento_id_remover" value="<?php echo $gosto['id_alimento']; ?>">
                                <button type="submit" name="remover_gosto" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum gosto alimentar registrado ainda.</p>
                    <p class="text-muted">Use o formulário acima para adicionar os primeiros gostos alimentares do seu animal.</p>
                </div>
            <?php endif; ?>
        </div>
        <a href="coiso.php?id=<?php echo $animal_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar ao Perfil
            </a>
    </div>
<!-- Footer -->
<?php include('php/footer.php'); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>