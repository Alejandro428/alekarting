<!-- MODAL AYUDA CALENDARIO DE CARRERAS - VERSIÓN SIMPLIFICADA Y PERSONALIZADA -->
<div class="modal fade" id="modalAyudaCarreras" tabindex="-1" aria-labelledby="modalAyudaCarrerasLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content overflow-hidden" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
      
      <!-- CABECERA DEL MODAL CON DEGRADADO -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #2c3e50 100%);">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-flag-checkered fa-2x me-3"></i>
          <h5 class="modal-title mb-0" id="modalAyudaCarrerasLabel" style="font-weight: 700; letter-spacing: 0.5px;">AYUDA - CALENDARIO DE CARRERAS</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      
      <!-- CUERPO DEL MODAL -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">

        <h4 class="text-center mb-4" style="color: #2c3e50; font-weight: 600;">Restricciones y Leyenda de Colores</h4>

        <section class="mb-4">
          <h5 style="color: #17a2b8; font-weight: 600;">Restricciones del Calendario</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li>No se puede retroceder a meses anteriores al mes actual.</li>
            <li>No se pueden hacer reservas para fechas posteriores a 2 años desde hoy.</li>
            <li>Si al intentar hacer la reserva, no hay empleados de carreras operativos en este momento, no se podrá realizar la reserva.</li>
            <li>Se puede seleccionar cualquier fecha válida, pero al hacer la reserva se validará que cumpla con las reglas.</li>
          </ul>
        </section>

        <section>
          <h5 style="color: #17a2b8; font-weight: 600;">Leyenda de Colores - Días</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><span class="color-dia verde"></span> Todos los horarios del día están libres.</li>
            <li><span class="color-dia gris"></span> El día tiene reservas.</li>
            <li><span class="color-dia azul"></span> El día tiene una reserva hecha por ti (sesión actual).</li>
            <li><span class="color-dia rojo"></span> Día totalmente reservado o ya pasado.</li>
          </ul>
        </section>

        <section class="mt-3">
          <h5 style="color: #17a2b8; font-weight: 600;">Leyenda de Colores - Horarios</h5>
          <ul style="color: #444; font-size: 1rem; line-height: 1.5;">
            <li><span class="color-horario verde"></span> Horario disponible.</li>
            <li><span class="color-horario gris"></span> Horario del día actual que ya pasó.</li>
            <li><span class="color-horario rojo-oscuro"></span> Horario reservado y pasado.</li>
            <li><span class="color-horario azul"></span> Horario reservado por ti (clic para ver resumen).</li>
            <li><span class="color-horario rojo"></span> Horario reservado por otro usuario.</li>
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
  .color-dia, .color-horario {
    display: inline-block;
    width: 18px;
    height: 18px;
    margin-right: 8px;
    vertical-align: middle;
    border-radius: 4px;
  }

  /* Colores para días */
  .color-dia.verde { background-color: #a8e063; }
  .color-dia.gris { background-color: #cccccc; }
  .color-dia.azul { background-color: #007bff; }
  .color-dia.rojo { background-color: #f44336; }

  /* Colores para horarios */
  .color-horario.verde { background: linear-gradient(135deg, #a8e063, #56ab2f); }
  .color-horario.gris { background-color: #9e9e9e; }
  .color-horario.rojo-oscuro { background-color: #b71c1c; }
  .color-horario.azul { background-color: #2196f3; }
  .color-horario.rojo { background-color: #f44336; }
</style>
