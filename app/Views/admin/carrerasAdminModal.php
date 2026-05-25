<div class="modal fade" id="modalCarreras" tabindex="-1" aria-labelledby="modalMostrarCarreraLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Carreras</h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltitulo">
          <a href="" class="tx-inverse hover-primary">Modal de carreras</a>
        </h4>
        <form id="formCarrera">
          <input type="hidden" name="idcarrera" id="idcarrera">

          <div class="form-container" style="margin-left: 15px; margin-right: 15px;">

            <!-- BLOQUE 1: INFORMACIÓN BÁSICA -->
            <div class="card mb-4 border-primary">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0 tx-bold">Información Básica</h5>
              </div>
              <div class="card-body">

              <!-- ID USUARIO -->
              <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="id_usuario" class="form-label">Usuario:<span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="id_usuario" name="id_usuario" class="form-control" required>
                      <!-- Vacío, se llenará vía AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor seleccione a un usuario.
                    </div>
                  </div>
                </div>

                <!-- EMPLEADO ASIGNADO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="id_empleado" class="form-label" style="white-space: nowrap;">Empleado asignado<span class="tx-danger"> *</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="id_empleado" name="id_empleado" class="form-control">
                      <!-- Vacío, se llenará vía AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor seleccione un empleado.
                    </div>
                  </div>
                </div>

                <!-- PISTA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="id_pista" class="form-label">Pista <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="id_pista" name="id_pista" class="form-control">
                      <!-- Vacío, se llenará vía AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor seleccione una pista.
                    </div>
                  </div>
                </div>

                <!-- NUMERO DE PARTICIPANTES -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="num_participantes" class="form-label">Num. Participantes <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="number" id="num_participantes" name="num_participantes" class="form-control" placeholder="Ingresa un número de participantes" min="1">
                    <div class="invalid-feedback small-invalid-feedback">
                      El número de participantes es requerido y como máximo 20 participantes.
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- BLOQUE 2: FECHAS Y HORARIOS -->
            <div class="card mb-4 border-info">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0 tx-bold">Fechas y Horarios</h5>
              </div>
              <div class="card-body">

                <!-- FECHA CARRERA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="fecha_carrera" class="form-label">Fecha de la carrera <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                        </div>
                      </div>
                      <input id="fecha_carrera" name="fecha_carrera" type="text" class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly>
                    </div>
                    <div class="tx-8 tx-info" id="borrarFechaCarrera">Borrar fecha carrera</div>
                  </div>
                </div>

                <!-- HORARIO CARRERA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="id_horario" class="form-label">Horario <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="id_horario" name="id_horario" class="form-control">
                      <!-- Vacío, se llenará vía AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor seleccione un horario disponible.
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- BLOQUE 3: PAGO -->
            <div class="card mb-4 border-success">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0 tx-bold">Información de Pago</h5>
              </div>
              <div class="card-body">
                <!-- METODO DE PAGO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="metodo_pago" class="form-label">Método de pago <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="metodo_pago" name="metodo_pago" class="form-control">
                      <option value="card">Tarjeta</option>  
                      <option value="paypal">Paypal</option>
                      <option value="presencial">Pago presencial</option>
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor selecciona un método de pago.
                    </div>
                  </div>
                </div>

                <!-- PRECIO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="precio" class="form-label">Precio <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="number" id="precio" name="precio" class="form-control" placeholder="Ingresa un precio" readonly>
                    <div class="invalid-feedback small-invalid-feedback">
                      El precio es requerido.
                    </div>
                  </div>
                </div>

                <!-- FECHA DE PAGO -->
                <div class="form-group row mb-0">
                  <div class="col-12 col-lg-3">
                    <label for="fecha_pago" class="form-label">Fecha de Pago <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                        </div>
                      </div>
                      <input id="fecha_pago" name="fecha_pago" type="text" class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div><!-- form-container -->
        </form>
      </div><!-- modal-body -->
      <div class="modal-footer">
        <button type="button" name="action" id="btnsalvar" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Salvar</button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->