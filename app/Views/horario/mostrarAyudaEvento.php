<!-- MODAL AYUDA CALENDARIO DE EVENTOS -->
<div class="modal fade" id="modalAyudaEventos" tabindex="-1" aria-labelledby="modalAyudaEventosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
      
      <!-- CABECERA DEL MODAL CON DEGRADADO -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-calendar-alt fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaEventosLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - CALENDARIO DE EVENTOS</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Restricciones y Leyenda de Colores</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Restricciones del Calendario</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>Solo se muestran eventos próximos; los eventos pasados no se visualizan.</li>
            <li>No se puede retroceder a meses anteriores al mes actual.</li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Leyenda de Colores - Días</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><span class="color-dia verde-oscuro"></span> Día con eventos disponibles sin reservas hechas.</li>
            <li><span class="color-dia gris"></span> Día con eventos que tienen reservas de otros usuarios.</li>
            <li><span class="color-dia azul"></span> Día con al menos un evento reservado por ti (sesión actual).</li>
            <li><span class="color-dia verde-claro"></span> Día sin eventos.</li>
            <li><span class="color-dia rojo"></span> Día pasado o con eventos totalmente reservados.</li>
          </ul>
        </section>

        <section class="mt-3">
          <h5 style="color: #17a2b8; font-weight: 600;">Leyenda de Colores - Eventos</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><span class="color-evento verde"></span> Evento sin reservas hechas.</li>
            <li><span class="color-evento gris"></span> Evento con reservas hechas por otros usuarios.</li>
            <li><span class="color-evento azul"></span> Evento reservado por ti (clic para ver detalles).</li>
            <li><span class="color-evento rojo"></span> Evento totalmente reservado.</li>
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

<style>
  .color-dia, .color-evento {
    display: inline-block;
    width: 18px;
    height: 18px;
    margin-right: 8px;
    vertical-align: middle;
    border-radius: 4px;
  }

  /* Colores para días */
  .color-dia.verde-oscuro { background-color: #388e3c; } /* verde oscuro no muy oscuro */
  .color-dia.gris { background-color: #9e9e9e; }
  .color-dia.azul { background-color: #007bff; }
  .color-dia.verde-claro { background-color: #c8e6c9; }
  .color-dia.rojo { background-color: #f44336; }

  /* Colores para eventos */
  .color-evento.verde { background-color: #4caf50; }
  .color-evento.gris { background-color: #9e9e9e; }
  .color-evento.azul { background-color: #2196f3; }
  .color-evento.rojo { background-color: #f44336; }
</style>
