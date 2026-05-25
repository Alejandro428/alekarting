<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sección de Noticias</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/noticiaVista.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
<script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
<?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
<?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</head>
<body>
  <header class="header-noticias position-relative text-center">
  <h1 class="titulo-noticias mb-0">Noticias</h1>
   <button 
      id="btnAyudaNoticias" 
      type="button" 
      class="btn btn-outline-info position-absolute top-50 end-0 translate-middle-y me-3"
      title="Ayuda">
      <i class="bi bi-question-circle"></i>
    </button>
</header>

<!-- Contenedor de filtros centrados en la misma línea -->
<div class="container my-4 d-flex justify-content-center align-items-center gap-4 flex-wrap">

  <!-- Filtro de categorías -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-categorias" class="mb-1">Filtrar por:</label>
    <select id="filtro-categorias" class="form-select">
      <option value="todas">Todas las categorías</option>
    </select>
  </div>

  <!-- Filtro de orden por fecha -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-fecha" class="mb-1">Ordenar por fecha:</label>
    <select id="filtro-fecha" class="form-select">
      <option value="recientes">Más recientes</option>
      <option value="antiguas">Más antiguas</option>
    </select>
  </div>

  <!-- Buscador -->
  <div class="d-flex flex-column align-items-center">
    <label for="filtro-nombre-noticia" class="mb-1">Buscar noticia:</label>
    <div class="position-relative">
      <i class="fas fa-search position-absolute start-0 ms-3 text-secondary" style="top: 50%; transform: translateY(-50%);"></i>
      <input type="text" id="filtro-nombre-noticia" class="form-control ps-5" placeholder="Buscar noticia...">
    </div>
  </div>

  <!-- Botón para limpiar filtros -->
  <div class="d-flex flex-column align-items-center">
    <label class="mb-1 text-transparent">Limpiar</label> <!-- etiqueta invisible para alinear -->
    <button id="btn-limpiar-filtros" class="btn btn-outline-secondary">Limpiar filtros</button>
  </div>

</div>



  <div class="container">
    <div class="noticias-izquierda">
      <!-- Aquí se mostrarán las tarjetas de noticias principales -->
    </div>

    <div class="noticias-derecha">
      <h3>Noticias Populares</h3>
      <!-- Aquí se mostrarán las tarjetas de noticias populares -->
    </div>
  </div> <!-- Fin .container -->

  <?= view('noticia/mostrarAyudaNoticia') ?>

  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/js/noticiaVista.js') ?>"></script>
</body>
</html>
