<?php session_start(); ?> <!--iniciamos sesion por variables(los errores se guardan en sesion)-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
        .background-radial-gradient {
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
        }
    </style>
<body class="background-radial-gradient">

<section >
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            
            <!-- Formulario de Login -->
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <img src="img/logo.png"
                    style="width: 185px;" alt="logo">
                  <h4 class="mt-1 mb-5 pb-1">Bienvenido</h4>
                </div>

                <form action="procesar_login.php" method="POST">
                  <p>Por favor, inicia sesión en tu cuenta</p>

                  <div class="form-outline mb-4">
                    <input type="text" name="usuario" id="usuario" class="form-control" required />
                    <label class="form-label" for="usuario">Usuario</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="password" class="form-control" required />
                    <label class="form-label" for="password">Contraseña</label>
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit" name="login">Iniciar Sesión</button>
                   
                  </div>

                  <div class="d-flex align-items-center justify-content-center pb-4">
                    <p class="mb-0 me-2">¿No tienes cuenta?</p>
                    <a href="signup.php" class="btn btn-outline-danger">Crear cuenta</a>
                  </div>

                </form>

                <!-- mostrar errores y quitar alerta de errores cuando se resuelva -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger mt-3"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

              </div>
            </div>

            <!-- Sección de información -->
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4">¡Bienvenido a nuestra plataforma!</h4>
                <p class="small mb-0">Accede a tu cuenta y gestiona tus productos de manera sencilla y eficiente.</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</body>
</html>
