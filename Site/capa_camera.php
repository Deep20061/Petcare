<?php
include('php/server.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Capa Microcâmera - Pet Care</title>

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
                <h1 class="m-0 display-5 text-capitalize font-italic text-white"><span class="text-primary">Pet</span>Care</span></h1>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Início</a>
                    <a href="about.php" class="nav-item nav-link">Sobre</a>
                    <a href="service.php" class="nav-item nav-link">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link">Raças</a>
                    <a href="booking.php" class="nav-item nav-link">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Outras Páginas</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="capa_camera.php" class="dropdown-item">Capa para Micro Câmera</a>
                            <a href="blog_single.php" class="dropdown-item active">Texto Informativos</a>
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

    <!-- Main Content Start -->
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 mb-4">Capa para Micro Câmera</h1>
                <p class="mb-4">
                    Esta capa para a micro câmera V720 A11 foi desenvolvida para proteger e otimizar o dispositivo criando uma interface bonita para a câmera. 
                    É leve, resistente e fácil de instalar. Ideal para quem procura qualidade e praticidade.

                    <!-- Placeholder text, you can edit this later -->
                </p>
                <a href="download/Capa%20micro%20camara.stl" class="btn btn-primary btn-lg" download>
                    <i class="fa fa-download mr-2"></i> Download da Capa
                </a>
            </div>
            <div class="col-lg-6">
                <div id="stl-viewer" style="width: 100%; height: 400px; border: 1px solid #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                    <div style="text-align: center; color: #6c757d;">
                        <i class="fa fa-cube fa-3x mb-3"></i>
                        <p>Visualizador 3D<br><small>Carregando modelo...</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content End -->

    <!-- Footer -->
    <?php include('php/footer.php'); ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Three.js usando versão mais antiga que funciona -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/loaders/STLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mrdoob/three.js@r128/examples/js/controls/OrbitControls.js"></script>

    <script>
        // Aguardar que todos os scripts carreguem
        window.addEventListener('load', function() {
            console.log('Página carregada, verificando THREE.js...');
            
            // Verificar se THREE está disponível
            if (typeof THREE === 'undefined') {
                console.error('THREE.js não carregou');
                document.getElementById('stl-viewer').innerHTML = '<div style="text-align: center; color: #dc3545;"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Erro: THREE.js não carregou</p></div>';
                return;
            }
            
            console.log('THREE.js carregado, versão:', THREE.REVISION);
            
            // Aguardar um pouco mais para os loaders carregarem
            setTimeout(function() {
                // Verificar se STLLoader está disponível
                if (typeof THREE.STLLoader === 'undefined') {
                    console.error('STLLoader não carregou');
                    document.getElementById('stl-viewer').innerHTML = '<div style="text-align: center; color: #dc3545;"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Erro: STLLoader não carregou</p></div>';
                    return;
                }
                
                console.log('STLLoader disponível');

                try {
                    // Set up scene, camera, renderer
                    const scene = new THREE.Scene();
                    scene.background = new THREE.Color(0xf0f0f0);
                    const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
                    
                    // Posicionar câmera de frente (eixo Z positivo olhando para o centro)
                    camera.position.set(0, 0, 100);
                    camera.lookAt(0, 0, 0);

                    const renderer = new THREE.WebGLRenderer({ antialias: true });
                    const container = document.getElementById('stl-viewer');
                    
                    // Limpar conteúdo inicial
                    container.innerHTML = '';
                    
                    renderer.setSize(container.clientWidth, container.clientHeight);
                    container.appendChild(renderer.domElement);

                    // Add lights
                    const ambientLight = new THREE.AmbientLight(0x404040, 0.6);
                    scene.add(ambientLight);
                    
                    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
                    directionalLight.position.set(1, 1, 1).normalize();
                    scene.add(directionalLight);
                    
                    const directionalLight2 = new THREE.DirectionalLight(0xffffff, 0.4);
                    directionalLight2.position.set(-1, -1, -1).normalize();
                    scene.add(directionalLight2);

                    // Load STL model - usando URL codificada para espaços
                    const loader = new THREE.STLLoader();
                    console.log('Iniciando carregamento do STL...');
                    
                    // Tentar diferentes variações do nome do arquivo
                    const possiblePaths = [
                        'download/Capa%20micro%20camara.stl',  // URL encoded
                        'download/Capa micro camara.stl',      // Original
                        'download/capa_micro_camara.stl',      // Com underscores
                        'download/capa-micro-camara.stl'       // Com hífens
                    ];
                    
                    let currentPathIndex = 0;
                    
                    function tryLoadSTL() {
                        if (currentPathIndex >= possiblePaths.length) {
                            console.error('Nenhum arquivo STL encontrado');
                            container.innerHTML = '<div style="text-align: center; color: #dc3545; padding: 20px;"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Arquivo STL não encontrado<br><small>Verifique se o arquivo existe na pasta download/</small></p></div>';
                            return;
                        }
                        
                        const currentPath = possiblePaths[currentPathIndex];
                        console.log('Tentando carregar:', currentPath);
                        
                        loader.load(currentPath, 
                            function (geometry) {
                                console.log('STL carregado com sucesso de:', currentPath);
                                
                                const material = new THREE.MeshPhongMaterial({ 
                                    color: 0x606060, 
                                    specular: 0x111111, 
                                    shininess: 200 
                                });
                                const mesh = new THREE.Mesh(geometry, material);

                                // Center the geometry
                                geometry.computeBoundingBox();
                                const center = new THREE.Vector3();
                                geometry.boundingBox.getCenter(center);
                                mesh.geometry.translate(-center.x, -center.y, -center.z);

                                scene.add(mesh);

                                // Calcular distância da câmera baseada no tamanho do objeto
                                const bbox = geometry.boundingBox;
                                const size = new THREE.Vector3();
                                bbox.getSize(size);
                                const maxDim = Math.max(size.x, size.y, size.z);
                                const fov = camera.fov * (Math.PI / 180);
                                let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));
                                cameraZ *= 1.5; // zoom out um pouco
                                
                                // Posicionar câmera de frente
                                camera.position.set(0, 0, cameraZ);
                                camera.lookAt(0, 0, 0);

                                // Controls
                                let controls;
                                if (typeof THREE.OrbitControls !== 'undefined') {
                                    controls = new THREE.OrbitControls(camera, renderer.domElement);
                                    controls.target.set(0, 0, 0);
                                    controls.enableDamping = true;
                                    controls.dampingFactor = 0.1;
                                    
                                    // Definir posição inicial dos controles (vista frontal)
                                    controls.reset();
                                    controls.update();
                                    
                                    // Opcional: limitar rotação vertical
                                    controls.maxPolarAngle = Math.PI; // 180 graus
                                    controls.minPolarAngle = 0; // 0 graus
                                }

                                // Animation loop
                                function animate() {
                                    requestAnimationFrame(animate);
                                    
                                    if (controls) {
                                        controls.update();
                                    } else {
                                        // Rotação automática suave se não houver controles
                                        mesh.rotation.y += 0.005;
                                    }
                                    
                                    renderer.render(scene, camera);
                                }
                                animate();
                                
                                console.log('Visualizador 3D inicializado com sucesso - Vista frontal');
                            }, 
                            function(progress) {
                                console.log('Progresso do carregamento:', progress);
                            },                            function (error) {
                                console.error('Erro ao carregar STL de:', currentPath, error);
                                currentPathIndex++;
                                tryLoadSTL(); // Tentar próximo caminho
                            }
                        );
                    }
                    
                    tryLoadSTL();

                    // Handle window resize
                    window.addEventListener('resize', () => {
                        const width = container.clientWidth;
                        const height = container.clientHeight;
                        renderer.setSize(width, height);
                        camera.aspect = width / height;
                        camera.updateProjectionMatrix();
                        renderer.render(scene, camera);
                    });

                } catch (error) {
                    console.error('Erro ao inicializar o visualizador 3D:', error);
                    document.getElementById('stl-viewer').innerHTML = '<div style="text-align: center; color: #dc3545; padding: 20px;"><i class="fa fa-exclamation-triangle fa-2x mb-2"></i><p>Erro ao inicializar visualizador 3D</p></div>';
                }
            }, 1000); // Aguardar 1 segundo para os loaders carregarem
        });
    </script>
</body>

</html>

