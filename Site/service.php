<?php
include('php/server.php');
?>
<?php
// Mostrar erros para debug (apaga em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se é uma chamada API (parâmetros role e history presentes)
if (isset($_GET['role']) && isset($_GET['history'])) {
    // Cabeçalhos CORS para permitir acesso via frontend local
    header('Access-Control-Allow-Origin: *'); // Para todos os domínios (só para testes)
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    // Inclui a config com a chave
    require_once 'php/config.php';

    $role = $_GET['role'] ?? '';
    $historyJson = $_GET['history'] ?? '';

    if (empty($role) || empty($historyJson)) {
        echo json_encode(['error' => 'Parâmetros "role" e "history" são obrigatórios.']);
        exit;
    }

    $history = json_decode($historyJson, true);
    if (!is_array($history)) {
        echo json_encode(['error' => 'Parâmetro "history" inválido.']);
        exit;
    }

    // Usa a chave do config
    $secretKey = GROQ_API_KEY;
    if (!$secretKey) {
        echo json_encode(['error' => 'Chave de API não definida.']);
        exit;
    }

    // Endpoint da API Groq
    $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

    // Monta o array de mensagens para a API, começando pelo sistema
    $messages = [
        ['role' => 'system', 'content' => $role]
    ];

    // Adiciona o histórico da conversa
    foreach ($history as $msg) {
        if (isset($msg['role']) && isset($msg['content'])) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }
    }

    // Dados para enviar à API
    $data = [
        'model' => 'llama3-8b-8192',
        'messages' => $messages
    ];

    // cURL para enviar pedido
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $secretKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['error' => curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // Decodifica resposta JSON
    $responseData = json_decode($response, true);
    if (!$responseData || !isset($responseData['choices'][0]['message']['content'])) {
        echo json_encode(['error' => 'Resposta inválida da API', 'raw' => $response]);
        exit;
    }

    // Retorna resposta ao frontend
    echo json_encode([
        'reply' => $responseData['choices'][0]['message']['content']
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Pet Care - Conversa com o Bartolomeu, o teu veterinário felino amigo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Flaticon Font -->
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap & Custom Styles -->
    <link href="css/style.css" rel="stylesheet">
<style>
    .scroll-list__wrp{
        overflow: auto, padding: 50px;
        box-shadow: 0px 7px 46px 0px rgba(0, 0, 0, 0.3);
        background-image: linear-gradient(147deg, #2e3a59 0%, #a2b46 74%);
    }
</style>
</head>

<body>
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
                    <a href="service.php" class="nav-item nav-link active">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link">Raças</a>
                    <a href="booking.php" class="nav-item nav-link">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown">Outras Páginas</a>
                                               <div class="dropdown-menu rounded-0 m-0">
                           <a href="capa_camera.php" class="dropdown-item">Capa para Micro Câmera</a>
                            <a href="blog_single.php" class="dropdown-item">Texto Informativos</a>
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

    <!-- Título -->
    <br>
    <h1 class="display-4 mb-4 text-center">Conversa com o <span class="text-primary">Bartolomeu</span>, o teu veterinário felino amigo</h1>

    <!-- Introductory Paragraph -->
    <div class="container mb-4">
        <p class="lead text-center">
            Olá! Aqui podes conversar com o Bartolomeu, um gato laranja e branco que é veterinário e trabalha para a PetCare. Ele adora ajudar com dúvidas sobre animais de estimação, especialmente gatos. Sente-te à vontade para perguntar o que quiseres, e o Bartolomeu vai responder com todo o carinho e conhecimento!
        </p>
    </div>

    <!-- AI Chat Section -->
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card text-white" style="background-color:#28a745">
                    <div class="card-body" id="messages" style="min-height: 60vh; max-height: 62vh; overflow-y: auto;">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-10">
                                <input type="text" id="inputPrompt" class="form-control" placeholder="Escreve a tua pergunta ao Bartolomeu aqui...">
                            </div>
                            <div class="col-12 col-sm-2">
                                <button class="btn btn-dark w-100 border-0 py-3" id="sendPromptBtn" onclick="GetResponse()">Enviar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3 text-white-50" style="font-style: italic;">
                    Não hesites em perguntar! O Bartolomeu está aqui para ajudar.
                </p>
            </div>
        </div>
    </div>

    <!-- Chat Script -->
    <script>
        // Altera aqui para a tua URL local ou ngrok (https obrigatório se usares ngrok)
        const API_BASE_URL = "service.php";
        // Exemplo ngrok: const API_BASE_URL = "https://abc123.ngrok.io/service.php";

        // Array para guardar o histórico da conversa
        let conversationHistory = [];

        // Adicionar evento de Enter no input
        document.getElementById("inputPrompt").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                GetResponse();
            }
        });

        async function GetResponse() {
            const inputPrompt = document.getElementById("inputPrompt");
            const sendPromptBtn = document.getElementById("sendPromptBtn");
            const messages = document.getElementById("messages");

            const promptValue = inputPrompt.value.trim();
            if (!promptValue) return;

            sendPromptBtn.disabled = true;
            sendPromptBtn.innerHTML = 'A enviar <span class="spinner-border spinner-border-sm text-light"></span>';

            // Adiciona a mensagem do utilizador ao histórico
            conversationHistory.push({ role: "user", content: promptValue });

            // Mostrar mensagem do utilizador
            messages.innerHTML += `
                <div class="bg-dark text-white p-3 rounded mt-3">
                    <small><strong>Tu:</strong></small>
                    <p>${promptValue}</p>
                </div>
            `;
            scrollMessagesToBottom();

            const roleValue = `O teu nome é Bartolomeu, (um gato laranja e branco, que é veterinário e trabalhas para a PetCare, que é uma plataforma de ajuda de cuido animal, tu só falas português de Portugal), és animado e ronrronas muito. Só respondes a coisas a haver com animais, principalmente animais de estimação. Se houver algo que seja preciso ir para um veterinário, ou perguntarem onde é o veterinario mais proximo, diz para abrir a página "mapa", para ver o serviço mais próximo.`;

            // Envia o histórico da conversa como JSON
            const apiUrl = API_BASE_URL +
                "?role=" + encodeURIComponent(roleValue) +
                "&history=" + encodeURIComponent(JSON.stringify(conversationHistory));

            try {
                const res = await fetch(apiUrl);
                if (!res.ok) throw new Error("Erro na resposta da API");

                const data = await res.json();

                if (data.reply) {
                    // Adiciona a resposta da IA ao histórico
                    conversationHistory.push({ role: "assistant", content: data.reply });

                    messages.innerHTML += `
                        <div class="bg-secondary text-white p-3 rounded mt-2">
                            <small><strong>🐱 Bartolomeu:</strong></small>
                            <p>${data.reply}</p>
                        </div>
                    `;
                    scrollMessagesToBottom();
                } else {
                    messages.innerHTML += `
                        <div class="bg-danger text-white p-3 rounded mt-2">
                            <small>Erro:</small>
                            <p>${JSON.stringify(data)}</p>
                        </div>
                    `;
                }
            } catch (error) {
                messages.innerHTML += `
                    <div class="bg-danger text-white p-3 rounded mt-2">
                        <small>Erro:</small>
                        <p>${error.message}</p>
                    </div>
                `;
            }

            scrollMessagesToBottom();
            inputPrompt.value = "";
            sendPromptBtn.disabled = false;
            sendPromptBtn.innerHTML = "Enviar";
        }

        function scrollMessagesToBottom() {
            const messages = document.getElementById("messages");
            messages.scrollTop = messages.scrollHeight;
        }
    </script>
   
    <!-- Footer -->
<?php include('php/footer.php'); ?>
    
    <!-- Voltar ao Topo -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>
    <script src="js/main.js"></script>
</body>

</html>

