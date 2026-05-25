<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto - AleKarting</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/contactoVista.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
  <!-- Contenedor principal de la sección de contacto -->
  <section class="contacto-contenedor">
    <img src="<?= base_url('assets/imagenes/imagenContactoMod.webp') ?>" alt="Imagen de Karting" class="imagen-fondo-contacto">

    <!-- Columna Izquierda: Formulario de contacto -->
    <div class="contacto-izquierda">
      <h2 class="titulo-formulario">Formulario de Contacto</h2>
      <div class="contenedor-formulario py-5 px-3">
  <form id="formContacto" name="formContacto" method="POST" action="<?= base_url('Correo/enviarContacto') ?>">
    <div class="container">
      <div class="row justify-content-center">
        <!-- Cambio de las clases para ocupar todo el ancho -->
        <div class="col-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12" style="max-width: 100%;">
          <div class="card border-primary shadow-lg w-100">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0">Formulario de Contacto</h4>
            </div>
            <div class="card-body p-4">

              <!-- Nombre -->
              <div class="mb-4">
                <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" placeholder="Introduce tu nombre">
                <div class="invalid-feedback">Escribe tu nombre</div>
              </div>

              <!-- Correo -->
              <div class="mb-4">
                <label for="correo" class="form-label">Correo: <span class="text-danger">*</span></label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="ejemplo@correo.com">
                <div class="invalid-feedback">Introduce un correo válido</div>
              </div>

              <!-- Asunto -->
              <div class="mb-4">
                <label for="asunto" class="form-label">Asunto:</label>
                <input type="text" class="form-control form-control-lg" id="asunto" name="asunto" placeholder="Título del mensaje">
                <div class="invalid-feedback">Escribe el asunto</div>
              </div>

              <!-- Mensaje -->
              <div class="mb-4">
                <label for="mensaje" class="form-label">Mensaje: <span class="text-danger">*</span></label>
                <textarea class="form-control form-control-lg" id="mensaje" name="mensaje" rows="10" placeholder="Escribe tu mensaje aquí..." style="resize: none;"></textarea>
                <div class="invalid-feedback">Escribe tu mensaje</div>
              </div>

              <!-- Botón -->
              <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5">Enviar</button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>



    </div>

    <!-- Columna Derecha: Información de contacto -->
    <div class="contacto-derecha">
      <p class="introduccion-contacto">
        ¿Tienes alguna pregunta o necesitas más información sobre nuestras pistas y servicios? ¡Estamos aquí para ayudarte!
      </p>

      <!-- Contenedor de los 4 ítems en una fila -->
      <div class="fila-items-contacto">
        <!-- Ítem: Teléfono -->
        <div class="item-contacto">
          <img src="<?= base_url('assets/imagenes/telefonoContacto.png') ?>" alt="Teléfono" class="icono-contacto">
          <p class="titulo-icono">Teléfono</p>
          <p class="texto-contacto">+34 635908583</p>
        </div>

        <!-- Ítem: Correo -->
        <div class="item-contacto">
          <img src="<?= base_url('assets/imagenes/correoContacto.png') ?>" alt="Correo" class="icono-contacto">
          <p class="titulo-icono">Correo</p>
          <p class="texto-contacto">alejandro@solvam.com</p>
        </div>

        <!-- Ítem: WhatsApp -->
        <div class="item-contacto">
          <img src="<?= base_url('assets/imagenes/whatsappContacto.png') ?>" alt="WhatsApp" class="icono-contacto">
          <p class="titulo-icono">WhatsApp</p>
          <p class="texto-contacto">+34 635908583</p>
        </div>

        <!-- Ítem: Ubicación -->
        <div class="item-contacto">
          <img src="<?= base_url('assets/imagenes/ubicacionContacto.png') ?>" alt="Ubicación" class="icono-contacto">
          <p class="titulo-icono">Ubicación</p>
          <p class="texto-contacto">Valencia</p>
        </div>
      </div>
    </div>

  </section>
 <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
 <!-- Variable base_url -->
 <script>
    var base_url = "<?= base_url(); ?>";
  </script>
<script src="<?= base_url('assets/js/contactoVista.js') ?>"></script>
</body>
</html>