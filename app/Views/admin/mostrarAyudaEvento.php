<!-- MODAL AYUDA GESTIÓN DE EVENTOS ADMIN -->
<div class="modal fade" id="modalAyudaGestionEventos" tabindex="-1" aria-labelledby="modalAyudaGestionEventosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
      
      <!-- CABECERA DEL MODAL CON DEGRADADO -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-theater-masks fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaGestionEventosLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - GESTIÓN DE EVENTOS ADMINISTRADOR</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        
        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Filtros y Acciones</h4>
        
        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtros disponibles</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Filtro por estado temporal: Eventos futuros (por defecto), pasados, todos o los de hoy.</li>
            <li>Búsqueda por fecha específica para localizar eventos de un día concreto.</li>
          </ul>
        </section>
        
        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Acciones permitidas</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Tipos de evento:</strong> Crear, editar o eliminar. No se permite eliminar tipos que tengan eventos asociados ni duplicar nombres.</li>
            <li><strong>Crear eventos:</strong> Selección de empleado responsable y detalles completos del evento. El evento queda disponible inmediatamente para recibir reservas.</li>
            <li><strong>Crear reservas:</strong> El administrador puede realizar reservas a usuarios. Se especifica el evento, el usuario, número de personas, etc. Se envía un correo con los detalles. La reserva queda como no pagada inicialmente.</li>
            <li><strong>Gestión de pagos:</strong> El administrador puede marcar reservas como pagadas posteriormente. Esto notifica al usuario por correo.</li>
          </ul>
        </section>
        
        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Tabla de eventos</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><strong>Botón "más":</strong> Muestra los detalles del evento, reservas totales, reservas pagadas y el total recaudado.</li>
            <li><strong>Editar:</strong> Disponible solo si el evento no es pasado. Se pueden modificar todos los campos excepto la capacidad y el precio.</li>
            <li><strong>Eliminar:</strong> Solo si el evento no tiene reservas asociadas.</li>
            <li><strong>Botón reservas:</strong> Al hacer clic, se cargan en la tabla de la derecha (vista escritorio) todas las reservas del evento seleccionado. Desde allí se pueden ver detalles, marcar como pagada o consultar la fecha de pago.</li>
          </ul>
        </section>
        
        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Notificaciones</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Se envían correos automáticamente al crear reservas y al marcarlas como pagadas.</li>
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
