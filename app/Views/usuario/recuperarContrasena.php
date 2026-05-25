<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Contraseña - AleKarting</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    var urlBase = "<?= base_url() ?>";
  </script>
  <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/recuperarContrasena.css') ?>">
  <script src="<?= base_url('assets/js/recuperarContrasena.js') ?>"></script>
</head>
<body class="bg-light">

<div class="main-container">
  <div class="card card-form shadow-sm border-0">
    <div class="card-body p-4 p-md-5">
      <div class="text-center mb-4">
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" 
             class="logo-recuperacion mb-3" alt="Logo AleKarting">
        <h1 class="text-primary display-5 mb-3">Recuperar Contraseña</h1>
        <p class="text-muted fs-5">Ingresa tu correo electrónico para restablecer tu contraseña</p>
      </div>
      
      <form id="formularioRecuperacion" class="tamano-ampliado">
        <div class="mb-4">
          <label for="correoElectronico" class="form-label fw-semibold fs-5">Correo electrónico registrado</label>
          <div class="input-group">
            <span class="input-group-text p-3"><i class="fas fa-envelope fa-lg"></i></span>
            <input type="email" class="form-control form-control-lg control-form-grande" 
                   id="correo" name="correo" 
                   placeholder="ejemplo@correo.com">
            <div class="invalid-feedback fs-6">Por favor ingresa un email válido</div>
          </div>
        </div>
        
        <div id="mensajeRecuperacion" class="alert d-none mb-4 fs-5"></div>
        
        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-4 fs-5">
          <i class="fas fa-paper-plane me-2"></i> Enviar Instrucciones
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

<!-- Bootstrap JS Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>