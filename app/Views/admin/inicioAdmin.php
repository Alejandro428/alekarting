<?php
$session = session();
$errorMsg = isset($error) ? $error : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/inicioAdmin.css'); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
  <!-- Notificación de error tipo "toast" -->
  <?php if (!empty($errorMsg)): ?>
    <div id="error-container" class="error-container">
      <p><?= $errorMsg; ?></p>
    </div>
  <?php endif; ?>

  <main class="contenedor-principal">
    <h2>Panel de Administración</h2>
    <p>Gestiona la plataforma y sus usuarios.</p>

    <!-- Tarjetas de disponibilidad de empleados -->
    <section class="empleados-disponibles">
    <div class="contenedor-tarjetas-empleados">
        <div class="tarjeta-empleado">
            <h3>Eventos</h3>
            <div id="empleados-eventos" class="estado-empleado">Cargando...</div>
        </div>
        <div class="tarjeta-empleado">
            <h3>Carreras</h3>
            <div id="empleados-carreras" class="estado-empleado">Cargando...</div>
        </div>
        <div class="tarjeta-empleado">
            <h3>Noticias</h3>
            <div id="empleados-noticias" class="estado-empleado">Cargando...</div>
        </div>
    </div>
</section>

    <!-- Sección: Tarjetas de Gestión -->
    <section class="tarjetas-gestor">
      <div class="tarjeta">
        <a href="<?= base_url('Admin/gestionEventos'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/eventos.jpg'); ?>" alt="Eventos" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Eventos</h3>
              <p>Gestiona los eventos de la empresa.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="tarjeta">
        <a href="<?= base_url('Admin/gestionCarreras'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/carreras.jpg'); ?>" alt="Carreras" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Carreras</h3>
              <p>Gestiona las carreras de karting.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="tarjeta">
        <a href="<?= base_url('Admin/gestionNoticias'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/noticias.jpg'); ?>" alt="Noticias" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Noticias</h3>
              <p>Gestiona las noticias de la plataforma.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="tarjeta">
        <a href="<?= base_url('Admin/gestionUsuarios'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/usuarios.png'); ?>" alt="Usuarios" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Usuarios</h3>
              <p>Administra a los usuarios del sistema.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="tarjeta">
        <a href="<?= base_url('Admin/gestionEmpleados'); ?>">
          <div class="tarjeta-img-container">
            <img src="<?= base_url('assets/imagenes/empleado.jpg'); ?>" alt="Empleados" class="tarjeta-img">
            <div class="tarjeta-info">
              <h3>Empleados</h3>
              <p>Administra a los empleados de la plataforma.</p>
            </div>
          </div>
        </a>
      </div>
    </section>
  </main>
  <script>
    var base_url = "<?= base_url(); ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/inicioAdmin.js'); ?>"></script>
</body>
</html>