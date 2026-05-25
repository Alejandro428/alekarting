<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tu CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/navbar.css') ?>">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<?php 
    $session = session();
    $datosSesion = $session->get('sesion_iniciada') ? $session->get() : [];
?>

<!-- Variables globales para JS -->
<script>
    var base_url = "<?= base_url() ?>";
    var datosSesion = <?= json_encode($datosSesion); ?>;
</script>

<!-- jQuery -->
<script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>

<!-- Bootstrap Bundle JS (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Tus scripts -->
<script src="<?= base_url('assets/js/inactividad.js') ?>"></script>
<script src="<?= base_url('assets/js/navbar.js') ?>"></script>

<?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>

<!-- BARRA DE NAVEGACIÓN EMPLEADO CON HAMBURGUESA -->
<div class="barra-navegacion d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4">

    <!-- Logo y botón hamburguesa para móvil -->
    <div class="d-flex justify-content-between align-items-center w-100 d-md-none">
        <div class="logo">
            <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
        </div>

        <button class="navbar-toggler hamburguesa" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size: 2rem;"></i>
        </button>
    </div>

    <!-- Logo para escritorio -->
    <div class="logo mb-3 mb-md-0 d-none d-md-block">
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
    </div>

    <!-- Menú -->
    <ul class="enlaces-nav d-flex flex-column flex-md-row gap-3 list-unstyled mb-0 align-items-md-center mobile-menu d-none d-md-flex">
        <?php if ($session->get('sesion_iniciada')): ?>
            <li class="elemento-enlace submenu position-relative">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-badge me-1"></i><?= $session->get('nombre') ?>
                </a>
                <ul class="submenu-items dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('Empleado/cambiarCredenciales') ?>"><i class="bi bi-key me-2"></i>Credenciales</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('Cerrar_Sesion') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li class="elemento-enlace"><a class="nav-link" href="<?= base_url('Empleado') ?>"><i class="bi bi-house-door me-1"></i>Inicio</a></li>

        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-clipboard-data me-1"></i>Gestión
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Empleado/Eventos') ?>"><i class="bi bi-calendar-event me-2"></i>Eventos</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Empleado/Carreras') ?>"><i class="bi bi-speedometer2 me-2"></i>Carreras</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Empleado/Noticias') ?>"><i class="bi bi-newspaper me-2"></i>Noticias</a></li>
            </ul>
        </li>
    </ul>

    <!-- Bienvenida solo escritorio -->
    <?php if ($session->get('sesion_iniciada')): ?>
        <div class="contenedor-bienvenida d-none d-md-block">
            <div class="bienvenida-usuario text-center text-md-end">
                <!-- Modo Empleado Badge -->
                <span class="badge bg-info"><i class="bi bi-person-badge me-1"></i> Modo Empleado</span><br>
                <i class="bi bi-emoji-smile"></i> ¡Bienvenido/a, <strong><?= esc($session->get('nombre')) ?></strong>!
            </div>
        </div>
    <?php endif; ?>
</div> <!-- Aquí se cierra el div de la barra de navegación -->
