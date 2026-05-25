<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCarrerasModel extends Model
{
    protected $table = 'carreras';          
    protected $primaryKey = 'id';          

    protected $allowedFields = [
        'id_usuario',         // ID del usuario que hace la reserva
        'empleado_id',        // ID del empleado
        'id_pistas',          // ID de la pista
        'fecha',              // Fecha de la carrera
        'franja_horaria_id',  // ID de la franja horaria
        'num_participantes',  // Número de participantes
        'cantidad',           // Importe o cantidad
        'metodo_pago',        // Método de pago
        'fecha_pago',          // Fecha de pago
        'pagado',
        'payment_intent_id',
    ];

      /**
     * MÉTODO REFERENCIADO EN js de nuevoCarrerasEmpleado (empleado), CONTROLADOR EmpleadoCarreras, Método obtenerReservasEmpleado 
     * se encarga de obtener todas las reservas que tiene el empleado de carreras asociadas 
     */
    public function obtenerReservasEmpleado($empleadoId)
    {
        $builder = $this->db->table('carreras');
        
        $builder->select('
            carreras.id,
            carreras.id_usuario,
            carreras.empleado_id,
            carreras.id_pistas,
            carreras.fecha as fecha_carrera,
            carreras.franja_horaria_id,
            carreras.num_participantes,
            carreras.cantidad as precio,
            carreras.metodo_pago,
            carreras.fecha_pago,
            carreras.pagado,
            carreras.payment_intent_id,
            franjas_horarias.hora_inicio,
            franjas_horarias.hora_fin,
            franjas_horarias.descripcion AS descripcion_franja,
            pistas.nombre AS nombre_pista,
            usuarios.nombre_usuario AS usuario_empleado,
            usuarios.nombre AS nombre_empleado,
            usuarios.apellidos AS apellidos_empleado,
            cliente.nombre_usuario AS usuario_cliente,
            cliente.nombre AS nombre_cliente,
            cliente.apellidos AS apellidos_cliente,
            cliente.email AS email_cliente
        ');
    
        // Joins optimizados
        $builder->join('franjas_horarias', 'franjas_horarias.id = carreras.franja_horaria_id', 'left');
        $builder->join('pistas', 'pistas.id = carreras.id_pistas', 'left');
        $builder->join('empleados', 'empleados.id = carreras.empleado_id', 'left');
        $builder->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left');
        $builder->join('usuarios as cliente', 'cliente.id = carreras.id_usuario', 'left');
    
        // Filtro principal
        $builder->where('carreras.empleado_id', $empleadoId);
        
        // Ordenar por fecha de carrera (más reciente primero)
        $builder->orderBy('carreras.fecha', 'DESC');
        $builder->orderBy('franjas_horarias.hora_inicio', 'ASC');
    
        // Para depuración (opcional)
        // echo $builder->getCompiledSelect(); exit;
    
        return $builder->get()->getResultArray();
    }
    
    // MÉTODO LLAMADO DESDE js gestionCarreras (admin), CONTROLADOR EmpleadoCarreras, Método obtenerHorariosDisponibles() 
    // ESTE MÉTODO SE ENCARGA DE OBTENER LOS HORARIOS DISPONIBLES TENIENDO EN CUENTA LA FECHA Y EL HORARIO QUE SE PASAN POR GET
    public function obtenerHorariosDisponiblesCarrera($fecha, $horarioActual = null)
    {
        date_default_timezone_set('Europe/Madrid');
        // Obtener IDs de franjas ya reservadas en la fecha dada (tabla 'carreras')
        $builderCarreras = $this->db->table('carreras');
        $builderCarreras->select('franja_horaria_id');
        $builderCarreras->where('fecha', $fecha);
        $queryCarreras = $builderCarreras->get();
        $reservadas = $queryCarreras->getResultArray();
    
        $idsReservados = [];
        foreach ($reservadas as $fila) {
            if (!empty($fila['franja_horaria_id'])) {
                $idsReservados[] = $fila['franja_horaria_id'];
            }
        }
    
        $hoy = new \DateTime();
        $fechaActualHoy = $hoy->format('Y-m-d');
        $horaActual = $hoy->format('H:i:s');
    
        // 1. Si la fecha es anterior a hoy, retornar array vacío
        if ($fecha < $fechaActualHoy) {
            return [];
        }
    
        // Consultar la tabla 'franjas_horarias' para obtener las franjas no reservadas
        $builderFranjas = $this->db->table('franjas_horarias');
        
        // 2. Si es hoy, excluir horarios que ya pasaron (incluyendo el actual)
        if ($fecha === $fechaActualHoy) {
            $builderFranjas->where('hora_inicio >', $horaActual);
        }
        
        if (!empty($idsReservados)) {
            $builderFranjas->whereNotIn('id', $idsReservados);
        }
        $queryFranjas = $builderFranjas->get();
        $horarios = $queryFranjas->getResult();
    
        // Si se pasa un horarioActual (modo edición) y no se encuentra, se añade para que aparezca
        if (!empty($horarioActual)) {
            $existe = false;
            foreach ($horarios as $horario) {
                if ($horario->id == $horarioActual) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $builder = $this->db->table('franjas_horarias');
                $builder->where('id', $horarioActual);
                $query = $builder->get();
                $horarioEncontrado = $query->getRow();
                if ($horarioEncontrado) {
                    $horarios[] = $horarioEncontrado;
                }
            }
        }
    
        // Ordenar los horarios por hora de inicio ascendente
        usort($horarios, function($a, $b) {
            return strtotime($a->hora_inicio) - strtotime($b->hora_inicio);
        });
    
        return $horarios;
    }

    // MÉTODO REFERENCIADO DESDE js gestionCarreras (admin), CONTROLADOR empleadoCarrera, método obtenerReservaCarreraEdicion
    // este método se encarga de obtener la reserva de carrera seleccionada al editar
    public function obtenerReservaCarreraEdicion($idCarrera)
    {
        $builder = $this->db->table('carreras');
        
        $builder->select('
            carreras.id, 
            carreras.id_usuario, 
            carreras.empleado_id, 
            carreras.id_pistas, 
            carreras.fecha as fecha_carrera, 
            carreras.franja_horaria_id, 
            carreras.num_participantes,
            carreras.cantidad as precio, 
            carreras.metodo_pago, 
            carreras.fecha_pago, 
            carreras.pagado,
            carreras.payment_intent_id,
            franjas_horarias.hora_inicio, 
            franjas_horarias.hora_fin, 
            franjas_horarias.descripcion AS descripcion_franja, 
            pistas.nombre AS nombre_pista, 
            usuarios.nombre_usuario AS usuario_empleado, 
            usuarios.nombre AS nombre_empleado, 
            usuarios.apellidos AS apellidos_empleado, 
            cliente.nombre_usuario AS usuario_cliente, 
            cliente.nombre AS nombre_cliente, 
            cliente.apellidos AS apellidos_cliente
        ');
        
        $builder->join('franjas_horarias', 'carreras.franja_horaria_id = franjas_horarias.id', 'left');
        $builder->join('pistas', 'carreras.id_pistas = pistas.id', 'left');
        $builder->join('empleados', 'carreras.empleado_id = empleados.id', 'left');
        $builder->join('usuarios', 'empleados.usuario_id = usuarios.id', 'left');
        $builder->join('usuarios as cliente', 'carreras.id_usuario = cliente.id', 'left');
        
        $builder->where('carreras.id', $idCarrera);
        
        $query = $builder->get();
        
        return $query->getRowArray();
    }

    // MÉTODO verificarDisponibilidad USADO POR EL CONTROLADOR EmpleadoCarreras, SIRVE PARA VER SI EXISTEN
    // EMPLEADOS CON EL ROL DE CARRERAS ACTIVO, Y QUE ADEMÁS ESOS EMPLEADOS NO SEAN ADMINISTRADORES Y 
    // ESTEN HABILITADOS ACTUALMENTE 
    public function existeEmpleadoCarreras()
    {
        $builder = $this->db->table('empleados');
        $builder->select('empleados.id');
        $builder->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left');
        $builder->where('empleados.emp_carreras', 1);
        $builder->where('empleados.esAdmin', 0); // Excluir admins
        $builder->where('usuarios.estado', 1);   // Solo empleados activos
    
        return $builder->countAllResults() > 0;
    }
    

    public function existeReservaCarrera(int $id): bool
{
    return $this->where('id', $id)->countAllResults() > 0;
}

// ESTE MÉTODO SE USA EN EL CONTROLADOR DE PAGOS, EN EL MÉTODO DE guardarReservaCarrera, SE USA
// PARA OBTENER AL EMPLEADO DE CARRERA DISPONIBLE QUE TOQUE, ADEMÁS, DE LOS DISPONIBLES ESCOJO
// AL QUE MENOS CARRERAS TENGA DESIGNADAS DURANTE ESA SEMANA
    
public function obtenerEmpleadoCarrerasDisponible($fecha)
{
    $builder = $this->db->table('empleados');
    $builder->select('empleados.*, COUNT(carreras.id) AS total_carreras');
    $builder->join('carreras', "empleados.id = carreras.empleado_id AND 
                YEARWEEK(carreras.fecha, 1) = YEARWEEK(" . $this->db->escape($fecha) . ", 1)", 'left');
    $builder->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left');
    $builder->where('empleados.emp_carreras', 1);
    $builder->where('empleados.esAdmin', 0); // Excluir admins
    $builder->where('usuarios.estado', 1);   // Solo empleados activos
    $builder->groupBy('empleados.id');
    $builder->orderBy('total_carreras', 'ASC');
    $builder->orderBy('empleados.id', 'ASC'); // Orden secundario por si hay empates
    $builder->limit(1);

    return $builder->get()->getRowArray();
}

// Método llamado desde js gestionCarreras (admin) y nuevoCarrerasEmpleado (empleado),
// CONTROLADOR empleadoCarreras y método eliminarReservaCarrera, se encarga 
// de eliminar la reserva de carrera seleccionada.
    public function eliminarReservaCarrera($id)
    {
        return $this->db->table('carreras')->delete(['id' => $id]);
    }

    public function obtenerPrecioPista($pistaId)
    {
        $builder = $this->db->table('pistas');
        $builder->select('precio');
        $builder->where('id', $pistaId);
        $query = $builder->get();
        return $query->getRow() ? $query->getRow()->precio : 0;
    }

    public function calcularTotal($pistaId, $numParticipantes)
    {
        return $this->obtenerPrecioPista($pistaId) * $numParticipantes;
    }

    public function editarReservaCarrera($id, $datosActualizar)
    {
        return $this->update($id, $datosActualizar);
    }

    // MÉTODO REFERENCIADO DESDE js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO verificarCarrerasPendientes
    // se encarga de verificar si el empleado de carreras tiene 
    // asignadas carreras para un futuro, en ese caso no se le podrá 
    // deshabilitar su rol
    public function obtenerCarrerasFuturas($idEmpleado)
    {
        $fechaActual = date('Y-m-d');
        $horaActual = date('H:i:s');
    
        return $this->db->table('carreras')
            ->select('carreras.id, carreras.fecha, 
                    carreras.empleado_id,
                    franjas_horarias.hora_inicio,
                    usuarios.nombre_usuario as nombre_empleado,
                    carreras.id_pistas, carreras.pagado,
            carreras.payment_intent_id')
            ->join('franjas_horarias', 'carreras.franja_horaria_id = franjas_horarias.id', 'left')
            ->join('empleados', 'carreras.empleado_id = empleados.id', 'left')
            ->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left')
            ->where('carreras.empleado_id', $idEmpleado)
            ->groupStart()
                ->where('carreras.fecha >', $fechaActual)
                ->orGroupStart()
                    ->where('carreras.fecha', $fechaActual)
                    ->where('franjas_horarias.hora_inicio >', $horaActual)
                ->groupEnd()
            ->groupEnd()
            ->get()
            ->getResult();
    }
    // MÉTODO LLAMADO DESDE js de gestionCarreras (admin) y nuevoCarrerasEmpleado (empleado),
    // CONTROLADOR EmpleadoCarreras, método confirmarPago,
    // se encarga de confirmar el pago cuando el empleado/admin quiere poner esa carrera como pagada
    public function marcarComoPagado($idCarrera)
{
    $db = \Config\Database::connect();
    
    // 1. Verificar que la reserva existe y no está pagada
    $builder = $db->table($this->table)
                 ->select('pagado')
                 ->where('id', $idCarrera);
    
    $reserva = $builder->get()->getRow();
    
    if (!$reserva) {
        throw new \Exception('Reserva no encontrada');
    }

    if ($reserva->pagado == 1) {
        throw new \Exception('La reserva ya está marcada como pagada');
    }

    // 2. Actualización directa con Query Builder
    $builder = $db->table($this->table)
                 ->where('id', $idCarrera);
    
    $datosActualizar = [
        'pagado' => 1,
        'fecha_pago' => date('Y-m-d H:i:s')
    ];
    
    $resultado = $builder->update($datosActualizar);

    // 3. Verificación explícita de filas afectadas
    if ($db->affectedRows() === 0) {
        log_message('error', 'Error al marcar reserva como pagada. ID: ' . $idCarrera);
        throw new \Exception('No se afectaron filas al actualizar');
    }

    return true;
}

// MÉTODO LLAMADO DESDE EL JS DE gestionCarreras (Admin), Controlador EmpleadoCarreras, método obtenerTodasLasCarreras,
// este método se encarga de dar al admin todas las carreras que existen en AleKarting, tanto pasadas como futuras
    public function obtenerTodasLasCarreras()
    {
        return $this->db->table('carreras')
            ->select('
                carreras.id,
                carreras.id_usuario,
                carreras.empleado_id,
                carreras.id_pistas,
                carreras.fecha as fecha_carrera,
                carreras.franja_horaria_id,
                carreras.num_participantes,
                carreras.cantidad as precio,
                carreras.metodo_pago,
                carreras.fecha_pago,
                carreras.pagado,
                carreras.payment_intent_id,
                franjas_horarias.hora_inicio,
                franjas_horarias.hora_fin,
                franjas_horarias.descripcion AS descripcion_franja,
                pistas.nombre AS nombre_pista,
                usuarios.nombre_usuario AS usuario_empleado,
                usuarios.nombre AS nombre_empleado,
                usuarios.apellidos AS apellidos_empleado,
                cliente.nombre_usuario AS usuario_cliente,
                cliente.nombre AS nombre_cliente,
                cliente.apellidos AS apellidos_cliente,
                cliente.email AS email_cliente
            ')
            ->join('franjas_horarias', 'franjas_horarias.id = carreras.franja_horaria_id', 'left')
            ->join('pistas', 'pistas.id = carreras.id_pistas', 'left')
            ->join('empleados', 'empleados.id = carreras.empleado_id', 'left')
            ->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left')
            ->join('usuarios as cliente', 'cliente.id = carreras.id_usuario', 'left')
            ->orderBy('carreras.fecha', 'DESC')  // Más recientes primero
            ->orderBy('franjas_horarias.hora_inicio', 'ASC')  // Horas más tempranas primero
            ->get()
            ->getResultArray();
    }

}
