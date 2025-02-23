<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$archivoXML = "productos.xml";


// Cargar el archivo XML o crear uno nuevo si no existe
if (file_exists($archivoXML)) {
    $xml = simplexml_load_file($archivoXML);
    if (!$xml) {
        die("❌ Error al cargar el archivo XML.");
    }
} else {
    $xml = new SimpleXMLElement("<productos></productos>");
}

// 🔹 Agregar nuevo producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $categoria = trim($_POST['categoria']);
    $precio = trim($_POST['precio']);
    $existencias = trim($_POST['existencias']);

    // Validaciones básicas
    if (!preg_match('/^PROD\d{5}$/', $codigo)) die("❌ Código inválido.");
    if (!is_numeric($precio) || $precio <= 0) die("❌ Precio no válido.");
    if (!ctype_digit($existencias) || $existencias < 0) die("❌ Existencias no válidas.");

    // Procesar imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $imagen = $_FILES["imagen"];
        $nombreImagen = "uploads/" . basename($imagen["name"]);
        move_uploaded_file($imagen["tmp_name"], $nombreImagen);
    } else {
        $nombreImagen = "uploads/default.jpg"; // Imagen por defecto si no se sube ninguna
    }

    // Crear nuevo producto en XML
    $producto = $xml->addChild("producto");
    $producto->addChild("codigo", $codigo);
    $producto->addChild("nombre", $nombre);
    $producto->addChild("descripcion", $descripcion);
    $producto->addChild("imagen", $nombreImagen);
    $producto->addChild("categoria", $categoria);
    $producto->addChild("precio", $precio);
    $producto->addChild("existencias", $existencias);

    // Guardar cambios
    $xml->asXML($archivoXML);
    header("Location: admin.php?exito=1");
    exit();
}

// 🔹 Eliminar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $codigo = trim($_POST['codigo']);
    $productoEncontrado = false;

    // Buscar y eliminar el producto
    $dom = dom_import_simplexml($xml);
    foreach ($xml->producto as $producto) {
        if ((string) $producto->codigo === $codigo) {
            $productoEncontrado = true;
            $domProducto = dom_import_simplexml($producto);
            $domProducto->parentNode->removeChild($domProducto);
            break;
        }
    }

    if ($productoEncontrado) {
        $xml->asXML($archivoXML);
        header("Location: admin.php?eliminado=1");
    } else {
        die("❌ Producto no encontrado.");
    }
    exit();
}

// 🔹 Editar producto existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $categoria = trim($_POST['categoria']);
    $precio = trim($_POST['precio']);
    $existencias = trim($_POST['existencias']);

    // Cargar XML
    $archivoXML = 'productos.xml';
    $productos = simplexml_load_file($archivoXML);
    
    // Buscar el producto en el XML
    foreach ($productos->producto as $producto) {
        if ((string)$producto->codigo === $codigo) {
            $producto->nombre = $nombre;
            $producto->descripcion = $descripcion;
            $producto->categoria = $categoria;
            $producto->precio = $precio;
            $producto->existencias = $existencias;

            // Procesar nueva imagen si se sube
            if (!empty($_FILES["imagen"]["name"])) {
                $imagen = $_FILES["imagen"];
                $nombreImagen = "uploads/" . basename($imagen["name"]);
                move_uploaded_file($imagen["tmp_name"], $nombreImagen);
                $producto->imagen = $nombreImagen; // Actualizar imagen en XML
            }

            break;
        }
    }

    // Guardar cambios en el XML
    $productos->asXML($archivoXML);
    header("Location: admin.php?editado=1");
    exit();
}


?>
