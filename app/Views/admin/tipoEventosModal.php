<!-- MODAL TIPO DE EVENTO -->
<div class="modal fade" id="modalTipoEvento" tabindex="-1" aria-labelledby="modalTipoEventoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- CABECERA DEL MODAL -->
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Tipo de Evento</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltituloTipoEvento">
          <a href="" class="tx-inverse hover-primary">Gestión de Tipos de Evento</a>
        </h4>
        
        <!-- FORMULARIO TIPO DE EVENTO -->
        <form id="formTipoEvento">
          <!-- ID TIPO EVENTO (OCULTO) -->
          <input type="hidden" name="id_tipo_evento" id="id_tipo_evento">
          
          <!-- CAMPO NOMBRE TIPO EVENTO -->
          <div id="divNombreTipoEvento" class="row no-gutters mb-3">
            <div class="col-12 col-lg-3">
              <label for="nombre_tipo_evento" class="form-label">Nombre Tipo Evento: <span class="tx-danger">*</span></label>
            </div>
            <div class="col-12 col-lg-9">
              <input type="text" class="form-control" name="nombre_tipo_evento" id="nombre_tipo_evento" maxlength="50" placeholder="Nombre tipo de evento..." required>
              <div class="invalid-feedback">Escribe el nombre del tipo de evento.</div>
            </div>
          </div>
          
          <!-- SELECTOR DE TIPO EVENTO -->
          <div id="divSelectTipoEvento" class="row no-gutters mb-3" style="display: none;">
            <div class="col-12 col-lg-3">
              <label for="select_tipo_evento" class="form-label">Seleccionar Tipo:</label>
            </div>
            <div class="col-12 col-lg-9">
              <select class="form-control" name="select_tipo_evento" id="select_tipo_evento">
                <option value="">Seleccione un tipo de evento...</option>
              </select>
              <div class="invalid-feedback" id="tipoEventoFeedback">Selecciona un tipo válido.</div>
            </div>
          </div>
        </form>
      </div>
      
      <!-- PIE DEL MODAL (ACCIONES) -->
      <div class="modal-footer">
        <!-- BOTÓN GUARDAR -->
        <button type="button" name="action" id="btnSalvarTipoEvento" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
          <i class="fas fa-save"></i> Guardar
        </button>
        
        <!-- BOTÓN ELIMINAR -->
        <button type="button" id="btnConfirmarEliminarTipo" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" style="display:none;">
          <i class="fas fa-trash-alt"></i> Confirmar Eliminación
        </button>
        
        <!-- BOTÓN CERRAR -->
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>