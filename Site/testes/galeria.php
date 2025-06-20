<?php
$host = "aws-0-eu-west-3.pooler.supabase.com";
$port = "5432"; // Porta padrão do PostgreSQL
$dbname = "postgres";
$user = "postgres.kszhqvvmlrlkvsvbpinx";
$password = "LEVufRUwFPTdywIp";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar todas as imagens
    $stmt = $conn->query("SELECT id, nome FROM imagens ORDER BY data_upload DESC");
    $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Galeria de Imagens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        .image-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .image-card:hover {
            transform: translateY(-5px);
        }
        .image-container {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
        }
        .image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .image-info {
            padding: 15px;
            text-align: center;
        }
        .image-name {
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-word;
        }
        .image-date {
            color: #666;
            font-size: 0.9em;
        }
        .no-images {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>Galeria de Imagens</h1>
    
    <?php if (empty($imagens)): ?>
        <div class="no-images">
            Nenhuma imagem encontrada na base de dados.
        </div>
    <?php else: ?>
        <div class="gallery">
            <?php foreach ($imagens as $img): ?>
                <div class="image-card">
                    <div class="image-container">
                        <img src="exibir_imagem.php?id=<?= $img['id'] ?>" 
                             alt="<?= htmlspecialchars($img['nome']) ?>"
                             loading="lazy">
                    </div>
                    <div class="image-info">
                        <div class="image-name"><?= htmlspecialchars($img['nome']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>