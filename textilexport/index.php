<?php
session_start(); // Inicia la sesión para gestionar datos de usuario 

$archivoXML = "productos.xml"; // Archivo donde están almacenados los productos

$xml = file_exists($archivoXML) ? simplexml_load_file($archivoXML) : new SimpleXMLElement("<productos></productos>");


$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : "all";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - TextilExport</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>

    <!-- Navbar Offcanvas -->
    <nav class="navbar navbar-dark bg-black fixed-top">
        <div class="container-fluid">
            
            <a class="navbar-brand text-light" href="index.php">
                <img src="img/logo.png" alt="Logo" style="height: 80px; margin-right: 5px;"> TextilExport
            </a>
            <!-- Botón para abrir el menú lateral -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú lateral Offcanvas -->
            <div class="offcanvas offcanvas-end bg-dark text-light" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">TextilExport</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                       
                        <li class="nav-item">
                            <a class="nav-link text-light" href="index.php">HOME</a>
                        </li>
                        <!-- Menú desplegable de categorías -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown">
                                CATEGORÍAS
                            </a>
                            <ul class="dropdown-menu bg-dark">
                                <li><a class="dropdown-item text-light" href="index.php?categoria=Textil">TEXTIL</a></li>
                                <li><a class="dropdown-item text-light" href="index.php?categoria=Promocionales">PROMOCIONALES</a></li>
                            </ul>
                        </li>
                    </ul>
                    <hr>
                    <!-- Botón de inicio de sesión -->
                    <a href="login.php" class="btn btn-iniciars w-100">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Carrusel de imágenes -->
    <div id="carouselExampleIndicators" class="carousel slide mt-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/Banner1.png" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="img/Banner2.png" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="img/Banner3.png" class="d-block w-100" alt="Banner 3">
            </div>
        </div>
        <!-- Botones de navegación del carrusel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Sección de productos -->
    <div class="container mt-4">
        <h1 class="text-center">Catálogo de Productos</h1> 
        <!-- Barra de búsqueda -->
        <input type="text" id="buscador" class="form-control w-50 mx-auto mt-3" placeholder="Buscar productos...">
        <div class="row" id="productos-container">
            <?php foreach ($xml->producto as $producto): ?>
                <?php if ($categoriaSeleccionada === "all" || $producto->categoria == $categoriaSeleccionada): ?>
                <div class="col-md-4 mb-4 producto" data-categoria="<?= $producto->categoria ?>">
                    <div class="card h-100 text-center bg-dark text-light">
                        <img src="<?= $producto->imagen ?>" class="card-img-top img-fluid" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $producto->nombre ?></h5>
                            <p class="card-text"><?= $producto->descripcion ?></p>
                            <p class="text-warning fw-bold">$<?= $producto->precio ?></p>
                            <p class="<?= $producto->existencias > 0 ? 'text-warning' : 'text-danger' ?>">
                                <?= $producto->existencias > 0 ? "Disponible" : "Producto No Disponible" ?>
                            </p>
                            <div class="d-flex justify-content-center">
                                <!-- Botón para ver detalles del producto en un modal -->
                                <button class="btn btnver-detalle ver-detalle" data-bs-toggle="modal" data-bs-target="#modalProducto" 
                                        data-codigo="<?= $producto->codigo ?>" data-nombre="<?= $producto->nombre ?>"
                                        data-descripcion="<?= $producto->descripcion ?>" data-imagen="<?= $producto->imagen ?>"
                                        data-precio="<?= $producto->precio ?>" data-existencias="<?= $producto->existencias ?>">
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal para detalles del producto -->
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImagen" class="img-fluid w-75 mb-3">
                    <p id="modalDescripcion"></p>
                    <h4 class="text-warning">$<span id="modalPrecio"></span></h4>
                    <p id="modalDisponibilidad"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 TextilExport. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

</body>
</html>
