<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id',
        'nombre_usuario',
        'nombre',
        'apellidos',
        'email',
        'contraseña', 
        'telefono',
        'id_tipo',
        'estado',
        'token_recuperacion',  
        'expiracion_token'   
    ];

    // MÉTODO REFERENCIADO DESDE JS gestionEmpleados, gestionUsuarios, CONTROLADOR ADMIN, MÉTODO desactivarEmpleadose, 
    // se encarga de desactivar al empleado seleccionado
        public function desactivarUsuario($usuarioId)
    {
        return $this->where('id', $usuarioId)
                ->set(['estado' => 0])  // 0 = desactivado
                ->update();
    }
    
    // MÉTODO REFERENCIADO EN js de gestionEmpleados, gestionUsuarios, CONTROLADOR ADMIN, MÉTODO activarEmpleado, 
    // se encarga de activar al empleado seleccionado
        public function activarUsuario($usuarioId)
    {
        return $this->where('id', $usuarioId)
                ->set(['estado' => 1])  // 1 = activo
                ->update();
    }

    // MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR ADMIN, MÉTODO obtenerAdmins, 
    // se encarga de obtener a todos los admins en la web
    public function obtenerAdmins()
    {
        $builder = $this->db->table('empleados');
        
        // SELECT en múltiples líneas para mejor legibilidad
        $builder->select([
            'empleados.id AS id_empleado', 
            'empleados.usuario_id',
            'empleados.emp_noticia',
            'empleados.emp_evento',
            'empleados.emp_carreras',
            'usuarios.id',
            'usuarios.nombre_usuario',
            'usuarios.nombre',
            'usuarios.apellidos',
            'usuarios.email',
            'usuarios.telefono',
            'usuarios.estado'
        ]);
        
        $builder->join('usuarios', 'empleados.usuario_id = usuarios.id');
        $builder->where('empleados.esAdmin', 1);
        $builder->orderBy('usuarios.nombre', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // MÉTODO REFERENCIADO DESDE js de gestionPagos, CONTROLADOR RegistroPagos, Método obtenerTodosLosDiasConPagos, 
    // que se encarga de obtener todos los días que tengan pagos realizados, tengan o solo pagos de reservas de carreras, de eventos, o ambos
    public function obtenerDiasConPagos()
    {
        // Obtener pagos de carreras (usando fecha de la carrera)
        $builderCarreras = $this->db->table('carreras');
        $carreras = $builderCarreras->select("
            DATE(carreras.fecha) as dia,
            SUM(carreras.cantidad) as total_carreras,
            COUNT(*) as cantidad_carreras
        ")
        ->where('carreras.pagado', 1)
        ->where('carreras.fecha IS NOT NULL')
        ->groupBy('DATE(carreras.fecha)')
        ->get()
        ->getResultArray();
    
        // Obtener pagos de eventos (haciendo JOIN con eventos para obtener la fecha)
        $builderEventos = $this->db->table('reservas_eventos');
        $eventos = $builderEventos->select("
            DATE(eventos.fecha) as dia,
            SUM(reservas_eventos.total) as total_eventos,
            COUNT(*) as cantidad_eventos
        ")
        ->join('eventos', 'eventos.id = reservas_eventos.evento_id')
        ->where('reservas_eventos.pagado', 1)
        ->where('eventos.fecha IS NOT NULL')
        ->groupBy('DATE(eventos.fecha)')
        ->get()
        ->getResultArray();
    
        // Combinar resultados
        $resultados = [];
    
        // Procesar carreras
        foreach ($carreras as $carrera) {
            $dia = $carrera['dia'];
            $resultados[$dia] = [
                'dia' => $dia,
                'total_carreras' => number_format((float)$carrera['total_carreras'], 2, '.', ''),
                'cantidad_carreras' => (int)$carrera['cantidad_carreras'],
                'total_eventos' => '0.00',
                'cantidad_eventos' => 0,
                'total_dia' => number_format((float)$carrera['total_carreras'], 2, '.', '')
            ];
        }
    
        // Procesar eventos
        foreach ($eventos as $evento) {
            $dia = $evento['dia'];
            if (isset($resultados[$dia])) {
                $resultados[$dia]['total_eventos'] = number_format((float)$evento['total_eventos'], 2, '.', '');
                $resultados[$dia]['cantidad_eventos'] = (int)$evento['cantidad_eventos'];
                $resultados[$dia]['total_dia'] = number_format(
                    (float)$resultados[$dia]['total_carreras'] + (float)$evento['total_eventos'], 
                    2, 
                    '.', 
                    ''
                );
            } else {
                $resultados[$dia] = [
                    'dia' => $dia,
                    'total_carreras' => '0.00',
                    'cantidad_carreras' => 0,
                    'total_eventos' => number_format((float)$evento['total_eventos'], 2, '.', ''),
                    'cantidad_eventos' => (int)$evento['cantidad_eventos'],
                    'total_dia' => number_format((float)$evento['total_eventos'], 2, '.', '')
                ];
            }
        }
    
        // Ordenar por fecha (más reciente primero)
        krsort($resultados);
    
        // Convertir a array indexado y asegurar formato numérico
        return array_map(function($item) {
            return [
                'dia' => $item['dia'],
                'total_carreras' => number_format((float)$item['total_carreras'], 2),
                'cantidad_carreras' => (int)$item['cantidad_carreras'],
                'total_eventos' => number_format((float)$item['total_eventos'], 2),
                'cantidad_eventos' => (int)$item['cantidad_eventos'],
                'total_dia' => number_format((float)$item['total_dia'], 2)
            ];
        }, array_values($resultados));
    }

    public function obtenerTodasLasReservasIndividuales()
{
    // 1. Obtener todas las carreras individuales
    $builderCarreras = $this->db->table('carreras');
    $carreras = $builderCarreras->select("
        carreras.id,
        carreras.id_usuario,
        usuarios.nombre as nombre_usuario,
        usuarios.apellidos as apellidos_usuario,
        usuarios.email as email_usuario,
        carreras.fecha,
        carreras.cantidad as total,
        carreras.pagado,
        carreras.fecha_pago,
        'carrera' as tipo,
        pistas.nombre as nombre_pista,
        NULL as nombre_evento
    ")
    ->join('usuarios', 'usuarios.id = carreras.id_usuario')
    ->join('pistas', 'pistas.id = carreras.id_pistas', 'left')
    ->orderBy('carreras.fecha', 'DESC')
    ->get()
    ->getResultArray();

    // 2. Obtener todos los eventos individuales
    $builderEventos = $this->db->table('reservas_eventos');
    $eventos = $builderEventos->select("
        reservas_eventos.id,
        reservas_eventos.usuario_id as id_usuario,
        usuarios.nombre as nombre_usuario,
        usuarios.apellidos as apellidos_usuario,
        usuarios.email as email_usuario,
        eventos.fecha,
        reservas_eventos.total,
        reservas_eventos.pagado,
        reservas_eventos.fecha_pago,
        'evento' as tipo,
        NULL as nombre_pista,
        eventos.nombre as nombre_evento
    ")
    ->join('usuarios', 'usuarios.id = reservas_eventos.usuario_id')
    ->join('eventos', 'eventos.id = reservas_eventos.evento_id')
    ->orderBy('eventos.fecha', 'DESC')
    ->get()
    ->getResultArray();

    // 3. Combinar y formatear resultados
    $resultados = array_merge($carreras, $eventos);
    
    // Ordenar por fecha descendente
    usort($resultados, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });

    // Formatear campos
    return array_map(function($item) {
        return [
            'id' => $item['id'],
            'usuario_id' => $item['id_usuario'],
            'nombre_completo' => $item['nombre_usuario'] . ' ' . $item['apellidos_usuario'],
            'email' => $item['email_usuario'],
            'fecha' => $item['fecha'],
            'fecha_formateada' => date('d/m/Y', strtotime($item['fecha'])),
            'total' => number_format($item['total'], 2),
            'pagado' => (bool)$item['pagado'],
            'fecha_pago' => $item['fecha_pago'],
            'fecha_pago_formateada' => $item['fecha_pago'] ? date('d/m/Y H:i', strtotime($item['fecha_pago'])) : null,
            'tipo' => $item['tipo'],
            'nombre_actividad' => $item['tipo'] === 'carrera' ? $item['nombre_pista'] : $item['nombre_evento'],
            'tipo_actividad' => $item['tipo']
        ];
    }, $resultados);
}

}