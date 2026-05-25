<?php

namespace App\Models;

use CodeIgniter\Model;

class PerfilModel extends Model
{
    /**
     * Obtiene las reservas actuales para un usuario.
     * Se combinan las reservas de la tabla 'carreras' y 'reservas_eventos'.
     */
    public function obtenerReservasActuales($idUsuario)
{
    // Reservas en la tabla carreras (sin cambios)
    $queryCarreras = $this->db->table('carreras')
        ->select('id, id_usuario, empleado_id, id_pistas, fecha, franja_horaria_id, num_participantes, cantidad, metodo_pago, fecha_pago')
        ->where('id_usuario', $idUsuario)
        ->where('fecha >=', date('Y-m-d'))
        ->get()
        ->getResultArray();

    // Reservas en la tabla reservas_eventos (actualizada)
    $queryEventos = $this->db->table('reservas_eventos')
        ->select('
            reservas_eventos.id,
            reservas_eventos.usuario_id as id_usuario,
            reservas_eventos.evento_id,
            eventos.fecha,                     
            eventos.franja_horaria_id,         
            reservas_eventos.cantidad,
            reservas_eventos.metodo_pago,
            reservas_eventos.fecha_pago,
            reservas_eventos.total,
            franjas_horarias.hora_inicio,     
            franjas_horarias.hora_fin         
        ')
        ->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left')
        ->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left')
        ->where('reservas_eventos.usuario_id', $idUsuario)
        ->where('eventos.fecha >=', date('Y-m-d'))  // Filtro por fecha desde eventos
        ->get()
        ->getResultArray();

    // Agregamos un indicador del tipo de reserva
    foreach ($queryCarreras as &$reserva) {
        $reserva['tipo_reserva'] = 'carrera';
    }
    foreach ($queryEventos as &$reserva) {
        $reserva['tipo_reserva'] = 'evento';
    }

    // Combinar ambos resultados
    $reservas = array_merge($queryCarreras, $queryEventos);

    // Ordenar el arreglo por fecha (ascendente)
    usort($reservas, function($a, $b) {
        return strtotime($a['fecha']) - strtotime($b['fecha']);
    });

    return $reservas;
}

    /**
     * Obtiene el historial de reservas para un usuario.
     * Se combinan los registros de la tabla 'carreras' y 'reservas_eventos' cuya fecha es menor a hoy.
     * int $idUsuario
     */
    public function obtenerHistorialReservas($idUsuario)
{
    // Historial en la tabla carreras (sin cambios)
    $queryCarreras = $this->db->table('carreras')
        ->select('id, id_usuario, empleado_id, id_pistas, fecha, franja_horaria_id, num_participantes, cantidad, metodo_pago, fecha_pago')
        ->where('id_usuario', $idUsuario)
        ->where('fecha <', date('Y-m-d'))
        ->get()
        ->getResultArray();

    // Historial en la tabla reservas_eventos (actualizada)
    $queryEventos = $this->db->table('reservas_eventos')
        ->select('
            reservas_eventos.id,
            reservas_eventos.usuario_id as id_usuario,
            reservas_eventos.evento_id,
            eventos.fecha,                 
            eventos.franja_horaria_id,    
            reservas_eventos.cantidad,
            reservas_eventos.metodo_pago,
            reservas_eventos.fecha_pago,
            reservas_eventos.total,
            franjas_horarias.hora_inicio,      
            franjas_horarias.hora_fin          
        ')
        ->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left')
        ->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left')
        ->where('reservas_eventos.usuario_id', $idUsuario)
        ->where('eventos.fecha <', date('Y-m-d'))  // Filtro por fecha desde eventos
        ->get()
        ->getResultArray();

    // Agregar indicador del tipo de reserva
    foreach ($queryCarreras as &$reserva) {
        $reserva['tipo_reserva'] = 'carrera';
    }
    foreach ($queryEventos as &$reserva) {
        $reserva['tipo_reserva'] = 'evento';
    }

    // Combinar ambos resultados
    $historial = array_merge($queryCarreras, $queryEventos);

    // Ordenar el historial por fecha descendente (más recientes primero)
    usort($historial, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });

    return $historial;
}

    /**
     * Actualiza las credenciales del usuario en la tabla 'usuarios'
     *
     * int $idUsuario
     * array $datos
     */
    public function actualizarCredenciales($idUsuario, $datos)
    {
        return $this->db->table('usuarios')
            ->where('id', $idUsuario)
            ->update($datos);
    }

    /**
     * Obtiene el nombre del tipo de usuario a partir del id_tipo.
     * int $idTipo
     */
    public function obtenerTipoUsuarioPorId($idTipo)
    {
        $resultado = $this->db->table('tipo_usuario')
            ->select('nombre_tipo')
            ->where('id', $idTipo)
            ->get()
            ->getRowArray();
        return $resultado ? $resultado['nombre_tipo'] : null;
    }
}
