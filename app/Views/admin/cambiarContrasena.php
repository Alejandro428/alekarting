<!-- MODAL CAMBIO DE CONTRASENA -->
<div class="modal fade" id="modalCambioContrasena" tabindex="-1" aria-labelledby="modalCambioContrasenaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- CABECERA DEL MODAL -->
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Cambio de Contrasena</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltituloCambioContrasena">
          <a href="" class="tx-inverse hover-primary">Gestionar Contrasena de Usuario</a>
        </h4>

        <!-- FORMULARIO CAMBIO DE CONTRASENA -->
        <form id="formCambioContrasena">
          <!-- SELECCIONAR USUARIO -->
          <div class="row no-gutters mb-3">
            <div class="col-12 col-lg-3">
              <label for="id_usuarioC" class="form-label">Seleccionar Usuario: <span class="tx-danger">*</span></label>
            </div>
            <div class="col-12 col-lg-9">
              <select class="form-control" name="id_usuarioC" id="id_usuarioC" required style="width: 100%;">
                <option value="">Seleccione un usuario...</option>
              </select>
              <div class="invalid-feedback">Selecciona un usuario válido.</div>
            </div>
          </div>

          <!-- NUEVA CONTRASENA -->
          <div class="row no-gutters mb-3">
              <div class="col-12 col-lg-3">
                  <label for="nueva_contrasena" class="form-label">Nueva Contraseña: <span class="tx-danger">*</span></label>
              </div>
              <div class="col-12 col-lg-9">
                  <div class="input-group" style="width: 100%;">
                      <input type="password" class="form-control" name="nueva_contrasena" id="nueva_contrasena" maxlength="50" placeholder="Nueva contraseña..." required style="flex-grow: 1;">
                      <div class="input-group-append">
                          <button class="btn btn-outline-primary" type="button" id="verNuevaContrasena">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                          </button>
                      </div>
                  </div>
                  <div class="invalid-feedback">Escribe una nueva contraseña.</div>
              </div>
          </div>

          <!-- CONFIRMAR CONTRASENA -->
          <div class="row no-gutters mb-3">
              <div class="col-12 col-lg-3">
                  <label for="confirmar_nueva_contrasena" class="form-label">Confirmar Contraseña: <span class="tx-danger">*</span></label>
              </div>
              <div class="col-12 col-lg-9">
                  <div class="input-group" style="width: 100%;">
                      <input type="password" class="form-control" name="confirmar_nueva_contrasena" id="confirmar_nueva_contrasena" maxlength="50" placeholder="Confirmar contraseña..." required style="flex-grow: 1;">
                      <div class="input-group-append">
                          <button class="btn btn-outline-primary" type="button" id="verConfirmarContrasena">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                          </button>
                      </div>
                  </div>
                  <div class="invalid-feedback">Confirma la nueva contrasena.</div>
              </div>
          </div>
        </form>
      </div>

      <!-- PIE DEL MODAL -->
      <div class="modal-footer">
        <button type="button" name="action" id="btnGuardarContrasena" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">
          <i class="fas fa-save"></i> Guardar Cambios
        </button>
        <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>