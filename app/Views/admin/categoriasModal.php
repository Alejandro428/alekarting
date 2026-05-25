<!-- MODAL CATEGORÍA (Versión mejorada) -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- CABECERA DEL MODAL -->
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Categoría</h6>
        <!-- Botón de cierre CORREGIDO (compatible con Bootstrap 5) -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body pd-25">
        <!-- Título adicional (como en tu modal que funciona) -->
        <h4 class="lh-3 mg-b-20" id="mdltituloCategoria">
          <a href="" class="tx-inverse hover-primary">Gestión de Categorías</a>
        </h4>
        
        <!-- FORMULARIO CATEGORÍA -->
        <form id="formCategoria">
          <!-- ID CATEGORIA (OCULTO) -->
          <input type="hidden" name="id_categoria" id="id_categoria">
          
          <!-- CAMPO NOMBRE CATEGORIA -->
          <div id="divNombreCategoria" class="row no-gutters mb-3">
            <div class="col-12 col-lg-3">
              <label for="nombre_categoria" class="form-label">Nombre Categoría: <span class="tx-danger">*</span></label>
            </div><!-- col-3 -->
            <div class="col-12 col-lg-9">
              <input type="text" class="form-control w-100" name="nombre_categoria" id="nombre_categoria" maxlength="50" placeholder="Nombre categoría..." required>
              <div class="invalid-feedback">Escribe el nombre de la categoría.</div>
            </div><!-- col-9 -->
          </div><!-- row -->
          
          <!-- SELECTOR DE CATEGORÍA -->
          <div id="divSelectCategoria" class="row no-gutters mb-3" style="display: none;">
            <div class="col-12 col-lg-3">
              <label for="select_categoria" class="form-label">Seleccionar Categoría:</label>
            </div><!-- col-3 -->
            <div class="col-12 col-lg-9">
              <select class="form-control w-100" name="select_categoria" id="select_categoria">
                <option value="">Seleccione una categoría...</option>
              </select>
              <div class="invalid-feedback" id="categoriaFeedback">Selecciona una categoría válida.</div>
            </div><!-- col-9 -->
          </div><!-- row -->
        </form>
      </div><!-- modal-body -->
      
      <!-- PIE DEL MODAL (ACCIONES) -->
      <div class="modal-footer">
        <!-- BOTÓN GUARDAR (para crear/editar) -->
        <button type="button" name="action" id="btnSalvarCategoria" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
          <i class="fas fa-save"></i> Guardar
        </button>
        
        <!-- BOTÓN ELIMINAR (solo visible en modo eliminación) -->
        <button type="button" id="btnConfirmarEliminar" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" style="display:none;">
          <i class="fas fa-trash-alt"></i> Confirmar Eliminación
        </button>
        
        <!-- BOTÓN CERRAR/CANCELAR -->
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->