<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <!-- Agregar FontAwesome para el icono -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/eventoVista.css') ?>">
  <!-- En el head -->
<script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
<link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
<?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</head>
<body>
   <header class="header-eventos" style="position: relative;">
    <h1 class="titulo-eventos">Próximos Eventos</h1>
    <!-- Botón ayuda -->
    <button 
      id="btnAyudaEventos" 
      type="button" 
      class="btn btn-outline-info position-absolute top-50 end-0 translate-middle-y me-3"
      title="Ayuda">
      <i class="bi bi-question-circle"></i>
    </button>
  </header>

<!-- Filtro de Eventos con Bootstrap -->
<div class="container my-4 d-flex justify-content-center align-items-end gap-4 flex-wrap">
  
  <!-- Tipo de evento -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-tipo-evento" class="form-label">Tipo de evento:</label>
    <select id="filtro-tipo-evento" class="form-select">
      <option value="todos">Todos</option>
    </select>
  </div>

  <!-- Buscar evento -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-nombre-evento" class="form-label">Buscar evento</label>
    <div class="input-group">
      <input type="text" id="filtro-nombre-evento" class="form-control" placeholder="Buscar evento...">
      <span class="input-group-text"><i class="fas fa-search"></i></span>
    </div>
  </div>

  <!-- Ordenar por fecha -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-fecha-evento" class="form-label">Ordenar por fecha:</label>
    <select id="filtro-fecha-evento" class="form-select">
      <option value="recientes">Más recientes</option>
      <option value="antiguas">Más antiguas</option>
    </select>
  </div>

  <!-- Checkbox de plazas -->
  <div class="d-flex flex-column align-items-center">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="filtro-no-completos">
      <label class="form-check-label" for="filtro-no-completos">
        Disponibles
      </label>
    </div>
  </div>

  <!-- Botón limpiar -->
  <div class="d-flex flex-column align-items-center">
    <button id="btn-limpiar-filtros" class="btn btn-outline-secondary">Limpiar filtros</button>
  </div>
</div>

  <!-- Contenedor principal de Eventos -->
  <div class="contenedor-eventos" id="eventos-container">
    <!-- Aquí se pintarán las tarjetas de evento dinámicamente -->
  </div>

  <?= view('evento/mostrarAyudaEvento') ?>
  <script>
    var base_url = "<?= base_url() ?>";
    var urlCondiciones = "<?= base_url('Condiciones') ?>";
  </script>
  <script src="<?= base_url('assets/js/eventoVista.js') ?>"></script>
</body>
</html>