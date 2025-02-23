<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .background-radial-gradient {
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
        }
    </style>
</head>
<body class="background-radial-gradient">
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <img src="img/logo.png" class="logo" alt="logo">
                <h1 class="my-5 display-5 fw-bold text-light ">
                    Bienvenido <br />
                    <span style="color:rgb(250, 103, 45);">Regístrate ahora</span>
                </h1>
                <p class="mb-4 text-light">
                    Crea una cuenta para acceder a todas las funcionalidades.
                </p>
            </div>
            <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div class="card bg-glass">
                    <div class="card-body px-4 py-5 px-md-5">
                        <form action="procesar_signup.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" name="nombre" class="form-control" required />
                                        <label class="form-label">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" name="apellido" class="form-control" required />
                                        <label class="form-label">Apellido</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="email" name="correo" class="form-control" required />
                                <label class="form-label">Correo</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" name="carnet" id="carnet" class="form-control" readonly />
                                <label class="form-label">Carnet de Administrador</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" name="usuario" class="form-control" required />
                                <label class="form-label">Usuario</label>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="password" class="form-control" required />
                                <label class="form-label">Contraseña</label>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary btn-block mb-4">Registrarse</button>
                            <div class="text-center">
                                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector("[name='nombre']").addEventListener("input", generarCarnet);
        document.querySelector("[name='apellido']").addEventListener("input", generarCarnet);

        function generarCarnet() {
            let nombre = document.querySelector("[name='nombre']").value.trim().toUpperCase();
            let apellido = document.querySelector("[name='apellido']").value.trim().toUpperCase();
            
            if (nombre.length > 0 && apellido.length > 0) {
                let iniciales = nombre.charAt(0) + apellido.charAt(0);
                let numeros = Math.floor(100000 + Math.random() * 900000);
                document.getElementById("carnet").value = iniciales + numeros;
            }
        }
    </script>
</body>
</html>
