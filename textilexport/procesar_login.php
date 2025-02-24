<?php
session_start();
$archivoXML = "usuarios.xml";//archivo con usuarios registrados

//verificamos si el xml existe
if (!file_exists($archivoXML)) {
    $_SESSION["error"] = "No hay usuarios registrados.";
    header("Location: login.php");
    exit();
}

//cargamos el xml y obtenemos los valores del form login
$xml = simplexml_load_file($archivoXML);
$usuario = $_POST["usuario"];
$password = $_POST["password"];

//recorremos los usuarios para comprobar que existe y guardarlo en la sesion
foreach ($xml->usuario as $u) {
    if ($u->usuario == $usuario && password_verify($password, $u->password)) {
        $_SESSION["usuario"] = (string)$u->usuario;
        header("Location: admin.php");
        exit();
    }
}

$_SESSION["error"] = "Usuario o contraseña incorrectos.";
header("Location: login.php");//regresa al login si las credenciales no existen
exit();
?>
