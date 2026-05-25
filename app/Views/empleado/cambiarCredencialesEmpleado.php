<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empleados - Cambiar Credenciales</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/perfilVista.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
  <script>
    var base_url = "<?= base_url() ?>";
  </script>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <script src="<?= base_url('assets/js/cambiarCredencialesEmpleado.js') ?>"></script>
</head>
<body>
<section class="perfil-contenedor">
    <h1>Cambiar credenciales de <?= session()->get('nombre_usuario') ?? 'Usuario' ?></h1>

    <!-- Cambiar Credenciales -->
    <div id="cambiar_credenciales">
        <h2 class="text-center">Actualiza tu información</h2>
        <form id="formCredenciales" name="formCredenciales" action="<?= base_url('Empleado/actualizarCredenciales') ?>" method="POST">
            <!-- Información básica -->
            <div class="form-container container px-3">
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 tx-bold">Información Básica</h5>
                    </div>
                    <div class="card-body">
                        <!-- ID Oculto -->
                        <input type="hidden" name="id" value="<?= esc(session()->get('id') ?? '') ?>">

                        <!-- Nombre de Usuario -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="nombre_usuario" class="form-label">Nombre de Usuario: <span class="tx-danger">*</span></label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                                       placeholder="Tu nombre de usuario"
                                       value="<?= esc(session()->get('nombre_usuario') ?? '') ?>"
                                       autocomplete="usuario">
                                <div class="invalid-feedback small-invalid-feedback">Escribe el nombre de usuario</div>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="nombre" class="form-label">Nombre: <span class="tx-danger">*</span></label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       placeholder="Tu nombre"
                                       value="<?= esc(session()->get('nombre') ?? '') ?>"
                                       autocomplete="nombre">
                                <div class="invalid-feedback small-invalid-feedback">Escribe tu nombre</div>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="apellidos" class="form-label">Apellidos: <span class="tx-danger">*</span></label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                       placeholder="Tus apellidos"
                                       value="<?= esc(session()->get('apellidos') ?? '') ?>"
                                       autocomplete="apellidos">
                                <div class="invalid-feedback small-invalid-feedback">Escribe tus apellidos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0 tx-bold">Datos de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <!-- Email -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="email" class="form-label">Correo Electrónico: <span class="tx-danger">*</span></label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="nuevo@mail.com"
                                       value="<?= esc(session()->get('email') ?? '') ?>"
                                       autocomplete="correo">
                                <div class="invalid-feedback small-invalid-feedback">Introduce un email válido</div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="telefono" class="form-label">Teléfono: <span class="tx-danger">*</span></label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       placeholder="Número de teléfono"
                                       value="<?= esc(session()->get('telefono') ?? '') ?>"
                                       autocomplete="teléfono">
                                <div class="invalid-feedback small-invalid-feedback">Introduce un número de 9 dígitos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cambio de contraseña -->
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0 tx-bold">Seguridad</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nueva Contraseña -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="contraseña" class="form-label">Nueva Contraseña:</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="contraseña" name="contraseña"
                                           placeholder="Mínimo 8 caracteres"
                                           autocomplete="new-password">
                                    <button class="btn btn-outline-primary" type="button" id="verContraseña">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback small-invalid-feedback">La contraseña debe tener al menos 8 caracteres</div>
                            </div>
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div class="form-group row mb-3 align-items-center">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña:</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña"
                                           placeholder="Repite tu nueva contraseña"
                                           autocomplete="new-password">
                                    <button class="btn btn-outline-primary" type="button" id="verConfirmarContraseña">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback small-invalid-feedback">Las contraseñas no coinciden</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensajes -->
                <div id="mensajeErrorGeneral" class="mensaje-registro mensaje-negativo" style="display:none;"></div>
                <div id="mensajeExito" class="mensaje-registro mensaje-positivo" style="display:none;"></div>

                <!-- Botones -->
                <div class="acciones-formulario text-center mt-3">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</section>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</body>
</html>