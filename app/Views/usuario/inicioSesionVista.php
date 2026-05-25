<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión - AleKarting</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/inicioSesionVista.css') ?>">
  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/inicioSesionVista.js') ?>"></script>
</head>
<body>

  <!-- Elemento para la imagen de fondo -->
  <div class="background-image">
    <img src="<?= base_url('assets/imagenes/imagenInicioSesionMod.webp') ?>" alt="Imagen de fondo">
  </div>

  <section class="login-container">
    <!-- Columna Izquierda: Bienvenida, Logo, Texto, Redes Sociales -->
    <div class="login-left">
      <h1 class="titulo-bienvenida">
        Bienvenido a <span>AleKarting</span>
      </h1>
      <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo AleKarting" class="logo-empresa">
      <p class="texto-descriptivo">
        Prepárate para una experiencia extraordinaria
      </p>
      <p class="texto-descriptivo">
        Síguenos en nuestras redes sociales
      </p>
      <div class="redes-sociales">
        <a href="https://facebook.com" target="_blank">
          <img src="<?= base_url('assets/imagenes/facebook.png') ?>" alt="Facebook">
        </a>
        <a href="https://twitter.com" target="_blank">
          <img src="<?= base_url('assets/imagenes/twitter.png') ?>" alt="Twitter">
        </a>
        <a href="https://instagram.com" target="_blank">
          <img src="<?= base_url('assets/imagenes/instagram.png') ?>" alt="Instagram">
        </a>
      </div>
    </div>

    <!-- Columna Derecha: Formulario de Inicio de Sesión -->
    <div class="login-right">
      <h2 class="titulo-sesion">Iniciar Sesión</h2>
      <p class="texto-pequeno">
        ¿No tienes una cuenta creada? 
        <a href="<?= base_url('Registro') ?>">Crea una cuenta</a>.
      </p>

      <!-- Contenedor global para mensajes -->
      <?php 
          $mensaje = "";
          if (session()->has('error')) {
              $mensaje = session('error'); // Mensaje desde el backend
          } else if(isset($_GET['mensaje'])) {
              if($_GET['mensaje'] === 'expirada'){
                  $mensaje = "Sesión expirada por inactividad.";
              } else if($_GET['mensaje'] === 'cerrada'){
                  $mensaje = "Sesión cerrada correctamente.";
              }
          }
      ?>
      <?php if(!empty($mensaje)): ?>
          <div id="mensajeErrorGeneral" class="mensaje-registro mensaje-negativo">
              <label><?= $mensaje ?></label>
          </div>
      <?php else: ?>
          <div id="mensajeErrorGeneral" class="mensaje-registro mensaje-negativo" style="display: none;"></div>
      <?php endif; ?>


      <form action="#" method="POST" class="form-login">
        <label for="usuario">Usuario</label>
        <div class="input-container">
          <input type="text" id="usuario" name="usuario" placeholder="Tu usuario">
          <span class="mensaje-error" id="error-usuario"></span>
        </div>

        <label for="password">Contraseña</label>
        <div class="input-container">
          <input type="password" id="password" name="password" placeholder="Tu contraseña">
          <span class="mensaje-error" id="error-password"></span>
        </div>

        <button type="submit" class="btn-iniciar">Iniciar Sesión</button>
      </form>

      <p class="texto-pequeno">
        ¿Nuevo usuario? <a href="<?= base_url('Registro') ?>">Regístrate</a>.
      </p>
      <a href="<?= base_url('Recuperar') ?>">Recuperar Contraseña</a>
      <a href="<?= base_url('Inicio') ?>" class="btn-back">Regresar a Inicio</a>
    </div>
  </section>
  
</body>
</html>
