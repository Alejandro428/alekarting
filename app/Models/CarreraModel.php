<?php
namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table = 'carreras';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_usuario',
        'empleado_id',
        'id_pistas',
        'fecha',
        'franja_horaria_id',
        'num_participantes', 
        'cantidad',
        'metodo_pago',
        'fecha_pago',
        'pagado',
        'payment_intent_id'
    ];

    /**
     * Inserta una carrera en la base de datos luego de validar los datos.
     * array $datos Datos completos de la carrera.
     */
    public function insertarCarrera($datos)
    {
        // Validaciones básicas
        $requiredFields = [
            'id_usuario',
            'empleado_id',
            'id_pistas',
            'fecha',
            'franja_horaria_id',
            'num_participantes',
            'cantidad'
        ];

        foreach ($requiredFields as $field) {
            if (empty($datos[$field])) {
                log_message('error', "Falta el campo obligatorio: $field");
                return false;
            }
        }

        // Validar que 'num_participantes' y 'cantidad' sean numéricos
        if (!is_numeric($datos['num_participantes']) || !is_numeric($datos['cantidad'])) {
            log_message('error', "Los campos num_participantes y cantidad deben ser numéricos");
            return false;
        }

        // Validar el formato de la fecha (se espera "YYYY-MM-DD")
        $d = \DateTime::createFromFormat("Y-m-d", $datos['fecha']);
        if (!($d && $d->format("Y-m-d") === $datos['fecha'])) {
            log_message('error', "El formato de la fecha es inválido");
            return false;
        }

        // Insertar la carrera y retornar true o false según el resultado
        $result = $this->insert($datos);
        return ($result !== false);
    }
/*
MÉTODO QUE USABA ANTES AL DESACTIVAR AL USUARIO EN EL JS DE GESTIÓN USUARIOS, IDEA DESCARTADA, YA QUE NO ME INTERESA BORRAR RESERVAS 
   HECHAS A FUTURO, BORRAR RESERVAS NO TIENE SENTIDO
   
    public function eliminarCarrerasFuturas($usuarioId)
{
    // Obtener fecha y hora actual del servidor
    $fecha_actual = date('Y-m-d'); // Formato: 2025-03-28
    $hora_actual = date('H:i:s');  // Formato: 08:00:00
    
    // Primero obtener los IDs de las carreras futuras
    $builder = $this->db->table('carreras')
        ->select('carreras.id')
        ->join('franjas_horarias', 'carreras.franja_horaria_id = franjas_horarias.id')
        ->where('carreras.id_usuario', $usuarioId)
        ->groupStart()
            ->where('carreras.fecha >', $fecha_actual) // Fechas posteriores
            ->orGroupStart()
                ->where('carreras.fecha', $fecha_actual) // Mismo día
                ->where('franjas_horarias.hora_inicio >', $hora_actual) // Horas posteriores
            ->groupEnd()
        ->groupEnd();
    
    $ids = $builder->get()->getResultArray();
    $ids = array_column($ids, 'id');

    // Eliminar solo si hay registros que coincidan
    if (!empty($ids)) {
        return $this->db->table('carreras')
            ->whereIn('id', $ids)
            ->delete();
    }
    
    return true;
}
*/

// VERIFICO QUE EL DIA Y HORARIO SELECCIONADO QUE SE PASA DESDE LA URL DESDE
// EL JS DE horarioCarrera ESTA DISPONIBLE, MÉTODO USADO POR EL MÉTODO verificarDisponibilidadHorario
// DEL CONTROLADOR DE CARRERAS
public function verificarDisponibilidad($fecha, $franja_horaria_id)
{
    // 1. Obtener la franja horaria
    $franja = $this->db->table('franjas_horarias')
                      ->select('hora_inicio')
                      ->where('id', $franja_horaria_id)
                      ->get()
                      ->getRow();
    
    if (!$franja) {
        return false; // Si no existe la franja horaria
    }
    
    // 2. Verificar si el horario ya pasó o está comenzando ahora
    $horaActual = date('H:i:s');
    $fechaActual = date('Y-m-d');
    
    // Comparación exacta incluyendo el mismo minuto
    if ($fecha < $fechaActual || 
       ($fecha == $fechaActual && $franja->hora_inicio <= $horaActual)) {
        return false;
    }
    
    // 3. Verificar disponibilidad en carreras
    $count = $this->db->table('carreras')
                     ->where('fecha', $fecha)
                     ->where('franja_horaria_id', $franja_horaria_id)
                     ->countAllResults();
    
    return $count === 0;
}

}