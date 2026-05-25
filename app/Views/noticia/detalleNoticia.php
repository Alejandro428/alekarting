<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($noticia['titulo']) ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/detalleNoticia.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
  <!-- Botón de regresar siempre visible -->
  <a class="back-button" href="<?= base_url('Noticias') ?>">← Regresar</a>

  <!-- Layout principal: Sidebars y Contenido de la noticia -->
  <div class="layout-container">
    <!-- Sidebar Izquierdo (Anuncio) -->
    <aside class="sidebar">
      <h2>Anuncio Izquierdo</h2>
      <p>Espacio para publicidad.</p>
    </aside>

    <!-- Contenido Principal: Noticia -->
    <main class="detalle-noticia" data-noticia-id="<?= esc($noticia['id']) ?>">
      <div class="detalle-container">
        <!-- Imagen de la noticia como background -->
        <div class="imagen-container" style="background-image: url('<?= base_url('assets/imagenes/noticias/imgs/' . ($noticia['imagen'] ? $noticia['imagen'] : 'ejemplo1Noticia.jpg')) ?>');"></div>
        
        <!-- Contenido textual -->
        <div class="detalle-contenido">
          <h2 class="detalle-titulo"><?= esc($noticia['titulo']) ?></h2>
          <h3 class="detalle-subtitulo"><?= esc($noticia['subtitulo']) ?></h3>

          <?php
            // Formateamos la fecha en el formato dd-mm-yyyy
            $fecha = new DateTime($noticia['fecha_publicacion']);
            $fecha_formateada = $fecha->format('d-m-Y');
          ?>
          <p class="detalle-fecha">Publicado el: <?= esc($fecha_formateada) ?></p>

          <p class="detalle-categoria">Categoría: <?= esc($noticia['nombre_categoria']) ?></p>
          <div class="detalle-texto">
            <?= nl2br($noticia['contenido']) ?>
          </div>

          <!-- Sección del video -->
          <?php if (!empty($noticia['video'])): ?>
            <div class="detalle-video">
              <h3>Video relacionado</h3>
              <video controls width="100%">
                <source src="<?= base_url('assets/imagenes/noticias/videos/' . $noticia['video']) ?>" type="video/mp4">
                Tu navegador no soporta el elemento de video.
              </video>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </main>

    <!-- Sidebar Derecho (Anuncio) -->
    <aside class="sidebar">
      <h2>Anuncio Derecho</h2>
      <p>Espacio para publicidad.</p>
    </aside>
  </div>

  <!-- Variable global para la URL base -->
  <script>var base_url = "<?= base_url() ?>";</script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/detalleNoticia.js') ?>"></script>

</body>
</html>
