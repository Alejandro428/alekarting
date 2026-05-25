<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <!-- Meta viewport para responsividad -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Noticias - Admin</title>
  <!-- Incluye el mainHead con tus vendor CSS y demás -->
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/noticiasEmpleado.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/filtros.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
<main class="container-fluid">
  <!-- Contenedor principal para alinear encabezado y tabla -->
  <div class="contenedor-principal px-4">

    <!-- Encabezado dentro del contenedor -->
<header class="heading-section py-4 mb-4 position-relative" style="min-height: 60px;">
  <div class="pe-5"> <!-- Padding a la derecha para dejar espacio al botón -->
    <h2 class="section-title">Gestión de Noticias</h2>
    <p class="section-subtitle">
      Aquí puedes gestionar (crear, modificar, eliminar) todas las noticias.
    </p>
  </div>

  <!-- Botón con posicionamiento absoluto, alineado con el título -->
  <button
    id="btnAyudaNoticias"
    type="button"
    class="btn btn-outline-info position-absolute top-0 end-0 mt-2 me-3"
    title="Ayuda">
    <i class="bi bi-question-circle"></i>
  </button>
</header>



    <!-- Sección con la estructura "br-pagebody" y "br-section-wrapper" -->
    <div class="br-pagebody">
      <div class="br-section-wrapper">

        <!-- Sección de filtros en tarjetas -->
        <div class="filter-container mb-4">
          <!-- Alerta de filtro activo -->
          <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" id="filter-alert" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-filter me-2"></i>
                <span>Filtros aplicados: </span>
                <span id="active-filters-text"></span>
              </div>
              <button type="button" class="btn btn-sm btn-outline-warning" id="clear-filter">Limpiar filtros</button>
            </div>
          </div>

          <!-- Acordeón de Filtros de Noticias -->
          <div id="accordionNoticias" class="accordion mb-4">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a id="accordion-toggle-noticias" 
                     class="d-block p-3 bg-primary text-white collapsed" 
                     data-toggle="collapse" 
                     href="#collapseNoticias"
                     style="text-decoration: none;">
                    <i class="fas fa-filter me-2"></i>Filtros de Noticias
                  </a>
                </h6>
              </div>
              <div id="collapseNoticias" class="collapse" data-parent="#accordionNoticias">
                <div class="card-body pd-20 pt-3">
                  <div class="row g-3">
                    <!-- Filtro Estado de la noticia -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-toggle-on me-2"></i>Estado de la Noticia
                          </h6>
                        </div>
                        <div class="card-body p-2">
                          <div class="status-selector">
                            <div class="status-option estado-todos">
                              <input type="radio" name="filtroNoticias" id="all" value="all" class="status-radio" checked>
                              <label for="all" class="status-label">
                                <span class="status-icon"><i class="fas fa-layer-group"></i></span>
                                <span class="status-text fs-5">Todas</span>
                              </label>
                            </div>
                            <div class="status-option estado-pasada">
                              <input type="radio" name="filtroNoticias" id="past" value="past" class="status-radio">
                              <label for="past" class="status-label">
                                <span class="status-icon"><i class="fas fa-history"></i></span>
                                <span class="status-text fs-5">Pasadas</span>
                              </label>
                            </div>
                            <div class="status-option estado-hoy">
                              <input type="radio" name="filtroNoticias" id="current" value="current" class="status-radio">
                              <label for="current" class="status-label">
                                <span class="status-icon"><i class="fas fa-calendar-day"></i></span>
                                <span class="status-text fs-5">Hoy</span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Filtro por fecha -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Fecha de publicación
                          </h6>
                        </div>
                        <div class="card-body p-2 d-flex flex-column justify-content-center">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text py-0 px-2">
                                <i class="fa fa-calendar"></i>
                              </div>
                            </div>
                            <input id="dateCreateFilter" type="text" class="form-control fc-datepicker py-1" 
                                  placeholder="dd-mm-aaaa" readonly style="min-width: 120px;">
                          </div>
                          <!-- Botón Borrar Fecha -->
                          <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="borrarFechaFiltro">
                              <i class="fas fa-trash-alt me-1 fs-5"></i> Borrar fecha
                            </button>
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
                  <i class="fas fa-cogs me-2"></i>Acciones
                </a>
              </h6>
            </div>
            <div id="collapseAcciones" class="collapse" data-parent="#accordionAcciones">
              <div class="card-body pd-20 pt-3">
                <!-- Botón 1: Crear Categoría (outline-primary) -->
                <button class="btn btn-outline-primary w-100 mb-2 fs-5 text-center" id="btnCrearCategoria">
                  <i class="fas fa-plus me-2"></i> Crear Categoría
                </button>

                <!-- Botón 2: Editar Categoría (outline-secondary) -->
                <button class="btn btn-outline-secondary w-100 mb-2 fs-5 text-center" id="btnEditarCategoria">
                  <i class="fas fa-edit me-2"></i> Editar Categoría
                </button>

                <!-- Botón 3: Eliminar Categoría (outline-danger) -->
                <button class="btn btn-outline-danger w-100 mb-2 fs-5 text-center" id="btnEliminarCategoria">
                  <i class="fas fa-trash me-2"></i> Eliminar Categoría
                </button>

                <!-- Botón 4: Nueva Noticia (outline-primary) -->
                <button class="btn btn-outline-primary w-100 fs-5 text-center" id="btnnuevo">
                  <i class="fas fa-plus-circle me-2"></i> Nueva Noticia
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

        <!-- Tabla de noticias -->
        <div class="table-wrapper table-responsive">
          <table id="noticias_data" class="table display nowrap">
            <thead>
              <tr>
                <th></th>
                <th>Autor</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Imagen</th>
                <th>Editar</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <!-- Los datos se cargarán vía AJAX -->
            </tbody>
            <tfoot>
              <tr>
                <th><input type="text" placeholder="NO Buscar" class="d-none" /></th>
                <th><input type="text" placeholder="Buscar autor" class="form-control form-control-sm" /></th>
                <th><input type="text" placeholder="Buscar título" class="form-control form-control-sm" /></th>
                <th><input type="text" placeholder="Buscar categoría" class="form-control form-control-sm" /></th>
                <th><input type="text" placeholder="dd-mm-aaaa" class="form-control form-control-sm" /></th>
                <th><input type="text" placeholder="NO Buscar" class="d-none" /></th>
                <th><input type="text" placeholder="NO Buscar" class="d-none" /></th>
                <th><input type="text" placeholder="NO Buscar" class="d-none" /></th>
              </tr>
            </tfoot>
          </table>
        </div><!-- Fin table-wrapper -->

      </div><!-- Fin br-section-wrapper -->
    </div><!-- Fin br-pagebody -->
  </div><!-- Fin contenedor-principal -->
</main>


  <!-- Variable base_url -->
  <script>
    var base_url = "<?= base_url(); ?>";
  </script>
  
  <!-- Incluye otros scripts personalizados -->
  <?= view('empleado/noticiasModal') ?>
  <?= view('admin/categoriasModal') ?>
  <?= view('admin/mostrarAyudaNoticia') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <script src="<?= base_url('assets/js/gestionNoticias.js') ?>"></script>
</body>
</html>
