<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/navbar.css') ?>">
<!-- Añadir Bootstrap Icons -->
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
<script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
<script src="<?= base_url('assets/js/inactividad.js') ?>"></script>
<script src="<?= base_url('assets/js/navbar.js') ?>"></script>
<?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>

<!-- BARRA DE NAVEGACIÓN CON ICONOS -->
<div class="barra-navegacion d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4">
    <!-- Logo y botón hamburguesa -->
    <div class="d-flex justify-content-between align-items-center w-100 d-md-none">
        <!-- Logo -->
        <div class="logo">
            <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
        </div>
        
        <!-- Botón Hamburguesa (solo móvil) -->
        <button class="navbar-toggler hamburguesa" type="button" aria-label="Toggle navigation">
            <i class="bi bi-list" style="font-size: 2rem;"></i>
        </button>
    </div>

    <!-- Logo para desktop -->
    <div class="logo mb-3 mb-md-0 d-none d-md-block">
        <img src="<?= base_url('assets/imagenes/logo_karting.png') ?>" alt="Logo" class="img-fluid" style="max-width: 200px;">
    </div>

    <!-- Enlaces de navegación con iconos -->
    <ul class="enlaces-nav d-flex flex-column flex-md-row gap-3 list-unstyled mb-0 align-items-md-center mobile-menu">
        <?php if ($session->get('sesion_iniciada')): ?>
            <!-- Usuario logueado con icono -->
            <li class="elemento-enlace submenu position-relative">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i><?= $session->get('nombre') ?>
                </a>
                <ul class="submenu-items dropdown-menu">
                    <li><a class="dropdown-item" href="<?= base_url('Perfil') ?>"><i class="bi bi-person-lines-fill me-2"></i>Perfil</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('Cerrar_Sesion') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Submenú para Inicio y Sobre Nosotros -->
        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-house-door me-1"></i>Inicio
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Inicio') ?>"><i class="bi bi-house-door me-2"></i>Home</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Sobre_Nosotros') ?>"><i class="bi bi-info-circle me-2"></i>Acerca</a></li>
            </ul>
        </li>

        <!-- Submenú Actividades -->
        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-activity me-1"></i>Actividades
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Eventos') ?>"><i class="bi bi-calendar-event me-2"></i>Eventos</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Carreras') ?>"><i class="bi bi-speedometer2 me-2"></i>Carreras</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Noticias') ?>"><i class="bi bi-newspaper me-2"></i>Noticias</a></li>
            </ul>
        </li>

        <!-- Submenú para Horarios -->
        <li class="elemento-enlace submenu position-relative">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-clock me-1"></i>Horarios
            </a>
            <ul class="submenu-items dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('Horarios/Carreras') ?>"><i class="bi bi-stopwatch me-2"></i>Carreras</a></li>
                <li><a class="dropdown-item" href="<?= base_url('Horarios/Eventos') ?>"><i class="bi bi-calendar-check me-2"></i>Eventos</a></li>
            </ul>
        </li>

        <li class="elemento-enlace"><a class="nav-link" href="<?= base_url('Contacto') ?>"><i class="bi bi-envelope me-1"></i>Contacto</a></li>

        <?php if ($session->get('sesion_iniciada')): ?>
        <?php else: ?>
            <li class="elemento-enlace"><a class="nav-link" href="<?= base_url('Iniciar_Sesion') ?>"><i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión</a></li>
        <?php endif; ?>
    </ul>

    <!-- Bienvenida con icono -->
    <?php if ($session->get('sesion_iniciada')): ?>
        <div class="contenedor-bienvenida d-none d-md-block">
            <div class="bienvenida-usuario text-center text-md-end">
                <i class="bi bi-emoji-smile"></i> ¡Bienvenido/a, <strong><?= esc($session->get('nombre')) ?></strong>!
            </div>
        </div>
    <?php endif; ?>
</div>