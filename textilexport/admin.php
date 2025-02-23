<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$archivoXML = "productos.xml";
$xml = file_exists($archivoXML) ? simplexml_load_file($archivoXML) : new SimpleXMLElement("<productos></productos>");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="admin-body">
        <nav class="navbar navbar-dark fixed-top">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Administración de Productos</span>
                <a href="logout.php" class="btn btn-cerrar">Cerrar Sesión</a>
            </div>
        </nav>


    <div class="container mt-4">
    <h2 class="text-center">Productos Registrados</h2>
    <button class="btn btn-agregar mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Producto</button>

    <?php
    // Agrupar productos por categoría
    $productosPorCategoria = [];
    foreach ($xml->producto as $producto) {
        $categoria = (string) $producto->categoria;
        if (!isset($productosPorCategoria[$categoria])) {
            $productosPorCategoria[$categoria] = [];
        }
        $productosPorCategoria[$categoria][] = $producto;
    }
    ?>

    <?php foreach ($productosPorCategoria as $categoria => $productos): ?>
        <h3 class="categoria-header"><?= $categoria ?></h3>
        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Existencias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto->codigo ?></td>
                    <td><?= $producto->nombre ?></td>
                    <td><img src="<?= $producto->imagen ?>" width="50"></td>
                    <td>$<?= $producto->precio ?></td>
                    <td><?= $producto->existencias ?></td>
                    <td>
                        <form action="procesar.php" method="POST" style="display:inline;">
                            <input type="hidden" name="codigo" value="<?= $producto->codigo ?>">
                            <button type="submit" class="btn btn-eliminar btn-sm" name="eliminar">Eliminar</button>
                        </form>
                        <button class="btn btn-editar btn-sm editar" 
                                data-codigo="<?=  $producto->codigo ?>" 
                                data-nombre="<?=  $producto->nombre ?>" 
                                data-descripcion="<?=  $producto->descripcion ?>"
                                data-imagen="<?=  $producto->imagen ?>"
                                data-categoria="<?=  $producto->categoria ?>"
                                data-precio="<?= $producto->precio ?>" 
                                data-existencias="<?=   $producto->existencias ?>"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditar">
                            Editar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

    <!-- Modal para Agregar Producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="procesar.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo" required pattern="PROD\d{5}" title="Debe seguir el formato PROD00000">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-control" name="categoria" required>
                                <option value="Textil">Textil</option>
                                <option value="Promocionales">Promocionales</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="existencias" class="form-label">Existencias</label>
                            <input type="number" class="form-control" name="existencias" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="procesar.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="codigo" id="editCodigo">
                    
                    <label>Nombre:</label>
                    <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    
                    <label>Descripción:</label>
                    <input type="text" name="descripcion" id="editDescripcion" class="form-control">
                    
                    <label>Imagen Actual:</label>
                    <img id="editImagenPreview" src="" width="100"><br>
                    <label>Subir Nueva Imagen:</label>
                    <input type="file" name="imagen" class="form-control">

                    <label>Categoría:</label>
                    <input type="text" name="categoria" id="editCategoria" class="form-control" required>

                 
                    <label>Precio:</label>
                    <input type="number" name="precio" step="0.01" id="editPrecio" class="form-control" required>

                    <label>Existencias:</label>
                    <input type="number" name="existencias" id="editExistencias" class="form-control" required>
                    
                   
                    
                    <button type="submit" name="editar" class="btn btn-primary mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
