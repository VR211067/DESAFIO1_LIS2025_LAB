<?php
session_start();
session_destroy(); //eliminar la sesion y todas sus variables
header("Location: index.php");//redirigir al usuario
exit();
?>
