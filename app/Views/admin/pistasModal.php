<!-- MODAL PISTA -->
<div class="modal fade" id="modalPista" tabindex="-1" aria-labelledby="modalPistaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- CABECERA DEL MODAL -->
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Gestión de Pistas</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltituloPista">
          <a href="" class="tx-inverse hover-primary" id="tituloAccionPista">Nueva Pista</a>
        </h4>
        
        <!-- FORMULARIO PISTA -->
        <form id="formPista">
          <!-- ID PISTA (OCULTO) -->
          <input type="hidden" name="id_pista" id="id_pista">
          
          <!-- SELECTOR DE PISTA (para editar/eliminar) -->
          <div id="divSelectPista" class="row no-gutters mb-3" style="display: none;">
            <div class="col-12 col-lg-3">
              <label for="select_pista" class="form-label">Seleccionar Pista: <span class="tx-danger">*</span></label>
            </div>
            <div class="col-12 col-lg-9">
              <select class="form-control w-100" name="select_pista" id="select_pista" required>
                <option value="">Seleccione una pista...</option>
                <!-- Opciones se llenarán con JavaScript -->
              </select>
              <div class="invalid-feedback">Selecciona una pista válida.</div>
            </div>
          </div>
          
          <!-- CAMPO NOMBRE PISTA -->
          <div id="divNombrePista" class="row no-gutters mb-3">
            <div class="col-12 col-lg-3">
              <label for="nombre_pista" class="form-label">Nombre Pista: <span class="tx-danger">*</span></label>
            </div>
            <div class="col-12 col-lg-9">
              <input type="text" class="form-control w-100" name="nombre_pista" id="nombre_pista" maxlength="50" placeholder="Nombre de la pista..." required>
              <div class="invalid-feedback">Escribe el nombre de la pista.</div>
            </div>
          </div>
          
          <!-- CAMPO PRECIO PISTA -->
          <div id="divPrecioPista" class="row no-gutters mb-3">
            <div class="col-12 col-lg-3">
              <label for="precio_pista" class="form-label">Precio (€): <span class="tx-danger">*</span></label>
            </div>
            <div class="col-12 col-lg-9">
              <input type="number" class="form-control w-100" name="precio_pista" id="precio_pista" min="0" step="0.01" placeholder="Precio por hora..." required>
              <div class="invalid-feedback">Ingresa un precio válido.</div>
            </div>
          </div>
        </form>
      </div>
      
      <!-- PIE DEL MODAL (ACCIONES) -->
      <div class="modal-footer">
        <!-- BOTÓN GUARDAR (para crear/editar) -->
        <button type="button" name="action" id="btnSalvarPista" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
          <i class="fas fa-save"></i> Guardar
        </button>
        
        <!-- BOTÓN ELIMINAR (solo visible en modo eliminación) -->
        <button type="button" id="btnConfirmarEliminarPista" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" style="display:none;">
          <i class="fas fa-trash-alt"></i> Confirmar Eliminación
        </button>
        
        <!-- BOTÓN CERRAR/CANCELAR -->
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>