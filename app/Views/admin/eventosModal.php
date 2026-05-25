<div class="modal fade" id="modalEventos" tabindex="-1" aria-labelledby="modalMostrarEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Eventos</h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltitulo">
          <a href="" class="tx-inverse hover-primary">Modal de eventos</a>
        </h4>
        <form id="formEvento">
          <!-- ID EVENTO -->
          <input type="hidden" name="idevento" id="idevento">

          <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
          <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0 tx-bold">Información Básica</h5>
            </div>
            <div class="card-body">
              <!-- EMPLEADO RESPONSABLE -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label class="form-label">Responsable:</label>
                </div>
                <div class="col-12 col-lg-9">
                  <select id="empleados_id" name="empleados_id" class="form-control w-100">
                    <option value="">Seleccione un empleado</option>
                    <!-- Se llenará dinámicamente con AJAX -->
                  </select>
                  <div class="invalid-feedback small-invalid-feedback">
                    Por favor seleccione un empleado responsable
                  </div>
                </div>
              </div>

              <!-- NOMBRE -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="nombre" class="form-label">Nombre: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <input type="text" class="form-control w-100" name="nombre" id="nombre" maxlength="70" placeholder="Nombre del evento" autofocus>
                  <div class="invalid-feedback small-invalid-feedback">
                    Solo letras y espacios (mínimo 3 caracteres y máximo de 70)
                  </div>
                </div>
              </div>

              <!-- DESCRIPCIÓN -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="descripcion" class="form-label">Descripción: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <!-- Div principal de Summernote -->
                  <div id="descripcion" name="descripcion" class="summernote-descripcion"></div>
                  <div class="invalid-feedback small-invalid-feedback">
                    La descripción es requerida
                  </div>
                </div>
              </div>

              <!-- TIPO DE EVENTO -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="tipo_evento_id" class="form-label">Tipo: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <select id="tipo_evento_id" name="tipo_evento_id" class="form-control w-100">
                    <!-- Vacío, se llenará vía AJAX -->
                  </select>
                  <div class="invalid-feedback small-invalid-feedback">
                    Por favor seleccione un tipo de evento.
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SECCIÓN 2: DETALLES DEL EVENTO -->
          <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0 tx-bold">Detalles del Evento</h5>
            </div>
            <div class="card-body">
              <!-- FECHA -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="fecha" class="form-label">Fecha: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <div class="input-group w-100">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                      </div>
                    </div>
                    <input id="fecha" name="fecha" type="text" class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly>
                  </div>
                  <div class="tx-8 tx-info" id="borrarFechaEvento">Borrar fecha</div>
                </div>
              </div>

              <!-- HORARIO -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="franja_horaria_id" class="form-label">Horario: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <select id="franja_horaria_id" name="franja_horaria_id" class="form-control w-100">
                    <!-- Vacío, se llenará vía AJAX -->
                  </select>
                  <div class="invalid-feedback small-invalid-feedback">
                    Por favor seleccione un horario disponible.
                  </div>
                </div>
              </div>

              <!-- CAPACIDAD -->
              <div class="form-group row mb-3">
                <div class="col-12 col-lg-3">
                  <label for="capacidad" class="form-label">Capacidad: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <input type="number" id="capacidad" name="capacidad" class="form-control w-100" placeholder="Número de participantes" min="1">
                  <div class="invalid-feedback small-invalid-feedback">
                    La capacidad es requerida.
                  </div>
                </div>
              </div>

              <!-- PRECIO -->
              <div class="form-group row mb-0">
                <div class="col-12 col-lg-3">
                  <label for="precio" class="form-label">Precio: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <div class="input-group w-100">
                    <input type="number" id="precio" name="precio" class="form-control" placeholder="Precio por persona">
                    <div class="input-group-append">
                      <span class="input-group-text">€</span>
                    </div>
                    <div class="invalid-feedback small-invalid-feedback">
                    El precio es requerido.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SECCIÓN 3: IMAGEN -->
          <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0 tx-bold">Imagen del Evento</h5>
            </div>
            <div class="card-body">
              <!-- IMAGEN -->
              <div class="form-group row mb-0">
                <div class="col-12 col-lg-3">
                  <label for="imagen" class="form-label">Imagen: <span class="tx-danger">*</span></label>
                </div>
                <div class="col-12 col-lg-9">
                  <input type="file" id="imagen" name="imagen" class="form-control w-100" accept="image/*">
                  <small class="form-text text-muted">Recomendado: 800x600px, formato JPG/PNG</small>
                  <div class="invalid-feedback small-invalid-feedback">
                    Selecciona una imagen válida.
                  </div>
                </div>
              </div>
            </div>
          </div>

        </form>
      </div><!-- modal-body -->
      <div class="modal-footer">
        <button type="button" name="action" id="btnsalvar" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Guardar</button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->