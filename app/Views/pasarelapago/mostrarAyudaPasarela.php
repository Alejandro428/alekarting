<!-- MODAL AYUDA PASARELA DE PAGO -->
<div class="modal fade" id="modalAyudaPasarela" tabindex="-1" aria-labelledby="modalAyudaPasarelaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">

      <!-- CABECERA DEL MODAL -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-credit-card fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaPasarelaLabel" style="font-weight: 700; letter-spacing: 0.5px;">
            AYUDA - RESERVA Y PASARELA DE PAGO
          </h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc; color: #2c3e50; font-size: 1rem;">

        <p>Al abrir el modal de reserva y pago, en dispositivos con pantalla amplia, verás dos columnas (si no, todo se muestra de arriba a abajo):</p>
        <ul>
          <li><strong>Izquierda:</strong> Información detallada de la reserva o evento que deseas hacer (como la carrera o evento, fecha, precio).</li>
          <li><strong>Derecha:</strong> Las opciones para pagar tu reserva:
            <ul>
              <li>Pagar con tarjeta: puedes introducir los datos de una tarjeta nueva o usar un <em>link</em> para que tus tarjetas queden registradas en la cuenta de correo que pongas, y así no tengas que introducir los datos cada vez.</li>
              <li>Pagar con PayPal: serás redirigido a la plataforma de PayPal donde solo debes ingresar tu nombre y aceptar el pago.</li>
            </ul>
          </li>
        </ul>

        <p class="info-pago">Para el pago con tarjeta, usamos Stripe y estos son los <strong>códigos de prueba</strong> que puedes usar para simular distintos escenarios:</p>
        <ul>
          <li>Pago aprobado normal: <span class="codigo-prueba">4111 1111 1111 1111</span></li>
          <li>Pago con confirmación 3D Secure: <span class="codigo-prueba">4000 0027 6000 3184</span></li>
          <li>Tarjeta rechazada: <span class="codigo-prueba">4000 0000 0000 0002</span></li>
        </ul>

        <p>Si el pago con tarjeta es exitoso, verás un mensaje de confirmación en pantalla, y a continuación recibirás un correo con los detalles de tu reserva.</p>

        <p>Luego, en tu perfil, podrás revisar el historial de tus reservas y los pagos realizados.</p>

        <p>Si eliges pagar con PayPal, se abrirá la plataforma de PayPal para completar el pago poniendo solo tu nombre y aceptando el cargo. Una vez finalizado, te mostrará que el pago ha sido completado, igual que en el pago con tarjeta.</p>

        <p>Aclarar que si el usuario hace su reserva con pasarela, este pago directamente estará como pagado.</p>
      </div>

      <!-- PIE DEL MODAL -->
      <div class="modal-footer border-0" style="background: #f1f5f9;">
        <button type="button" class="btn btn-primary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">
           👍 Entendido
        </button>
      </div>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

  <style>
    .info-pago {
      color: #17a2b8;
      font-weight: 600;
      margin-top: 1rem;
      margin-bottom: 0.5rem;
    }
    .codigo-prueba {
      background-color: #e9f7fc;
      padding: 4px 8px;
      border-radius: 5px;
      font-family: monospace;
      color: #0b4f6c;
    }
  </style>
</div><!-- /.modal -->
