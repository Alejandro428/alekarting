<?php

namespace App\Models;

use CodeIgniter\Model;

class EventoModel extends Model
{
    protected $table      = 'eventos';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'nombre', 'descripcion', 'tipo_evento_id', 'imagen', 
        'precio', 'fecha', 'capacidad', 'empleados_id', 'franja_horaria_id'
    ];
    
    // MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) obtener los datos del evento que no tiene
    // la reserva del evento por su cuenta, se obtienen todos los datos del evento, CONTROLADOR EmpleadoEventos, MÉTODO obtenerFechaEvento
    public function obtenerEventoPorId($id)
{
    $builder = $this->db->table('eventos');
    $builder->select('eventos.*, franjas_horarias.hora_inicio, franjas_horarias.hora_fin');
    $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left');
    $builder->where('eventos.id', $id);
    
    $query = $builder->get();
    return $query->getRow();
}

    /**
     * Obtiene los eventos próximos (desde hoy en adelante y que aún no han comenzado)
     *
     * Se asume que la tabla "franjas_horarias" tiene las columnas "hora_inicio" y "hora_fin".
     * MÉTODO LLAMADO DESDE EL JS DE eventoVista, controlador EVENTOS, método getEventosProximos,
     * se encarga de obtener todos los eventos próximos
     */

    // MÉTODO REFERENCIADO DE JS gestionEventos (admin) que se encarga de obtener todos los eventos próximos.
    // CONTROLADOR EVENTO, MÉTODO getEventosProximos

     public function obtenerEventosProximos()
{
    $builder = $this->db->table($this->table);
    $builder->select('eventos.*, fh.hora_inicio, fh.hora_fin, te.nombre as tipo_evento, 
      (SELECT IFNULL(SUM(re.cantidad), 0) FROM reservas_eventos re WHERE re.evento_id = eventos.id) as plazas_reservadas');
    $builder->join('franjas_horarias as fh', 'eventos.franja_horaria_id = fh.id', 'inner');
    $builder->join('tipo_evento as te', 'eventos.tipo_evento_id = te.id', 'left');
    $builder->where('(eventos.fecha > CURDATE()) OR (eventos.fecha = CURDATE() AND fh.hora_inicio > CURTIME())');
    $builder->orderBy('eventos.fecha, fh.hora_inicio', 'ASC');

    $query = $builder->get();
    return $query->getResult();
}

// MÉTODO REFERENCIADO EN js nuevoEventoEmpleado (empleado), se encarga de obtener los eventos
// que tiene asignados el empleado de eventos, CONTROLADOR Eventos, Método getEventosProximosEmpleado 
public function obtenerEventosProximosPorEmpleado($empleadoId)
{
    $builder = $this->db->table($this->table);
    $builder->select('eventos.*, fh.hora_inicio, fh.hora_fin, te.nombre as tipo_evento,
        (SELECT IFNULL(SUM(re.cantidad), 0) 
         FROM reservas_eventos re 
         WHERE re.evento_id = eventos.id) as plazas_reservadas');

    $builder->join('franjas_horarias as fh', 'eventos.franja_horaria_id = fh.id', 'inner');
    $builder->join('tipo_evento as te', 'eventos.tipo_evento_id = te.id', 'left');

    // Agrupamos la lógica de eventos próximos (por fecha y hora)
    $builder->groupStart()
        ->where('eventos.fecha > CURDATE()')
        ->orWhere('(eventos.fecha = CURDATE() AND fh.hora_inicio > CURTIME())')
    ->groupEnd();

    // Filtramos por el empleado asignado
    $builder->where('eventos.empleados_id', $empleadoId);

    $builder->orderBy('eventos.fecha, fh.hora_inicio', 'ASC');

    return $builder->get()->getResult();
}


/* 
* MÉTODO REFERENCIADO DESDE horarioEvento, CONTROLADOR Calendario, Método getEventos con Reservas,
* sirve para obtener los eventos de la fecha pasada por parámetro, además, también obtiene
* datos como son el total de reservados que hay en los eventos, para después
* pintar los días, dejar accesible o no el acceder a ese evento, etc.
*/
public function getEventosConReservas($fecha)
{
    $builder = $this->db->table('eventos');
    
    $builder->select('
        eventos.*,
        franjas_horarias.hora_inicio,
        franjas_horarias.hora_fin,
        tipo_evento.nombre as tipo_evento,
        COALESCE(SUM(reservas_eventos.cantidad), 0) as total_reservados
    ');
    
    // Unir la tabla de franjas horarias
    $builder->join('franjas_horarias', 'eventos.franja_horaria_id = franjas_horarias.id', 'left');
    
    // Unir la tabla de tipo de evento
    $builder->join('tipo_evento', 'eventos.tipo_evento_id = tipo_evento.id', 'left');
    
    // Filtrar por la fecha del evento
    $builder->where('eventos.fecha', $fecha);
    
    // Unir la tabla de reservas de eventos
    $builder->join(
        'reservas_eventos',
        "eventos.id = reservas_eventos.evento_id 
         AND eventos.fecha = " . $this->db->escape($fecha),
        'left'
    );
    
    // Agrupar por el id del evento
    $builder->groupBy('eventos.id');
    
    $query = $builder->get();
    return $query->getResultArray();
}


}