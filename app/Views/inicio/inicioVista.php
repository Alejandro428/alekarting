<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
      <!-- inicioVista.php -->
    <link rel="stylesheet" href="<?= base_url('assets/css/inicioVista.css') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap">
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/favicon.ico') ?>">
</head>
<body>
    <script>console.log("<?= base_url('public/favicon.ico') ?>")</script>
    <div class="inicio-contenedor">
      <!-- Sección Hero -->
      <section class="hero">
      <!-- Texto sobre la imagen -->
      <div class="hero-texto">
        <h1>AleKarting</h1>
        <h2>Diversión y Velocidad para Todos</h2>
        <p>
          En AleKarting tenemos la pista perfecta para disfrutar a máxima velocidad,
          sin importar tu edad o experiencia. Vive la emoción de cada curva y comparte
          momentos únicos con amigos y familiares.
        </p>
        <a href="<?= base_url('Carreras') ?>" class="btn-hero">¡Descúbrelo tú mismo!</a>
      </div>

      <!-- Contenedor para la imagen -->
      <div class="hero-imagen">
        <img src="<?= base_url('assets/imagenes/inicio1ImagenMod.webp') ?>" alt="Imagen de Karting">
      </div>
    </section>


      <!-- Sección de Apartados Generales -->
      <section class="apartados-generales">
        <h2>Apartados Generales</h2>
        <div class="cards-container">
          <!-- Tarjeta de Eventos -->
          <div class="card">
            <a href="<?= base_url('Eventos') ?>" class="card-link">
            <img src="<?= base_url('assets/imagenes/eventos.jpg') ?>" alt="Eventos">
            <h3>Eventos</h3>
            <p>Descubre los eventos que organizamos, ¡ven a vivir la emoción!</p>
            </a>
          </div>
          <!-- Tarjeta de Carreras -->
          <div class="card">
            <a href="<?= base_url('Carreras') ?>" class="card-link">
            <img src="<?= base_url('assets/imagenes/carreras.jpg') ?>" alt="Carreras">
            <h3>Carreras</h3>
            <p>Prueba nuestras carreras de karting y compite con tus amigos.</p>
            </a>
          </div>
          <!-- Tarjeta de Noticias -->
          <div class="card">
            <a href="<?= base_url('Noticias') ?>" class="card-link">
            <img src="<?= base_url('assets/imagenes/noticias.jpg') ?>" alt="Noticias">
            <h3>Noticias</h3>
            <p>Mantente informado de las últimas novedades de AleKarting.</p>
            </a>
          </div>
        </div>
      </section>

      <section class="banner-promocional">
  <!-- Capa de overlay para oscurecer el fondo -->
  <div class="banner-overlay"></div>
  <div class="banner-contenido">
    <!-- Columna Izquierda: Imagen o Logo -->
    <div class="banner-izquierda">
      <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo AleKarting">
    </div>
    <!-- Columna Derecha: Texto y Llamado a la Acción -->
    <div class="banner-derecha">
      <h2>¡Vive la Pasión del Motor!</h2>
      <p>Descubre todo lo que AleKarting ofrece para los amantes de la velocidad: desde emocionantes carreras hasta eventos exclusivos y las últimas noticias del automovilismo.</p>
      <ul>
        <!-- Carreras -->
        <li><strong>Carreras:</strong> Compite con los mejores y vive la adrenalina</li>
        <!-- Eventos -->
        <li><strong>Eventos:</strong> Regístrate y participa en actividades únicas</li>
        <!-- Noticias -->
        <li><strong>Noticias:</strong> Mantente al día con el mundo del motor</li>
      </ul>
      <div class="botones-banner">
        <a href="<?= base_url('Carreras') ?>" class="btn-banner">Ver Carreras</a>
        <a href="<?= base_url('Eventos') ?>" class="btn-banner">Ver Eventos</a>
        <a href="<?= base_url('Noticias') ?>" class="btn-banner">Noticias</a>
      </div>
    </div>
  </div>
</section>
  </div>
    
</body>
</html>
