<?php
session_start();//iniciamos para guardar errores o datos del usuario
$archivoXML = "usuarios.xml";

if (!file_exists($archivoXML)) {
    $xml = new SimpleXMLElement("<usuarios></usuarios>");
} else {
    $xml = simplexml_load_file($archivoXML);
}

//captura de datos para registrar un usuario
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$correo = $_POST["correo"];
$carnet = $_POST["carnet"];
$usuario = $_POST["usuario"];
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);

// Validar que el usuario no exista
foreach ($xml->usuario as $u) {
    if ($u->usuario == $usuario) {
        $_SESSION["error"] = "El usuario ya existe.";
        header("Location: signup.php");
        exit();
    }
}

// Agregar nuevo usuario
$nuevoUsuario = $xml->addChild("usuario");
$nuevoUsuario->addChild("nombre", $nombre);
$nuevoUsuario->addChild("apellido", $apellido);
$nuevoUsuario->addChild("correo", $correo);
$nuevoUsuario->addChild("carnet", $carnet);
$nuevoUsuario->addChild("usuario", $usuario);
$nuevoUsuario->addChild("password", $password);

//guardamos el xml actualizado y automaticamente se inicia sesion del usuario
$xml->asXML($archivoXML);
$_SESSION["usuario"] = $usuario;
header("Location: admin.php");
exit();
?>
