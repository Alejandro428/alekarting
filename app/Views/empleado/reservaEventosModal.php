<div class="modal fade" id="modalReservaEventos" tabindex="-1" aria-labelledby="modalMostrarReservaEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <!-- Cabecera del Modal -->
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Gestión de Reserva</h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <!-- Cuerpo del Modal -->
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltitulo">
          <a href="" class="tx-inverse hover-primary">Detalles de Reserva</a>
        </h4>
        
        <form id="formReservaEvento">
          <input type="hidden" name="idreserva" id="idreserva">
          <input type="hidden" id="precio_evento" name="precio_evento">
          
          <!-- Sección 1: Información Básica -->
          <div class="form-container">
            <div class="card mb-4 border-primary">
              <div class="card-header bg-primary text-white">
                <h5 class="tx-bold mb-0">Información Básica</h5>
              </div>
              <div class="card-body">
                <!-- Fila: Cantidad -->
                <div class="form-group row mb-3">
                  <label for="cantidad" class="col-md-3 col-form-label">Cantidad <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Número de personas" min="1" required>
                    <div class="disponibilidad-texto mt-2"></div>
                    <div class="invalid-feedback small-invalid-feedback">La cantidad es requerida</div>
                  </div>
                </div>
                
                <!-- Fila: ID de Evento -->
                <div class="form-group row mb-3">
                  <label for="idevento" class="col-md-3 col-form-label">Evento <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <select id="ideventoR" name="ideventoR" class="form-control" required>
                      <option value="">Seleccione un evento</option>
                      <!-- Se llenará dinámicamente con AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">Seleccione un evento</div>
                  </div>
                </div>

                <!-- Fila: ID de Usuario -->
                <div class="form-group row mb-3">
                  <label for="idusuario" class="col-md-3 col-form-label">Usuario <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <select id="idusuarioR" name="idusuarioR" class="form-control" required>
                      <option value="">Seleccione un usuario</option>
                      <!-- Se llenará dinámicamente con AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">Seleccione un usuario</div>
                  </div>
                </div>

                <!-- Fila: Método de Pago -->
                <div class="form-group row mb-3">
                  <label for="metodo_pago" class="col-md-3 col-form-label">Método de Pago <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                      <option value="card">Tarjeta</option>
                      <option value="paypal">PayPal</option>
                      <option value="presencial">Pago presencial</option>
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">Seleccione un método</div>
                  </div>
                </div>

                <!-- Fila: Fecha de Pago -->
                <div class="form-group row mb-3">
                  <label for="fecha_pago" class="col-md-3 col-form-label">Fecha de Pago <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                      <input id="fecha_pago" name="fecha_pago" type="text" class="form-control fc-datepicker" placeholder="DD-MM-AAAA" readonly required>
                    </div>
                    <div class="text-right">
                      <div class="tx-8 tx-info" id="borrarFechaPago">Borrar fecha</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sección 2: Detalles Financieros -->
            <div class="card mb-4 border-info">
              <div class="card-header bg-info text-white">
                <h5 class="tx-bold mb-0">Detalles Financieros</h5>
              </div>
              <div class="card-body">
                <!-- Fila: Total -->
                <div class="form-group row mb-3">
                  <label for="total" class="col-md-3 col-form-label">Total <span class="tx-danger">*</span></label>
                  <div class="col-md-9">
                    <div class="input-group">
                      <input type="number" id="total" name="total" class="form-control" placeholder="0.00" step="0.01" readonly required>
                      <div class="input-group-append">
                        <span class="input-group-text">€</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div><!-- form-container -->
        </form>
      </div>
      
      <!-- Pie del Modal -->
      <div class="modal-footer">
        <button type="button" id="btnsalvarReservaEvento" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
          <i class="fas fa-save mg-r-5"></i> Guardar
        </button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">
          <i class="fas fa-times mg-r-5"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
