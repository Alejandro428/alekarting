<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carreras - Reserva</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fuentes de Google -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
  <!-- Enlaza el CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/carreraVista.css') ?>">
   <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
  <!-- Cabecera fuera del contenedor para que quede pegada al tope -->
<header class="header-carrera" style="position: relative;">
  <h1 class="titulo-carrera">Reserva tu Carrera</h1>
  <!-- Botón de ayuda añadido -->
  <button
    id="btnAyudaCarreras"
    type="button"
    class="btn btn-outline-info position-absolute top-50 end-0 translate-middle-y me-3"
    title="Ayuda">
    <i class="bi bi-question-circle"></i>
  </button>
</header>
  
  <div class="contenedor">
    <div class="distribucion-carrera">
      
      <!-- SECCIÓN IZQUIERDO: Agrupación de toda la información de la carrera -->
      <div class="contenedor-izquierdo">
        <div class="informacion-carrera">
          <!-- BLOQUE DE TEXTO INFORMATIVO -->
          <div class="texto-carrera">
            <h3>Información Importante</h3>
            <p>La edad mínima para participar es de 12 años.</p>
            <p>Los menores de 18 años deben ir acompañados por un adulto.</p>
            <p>Es obligatorio el uso de:</p>
            <ul>
              <li>Casco de seguridad (proporcionado en el lugar).</li>
              <li>Ropa adecuada.</li>
              <li>Calzado cerrado.</li>
            </ul>
          </div>

          <!-- Información dinámica de selección -->
          <div id="infoSeleccionado">
            <p id="diaSeleccionado">Día seleccionado: Aún no se ha seleccionado</p>
            <p id="fechaSeleccionada">Fecha: Aún no se ha seleccionado</p>
            <p id="horaSeleccionada">Hora: Aún no se ha seleccionado</p>
          </div>

          <h2>Información de la Carrera</h2>
          <!-- Select dinámico: se cargarán las pistas -->
          <select id="pistaElegida"></select>
          <div class="cantidad-personas">
            <button id="botonRestar">-</button>
            <input type="number" id="inputCantidad" value="1" min="1" max="20">
            <button id="botonSumar">+</button>
          </div>
          <div class="precio-por-persona" id="divPrecio">Precio: 0€/persona</div>
          <div class="total" id="spanTotal">Total: 0€</div>
          <a href="#" class="btn-reservar" id="btnReservar">Reservar</a>
        </div>
      </div>

      <!-- SECCIÓN DERECHA: Calendario y Franjas Horarias -->
      <div class="lado-derecho">
        <div class="calendario-container">
          <h3>Elige una fecha</h3>
          <div id="calendario"></div>
        </div>
        <div class="franjas-container">
          <h3>Elige una hora</h3>
          <div id="timeSlotsContainer">
            <!-- Aquí se cargarán las franjas horarias -->
          </div>
        </div>
      </div>

    </div>
  </div>
  <?= view('carrera/mostrarAyudaCarrera') ?>
  <!-- Definir la variable base_url -->
  <script>
    var base_url = "<?= base_url() ?>";
    var usuarioId = "<?= session()->get('id') ?>"; 
    var urlCondiciones = "<?= base_url('Condiciones') ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <!-- Enlaza el JavaScript -->
  <script src="<?= base_url('assets/js/carreraVista.js') ?>"></script>
</body>
</html>