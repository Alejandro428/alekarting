<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionar Eventos - Empleado</title>
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
          Aquí puedes gestionar (crear, modificar, eliminar) los eventos que tengas asignados como empleado.
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

                      <!-- Filtro por fecha evento -->
                      <div class="col-12 col-md-4">
                        <div class="card shadow-sm h-100">
                          <div class="card-header bg-white border-bottom py-2">
                            <h6 class="mb-0 text-primary">
                              <i class="fas fa-calendar-alt me-2"></i>Fecha
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
                                <i class="fas fa-trash-alt me-1"></i> Borrar Fecha
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
                    <div class="row g-2">
                      <!-- Botón Nuevo Evento -->
                      <div class="col-12">
                        <button class="btn btn-oblong btn-outline-primary w-100 fs-5" id="btnnuevo" 
                                style="border-radius: 4px; border-width: 2px;">
                          <i class="fas fa-plus-circle me-2"></i> Nuevo Evento
                        </button>
                      </div>
                      
                      <!-- Botón Nueva Reserva -->
                      <div class="col-12">
                        <button class="btn btn-oblong btn-outline-success w-100 fs-5" id="btnnuevoReserva" 
                                style="border-radius: 4px; border-width: 2px;">
                          <i class="bi bi-calendar-plus me-2"></i> Nueva Reserva
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Tablas de Eventos y Reservas -->
          <div class="row g-3 align-items-start">
            <!-- Tabla de Eventos (izquierda) -->
            <div class="col-lg-8">
              <div class="table-wrapper table-responsive h-100">
                <table id="eventos_data" class="table display nowrap mb-0">
                  <thead>
                    <tr>
                      <th class="wp-5p"></th>
                      <th class="wp-50p">Nombre Ev</th>
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
              </div>
            </div>
            
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
                    <!-- Datos cargados via AJAX -->
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th><input type="text" placeholder="Usuario..." class="form-control form-control-sm"></th>
                      <th><input type="text" placeholder="Pagado?" class="form-control form-control-sm"></th>
                      <th class="d-none"><input type="text" class="d-none"></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>

  <script>
    var base_url = "<?= base_url(); ?>";
  </script>
  <?= view('admin/modalDetallesReservaEventos') ?>
  <?= view('empleado/eventosModal') ?>
  <?= view('empleado/reservaEventosModal') ?>
  <?= view('empleado/mostrarAyudaEvento') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <script src="<?= base_url('assets/js/nuevoEventosEmpleado.js') ?>"></script>
</body>
</html>