<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario de Eventos</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/horarioEvento.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</head>
<body>
  <!-- Header -->
  <header class="header-evento" style="position: relative;">
    <h1 class="titulo-evento">Calendario de Eventos</h1>
    <!-- Botón ayuda -->
    <button 
      id="btnAyudaEventos" 
      type="button" 
      class="btn btn-outline-info position-absolute top-50 end-0 translate-middle-y me-3"
      title="Ayuda">
      <i class="bi bi-question-circle"></i>
    </button>
  </header>

  <!-- Contenedor -->
  <div class="contenedor">
    <div class="distribucion-evento">
      <!-- Columna Derecha: Calendario de Eventos -->
      <div id="calendarioEventos-container" class="contenedor-derecho">
        <h2>Calendario de Eventos</h2>
        <div id="calendarioEventos"></div>
        <div id="detalles">
          <!-- Aquí se mostrarán los detalles para el día seleccionado -->
        </div>
      </div>
    </div>
  </div>
  <?= view('horario/mostrarAyudaEvento') ?>

  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/js/horarioEvento.js') ?>"></script>
</body>
</html>
