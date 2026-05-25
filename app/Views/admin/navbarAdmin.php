<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/navbar.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<?php 
    $session = session();
    $datosSesion = $session->get('sesion_iniciada') ? $session->get() : [];
?>
<script>
    var base_url = "<?= base_url() ?>";
    var datosSesion = <?= json_encode($datosSesion); ?>;
</script>
<script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
<script src="<?= base_url('assets/js/inactividad.js') ?>"></script>
<script src="<?= base_url('assets/js/navbar.js') ?>"></script>
<?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>

<!-- BARRA DE NAVEGACIÓN ADMIN -->
<div class="barra-navegacion d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4">

    <!-- Logo y botón hamburguesa móvil -->
    <div class="d-flex justify-content-between align-items-center w-100 d-md-none">
        <div class="logo">
            <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
        </div>

        <button class="navbar-toggler hamburguesa" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size: 2rem;"></i>
        </button>
    </div>

    <!-- Logo desktop -->
    <div class="logo mb-3 mb-md-0 d-none d-md-block">
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
    </div>

    <!-- Enlaces de navegación -->
    <ul class="enlaces-nav d-flex flex-column flex-md-row gap-3 list-unstyled mb-0 align-items-md-center mobile-menu">
        <?php if ($session->get('sesion_iniciada')): ?>
            <li class="elemento-enlace submenu position-relative">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-fill-gear me-1"></i><?= esc($session->get('nombre')) ?>
                </a>
                <ul class="submenu-items dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('Admin/cambiarCredenciales') ?>"><i class="bi bi-key me-2"></i>Credenciales</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('Cerrar_Sesion') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li class="elemento-enlace"><a class="nav-link" href="<?= base_url('Admin') ?>"><i class="bi bi-house-door me-1"></i>Inicio</a></li>

        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-collection me-1"></i>Gestión
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Admin/gestionEventos') ?>"><i class="bi bi-calendar-event me-2"></i>Eventos</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Admin/gestionCarreras') ?>"><i class="bi bi-speedometer2 me-2"></i>Carreras</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Admin/gestionNoticias') ?>"><i class="bi bi-newspaper me-2"></i>Noticias</a></li>
            </ul>
        </li>

        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-people me-1"></i>Usuarios
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Admin/gestionUsuarios') ?>"><i class="bi bi-person-lines-fill me-2"></i>Usuarios</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Admin/gestionEmpleados') ?>"><i class="bi bi-person-badge me-2"></i>Empleados</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Admin/pagosPorDia') ?>"><i class="bi bi-person-badge me-2"></i>Pagos por día</a></li>
            </ul>
        </li>
    </ul>

    <!-- Bienvenida admin -->
    <?php if ($session->get('sesion_iniciada')): ?>
        <div class="contenedor-bienvenida d-none d-md-block">
            <div class="bienvenida-usuario text-center text-md-end">
                <span class="badge bg-dark"><i class="bi bi-shield-lock"></i> Modo Administrador</span><br>
                ¡Bienvenido/a, <strong><?= esc($session->get('nombre')) ?></strong>!
            </div>
        </div>
    <?php endif; ?>
</div>
