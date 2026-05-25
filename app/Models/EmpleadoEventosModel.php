<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoEventosModel extends Model
{
    protected $table      = 'eventos';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'nombre', 'descripcion', 'tipo_evento_id', 'imagen', 'precio',
        'fecha', 'franja_horaria_id', 'capacidad', 'empleados_id'
    ];

    // MÉTODO REFERENCIADO DE js nuevoEventosEmpleado (empleado), Controlador EmpleadoEventos, Método obtenerEventosEmpleado, 
    // se encarga de obtener los eventos del empleado
    public function obtenerEventosEmpleado($empleadoId)
    {
        $builder = $this->db->table('eventos');
        
        $builder->select('
            eventos.id, 
            eventos.nombre,
            eventos.descripcion,
            eventos.fecha,
            eventos.precio,
            eventos.capacidad,
            eventos.imagen,
            eventos.tipo_evento_id,  
            eventos.franja_horaria_id,  
            eventos.empleados_id,
            franjas_horarias.hora_inicio,
            franjas_horarias.hora_fin,
            tipo_evento.nombre AS nombre_tipo, 
            usuarios.nombre_usuario AS empleado_usuario,
            usuarios.nombre AS nombre_empleado,
            usuarios.apellidos AS apellidos_empleado,
            COALESCE(SUM(CASE WHEN reservas.pagado = 1 THEN reservas.total ELSE 0 END), 0) AS total_recaudado,
            COUNT(CASE WHEN reservas.pagado = 1 THEN reservas.id ELSE NULL END) AS reservas_pagadas,
            COUNT(reservas.id) AS total_reservas
        ');
    
        // Joins principales
        $builder->join('tipo_evento', 'tipo_evento.id = eventos.tipo_evento_id', 'left');
        $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left');
        $builder->join('empleados', 'empleados.id = eventos.empleados_id', 'left');
        $builder->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left');
        
        // Join con reservas_eventos (LEFT JOIN para incluir eventos sin reservas)
        $builder->join('reservas_eventos as reservas', 'reservas.evento_id = eventos.id', 'left');
    
        // Filtro por empleado
        $builder->where('eventos.empleados_id', $empleadoId);
        
        // Agrupación por evento
        $builder->groupBy('eventos.id');
        
        // Ordenamiento
        $builder->orderBy('eventos.fecha', 'DESC');
        $builder->orderBy('franjas_horarias.hora_inicio', 'ASC');
    
        return $builder->get()->getResultArray();
    }

public function obtenerEventoPorId($id)
{
    // Consulta para obtener un evento específico por ID
    $builder = $this->db->table('eventos');
    $builder->select('*');
    $builder->where('id', $id);
    $query = $builder->get();
    
    // Retornar el resultado como objeto (o null si no existe)
    return $query->getRow();
}

    /**
     * Obtiene los datos del evento para edición, seleccionando únicamente 
     * los campos de la tabla "eventos".
     */
    public function obtenerEventoEdicion($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, nombre, descripcion, tipo_evento_id, imagen, precio, fecha, franja_horaria_id, capacidad, empleados_id');
        $builder->where('id', $id);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        }
        return false;
    }

    // MÉTODO REFERENCIADO EN js gestionEventos, CONTROLADOR EmpleadoEventos, METODO obtenerHorariosDisponibles, 
    // se encarga de obtener los horarios disponibles, teniendo en cuenta la fecha y horario que se pasan por método get
    public function obtenerHorariosDisponibles($fecha, $horarioActual = null)
    {
        date_default_timezone_set('Europe/Madrid');
        
        // Obtener los IDs de franjas ya reservadas para la fecha dada
        $builderEventos = $this->db->table('eventos');
        $builderEventos->select('franja_horaria_id');
        $builderEventos->where('fecha', $fecha);
        $queryEventos = $builderEventos->get();
        $reservadas = $queryEventos->getResultArray();
        
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
    
        // Consultar la tabla "franjas_horarias"
        $builderFranjas = $this->db->table('franjas_horarias');
        
        // 2. Si es hoy, excluir horarios que ya pasaron
        if ($fecha === $fechaActualHoy) {
            $builderFranjas->where('hora_inicio >', $horaActual);
        }
        
        // Excluir las franjas ya reservadas
        if (!empty($idsReservados)) {
            $builderFranjas->whereNotIn('id', $idsReservados);
        }
        
        $queryFranjas = $builderFranjas->get();
        $horarios = $queryFranjas->getResult();
    
        // Si se pasa un "horarioActual" (modo edición) y no está en la lista, agregarlo
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
    
        // Ordenar los horarios por hora de inicio
        usort($horarios, function($a, $b) {
            return strtotime($a->hora_inicio) - strtotime($b->hora_inicio);
        });
    
        return $horarios;
    }

public function existenReservas($idEvento)
{
    // Utilizamos el constructor de consultas para la tabla 'reservas_eventos'
    $builder = $this->db->table('reservas_eventos');
    // Filtramos por el campo 'evento_id'
    $builder->where('evento_id', $idEvento);
    // Contamos el número de registros encontrados
    $totalReservas = $builder->countAllResults();
    
    // Si el total es mayor que cero, existen reservas
    return $totalReservas > 0;
}

// MÉTODO REFERENCIADO EN js de gestionEventos, nuevoEventoEmpleado, CONTROLADOR EmpleadoEventos, MÉTODO verificarReservasEvento, 
// se encarga de comprobar si el evento tiene reservas hechas, en ese caso, no se podrá eliminar el evento

public function obtenerTotalReservasEvento($idEvento)
{
    $builder = $this->db->table('reservas_eventos');
    $builder->where('evento_id', $idEvento);
    return $builder->countAllResults();
}
// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado), obtener las reservas del evento escogido que existan
// CONTROLADOR EmpleadoEventos, MÉTODO obtenerReservasEventoEscogido

public function obtenerReservasDelEvento($idEvento)
{
    $builder = $this->db->table('reservas_eventos');
    $builder->select('
        reservas_eventos.id,
        usuarios.nombre_usuario as nombreUsuario,
        usuarios.id as usuario_id,
        eventos.nombre as nombreEvento,
        tipo_evento.nombre as tipoEvento,  
        reservas_eventos.cantidad,
        reservas_eventos.metodo_pago,
        reservas_eventos.fecha_pago,
        reservas_eventos.total,
        reservas_eventos.pagado,
        reservas_eventos.payment_intent_id,
        usuarios.email, 
        usuarios.telefono,
        eventos.fecha,                   
        eventos.franja_horaria_id,          
        franjas_horarias.hora_inicio,       
        franjas_horarias.hora_fin          
    ');
    $builder->join('usuarios', 'usuarios.id = reservas_eventos.usuario_id', 'left');
    $builder->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left');         
    $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left');
    $builder->join('tipo_evento', 'tipo_evento.id = eventos.tipo_evento_id', 'left');  
    $builder->where('reservas_eventos.evento_id', $idEvento);
    
    return $builder->get()->getResultArray(); 
}


// FUNCION PARA EDITAR LA RESERVA

public function obtenerReservaEventoUsuario($idReserva) {
    $builder = $this->db->table('reservas_eventos AS re');
    $builder->select('
        re.id,
        re.usuario_id,
        re.evento_id,
        eventos.fecha,
        eventos.franja_horaria_id,
        re.cantidad,
        re.metodo_pago,
        re.fecha_pago,
        re.total,
        re.pagado,
        re.payment_intent_id,
        eventos.precio AS precio_evento,
        eventos.capacidad AS capacidad_evento,
        (SELECT SUM(cantidad) FROM reservas_eventos WHERE evento_id = re.evento_id) AS cantidad_total_reservas,
        GREATEST(0, eventos.capacidad - (SELECT SUM(cantidad) FROM reservas_eventos WHERE evento_id = re.evento_id)) AS cantidad_disponible,
        franjas_horarias.hora_inicio,
        franjas_horarias.hora_fin
    ');
    $builder->join('eventos', 'eventos.id = re.evento_id', 'left');
    $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left');
    $builder->where('re.id', $idReserva);
    $query = $builder->get();
    
    if ($query->getNumRows() > 0) {
        $reserva = $query->getRow();
        
        // Recalcular el total correctamente (precio * cantidad)
        $reserva->total = $reserva->cantidad * $reserva->precio_evento;
        
        return $reserva;
    }
    
    return false;
}

public function editarReserva($id, $datos)
{
    // Actualiza la tabla 'reservas_eventos' filtrando por el id de la reserva.
    $builder = $this->db->table('reservas_eventos');
    $builder->where('id', $id);
    return $builder->update($datos);
}

public function eliminarReserva($id)
{
    $builder = $this->db->table('reservas_eventos');
    $builder->where('id', $id);
    return $builder->delete();
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR ADMIN, MÉTODO verificarEventosPendientes,
// se encarga de ver si al usuario que se le quiere deshabilitar el rol de evento, tiene ahora mismo eventos
// pendientes, en ese caso no se le puede deshabilitar hasta asignarselos a otro empleado con el rol de 
// para ello se comprueba si tiene eventos futuros asignados
public function obtenerEventosFuturos($idEmpleado)
{
    $fechaActual = date('Y-m-d');
    $horaActual = date('H:i:s');

    return $this->db->table('eventos')
        ->select('eventos.id, eventos.nombre, eventos.fecha, 
                eventos.empleados_id,
                franjas_horarias.hora_inicio,
                usuarios.nombre_usuario as nombre_empleado')
        ->join('franjas_horarias', 'eventos.franja_horaria_id = franjas_horarias.id', 'left')
        ->join('empleados', 'eventos.empleados_id = empleados.id', 'left')
        ->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left')
        ->where('eventos.empleados_id', $idEmpleado)
        ->groupStart()
            ->where('eventos.fecha >', $fechaActual)
            ->orGroupStart()
                ->where('eventos.fecha', $fechaActual)
                ->where('franjas_horarias.hora_inicio >', $horaActual)
            ->groupEnd()
        ->groupEnd()
        ->get()
        ->getResult(); // Cambiado a getResult() para obtener objetos
}

// MÉTODO USADO PARA EL ADMIN PARA CARGAR EL DATATABLE DE EVENTOS, USADO EN EL JS gestionEventos
// MÉTODO REFERENCIADO DESDE EL js de gestionEventos (admin), se encarga de obtener
// todos los eventos que tiene la web, CONTROLADOR ADMIN, MÉTODO obtenerTodosLosEventos
public function obtenerTodosLosEventos()
{
    $builder = $this->db->table('eventos');
    
    $builder->select('
        eventos.id, 
        eventos.nombre,
        eventos.descripcion,
        eventos.fecha,
        eventos.precio,
        eventos.capacidad,
        eventos.imagen,
        eventos.tipo_evento_id,  
        eventos.franja_horaria_id,  
        eventos.empleados_id,
        franjas_horarias.hora_inicio,
        franjas_horarias.hora_fin,
        tipo_evento.nombre AS nombre_tipo, 
        usuarios.nombre_usuario AS empleado_usuario,
        usuarios.nombre AS nombre_empleado,
        usuarios.apellidos AS apellidos_empleado,
        COALESCE(SUM(CASE WHEN reservas.pagado = 1 THEN reservas.total ELSE 0 END), 0) AS total_recaudado,
        COUNT(CASE WHEN reservas.pagado = 1 THEN reservas.id ELSE NULL END) AS reservas_pagadas,
        COUNT(reservas.id) AS total_reservas
    ');
    
    // Joins principales
    $builder->join('tipo_evento', 'tipo_evento.id = eventos.tipo_evento_id', 'left');
    $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left');
    $builder->join('empleados', 'empleados.id = eventos.empleados_id', 'left');
    $builder->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left');
    
    // Join con reservas_eventos (LEFT JOIN para incluir eventos sin reservas)
    $builder->join('reservas_eventos as reservas', 'reservas.evento_id = eventos.id', 'left');
    
    // Agrupación por evento
    $builder->groupBy('eventos.id');
    
    // Ordenamiento
    $builder->orderBy('eventos.fecha', 'DESC');
    $builder->orderBy('franjas_horarias.hora_inicio', 'ASC');
    
    return $builder->get()->getResultArray();
}

/**
     * Marca una reserva de evento como pagada
     */

public function marcarComoPagado($idReserva)
    {
        // Verificar que la reserva existe
        $reserva = $this->find($idReserva);
        if (!$reserva) {
            throw new \Exception('Reserva no encontrada');
        }

        // Verificar que no esté ya pagada
        if ($reserva->pagado == 1) {
            throw new \Exception('La reserva ya está marcada como pagada');
        }

        // Preparar datos para actualización
        $datosActualizar = [
            'pagado' => 1,
            'fecha_pago' => date('Y-m-d')
        ];

        // Ejecutar actualización
        $resultado = $this->update($idReserva, $datosActualizar);

        if (!$resultado) {
            log_message('error', 'Error al marcar reserva como pagada. ID: ' . $idReserva);
            throw new \Exception('Error al actualizar el estado de pago');
        }

        return true;
    }

}
