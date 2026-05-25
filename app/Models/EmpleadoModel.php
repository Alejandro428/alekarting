<?php
namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario_id', 'emp_noticia', 'emp_evento', 'emp_carreras'];

    // MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR gestionEmpleados, método obtenerEmpleados,
    // se encarga de obtener a todos los empleados existentes en la web
    public function obtenerEmpleados()
    {
        $builder = $this->db->table('empleados');  // Asumiendo que la tabla se llama 'empleados'
        $builder->select('empleados.id AS id_empleado, empleados.usuario_id, empleados.emp_noticia, empleados.emp_evento, empleados.emp_carreras, usuarios.id, usuarios.nombre_usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, usuarios.telefono, usuarios.estado');
        
        // Agregar el join con la tabla 'usuarios'
        $builder->join('usuarios', 'empleados.usuario_id = usuarios.id');  // Suponiendo que 'empleados' tiene una relación con 'usuarios'
    
        // Filtrar empleados donde esAdmin es 0
        $builder->where('empleados.esAdmin', 0); // Filtrar empleados que no son administradores
    
        $query = $builder->get();
        return $query->getResultArray(); // Devuelve un array de empleados
    }

    // MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR ADMIN, MÉTODO desactivarEmpleadoEvento,
    // se encarga de desactivar al empleado del rol de evento
    public function desactivarRolEvento($empleadoId)
{
    // Actualización directa del campo de rol de eventos
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_evento' => 0]); // 0 = desactivado
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR ADMIN, MÉTODO activarRolEvento, 
// se encarga de activar al empleado del rol de evento 
public function activarRolEvento($empleadoId)
{
    // Actualización directa del campo
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_evento' => 1]); // 1 para activado
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO desactivarEmpleadoNoticia, 
// se encarga de desactivar el rol de noticia del empleado seleccionado
public function desactivarRolNoticia($empleadoId)
{
    // Actualizar campo emp_noticia a 0 (desactivado)
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_noticia' => 0]);
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO activarEmpleadoNoticia,
// se encarga de activar el rol de noticia del empleado seleccionado
public function activarRolNoticia($empleadoId)
{
    // Actualizar el campo emp_noticia a 1 (activado)
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_noticia' => 1]); // 1 = activado, 0 = desactivado
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO desactivarEmpleadoCarrerase, 
// se encarga de desactivar el rol de carrera del empleado seleccionado 
public function desactivarRolCarrera($empleadoId)
{
    // Actualizar campo emp_carreras a 0 (desactivado)
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_carreras' => 0]);
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO activarEmpleadoCarreras, 
// se encarga de activar el rol de carrera del empleado seleccionado 
public function activarRolCarrera($empleadoId)
{
    // Actualizar el campo emp_carreras a 1 (activado)
    return $this->db->table('empleados')
        ->where('id', $empleadoId)
        ->update(['emp_carreras' => 1]); // 1 = activado, 0 = desactivado
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, CONTROLADOR ADMIN, MÉTODO verificarRolesEmpleado, desactivarEmpleado
// se encarga de verificar si el empleado que se quiere deshabilitar tiene roles activos actualmente 
public function obtenerRolesActivos($idEmpleado)
{
    $builder = $this->db->table('empleados');
    $builder->select('emp_noticia, emp_evento, emp_carreras');
    $builder->where('id', $idEmpleado);
    $query = $builder->get();

    if ($query->getNumRows() === 0) {
        return [];
    }

    $empleado = $query->getRow();
    $roles = [];
    
    // Verificar cada tipo de rol (1 = activo, 0 = inactivo)
    if ($empleado->emp_noticia == 1) {
        $roles[] = 'Gestor de Noticias';
    }
    if ($empleado->emp_evento == 1) {
        $roles[] = 'Coordinador de Eventos';
    }
    if ($empleado->emp_carreras == 1) {
        $roles[] = 'Gestor de Carreras';
    }

    return $roles;
}

public function crearEmpleado(array $data)
{
    // Usamos el builder para crear la consulta
    $builder = $this->db->table($this->table);
    
    // Insertar los datos del empleado
    $builder->set('usuario_id', $data['usuario_id']);
    
    // Lógica para determinar los permisos
    if (isset($data['esAdmin']) && $data['esAdmin'] == 1) {
        // Si es admin, todos los permisos a 1
        $builder->set('emp_noticia', 1);
        $builder->set('emp_evento', 1);
        $builder->set('emp_carreras', 1);
        $builder->set('esAdmin', 1);
    } else {
        // Si no es admin, usar los valores proporcionados
        $builder->set('emp_noticia', $data['emp_noticia']);
        $builder->set('emp_evento', $data['emp_evento']);
        $builder->set('emp_carreras', $data['emp_carreras']);
    }
    
    // Ejecutar la consulta de inserción
    if ($builder->insert()) {
        return true; // Si la inserción fue exitosa, retornamos true
    }
    return false; // Si hubo un error, retornamos false
}

public function editarEmpleado($id, $data)
{
    return $this->db->table('empleados')
        ->where('usuario_id', $id)
        ->update($data);
}

public function obtenerEmpleadoPorUsuarioId($usuarioId)
{
    return $this->where('usuario_id', $usuarioId)->first();  // Devuelve el primer registro que coincida con el usuario_id
}

// MÉTODO LLAMADO DESDE EL JS DE gestionCarreras (admin) y nuevoCarrerasEmpleado (empleado), 
// CONTROLADOR DE ADMIN, MÉTODO getEmpleadosCarreras se utiliza para obtener a todos los empleados
// de carreras activos
public function obtenerEmpleadosConPermisosCarreras()
{
    return $this->db->table('empleados')
        ->select('
            empleados.id,
            empleados.usuario_id,
            empleados.emp_carreras,
            usuarios.nombre_usuario,
            usuarios.nombre,
            usuarios.apellidos,
            CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS nombre_completo,
            usuarios.email,
            usuarios.telefono
        ')
        ->join('usuarios', 'usuarios.id = empleados.usuario_id')
        ->where('empleados.emp_carreras', 1)  // Con permiso para carreras
        ->where('usuarios.estado', 1)        // Usuario activo
        ->where('empleados.esAdmin', 0)       // Excluye administradores
        ->orderBy('usuarios.nombre', 'ASC')
        ->get()
        ->getResultArray();
}

// MÉTODO REFERENCIADO DE js gestionEventos (admin), CONTROLADOR ADMIN, MÉTODO getEmpleadosEventos,
// se encarga de obtener a todos los empleados con el rol de eventos activo, que esten activos
public function obtenerEmpleadosConPermisosEventos()
{
    $builder = $this->db->table('empleados');
    
    $builder->select('
        empleados.id,
        empleados.usuario_id,
        empleados.emp_evento,
        usuarios.nombre_usuario,
        usuarios.nombre,
        usuarios.apellidos,
        CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS nombre_completo,
        usuarios.email,
        usuarios.telefono,
        usuarios.estado
    ')
    ->join('usuarios', 'usuarios.id = empleados.usuario_id')
    ->where('empleados.emp_evento', 1)
    ->where('usuarios.estado', 1)
    ->where('empleados.esAdmin', 0)
    ->orderBy('usuarios.nombre', 'ASC');
    
    $query = $builder->get();
    
    if (!$query) {
        log_message('error', 'Error al obtener empleados con permisos para eventos: ' . $this->db->error());
        return [];
    }
    
    return $query->getResultArray();
}

public function obtenerEmpleadosConPermisosNoticias()
{
    return $this->db->table('empleados')
        ->select('
            empleados.id,
            empleados.usuario_id,
            empleados.emp_noticia,
            usuarios.nombre_usuario,
            usuarios.nombre,
            usuarios.apellidos,
            CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS nombre_completo,
            usuarios.email,
            usuarios.telefono
        ')
        ->join('usuarios', 'usuarios.id = empleados.usuario_id')
        ->where('empleados.emp_noticia', 1)  // Con permiso para noticias
        ->where('usuarios.estado', 1)        // Usuario activo
        ->where('empleados.esAdmin', 0)       // Excluye administradores
        ->orderBy('usuarios.nombre', 'ASC')
        ->get()
        ->getResultArray();
}

}



