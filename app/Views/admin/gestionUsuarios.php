<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Usuarios - Admin</title>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/carrerasEmpleado.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/filtros.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
<main class="container-fluid">
  <!-- Contenedor principal unificado -->
  <div class="contenedor-principal px-4">
    
  <!-- Encabezado -->
<header class="heading-section py-4 mb-4 position-relative" style="min-height: 60px;">
  <div class="pe-5">
    <h2 class="section-title">Gestión de Usuarios</h2>
    <p class="section-subtitle">
      Aquí puedes gestionar (modificar, eliminar) los usuarios clientes que existen en la BD.
    </p>
  </div>

  <button
    id="btnAyudaUsuarios"
    type="button"
    class="btn btn-outline-info position-absolute top-0 end-0 mt-2 me-3"
    title="Ayuda">
    <i class="bi bi-question-circle"></i>
  </button>
</header>
    
    <!-- Contenedor unificado para filtros y tabla -->
    <div class="br-pagebody">
      <div class="br-section-wrapper">
        
        <!-- Sección de filtros agrupados -->
        <div class="filter-container mb-4">
          <!-- Alerta de filtro activo -->
          <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" id="filter-alert" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-filter me-2"></i>
                <span>Filtros aplicados: </span>
                <span id="active-filters-text"></span>
              </div>
              <button type="button" class="btn btn-sm btn-outline-warning" id="clear-filter">
                Limpiar filtros
              </button>
            </div>
          </div>

          <!-- Acordeón de Filtros de Usuarios -->
          <div id="accordionUsuarios" class="accordion mb-4">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a id="accordion-toggle-usuarios" 
                     class="d-block p-3 bg-primary text-white collapsed" 
                     data-toggle="collapse" 
                     href="#collapseUsuarios"
                     style="text-decoration: none;">
                    <i class="fas fa-filter me-2"></i>Filtros de Usuarios
                  </a>
                </h6>
              </div>
              <div id="collapseUsuarios" class="collapse" data-parent="#accordionUsuarios">
                <div class="card-body pd-20 pt-3">
                  <div class="row g-3">
                    <!-- Filtro Estado - Versión compacta vertical -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-user-check me-2"></i>Estado Usuario
                          </h6>
                        </div>
                        <div class="card-body p-2">
                          <div class="status-selector d-flex flex-column gap-2">
                            <div class="status-option estado-todos">
                              <input type="radio" name="filtroActivo" id="filterAll" value="all" class="status-radio" checked>
                              <label for="filterAll" class="status-label w-100 d-block py-1">
                                <span class="status-icon"><i class="fas fa-layer-group"></i></span>
                                <span class="status-text fs-5">Todos</span>
                              </label>
                            </div>
                            <div class="status-option estado-activo">
                              <input type="radio" name="filtroActivo" id="filterActive" value="1" class="status-radio">
                              <label for="filterActive" class="status-label w-100 d-block py-1">
                                <span class="status-icon"><i class="fas fa-user-check"></i></span>
                                <span class="status-text fs-5">Activos</span>
                              </label>
                            </div>
                            <div class="status-option estado-inactivo">
                              <input type="radio" name="filtroActivo" id="filterInactive" value="0" class="status-radio">
                              <label for="filterInactive" class="status-label w-100 d-block py-1">
                                <span class="status-icon"><i class="fas fa-user-slash"></i></span>
                                <span class="status-text fs-5">Inactivos</span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Acordeón de Acciones -->
          <div id="accordionAcciones" class="accordion">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a id="accordion-toggle-acciones" 
                     class="d-block p-3 bg-primary text-white collapsed" 
                     data-toggle="collapse" 
                     href="#collapseAcciones"
                     style="text-decoration: none;">
                    <i class="fas fa-cogs me-2"></i>Acciones de Usuario
                  </a>
                </h6>
              </div>
              <div id="collapseAcciones" class="collapse" data-parent="#accordionAcciones">
                <div class="card-body pd-20 pt-3">
                  <div class="row g-2">
                    <div class="col-12">
                      <!-- Botón Nuevo Usuario -->
                      <button class="btn btn-outline-primary w-100 py-2 text-nowrap fs-5" id="btnnuevo" 
                              style="font-size: 1.1rem; min-width: 180px;">
                        <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
                      </button>
                    </div>
                    <div class="col-12">
                      <!-- Botón Cambiar Contraseña Usuario -->
                      <button class="btn btn-outline-danger w-100 py-2 text-nowrap fs-5" id="btnCambiarContrasena" 
                              style="font-size: 1.1rem; min-width: 250px;">
                        <i class="bi bi-shield-lock me-1"></i> Cambiar Contraseña Usuario
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-wrapper table-responsive">
          <table id="usuarios_data" class="table display nowrap">
            <thead>
              <tr>
                <th></th>
                <th>Nombre usuario</th>
                <th>Estado</th>
                <th>Act/Desact</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody>
              <!-- Datos cargados via AJAX -->
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th><input type="text" placeholder="Buscar nombre usuario..." class="form-control form-control-sm" /></th>
                <th><input type="text" placeholder="Buscar por estado..." class="form-control form-control-sm" /></th>
                <th class="d-none"><input type="text" class="d-none" /></th>
                <th class="d-none"><input type="text" class="d-none" /></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

  <script>
    var base_url = "<?= base_url(); ?>";
  </script>
  
  <?= view('admin/cambiarContrasena') ?>
  <?= view('admin/usuariosModal') ?>
  <?= view('admin/mostrarAyudaUsuarios') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <script src="<?= base_url('assets/js/gestionUsuarios.js') ?>"></script>
</body>
</html>