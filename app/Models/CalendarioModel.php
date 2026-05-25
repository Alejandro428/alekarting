<?php
namespace App\Models;
use CodeIgniter\Model;

class CalendarioModel extends Model
{
    /**
     * Obtiene los días (números) en los que existen reservas de eventos
     * para un año y mes determinados.
     *
     * int $anio
     * int $mes
     */
    public function getDiasReservadosEventos($anio, $mes)
    {
        $builder = $this->db->table('reservas_eventos');
        $builder->select("DAY(fecha) as dia");
        $builder->where("YEAR(fecha)", $anio);
        $builder->where("MONTH(fecha)", $mes);
        $builder->groupBy("dia");
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $dias = [];
        foreach ($result as $row) {
            $dias[] = (int)$row['dia'];
        }
        return $dias;
    }

     /*
    MÉTODO USADO EN JS carreraVista, SE REFERENCIA EN EL MÉTODO getReservasCountCarreras DEL CONTROLADOR Calendario,  
    SE USA PARA OBTENER LAS RESERVAS DE TODOS LOS DÍAS DEL MES Y AÑO PASADOS, Y CONTIENE CADA DÍA LA CANTIDAD DE RESERVAS QUE TIENE

    MÉTODO TAMBIÉN REFERENCIADO POR EL JS horarioCarrera
     */
    public function getReservasCountCarreras($anio, $mes)
    {
        $builder = $this->db->table('carreras');
        $builder->select("DAY(fecha) as dia, COUNT(*) as count");
        $builder->where("YEAR(fecha)", $anio);
        $builder->where("MONTH(fecha)", $mes);
        $builder->where("franja_horaria_id IS NOT NULL", null, false);
        $builder->groupBy("dia");
        $query = $builder->get();
        $result = $query->getResultArray();

        $counts = [];
        foreach ($result as $row) {
            $counts[(int)$row['dia']] = (int)$row['count'];
        }
        return $counts;
    }

    /*
    MÉTODO USADO EN carreraVista, SE REFERENCIA EN EL MÉTODO DEL CONTROLADOR getHorariosDia DE Calendario,  
    SE USA PARA OBTENER TODOS LOS HORARIOS DE UNA FECHA INTRODUCIDA, SE DEVUELVE EL ESTADO DE EL
    HORARIO, PUEDE SER:
        - RESERVADO_EXPIRADO: TUVO RESERVA PERO YA PASÓ
        - RESERVADO: TIENE UNA RESERVA
        - EXPIRADO: SIN RESERVA PERO CON HORA PASADA
        - DISPONIBLE: ACTUALMENTE DISPONIBLE
    TAMBIEN SE USA EN EL JS horarioCarrera 
     */
    public function getHorariosPorDia($fecha)
{
    date_default_timezone_set('Europe/Madrid');
    $horaActual = date('H:i:s');
    $fechaActual = date('Y-m-d'); // Obtenemos la fecha actual para comparar

    // Obtener todas las franjas horarias
    $franjas = $this->db->table('franjas_horarias')->get()->getResultArray();

    // Obtener los IDs de franjas reservadas para la fecha indicada
    $builder2 = $this->db->table('carreras');
    $builder2->select('franja_horaria_id');
    $builder2->where("DATE(fecha) = '$fecha'", null, false);
    $builder2->where('franja_horaria_id IS NOT NULL', null, false);
    $builder2->distinct();
    $query2 = $builder2->get();
    $resultReserved = $query2->getResultArray();

    $reservedSlotIds = array_map('intval', array_column($resultReserved, 'franja_horaria_id'));

    // Determinar el estado de cada franja
    $horarios = [];
    foreach ($franjas as $franja) {
        $id = (int)$franja['id'];
        $horaInicio = $franja['hora_inicio'];

        // Solo comparamos con la hora actual si es el mismo día
        $mismoDia = ($fecha == $fechaActual);

        // Determinar estado del horario
        if (in_array($id, $reservedSlotIds)) {
            if ($mismoDia && $horaInicio <= $horaActual) {
                $estado = "reservado_expirado"; // Reservado y ya pasó su hora (solo hoy)
            } else {
                $estado = "reservado"; // Reservado (para hoy o futuro)
            }
        } elseif ($mismoDia && $horaInicio <= $horaActual) {
            $estado = "expirado"; // No reservado, pero ya pasó la hora (solo hoy)
        } else {
            $estado = "disponible"; // Se puede reservar (futuro o hoy si aún no ha pasado)
        }

        $horarios[] = [
            'franja_horaria_id' => $id,
            'hora_inicio'       => $horaInicio,
            'hora_fin'          => $franja['hora_fin'],
            'descripcion'       => $franja['descripcion'],
            'estado'            => $estado,
        ];
    }
    return $horarios;
}

    /*
     MÉTODO REFERENCIADO EN EL CONTROLADOR CALENDARIO POR EL MÉTODO getTotalFranjas Y USADO
     POR EL JS DE carreraVista y horarioVista, RETORNA LAS 14 FRANJAS EXISTENTES
     */
    public function getTotalFranjas()
    {
        $builder = $this->db->table('franjas_horarias');
        $query = $builder->get();
        return [ "total" => $query->getNumRows() ];
    }
}