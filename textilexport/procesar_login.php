<?php
session_start();
$archivoXML = "usuarios.xml";

if (!file_exists($archivoXML)) {
    $_SESSION["error"] = "No hay usuarios registrados.";
    header("Location: login.php");
    exit();
}

$xml = simplexml_load_file($archivoXML);
$usuario = $_POST["usuario"];
$password = $_POST["password"];

foreach ($xml->usuario as $u) {
    if ($u->usuario == $usuario && password_verify($password, $u->password)) {
        $_SESSION["usuario"] = (string)$u->usuario;
        header("Location: admin.php");
        exit();
    }
}

$_SESSION["error"] = "Usuario o contraseña incorrectos.";
header("Location: login.php");
exit();
?>
