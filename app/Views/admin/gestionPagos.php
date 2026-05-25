<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ver Pagos - Admin</title>
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
<header class="heading-section text-center py-4 mb-4 position-relative" style="min-height: 60px;">
  <div class="pe-5">
    <h2 class="section-title">Gestión de Pagos</h2>
    <p class="section-subtitle">
      Aquí puedes ver los pagos que se han hecho por día (Si ese día no ha tenido ningún pago, no aparecerá).
    </p>
  </div>

  <button
    id="btnAyudaPagos"
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

          <!-- Acordeón de Filtros de Carreras -->
          <div id="accordionPagos" class="accordion mb-4">
            <div class="card">
              <div class="card-header p-0">
                <h6 class="mg-b-0">
                  <a id="accordion-toggle-pagos" 
                     class="d-block p-3 bg-primary text-white collapsed" 
                     data-toggle="collapse" 
                     href="#collapsePagos"
                     style="text-decoration: none;">
                    <i class="fas fa-filter me-2"></i>Filtros de Pagos
                  </a>
                </h6>
              </div>
              <div id="collapsePagos" class="collapse" data-parent="#accordionPagos">
                <div class="card-body pd-20 pt-3">
                  <div class="row g-3">
                    <!-- Filtro Estado -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-toggle-on me-2"></i>Estado Pagos
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
                                <span class="status-text fs-5">Pasados</span>
                              </label>
                            </div>
                            <div class="status-option estado-futura">
                              <input type="radio" name="filterDates" id="filterFuture" value="future" class="status-radio" checked>
                              <label for="filterFuture" class="status-label">
                                <span class="status-icon"><i class="fas fa-calendar-check"></i></span>
                                <span class="status-text fs-5">Futuros</span>
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

                    <!-- Filtro por fecha pago -->
                    <div class="col-12 col-md-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                          <h6 class="mb-0 text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Fecha Pago
                          </h6>
                        </div>
                        <div class="card-body p-2 d-flex flex-column justify-content-center">
                          <div class="input-group mx-auto" style="width: 95%">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                              </div>
                            </div>
                            <input id="dateCreateFilter" type="text" class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly>
                          </div>
                          <button type="button" class="btn btn-sm btn-outline-danger mt-2 mx-auto" id="borrarFechaFiltro">
                            <i class="fas fa-trash-alt me-1"></i> Borrar fecha
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        <!-- Tabla de pagos -->
        <div class="table-wrapper table-responsive">
          <table id="pagos_data" class="table display nowrap">
            <thead>
              <tr>
                <th></th>
                <th>Dia Pago</th>
                <th>Total Carr</th>
                <th>Cantidad Carr</th>
                <th>Total Res Ev</th>
                <th>Cantidad Res Ev</th>
                <th>Total Pago día</th>
              </tr>
            </thead>
            <tbody>
              <!-- Datos cargados via AJAX -->
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th><input type="text" placeholder="Dia" class="form-control form-control-sm" /></th>
                <th></th>
                <th></th>
                <th></th>
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
  <?= view('admin/mostrarAyudaPagos') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/toastr.css') ?>">
  <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
  <script src="<?= base_url('assets/js/gestionPagos.js') ?>"></script>
</body>
</html>