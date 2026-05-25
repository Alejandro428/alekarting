<!-- MODAL AYUDA GESTIÓN DE USUARIOS -->
<div class="modal fade" id="modalAyudaUsuarios" tabindex="-1" aria-labelledby="modalAyudaUsuariosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">

      <!-- CABECERA DEL MODAL -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-users fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaUsuariosLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - GESTIÓN DE USUARIOS</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Filtros y Acciones</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtro de usuarios</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Permite filtrar los usuarios por su estado: <strong>activos</strong>, <strong>inactivos</strong> o <strong>todos</strong>.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Acciones de usuario</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Crear nuevo usuario:</strong> El administrador introduce los datos del usuario (nombre, correo, etc.). Se envían las credenciales al correo ingresado con una contraseña provisional. Se recomienda al usuario cambiar la contraseña al iniciar sesión.</li>
            <li><strong>Cambiar contraseña de un usuario:</strong> Se selecciona un usuario al que se le desea cambiar la contraseña. No se permite usar la misma contraseña anterior. El usuario recibirá una notificación por correo del cambio.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Tabla de usuarios</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Botón "más":</strong> Permite ver los detalles completos del usuario.</li>
            <li><strong>Activar / Desactivar:</strong> Cambia el estado del usuario. Los usuarios desactivados no pueden iniciar sesión. Se notificará por correo tanto al activarlo como al desactivarlo.</li>
            <li><strong>Editar:</strong> Se pueden modificar todos los datos del usuario. Si se cambia su contraseña, se le notificará además del cambio de datos.</li>
          </ul>
        </section>

      </div>

      <!-- PIE DEL MODAL -->
      <div class="modal-footer border-0" style="background: #f1f5f9;">
        <button type="button" class="btn btn-primary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">
          👍 Entendido
        </button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
