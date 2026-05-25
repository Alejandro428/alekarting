<!-- MODAL AYUDA GESTIÓN DE CARRERAS ADMIN -->
<div class="modal fade" id="modalAyudaGestionCarreras" tabindex="-1" aria-labelledby="modalAyudaGestionCarrerasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
      
      <!-- CABECERA DEL MODAL CON DEGRADADO -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-flag-checkered fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaGestionCarrerasLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - GESTIÓN DE CARRERAS EMPLEADO</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Filtros y Acciones</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtros disponibles</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Filtro por estado:</strong> Muestra carreras futuras (por defecto), pasadas, las de hoy o todas.</li>
            <li><strong>Filtro por fecha:</strong> Permite buscar carreras programadas para una fecha específica.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Crear nuevas carreras</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>El empleado define la reserva de la carrera, y todos los detalles que son necesarios. (La carrera es asignada al empleado con menos carreras asignadas durante esa semana)</li>
            <li>Una vez creada la reserva de la carrera, se envía un correo al usuario con los detalles de su reserva, eso si, la reserva sale como no pagada.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Gestión de carreras</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Al crear una reserva de carrera, se marca como no pagada y se envía un correo al usuario con los detalles.</li>
            <li>Se puede <strong>marcar una reserva como pagada</strong> desde el panel. Esto también genera una notificación por correo.</li>
            <li>Editar una reserva está permitido si la carrera no ha pasado. Solo se puede cambiar la fecha u horario.</li>
            <li>Eliminar una reserva solo es posible si no ha sido pagada y la carrera no ha pasado.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Tabla de carreras</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Botón "más":</strong> Permite ver los detalles completos de la carrera, el precio, el horario, entre otros.</li>
            <li><strong>Editar:</strong> Disponible solo si la carrera no ha pasado. Solo se puede modificar la fecha y el horario.</li>
            <li><strong>Eliminar:</strong> Solo si la carrera aún no tiene reservas asociadas.</li>
            <li><strong>Pagar:</strong> Se puede pagar las carreras que aun no han sido pagadas, al hacerlo se notifica al usuario. Si ya está pagada, muestra los detalles de la fecha del pago.</li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Notificaciones</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Se notificará por correo al usuario cada vez que se cree, edite, elimine o marque como pagada una reserva.</li>
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
