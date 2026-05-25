<div class="modal fade" id="modalMostrarEvento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEventoLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="details-container">
          <!-- Sección de información principal -->
          <div class="detail-section mb-3">
            <h6 class="detail-section-title bg-light p-2 rounded">
              <i class="bi bi-info-circle me-2"></i>Información Básica
            </h6>
            <div class="detail-grid">
              <div class="detail-item">
                <span class="detail-label">ID Reserva:</span>
                <span class="detail-value" id="detalle-id">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Evento:</span>
                <span class="detail-value" id="detalle-evento">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Tipo:</span>
                <span class="detail-value" id="detalle-tipo">N/D</span>
              </div>
            </div>
          </div>
          
                  <!-- Sección de pago -->
          <div class="detail-section">
            <h6 class="detail-section-title bg-light p-2 rounded">
              <i class="bi bi-credit-card me-2"></i>Información de Pago
            </h6>
            <div class="detail-grid">
              <div class="detail-item">
                <span class="detail-label">Método:</span>
                <span class="detail-value" id="detalle-metodo">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Cantidad:</span>
                <span class="detail-value" id="detalle-cantidad">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Fecha Pago:</span>
                <span class="detail-value" id="detalle-fecha">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Total:</span>
                <span class="detail-value fw-bold text-success" id="detalle-total">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">¿Pagado?:</span>
                <span class="detail-value fw-bold" id="detalle-pagado">N/D</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">ID de Stripe:</span>
                <span class="detail-value" id="detalle-payment-intent">N/D</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>