<div class="modal fade" id="modalMostrarEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header pd-y-20 pd-x-25 bg-primary text-white">
                <h5 class="modal-title" id="modalEventoLabel">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Evento
                </h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-25">
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="detail-item mb-3">
                            <i class="fas fa-tag detail-icon"></i>
                            <div>
                                <div class="detail-label">Nombre</div>
                                <div class="detail-value" id="nombreEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-calendar-alt detail-icon"></i>
                            <div>
                                <div class="detail-label">Tipo</div>
                                <div class="detail-value" id="tipoEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-calendar-day detail-icon"></i>
                            <div>
                                <div class="detail-label">Fecha</div>
                                <div class="detail-value" id="fechaEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-clock detail-icon"></i>
                            <div>
                                <div class="detail-label">Horario</div>
                                <div class="detail-value" id="horarioEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-users detail-icon"></i>
                            <div>
                                <div class="detail-label">Participantes</div>
                                <div class="detail-value" id="participantesEvento"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="detail-item mb-3">
                            <i class="fas fa-euro-sign detail-icon"></i>
                            <div>
                                <div class="detail-label">Total</div>
                                <div class="detail-value text-success fw-bold" id="totalEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-credit-card detail-icon"></i>
                            <div>
                                <div class="detail-label">Método de pago</div>
                                <div class="detail-value" id="metodoPagoEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-receipt detail-icon"></i>
                            <div>
                                <div class="detail-label">Fecha de pago</div>
                                <div class="detail-value" id="fechaPagoEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-info-circle detail-icon"></i>
                            <div>
                                <div class="detail-label">Estado de pago</div>
                                <div class="detail-value" id="estadoPagoEvento"></div>
                            </div>
                        </div>
                        <div class="detail-item mb-3">
                            <i class="fas fa-key detail-icon"></i>
                            <div>
                                <div class="detail-label">ID del pago</div>
                                <div class="detail-value text-muted" id="paymentIntentEvento"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
