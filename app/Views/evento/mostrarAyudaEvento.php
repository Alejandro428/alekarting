<!-- MODAL AYUDA EVENTOS - VERSIÓN ACTUALIZADA -->
<div class="modal fade" id="modalAyudaEventos" tabindex="-1" aria-labelledby="modalAyudaEventosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
      
      <!-- CABECERA -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-theater-masks fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaEventosLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - EVENTOS</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      
      <!-- CUERPO -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">¿Cómo funciona el apartado de eventos?</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtrado y búsqueda</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Puedes filtrar los eventos por <strong>tipo</strong> (categoría) y realizar una <strong>búsqueda por texto</strong>.</li>
            <li>La búsqueda se autocompleta y tiene en cuenta los filtros aplicados.</li>
            <li>También puedes <strong>ordenar</strong> los resultados por fecha: de más reciente a más antiguo (dentro de los eventos próximos).</li>
            <li>Marcando el <strong>check "Solo eventos disponibles"</strong> se ocultan los eventos que ya están completos.</li>
            <li>Existe una opción para limpiar todos los filtros aplicados.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Listado y botones de estado</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Cada evento muestra su información completa: nombre, fecha, hora, tipo, etc.</li>
            <li>Los botones de acción de cada evento indican su estado:
              <ul>
                <li><strong>Reservar:</strong> puedes iniciar una reserva para ese evento.</li>
                <li><strong>Completo:</strong> el evento no admite más reservas.</li>
                <li><strong>Ya reservado:</strong> ya tienes una reserva en este evento (puedes hacer clic para ver los detalles).</li>
              </ul>
            </li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Proceso de reserva</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Al hacer clic en <strong>Reservar</strong>, podrás elegir la <strong>cantidad de personas</strong> para la reserva.</li>
            <li>Luego, se mostrará un paso de <strong>confirmación</strong> donde deberás aceptar la <strong>política de privacidad y condiciones</strong>.</li>
            <li>Una vez aceptado, se te redirigirá automáticamente a la <strong>pasarela de pago</strong> para completar la reserva.</li>
          </ul>
        </section>

      </div>
      
      <!-- PIE -->
      <div class="modal-footer border-0" style="background: #f1f5f9;">
        <button type="button" class="btn btn-primary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">
           👍 Entendido
        </button>
      </div>

    </div>
  </div>
</div>
