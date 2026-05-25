<?php
$session = session();
$emp_evento   = $session->get('emp_evento');
$emp_carreras = $session->get('emp_carreras');
$emp_noticia  = $session->get('emp_noticia');
$errorMsg = isset($error) ? $error : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Rol - Empleado</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/inicioEmpleado.css'); ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
  <!-- Contenedor de error tipo "toast", posicionado fijo a 100px del tope -->
  <?php if (!empty($errorMsg)): ?>
    <div id="error-container" class="error-container">
      <p><?= $errorMsg; ?></p>
    </div>
  <?php endif; ?>

  <main class="contenedor-principal">
    <h2>Gestionar Rol</h2>
    <p>Aquí puedes gestionar los apartados según tus permisos.</p>
    
    <section class="tarjetas-gestor">
      <!-- Tarjeta de Eventos -->
      <div class="tarjeta" id="tarjeta-eventos" data-url="Empleado/Eventos">
        <a href="<?= base_url('Empleado/Eventos'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/eventos.jpg'); ?>" alt="Eventos" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Eventos</h3>
              <p>Gestiona los eventos de la empresa.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Tarjeta de Carreras -->
      <div class="tarjeta" id="tarjeta-carreras" data-url="Empleado/Carreras">
        <a href="<?= base_url('Empleado/Carreras'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/carreras.jpg'); ?>" alt="Carreras" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Carreras</h3>
              <p>Gestiona las carreras de karting.</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Tarjeta de Noticias -->
      <div class="tarjeta" id="tarjeta-noticias" data-url="Empleado/Noticias">
        <a href="<?= base_url('Empleado/Noticias'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/noticias.jpg'); ?>" alt="Noticias" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Noticias</h3>
              <p>Gestiona las noticias de la plataforma.</p>
            </div>
          </div>
        </a>
      </div>
    </section>
  </main>

  <!-- Inyección de variables JS para los permisos -->
  <script>
    var base_url = "<?= base_url(); ?>";
    var emp_evento   = <?= $emp_evento ? 'true' : 'false' ?>;
    var emp_carreras = <?= $emp_carreras ? 'true' : 'false' ?>;
    var emp_noticia  = <?= $emp_noticia ? 'true' : 'false' ?>;
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/inicioEmpleado.js'); ?>"></script>
</body>
</html>
