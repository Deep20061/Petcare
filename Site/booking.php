<?php
include('php/server.php');
?>
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>PetCare - Descobre os Serviços que estão perto de ti</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
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

    <style>
        #map { height: 600px; width: 100%; }
        .loading { display: none; }
        .error { color: red; display: none; }
        .spinner { border: 4px solid rgba(0, 0, 0, 0.1); border-left-color: #3b82f6; border-radius: 50%; width: 24px; height: 24px; animation: spin 1s linear infinite; display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }
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
                <img width="20%" src="img/02 Cabecalho-principal-ESVV-24-25.png" alt="Image" style="float: right;">
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
                    <a href="service.php" class="nav-item nav-link">Cat AI</a>
                    <a href="racas.php" class="nav-item nav-link ">Raças</a>
                    <a href="booking.php" class="nav-item nav-link active">Mapa</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Outras Páginas</a>
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
    <main class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-center mb-6">Encontra Veterinários, Abrigos, Canis e Gatís pelo Mundo</h2>
        <p class="text-center text-gray-600 mb-8">Pesquisa por uma cidade ou vê veterinários, centros de adoção, canis e gatís globais.</p>

        <div class="flex justify-center mb-8">
            <input id="searchInput" type="text" placeholder="Ex: Lisboa, Porto, ou deixe em branco para global..." class="border border-gray-300 rounded-l px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-600">
            <button onclick="searchLocations()" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">Pesquisar</button>
        </div>

        <div class="flex flex-wrap justify-center gap-4 mb-8">
                <select id="filterSelect" onchange="filterLocations(this.value)" class="border border-gray-300 rounded px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="all">Todos</option>
                    <option value="veterinary">Veterinários</option>
                    <option value="pet_shop">Pet Shops</option>
                    <option value="animal_shelter">Abrigos e Adoção</option>
                </select>
        </div>

        <div id="loading" class="loading text-center text-gray-600"><span class="spinner"></span> A carregar dados...</div>
        <div id="error" class="error text-center">Erro ao carregar locais. Verifica a conexão.</div>

        <div id="map" class="w-full mb-8"></div>

        <p id="locationCount" class="text-center text-gray-600">0 locais encontrados | Clica nos marcadores para mais informações</p>
    </main>

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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script>
        let map, allMarkers = [], markerClusterGroup, allLocations = [];

        // Define custom icons for different location types
        const iconUrls = {
            veterinary: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-blue.png',
            pet_shop: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-green.png',
            animal_shelter: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-yellow.png',
            kennel: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-red.png',
            cattery: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-violet.png',
            default: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            black: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-black.png'
        };

        const icons = {};
        for (const [key, url] of Object.entries(iconUrls)) {
            icons[key] = new L.Icon({
                iconUrl: url,
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        // Initialize Map
        function initMap() {
            console.log('Initializing Leaflet map...');
            try {
                map = L.map('map').setView([39.5, -8.0], 7);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                markerClusterGroup = L.markerClusterGroup();
                map.addLayer(markerClusterGroup);
                console.log('Map initialized successfully.');
                
                // Aguardar um pouco antes de fazer a pesquisa inicial
                setTimeout(() => {
                    searchLocations();
                }, 1000);
            } catch (error) {
                console.error('Map initialization failed:', error);
                document.getElementById('error').style.display = 'block';
                document.getElementById('error').textContent = 'Erro ao inicializar o mapa. Verifica o console para detalhes.';
            }
        }

        // Geocode function to get coordinates from query
        async function geocode(query) {
            console.log(`Geocoding ${query}...`);
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                if (!response.ok) throw new Error(`Geocoding failed: ${response.statusText}`);
                const data = await response.json();
                console.log('Geocoding response:', data);
                return data[0] ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : [39.5, -8.0];
            } catch (error) {
                console.error('Geocoding error:', error);
                return [39.5, -8.0];
            }
        }

        // Dados locais de instituições importantes
        const localInstitutions = [
            {
                name: "ADAAVV - Associação para a Defesa dos Animais e Ambiente de Vila Verde",
                type: "animal_shelter",
                lat: 41.6742,
                lng: -8.4331,
                info: "Mós, Vila Verde, Braga",
                phone: "253 353 000",
                website: "https://www.facebook.com/adaavv",
                description: "Associação dedicada à defesa e proteção dos animais abandonados"
            },
            {
                name: "Canil Municipal de Lisboa",
                type: "kennel",
                lat: 38.7223,
                lng: -9.1393,
                info: "Lisboa",
                phone: "218 170 000"
            },
            {
                name: "Gatil de Lisboa",
                type: "cattery",
                lat: 38.7500,
                lng: -9.1500,
                info: "Lisboa",
                phone: "218 170 001"
            },
            {
                name: "SOS Animal - Porto",
                type: "animal_shelter",
                lat: 41.1579,
                lng: -8.6291,
                info: "Porto",
                phone: "222 000 000"
            },
            {
                name: "Canil de Braga",
                type: "kennel",
                lat: 41.5518,
                lng: -8.4229,
                info: "Braga",
                phone: "253 000 000"
            },
            {
                name: "Gatil do Porto",
                type: "cattery",
                lat: 41.1496,
                lng: -8.6109,
                info: "Porto",
                phone: "222 000 001"
            },
            {
                name: "União Zoófila",
                type: "animal_shelter",
                lat: 38.7071,
                lng: -9.1364,
                info: "Lisboa",
                phone: "213 626 026"
            },
            {
                name: "Associação Animais de Rua",
                type: "animal_shelter",
                lat: 38.7500,
                lng: -9.2000,
                info: "Lisboa",
                phone: "214 000 000"
            }
        ];

        // Search Locations
        async function searchLocations() {
            const query = document.getElementById('searchInput').value.trim();
            document.getElementById('loading').style.display = 'block';
            document.getElementById('error').style.display = 'none';
            clearMarkers();

            try {
                console.log(`Searching for locations${query ? ` in ${query}` : ' in Portugal'}...`);
                const portugalBounds = {
                    south: 36.8381,
                    west: -9.5000,
                    north: 42.1543,
                    east: -6.1892
                };
                let [lat, lon] = [39.5, -8.0];
                let useBoundingBoxSearch = false;

                if (query) {
                    [lat, lon] = await geocode(query);
                    if (
                        lat < portugalBounds.south || lat > portugalBounds.north ||
                        lon < portugalBounds.west || lon > portugalBounds.east
                    ) {
                        console.log('Query location outside Portugal, using bounding box search.');
                        useBoundingBoxSearch = true;
                    }
                } else {
                    useBoundingBoxSearch = true;
                }

                // Primeiro, adicionar dados locais
                allLocations = [...localInstitutions];
                console.log('Added local institutions:', allLocations.length);

                // Depois buscar dados do OpenStreetMap
                let overpassUrl = '';
                if (useBoundingBoxSearch) {
                    overpassUrl = `https://overpass-api.de/api/interpreter?data=[out:json][timeout:25];(` +
                        `node["amenity"="veterinary"](${portugalBounds.south},${portugalBounds.west},${portugalBounds.north},${portugalBounds.east});` +
                        `node["shop"="pet"](${portugalBounds.south},${portugalBounds.west},${portugalBounds.north},${portugalBounds.east});` +
                        `node["amenity"="animal_shelter"](${portugalBounds.south},${portugalBounds.west},${portugalBounds.north},${portugalBounds.east});` +
                        `);out body;>;out skel qt;`;
                } else {
                    overpassUrl = `https://overpass-api.de/api/interpreter?data=[out:json][timeout:25];(` +
                        `node["amenity"="veterinary"](around:50000,${lat},${lon});` +
                        `node["shop"="pet"](around:50000,${lat},${lon});` +
                        `node["amenity"="animal_shelter"](around:50000,${lat},${lon});` +
                        `);out body;`;
                }

                console.log('Overpass URL:', overpassUrl);
                const response = await fetch(overpassUrl);
                if (!response.ok) throw new Error(`Falha na requisição Overpass: ${response.statusText} (${response.status})`);
                const data = await response.json();
                console.log('Overpass response:', data);

                const elements = data.elements || [];
                
                // Processar dados do OpenStreetMap e adicionar aos dados locais
                const osmLocations = elements.map(element => {
                    let type = '';
                    const tags = element.tags || {};
                    
                    if (tags.amenity === 'veterinary') {
                        type = 'veterinary';
                    } else if (tags.shop === 'pet') {
                        type = 'pet_shop';
                    } else if (tags.amenity === 'animal_shelter') {
                        type = 'animal_shelter';   
                    } else {
                        type = 'unknown';
                    }

                    return {
                        type: type,
                        name: tags.name || 'Local sem nome',
                        lat: element.lat,
                        lng: element.lon,
                        info: tags['addr:street'] ? 
                              `${tags['addr:street']}${tags['addr:housenumber'] ? ' ' + tags['addr:housenumber'] : ''}, ${tags['addr:city'] || ''}` : 
                              (tags['addr:city'] || 'Endereço não disponível'),
                        phone: tags.phone || '',
                        website: tags.website || ''
                    };
                }).filter(loc => loc.lat && loc.lng && loc.type !== 'unknown');

                // Combinar dados locais com dados do OSM
                allLocations = [...localInstitutions, ...osmLocations];

                // Log counts of each type
                const typeCounts = {
                    veterinary: allLocations.filter(loc => loc.type === 'veterinary').length,
                    pet_shop: allLocations.filter(loc => loc.type === 'pet_shop').length,
                    animal_shelter: allLocations.filter(loc => loc.type === 'animal_shelter').length,
                    kennel: allLocations.filter(loc => loc.type === 'kennel').length,
                    cattery: allLocations.filter(loc => loc.type === 'cattery').length
                };
                console.log('Location type counts:', typeCounts);

                if (allLocations.length === 0) {
                    console.warn('No valid locations found after filtering.');
                    document.getElementById('locationCount').textContent = '0 locais encontrados | Tenta outra pesquisa.';
                    return;
                }

                allMarkers = [];
                allLocations.forEach(loc => {
                    const icon = icons[loc.type] || icons.default;
                    let popupContent = `<b>${loc.name}</b><br>Tipo: ${getTypeLabel(loc.type)}<br>${loc.info}`;
                    if (loc.description) popupContent += `<br><i>${loc.description}</i>`;
                    if (loc.phone) popupContent += `<br>📞 ${loc.phone}`;
                    if (loc.website) popupContent += `<br>🌐 <a href="${loc.website}" target="_blank">Website</a>`;
                    
                    const marker = L.marker([loc.lat, loc.lng], { icon: icon }).bindPopup(popupContent);
                    allMarkers.push({ marker, type: loc.type });
                });

                allMarkers.forEach(m => markerClusterGroup.addLayer(m.marker));

                try {
                    if (allMarkers.length > 0) {
                        map.fitBounds(markerClusterGroup.getBounds(), { padding: [50, 50] });
                    }
                } catch (e) {
                    console.warn('Failed to fit map bounds, using default view:', e);
                    map.setView([39.5, -8.0], 7);
                }

                document.getElementById('locationCount').textContent = `${allLocations.length} locais encontrados | Clica nos marcadores para mais informações`;
            } catch (error) {
                console.error('Erro ao carregar locais:', error);
                document.getElementById('error').style.display = 'block';
                document.getElementById('error').textContent = `Erro: ${error.message}`;
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        // Função auxiliar para obter o label do tipo
        function getTypeLabel(type) {
            const labels = {
                veterinary: 'Veterinário',
                pet_shop: 'Pet Shop',
                animal_shelter: 'Abrigo/Adoção',
                kennel: 'Canil',
                cattery: 'Gatil'
            };
            return labels[type] || type;
        }

        // Filter Locations
        function filterLocations(type) {
            console.log(`Filtering locations by type: ${type}`);
            markerClusterGroup.clearLayers();
            let filteredCount = 0;
            allMarkers.forEach(m => {
                if (type === 'all' || m.type === type) {
                    markerClusterGroup.addLayer(m.marker);
                    filteredCount++;
                }
            });
            console.log(`Filtered count: ${filteredCount}`);
            document.getElementById('locationCount').textContent = `${filteredCount} locais encontrados | Clica nos marcadores`;
            try {
                if (filteredCount > 0) {
                    map.fitBounds(markerClusterGroup.getBounds(), { padding: [50, 50] });
                } else {
                    map.setView([39.5, -8.0], 7);
                }
            } catch (e) {
                console.warn('Failed to adjust map view after filtering:', e);
                map.setView([39.5, -8.0], 7);
            }
        }

        // Clear Markers
        function clearMarkers() {
            if (markerClusterGroup) {
                markerClusterGroup.clearLayers();
            }
            allMarkers = [];
            allLocations = [];
        }

        // Get User Location
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const { latitude, longitude } = position.coords;
                    const userLocation = [latitude, longitude];
                    map.setView(userLocation, 10);
                    const marker = L.marker(userLocation, { icon: icons.black }).addTo(map)
                        .bindPopup('Você está aqui!').openPopup();
                    console.log('User location set:', userLocation);
                }, () => {
                    console.warn('Unable to get user location.');
                });
            } else {
                console.warn('Geolocation not supported by browser.');
            }
        }

        // Initialize on DOM Content Loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing map...');
            initMap();
            getUserLocation();
        });

        // Fallback para window.onload
        window.onload = function() {
            if (!map) {
                console.log('Fallback: Page loaded, initializing map...');
                initMap();
                getUserLocation();
            }
        };
    </script>
</body>
</html>