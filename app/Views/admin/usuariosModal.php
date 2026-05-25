<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalMostrarCarreraLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Usuarios</h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltitulo">
          <a href="" class="tx-inverse hover-primary">Modal de usuarios</a>
        </h4>
        <form id="formUsuario">
          <!-- ID USUARIO -->
          <input type="hidden" name="id" id="id">
          
          <!-- Contenedor principal sin márgenes laterales -->
          <div class="form-container">
            
            <!-- BLOQUE 1: Información Básica -->
            <div class="card mb-4 border-primary">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0 tx-bold">Información Básica</h5>
              </div>
              <div class="card-body">
                <!-- NOMBRE USUARIO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="nombre_usuario" class="form-label">Nombre Usuario: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" maxlength="30" placeholder="Nombre usuario..." required>
                    <div class="invalid-feedback small-invalid-feedback">Escribe el nombre de usuario</div>
                  </div>
                </div>

                <!-- NOMBRE -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="nombre" class="form-label">Nombre: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="30" placeholder="Nombre..." required>
                    <div class="invalid-feedback small-invalid-feedback">Escribe el nombre</div>
                  </div>
                </div>

                <!-- APELLIDOS -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="apellidos" class="form-label">Apellidos: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="text" class="form-control" name="apellidos" id="apellidos" maxlength="50" placeholder="Apellidos..." required>
                    <div class="invalid-feedback small-invalid-feedback">Escribe los apellidos</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- BLOQUE 2: Datos de Contacto -->
            <div class="card mb-4 border-info">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0 tx-bold">Datos de Contacto</h5>
              </div>
              <div class="card-body">
                <!-- EMAIL -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="email" class="form-label">Email: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="email" class="form-control" name="email" id="email" maxlength="40" placeholder="Email" required>
                    <div class="invalid-feedback small-invalid-feedback">Formato de email válido (sin espacios)</div>
                  </div>
                </div>

                <!-- TELEFONO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="telefono" class="form-label">Teléfono: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="tel" class="form-control" name="telefono" id="telefono" maxlength="9" placeholder="Teléfono" required>
                    <div class="invalid-feedback small-invalid-feedback">Solo números (9 necesarios)</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- BLOQUE 3: Seguridad -->
            <div class="card mb-4 border-success">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0 tx-bold">Seguridad</h5>
              </div>
              <div class="card-body">
                              <!-- CONTRASEÑA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="contraseña" class="form-label">Contraseña: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <div class="input-group">
                      <input type="password" class="form-control" name="contraseña" id="contraseña" maxlength="30" placeholder="Contraseña" required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="verContraseña">
                          <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                      </div>
                    </div>
                    <div class="invalid-feedback small-invalid-feedback">La contraseña debe tener al menos 6 caracteres</div>
                  </div>
                </div>

                <!-- CONFIRMAR CONTRASEÑA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <div class="input-group">
                      <input type="password" class="form-control" name="confirmar_contraseña" id="confirmar_contraseña" maxlength="30" placeholder="Repite la contraseña" required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="verConfirmarContraseña">
                          <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                      </div>
                    </div>
                    <div class="invalid-feedback small-invalid-feedback">Las contraseñas no coinciden</div>
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