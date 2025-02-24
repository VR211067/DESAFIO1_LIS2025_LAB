<?php
session_start();// para verificar si el usuario ha iniciado sesión
//si no hay un usuario autenticado redirige al login 
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$archivoXML = "productos.xml";

if (file_exists($archivoXML)) { // Verificar si el archivo XML existe
    $xml = simplexml_load_file($archivoXML); 
    if (!$xml) {
        die("❌ Error al cargar el archivo XML."); // si existe algun error al cargarlo
    }
} else {
    $xml = new SimpleXMLElement("<productos></productos>"); // Si no existe se crea uno
}

// Agregar un nuevo producto al xml
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    //Obtenemos los datos del formulario y elimina espacios en blanco
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $categoria = trim($_POST['categoria']);
    $precio = trim($_POST['precio']);
    $existencias = trim($_POST['existencias']);

    // Validaciones 
    if (!preg_match('/^PROD\d{5}$/', $codigo)) die("❌ Código inválido."); //  formato "PROD" y 5 dígitos
    if (!is_numeric($precio) || $precio <= 0) die("❌ Precio no válido."); // El precio debe ser numerico y mayor que 0
    if (!ctype_digit($existencias) || $existencias < 0) die("❌ Existencias no válidas."); // Existencias deben ser números enteros y no negativos

    // Procesar imagen si se subió una
    if (!empty($_FILES["imagen"]["name"])) {
        $imagen = $_FILES["imagen"];
        $nombreImagen = "uploads/" . basename($imagen["name"]); // Ruta de la imagen guardada
        move_uploaded_file($imagen["tmp_name"], $nombreImagen); // Mover la imagen subida a la carpeta 
    } else {
        $nombreImagen = "uploads/default.jpg"; // Si no se sube imagen, asignar una por defecto
    }

    // Crear un nuevo elemento en el xml
    $producto = $xml->addChild("producto");
    $producto->addChild("codigo", $codigo);
    $producto->addChild("nombre", $nombre);
    $producto->addChild("descripcion", $descripcion);
    $producto->addChild("imagen", $nombreImagen); // Ruta de la imagen
    $producto->addChild("categoria", $categoria);
    $producto->addChild("precio", $precio);
    $producto->addChild("existencias", $existencias);

    // Guardar los cambios en el  XML
    $xml->asXML($archivoXML);

    // Redirigir a la página de administración 
    header("Location: admin.php?exito=1");
    exit();
}

// Eliminar un producto del XML
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $codigo = trim($_POST['codigo']); // código del producto a eliminar
    $productoEncontrado = false; 
    // Convertir el XML en un objeto DOM para manipularlo
    $dom = dom_import_simplexml($xml);
    // Recorrer los productos en el XML hasta encontrar el codigo que coincida y proceder a remover el producto
    foreach ($xml->producto as $producto) {
        if ((string) $producto->codigo === $codigo) { 
            $productoEncontrado = true;
            $domProducto = dom_import_simplexml($producto);
            $domProducto->parentNode->removeChild($domProducto);
            break;
        }
    }

    if ($productoEncontrado) {
        $xml->asXML($archivoXML); // Guardar cambios en el XML
        header("Location: admin.php?eliminado=1"); // Redirigir con un mensaje de éxito
    } else {
        die("❌ Producto no encontrado."); // Si no se encontró el producto, mostrar error
    }
    exit();
}

// Editar un producto existente en el XML
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    // Obtener los valores del formulario
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $categoria = trim($_POST['categoria']);
    $precio = trim($_POST['precio']);
    $existencias = trim($_POST['existencias']);

    // Cargar el archivo XML
    $productos = simplexml_load_file($archivoXML);

    // Buscar el producto en el XML y actualizar sus valores solo si el código coincide 
    foreach ($productos->producto as $producto) {
        if ((string)$producto->codigo === $codigo) {
            $producto->nombre = $nombre;
            $producto->descripcion = $descripcion;
            $producto->categoria = $categoria;
            $producto->precio = $precio;
            $producto->existencias = $existencias;

            // actualizar imagen si se cambia
            if (!empty($_FILES["imagen"]["name"])) {
                $imagen = $_FILES["imagen"];
                $nombreImagen = "uploads/" . basename($imagen["name"]);
                move_uploaded_file($imagen["tmp_name"], $nombreImagen);
                $producto->imagen = $nombreImagen; // 
            }

            break; // Salir del bucle una vez encontrado y modificado el producto
        }
    }
    $productos->asXML($archivoXML);

    // Redirigir a la página de administración 
    header("Location: admin.php?editado=1");
    exit();
}
?>
