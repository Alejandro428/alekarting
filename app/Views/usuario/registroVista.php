<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - AleKarting</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/registroVista.css') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/registroVista.js') ?>"></script>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
</head>
<body>

  <!-- Contenedor principal -->
  <section class="register-container">

    <!-- Columna Izquierda -->
    <div class="register-left">
      <img src="<?= base_url('assets/imagenes/imagenRegistrarMod.webp') ?>" alt="Fondo Izquierda" class="fondo-left">
      <div class="contenido-left">
        <h1 class="titulo-bienvenida">
          Bienvenido a <span>AleKarting</span>
        </h1>
        <h2 class="titulo-registro">Registro</h2>
        <p class="subtitulo-registro">Creando nuevo usuario</p>
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo AleKarting" class="logo-empresa">
      </div>
    </div>

<!-- Columna Derecha: Formulario de Registro -->
<div class="register-right container-fluid">
    <!-- Registro de Usuario -->
    <div id="registro_usuario">
        <form id="formRegistro" name="formRegistro" action="<?= base_url('Usuario/crear') ?>" method="POST">
            
            <!-- Información básica -->
            <div class="form-container">
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 font-weight-bold">Información Básica</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="row">
                            <!-- Nombre de Usuario -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="nombre_usuario" class="form-label">Usuario: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="nombre_usuario" name="nombre_usuario" placeholder="Tu nombre de usuario" autocomplete="username">
                                <div class="invalid-feedback">Escribe un nombre de usuario</div>
                            </div>

                            <!-- Nombre -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" placeholder="Tu nombre" autocomplete="given-name">
                                <div class="invalid-feedback">Escribe tu nombre</div>
                            </div>
                        </div>

                        <!-- Apellidos (ocupará toda la fila) -->
                        <div class="form-group col-12 mb-3">
                            <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="apellidos" name="apellidos" placeholder="Tus apellidos" autocomplete="family-name">
                            <div class="invalid-feedback">Escribe tus apellidos</div>
                        </div>

                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0 font-weight-bold">Datos de Contacto</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="row">
                            <!-- Email -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">Correo Electrónico: <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="ejemplo@correo.com" autocomplete="email">
                                <div class="invalid-feedback">Introduce un email válido</div>
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="telefono" name="telefono" placeholder="612345678" autocomplete="tel">
                                <div class="invalid-feedback">Introduce un teléfono válido</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Seguridad -->
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 font-weight-bold">Seguridad</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="row">
                            <!-- Contraseña -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="contraseña" class="form-label">Contraseña: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="contraseña" name="contraseña" placeholder="********" autocomplete="new-password">
                                    <button class="btn btn-outline-primary" type="button" id="verContraseña">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Introduce una contraseña segura</div>
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="form-group col-12 col-md-6 mb-3">
                                <label for="confirmar_contraseña" class="form-label">Confirmar contr: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="confirmar_contraseña" name="confirmar_contraseña" placeholder="********" autocomplete="new-password">
                                    <button class="btn btn-outline-primary" type="button" id="verConfirmarContraseña">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Las contraseñas no coinciden</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Mensajes -->
                <div id="mensajeErrorGeneral" class="alert alert-danger" style="display:none;"></div>
                <div id="mensajeExito" class="alert alert-success" style="display:none;"></div>

                <!-- Botón -->
                <div class="acciones-formulario text-center mt-3">
                  <button type="submit" class="btn-registrar">Registrarse</button>
                </div>
            </div>
        </form>
        <div class="text-center mt-4">
            <a href="<?= base_url('Iniciar_Sesion') ?>" class="btn-back">Volver a Iniciar Sesión</a>
            <a href="<?= base_url('Inicio') ?>" class="btn-back">Redirigir a Inicio</a>
        </div>
    </div>
</div>

  </section>
</body>
</html>
