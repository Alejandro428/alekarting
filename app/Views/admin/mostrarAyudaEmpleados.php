<!-- MODAL AYUDA GESTIÓN DE EMPLEADOS -->
<div class="modal fade" id="modalAyudaGestionEmpleados" tabindex="-1" aria-labelledby="modalAyudaGestionEmpleadosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">

      <!-- CABECERA DEL MODAL -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-user-cog fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaGestionEmpleadosLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - GESTIÓN DE EMPLEADOS</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Filtros y Acciones</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtro de empleados</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Filtra empleados por su estado: <strong>activos</strong>, <strong>inactivos</strong> o <strong>todos</strong>.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Acciones sobre empleados y administradores</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Nuevo administrador:</strong> Se crea introduciendo correo, contraseña y datos básicos. Recibe un correo con sus credenciales y contraseña provisional. Se recomienda cambiarla al iniciar sesión.</li>
            <li><strong>Nuevo empleado:</strong> Similar al anterior, pero se deben asignar uno o más roles (es obligatorio al menos uno). Se envía un correo con sus datos y contraseña provisional.</li>
            <li><strong>Cambiar contraseña - empleados:</strong> Permite seleccionar un empleado y establecer una nueva contraseña. <strong>No se permite reutilizar la misma contraseña anterior</strong>. Se notifica al empleado por correo.</li>
            <li><strong>Cambiar contraseña - administradores:</strong> Igual que con empleados, se debe establecer una nueva contraseña distinta a la anterior. <strong>Reutilizar la misma contraseña no está permitido</strong>. Se notifica al administrador por correo.</li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Tabla de empleados</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Botón "más":</strong> Muestra todos los detalles del empleado.</li>
            <li><strong>Activar / Desactivar:</strong> Solo se puede desactivar un empleado si <strong>no tiene roles asignados</strong> y <strong>no tiene tareas pendientes</strong> como eventos o carreras. Se notifica por correo tanto al activarlo como al desactivarlo.</li>
            <li><strong>Rol Noticias:</strong> Permite o restringe la creación de noticias por parte del empleado. Cada vez que se le otorga o retira este rol, se le notifica por correo.</li>
            <li><strong>Rol Eventos:</strong> Este rol permite al empleado crear y gestionar sus propios eventos. <strong>Para desactivarlo, el administrador debe reasignar todos los eventos actualmente asignados a este empleado a otro empleado con rol de eventos</strong>. Se notifica al empleado del cambio.</li>
            <li><strong>Rol Carreras:</strong> Permite crear y gestionar sus propias carreras. <strong>Para desactivarlo, primero deben reasignarse todas las carreras que tenga asignadas a otro empleado con rol de carreras</strong>. Al realizar el cambio, se notifica al empleado por correo.</li>
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
