<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasEventosModel extends Model
{
    protected $table      = 'reservas_eventos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'usuario_id', 
        'evento_id', 
        'cantidad', 
        'total',       
        'metodo_pago', 
        'fecha_pago',
        'pagado',
        'payment_intent_id'
    ];
/* MÉTODO QUE USABA ANTES AL DESACTIVAR AL USUARIO EN EL JS DE GESTIÓN USUARIOS, IDEA DESCARTADA, YA QUE NO ME INTERESA BORRAR RESERVAS 
   HECHAS A FUTURO, BORRAR RESERVAS NO TIENE SENTIDO
    public function eliminarEventosFuturos($usuario_id)
{
    // Obtener fecha y hora actual del servidor
    $fecha_actual = date('Y-m-d'); // Formato: 2025-03-28
    $hora_actual = date('H:i:s');  // Formato: 08:00:00
    
    // Primero obtener los IDs de las reservas futuras
    $builder = $this->db->table('reservas_eventos')
        ->select('reservas_eventos.id')
        ->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left')
        ->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left')
        ->where('reservas_eventos.usuario_id', $usuario_id)
        ->groupStart()
            ->where('eventos.fecha >', $fecha_actual) // Fechas posteriores
            ->orGroupStart()
                ->where('eventos.fecha', $fecha_actual) // Mismo día
                ->where('franjas_horarias.hora_inicio >', $hora_actual) // Horas posteriores
            ->groupEnd()
        ->groupEnd();
    
    $ids = $builder->get()->getResultArray();
    $ids = array_column($ids, 'id');

    // Eliminar solo si hay registros que coincidan
    if (!empty($ids)) {
        return $this->db->table('reservas_eventos')
            ->whereIn('id', $ids)
            ->delete();
    }
    
    return true;
}
*/

// MÉTODO REFERENCIADO EN js de gestionEventos, nuevoEventoEmpleado, eliminar la reserva del evento
// CONTROLADOR EmpleadoEventos, MÉTODO eliminarReserva

public function eliminarReserva($id)
{
    $builder = $this->db->table('reservas_eventos');
    $builder->where('id', $id);
    return $builder->delete();
}

// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) comprobar si al usuario que se le
// quiere hacer la reserva ya esta registrado en el evento, CONTROLADOR EmpleadoEventos, MÉTODO comprobarReservaExistente
public function existeReservaDuplicada($usuario_id, $evento_id, $id_reserva_actual = null)
{
    $builder = $this->where('usuario_id', $usuario_id)
                    ->where('evento_id', $evento_id);

    if (!empty($id_reserva_actual)) {
        $builder->where('id !=', $id_reserva_actual); // Ignora la reserva que se está editando
    }

    return $builder->countAllResults() > 0;
}

// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) confirmar el pago de la reserva del evento
// CONTROLADOR EmpledoEventos, MÉTODO confirmarPago
public function marcarComoPagado($idReserva)
{
    $db = \Config\Database::connect();
    
    // 1. Verificar que la reserva existe y no está pagada
    $builder = $db->table($this->table)
                 ->select('pagado')
                 ->where('id', $idReserva);
    
    $reserva = $builder->get()->getRow();
    
    if (!$reserva) {
        throw new \Exception('Reserva no encontrada');
    }

    if ($reserva->pagado == 1) {
        throw new \Exception('La reserva ya está marcada como pagada');
    }

    // 2. Actualización directa con Query Builder
    $builder = $db->table($this->table)
                 ->where('id', $idReserva);
    
    $datosActualizar = [
        'pagado' => 1,
        'fecha_pago' => date('Y-m-d H:i:s')
    ];
    
    $resultado = $builder->update($datosActualizar);

    // 3. Verificación explícita de filas afectadas
    if ($db->affectedRows() === 0) {
        log_message('error', 'Error al marcar reserva como pagada. ID: ' . $idReserva);
        throw new \Exception('No se afectaron filas al actualizar');
    }

    return true;
}

}