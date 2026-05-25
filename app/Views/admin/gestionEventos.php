<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Eventos - Admin</title>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/carrerasEmpleado.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/filtros.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/detallesReservasEventos.css') ?>">
  <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
</head>
<body>
<main class="container-fluid">
  <!-- Contenedor principal unificado -->
  <div class="contenedor-principal px-4">

<!-- Encabezado -->
<header class="heading-section py-4 mb-4 position-relative" style="min-height: 60px;">
  <div class="pe-5">
    <h2 class="section-title">Gestión de Eventos</h2>
    <p class="section-subtitle">
      Aquí puedes gestionar (crear, modificar, eliminar) los eventos que hayan existentes.
    </p>
  </div>

  <button
    id="btnAyudaEventos"
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

          <!-- Acordeón de Filtros de Eventos -->
          <div id="accordionEventos" class="accordion mb-4">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a id="accordion-toggle-eventos" 
                     class="d-block p-3 bg-primary text-white collapsed" 
                     data-toggle="collapse" 
                     href="#collapseEventos"
                     style="text-decoration: none;">
                    <i class="fas fa-filter me-2"></i>Filtros de Eventos
                  </a>
                </h6>
              </div>
              <div id="collapseEventos" class="collapse" data-parent="#accordionEventos">
                <div class="card-body pd-20 pt-3">
                  <div class="row g-3">
                    <!-- Filtro Estado -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-toggle-on me-2"></i>Estado Eventos
                          </h6>
                        </div>
                        <div class="card-body p-2">
                          <div class="status-selector">
                            <div class="status-option estado-todos">
                              <input type="radio" name="filterDates" id="filterAll" value="all" class="status-radio">
                              <label for="filterAll" class="status-label">
                                <span class="status-icon"><i class="fas fa-layer-group"></i></span>
                                <span class="status-text fs-5">Todos</span>
                              </label>
                            </div>
                            <div class="status-option estado-pasada">
                              <input type="radio" name="filterDates" id="filterPast" value="past" class="status-radio">
                              <label for="filterPast" class="status-label">
                                <span class="status-icon"><i class="fas fa-history"></i></span>
                                <span class="status-text fs-5">Pasadas</span>
                              </label>
                            </div>
                            <div class="status-option estado-futura">
                              <input type="radio" name="filterDates" id="filterFuture" value="future" class="status-radio" checked>
                              <label for="filterFuture" class="status-label">
                                <span class="status-icon"><i class="fas fa-calendar-check"></i></span>
                                <span class="status-text fs-5">Futuras</span>
                              </label>
                            </div>
                            <div class="status-option estado-hoy">
                              <input type="radio" name="filterDates" id="filterCurrent" value="current" class="status-radio">
                              <label for="filterCurrent" class="status-label">
                                <span class="status-icon"><i class="fas fa-calendar-day"></i></span>
                                <span class="status-text fs-5">Hoy</span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary"><i class="fas fa-calendar-alt me-2"></i>Fecha Evento</h6>
                        </div>
                        <div class="card-body p-2 d-flex flex-column justify-content-center">
                          <div class="input-group mx-auto mb-2" style="width: 95%">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar tx-16 lh-0 op-6"></i></div>
                            </div>
                            <input id="dateCreateFilter" type="text" class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly>
                          </div>
                          <button id="borrarFechaFiltro" class="btn btn-outline-danger btn-sm mx-auto" type="button">
                            <i class="fas fa-trash-alt me-1"></i> Borrar fecha
                          </button>
                        </div>
                      </div>
                    </div>
                  </div> <!-- end row -->
                </div> <!-- end card-body -->
              </div> <!-- end collapse -->
            </div> <!-- end card -->
          </div> <!-- end accordion -->

          <!-- Acordeón de Acciones -->
          <div id="accordionAcciones" class="accordion">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a class="d-block p-3 bg-primary text-white collapsed" data-toggle="collapse" href="#collapseAcciones" style="text-decoration: none;" id="accordion-toggle-acciones">
                    <i class="fas fa-cogs me-2"></i>Acciones
                  </a>
                </h6>
              </div>
              <div id="collapseAcciones" class="collapse" data-parent="#accordionAcciones">
                <div class="card-body pt-3">
                  <div class="row g-2">
                    <div class="col-12 mb-2">
                      <button class="btn btn-outline-primary w-100 fs-5" id="btnCrearTipoEvento">
                        <i class="bi bi-plus-lg me-1"></i>Crear Tipo de Evento
                      </button>
                    </div>
                    <div class="col-12 mb-2">
                      <button class="btn btn-outline-secondary w-100 fs-5" id="btnEditarTipoEvento">
                        <i class="bi bi-pencil me-1"></i>Editar Tipo de Evento
                      </button>
                    </div>
                    <div class="col-12 mb-2">
                      <button class="btn btn-outline-danger w-100 fs-5" id="btnEliminarTipoEvento">
                        <i class="bi bi-trash me-1"></i>Eliminar Tipo de Evento
                      </button>
                    </div>
                    <div class="col-12 mb-2">
                      <button class="btn btn-outline-primary w-100 fs-5" id="btnnuevo">
                        <i class="fas fa-plus-circle me-1"></i>Nuevo Evento
                      </button>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-outline-success w-100 fs-5" id="btnnuevoReserva">
                        <i class="bi bi-calendar-plus me-1"></i>Nueva Reserva
                      </button>
                    </div>
                  </div>
                </div> <!-- end card-body -->
              </div> <!-- end collapse -->
            </div> <!-- end card -->
          </div> <!-- end accordion -->
        </div> <!-- end filter-container -->

        <div class="row g-3 align-items-start">
          <!-- Tabla de Eventos (izquierda) -->
          <div class="col-lg-8">
            <div class="table-wrapper table-responsive h-100">
              <table id="eventos_data" class="table display nowrap mb-0">
                <thead>
                  <tr>
                    <th class="wp-5p"></th>
                    <th class="wp-50p">Nombre evento</th>
                    <th class="wp-5p">Imagen</th>
                    <th class="wp-10p">Fecha</th>
                    <th class="wp-10p">Empleado</th>
                    <th class="wp-10p">Reservas</th>
                    <th class="wp-5p">Editar</th>
                    <th class="wp-5p">Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Datos cargados via AJAX -->
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><input type="text" placeholder="Nombre..." class="form-control form-control-sm"></th>
                    <th></th>
                    <th><input type="text" placeholder="Fecha..." class="form-control form-control-sm"></th>
                    <th><input type="text" placeholder="Empleado..." class="form-control form-control-sm"></th>
                    <th class="d-none"><input type="text" class="d-none"></th>
                    <th class="d-none"><input type="text" class="d-none"></th>
                    <th class="d-none"><input type="text" class="d-none"></th>
                  </tr>
                </tfoot>
              </table>
            </div> <!-- Cierra table-wrapper -->
          </div> <!-- Cierra col-lg-8 -->

          <!-- Tabla de Reservas (derecha) -->
          <div class="col-lg-4">
            <div class="table-wrapper table-responsive h-100">
              <table id="reservas_data" class="table display nowrap mb-0">
                <thead>
                  <tr>
                    <th></th>
                    <th>Usuario</th>
                    <th>Pagado</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><input type="text" placeholder="Usuario..." class="form-control form-control-sm"></th>
                    <th><input type="text" placeholder="Esta pagado?" class="form-control form-control-sm"></th>
                    <th class="d-none"><input type="text" class="d-none"></th>
                  </tr>
                </tfoot>
              </table>
            </div> <!-- Cierra table-wrapper -->
          </div> <!-- Cierra col-lg-4 -->
        </div> <!-- Cierra row -->
      </div> <!-- Cierra br-section-wrapper -->
    </div> <!-- Cierra br-pagebody -->
  </div> <!-- Cierra contenedor-principal -->
</main>

<script>
  var base_url = "<?= base_url(); ?>";
</script>

<?= view('admin/eventosModal') ?>
<?= view('admin/reservaEventosModal') ?>
<?= view('admin/tipoEventosModal') ?>
<?= view('admin/modalDetallesReservaEventos') ?>
<?= view('admin/mostrarAyudaEvento') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
<script src="<?= base_url('assets/js/gestionEventos.js') ?>"></script>
<script>
    const usuarioEmail = "<?= session('email') ?>";
    const usuarioNombre = "<?= session('nombre') ?> <?= session('apellidos') ?>";
</script>
</body>
</html>