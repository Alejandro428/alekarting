<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña - AleKarting</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    var urlBase = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/restablecerContrasena.css') ?>">
  <script src="<?= base_url('assets/js/restablecerContrasena.js') ?>"></script>
</head>
<body class="bg-light">

<div class="main-container">
  <div class="card card-form shadow">
    <div class="card-body p-4 p-md-5">
      <div class="text-center mb-4">
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" 
             class="logo-recuperacion mb-3" alt="Logo AleKarting">
        <h1 class="text-primary display-5 mb-3">Restablecer Contraseña</h1>
        <p class="text-muted fs-5">Ingresa y confirma tu nueva contraseña</p>
      </div>
      
      <form id="formRestablecer" name="formRestablecer" class="tamano-ampliado">
        <input type="hidden" name="token" value="<?= esc($_GET['token'] ?? '') ?>">
        
        <div class="mb-4">
          <label for="nueva_contrasena" class="form-label fw-semibold fs-5">Nueva Contraseña</label>
          <div class="input-group">
            <span class="input-group-text p-3"><i class="fas fa-lock fa-lg"></i></span>
            <input type="password" class="form-control form-control-lg control-form-grande" 
                   id="nueva_contrasena" name="nueva_contrasena"
                   placeholder="Mínimo 10 caracteres con mayúsculas, números y símbolos"
                   required autocomplete="new-password">
            <span class="input-group-text p-3 password-toggle" id="toggleNuevaContrasena">
              <i class="fas fa-eye fa-lg"></i>
            </span>
          </div>
          <div class="invalid-feedback" id="error-contrasena"></div>
        </div>
        
        <div class="mb-4">
          <label for="confirmar_contrasena" class="form-label fw-semibold fs-5">Confirmar Contraseña</label>
          <div class="input-group">
            <span class="input-group-text p-3"><i class="fas fa-lock fa-lg"></i></span>
            <input type="password" class="form-control form-control-lg control-form-grande" 
                   id="confirmar_contrasena" name="confirmar_contrasena"
                   placeholder="Repite tu nueva contraseña"
                   required autocomplete="new-password">
            <span class="input-group-text p-3 password-toggle" id="toggleConfirmarContrasena">
              <i class="fas fa-eye fa-lg"></i>
            </span>
          </div>
          <div class="invalid-feedback" id="error-confirmacion"></div>
        </div>
        
        <div id="mensajeRestablecer" class="alert d-none mb-4 fs-5"></div>
        
        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-4 fs-5">
          <i class="fas fa-save me-2"></i> Guardar Nueva Contraseña
        </button>
        
        <div class="text-center mt-4 fs-5">
          <a href="<?= base_url('Iniciar_Sesion') ?>" class="text-decoration-none me-3">
            <i class="fas fa-sign-in-alt me-2"></i> Volver al Inicio de Sesión
          </a>
          <a href="<?= base_url('Inicio') ?>" class="text-decoration-none">
            <i class="fas fa-home me-2"></i> Página Principal
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>