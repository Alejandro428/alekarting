<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-labelledby="modalMostrarComercialLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pd-y-20 pd-x-25">
        <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Noticia</h6>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pd-25">
        <h4 class="lh-3 mg-b-20" id="mdltitulo">
          <a href="" class="tx-inverse hover-primary">Modal de Noticias</a>
        </h4>
        <form id="formNoticia" enctype="multipart/form-data">
          <!-- IDCARRERA -->
          <input type="hidden" name="idnoticia" id="idnoticia">
          
          <!-- Contenedor principal con espaciado -->
          <div class="form-container" style="margin-left: 15px; margin-right: 15px;">
            
            <!-- BLOQUE 1: Información Básica -->
            <div class="card mb-4 border-primary">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0 tx-bold">Información Básica</h5>
              </div>
              <div class="card-body">
                <!-- TITULO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="titulo" class="form-label">Título: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="text" class="form-control w-100" name="titulo" id="titulo" maxlength="90" placeholder="Título Elemento" required autofocus>
                    <div class="invalid-feedback small-invalid-feedback">
                      Solo letras y espacios (mínimo 3 caracteres y máximo de 90)
                    </div>
                  </div>
                </div>

                <!-- SUBTITULO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="subtitulo" class="form-label">Subtítulo: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="text" class="form-control w-100" name="subtitulo" id="subtitulo" maxlength="255" placeholder="Subtítulo Elemento" required>
                    <div class="invalid-feedback small-invalid-feedback">
                      Solo letras y espacios (mínimo 3 caracteres y máximo de 255)
                    </div>
                  </div>
                </div>

                <!-- CONTENIDO -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="contenido" class="form-label">Contenido: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <!-- Editor Summernote (sin input oculto) -->
                    <div id="contenido" name="contenido" class="form-control w-100 summernote-editor" style="min-height: 200px;"></div>
                    <div class="invalid-feedback small-invalid-feedback">
                      El contenido es requerido
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- BLOQUE 2: Fechas y Otros -->
            <div class="card mb-4 border-info">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0 tx-bold">Fechas y Archivos</h5>
              </div>
              <div class="card-body">
                <!-- FECHA PUBLICACIÓN -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="fecha_publicacion" class="form-label">Fecha Publicación: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9" style="position: relative;">
                    <div class="input-group w-100">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                        </div>
                      </div>
                      <input id="fecha_publicacion" name="fecha_publicacion" type="text" 
                            class="form-control fc-datepicker" placeholder="dd-mm-aaaa" readonly required>
                    </div>
                    <div class="invalid-feedback" id="fecha-error" style="display: none;">
                      Por favor, seleccione una fecha de publicación.
                    </div>
                    <div class="tx-8 tx-info" id="borrarFechaPublicacion">Borrar fecha</div>
                  </div>
                </div>

                <!-- CATEGORÍA -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="id_categoria" class="form-label">Categoría: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <select id="id_categoria" name="id_categoria" class="form-control w-100" required>
                      <!-- Vacío, se llenará vía AJAX -->
                    </select>
                    <div class="invalid-feedback small-invalid-feedback">
                      Por favor selecciona una categoría.
                    </div>
                  </div>
                </div>

                <!-- IMAGEN -->
                <div class="form-group row mb-3">
                  <div class="col-12 col-lg-3">
                    <label for="imagen" class="form-label">Imagen: <span class="tx-danger">*</span></label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="file" id="imagen" name="imagen" class="form-control w-100" accept="image/jpeg, image/png, image/webp" required>
                    <div class="invalid-feedback small-invalid-feedback">
                      Imagen requerida (JPEG, PNG o WEBP, max. 2MB, min. 800x600px)
                    </div>
                    <small class="form-text text-muted">Formatos: JPEG, PNG, WEBP (Max. 2MB, Mín. 800×600px)</small>
                  </div>
                </div>

                <!-- VIDEO -->
                <div class="form-group row mb-0">
                  <div class="col-12 col-lg-3">
                    <label for="video" class="form-label">Video:</label>
                  </div>
                  <div class="col-12 col-lg-9">
                    <input type="file" id="video" name="video" class="form-control w-100" accept="video/mp4">
                    <div class="invalid-feedback small-invalid-feedback">
                      Solo MP4 (max. 7MB, max. 2min)
                    </div>
                    <small class="form-text text-muted">Formato: MP4 (Max. 7MB, Max. 2min)</small>
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