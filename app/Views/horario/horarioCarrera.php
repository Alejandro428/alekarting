<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Calendario de Carreras</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/horarioCarrera.css') ?>" />
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon" />
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</head>
<body>
  <!-- Header -->
  <header class="header-carrera" style="position: relative;">
    <h1 class="titulo-carrera">Calendario de Carreras</h1>
    <!-- Botón ayuda dentro del header para que esté bien posicionado -->
    <button
      id="btnAyudaCarreras"
      type="button"
      class="btn btn-outline-info position-absolute top-50 end-0 translate-middle-y me-3"
      title="Ayuda">
      <i class="bi bi-question-circle"></i>
  </button>
</header>

  <!-- Contenedor -->
  <div class="contenedor">
    <div class="distribucion-carrera">
      <!-- Columna Izquierda: Calendario de Carreras -->
      <div id="calendarioCarreras-container" class="contenedor-izquierdo" style="position: relative;">
        <h2>Calendario de Carreras</h2>
        <div id="calendarioCarreras"></div>
        <div id="reservas">
          <!-- Aquí se mostrarán las reservas para el día seleccionado -->
        </div>
      </div>
    </div>
  </div>
  <?= view('horario/mostrarAyudaCarrera') ?>
  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/js/horarioCarrera.js') ?>"></script>
</body>
</html>
