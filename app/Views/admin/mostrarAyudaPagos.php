<!-- MODAL AYUDA GESTIÓN DE PAGOS -->
<div class="modal fade" id="modalAyudaGestionPagos" tabindex="-1" aria-labelledby="modalAyudaGestionPagosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">

      <!-- CABECERA DEL MODAL -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="bi bi-wallet2 fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaGestionPagosLabel" style="font-weight: 700; letter-spacing: 0.5px;">
            AYUDA - GESTIÓN DE PAGOS
          </h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Filtros y Vista General</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtro de tipo de pagos</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Permite seleccionar qué pagos ver: <strong>futuros</strong> (predeterminado), <strong>pasados</strong>, <strong>de hoy</strong> o <strong>todos</strong>.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Filtro por fecha específica</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Permite buscar todos los pagos realizados en una fecha concreta.</li>
          </ul>
        </section>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Tabla de gestión de pagos</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Se muestran los pagos agrupados por día.</li>
            <li>Cada fila muestra:
              <ul>
                <li><strong>Fecha del pago</strong></li>
                <li><strong>Total ganado en carreras</strong> ese día</li>
                <li><strong>Número de reservas de carreras</strong> realizadas ese día</li>
                <li><strong>Total ganado en eventos</strong> ese día</li>
                <li><strong>Número de reservas de eventos</strong> ese día</li>
                <li><strong>Total general ganado ese día</strong></li>
              </ul>
            </li>
            <li>El botón <strong>“Más”</strong> permite ver todos los detalles de ese día en la misma pantalla, expandiendo la fila o mostrando un panel detallado.</li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Detalles de pago</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Al usar el botón “Más”, podrás revisar a fondo:
              <ul>
                <li>Fecha exacta del pago.</li>
                <li>Total ganado en carreras y listado detallado de reservas.</li>
                <li>Total ganado en eventos y listado detallado de reservas.</li>
                <li>Resumen del total ganado ese día.</li>
              </ul>
            </li>
            <li>Esto facilita la gestión financiera sin necesidad de cambiar de pantalla.</li>
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
