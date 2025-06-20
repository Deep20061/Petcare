<?php
$host = "localhost";
$dbname = "seu_banco";
$user = "seu_usuario";
$password = "sua_senha";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("SELECT tipo, dados FROM imagens WHERE id = ?");
        $stmt->execute([$id]);
        
        $stmt->bindColumn(1, $tipo);
        $stmt->bindColumn(2, $dados, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);
        
        if (is_resource($dados)) {
            $dados = stream_get_contents($dados);
        }

        if (!empty($dados)) {
            header("Content-Type: " . $tipo);
            echo $dados;
            exit;
        }
    }
    
    // Se a imagem não for encontrada, exibe uma imagem padrão de erro
    header("Content-Type: image/png");
    readfile('imagem_erro.png');
    exit;
    
} catch(PDOException $e) {
    header("Content-Type: image/png");
    readfile('imagem_erro.png');
    exit;
}
?>