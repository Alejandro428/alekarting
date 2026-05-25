<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre_usuario', 
        'nombre', 
        'apellidos', 
        'email', 
        'contraseña', 
        'telefono', 
        'id_tipo',
        'token_recuperacion',  
        'expiracion_token'     
    ];

    // MÉTODO LLAMADO DESDE CONTROLADOR ADMIN Método validarCamposUnicos(), se utiliza para verificar si el
    // campo de usuario ya existe en la web de AleKarting
    public function existeNombreUsuario(string $nombre_usuario): bool
    {
        return $this->where('nombre_usuario', $nombre_usuario)->countAllResults() > 0;
    }

    // MÉTODO LLAMADO DESDE CONTROLADOR ADMIN Método validarCamposUnicos(), se utiliza para verificar si el
    // campo de teléfono ya existe en la web de AleKarting
    public function existeTelefono($telefono)
    {
        // Buscar un usuario que tenga el teléfono especificado
        $usuario = $this->where('telefono', $telefono)->first();
        // Si se encontró un registro, el teléfono ya existe
        return !empty($usuario);
    }

    // MÉTODO LLAMADO DESDE CONTROLADOR ADMIN Método validarCamposUnicos(), se utiliza para verificar si el
    // campo de correo ya existe en la web de AleKarting
    public function existeCorreo(string $correo): bool
    {
        return $this->where('email', $correo)->countAllResults() > 0;
    }
    
    // Usado en controlador EMPLEADO método actualizarCredencialesEmpleado
    // SE DEVUELVE SI EL USUARIO ACTUAL HA INTENTADO PONER UN NOMBRE DE USUARIO
    // QUE YA EXISTE, DESCARTANDO EL SUYO PROPIO
    public function existeNombreUsuarioDiferente(string $nombre_usuario, int $id): bool
    {
        return $this->where('nombre_usuario', $nombre_usuario)
                    ->where('id !=', $id)
                    ->countAllResults() > 0;
    }
    
     // Usado en controlador empleado método actualizarCredencialesEmpleado
    // SE DEVUELVE SI EL USUARIO ACTUAL HA INTENTADO PONER UN CORREO
    // QUE YA EXISTE, DESCARTANDO EL SUYO PROPIO
    public function existeCorreoDiferente(string $correo, int $id): bool
    {
        return $this->where('email', $correo)
                    ->where('id !=', $id)
                    ->countAllResults() > 0;
    }
    
     // Usado en controlador empleado método actualizarCredencialesEmpleado
    // SE DEVUELVE SI EL USUARIO ACTUAL HA INTENTADO PONER UN TELÉFONO 
    // QUE YA EXISTE, DESCARTANDO EL SUYO PROPIO
    public function existeTelefonoDiferente(string $telefono, int $id): bool
    {
        return $this->where('telefono', $telefono)
                    ->where('id !=', $id)
                    ->countAllResults() > 0;
    }

    // MÉTODO QUE REFERENCIA A js gestionEmpleados, gestionUsuarios, CONTROLADOR ADMIN, MÉTODO crearEmpleado se encarga de crear un nuevo empleado (para ello primero crea el usuario, y después el empleado y lo enlaza)
    // EN ESE JS SE PUEDE USAR TANTO PARA CREAR EMPLEADO COMO ADMIN

    public function crearUsuario(array $data, string $tipoUsuario)
{
    $data['contraseña'] = md5($data['contraseña']);

    // Obtener el id del tipo de usuario dinámicamente
    $subQuery = $this->db->table('tipo_usuario')
                         ->select('id')
                         ->where('nombre_tipo', $tipoUsuario)
                         ->limit(1)
                         ->getCompiledSelect();

    // Construcción de la consulta
    $builder = $this->db->table($this->table);
    $builder->set('nombre_usuario', $data['nombre_usuario']);
    $builder->set('nombre', $data['nombre']);
    $builder->set('apellidos', $data['apellidos']);
    $builder->set('email', $data['email']);
    $builder->set('contraseña', $data['contraseña']);
    $builder->set('telefono', $data['telefono']);
    $builder->set('id_tipo', "($subQuery)", false);
    $builder->set('estado', 1);

    // Ejecutar inserción y devolver el ID del usuario creado
    if ($builder->insert()) {
        return $this->db->insertID();
    }

    return false;
}
// MÉTODO QUE REFERENCIA A js gestionEmpleados, CONTROLADOR ADMIN, MÉTODO editarEmpleado se encarga de editar el usuario del empleado

public function editarUsuario($id, $data)
{
    return $this->db->table('usuarios')
        ->where('id', $id)
        ->update($data);
}

// MÉTODO REFERENCIADO DE js inicioSesionVista, se encarga de iniciar la sesión
// con las credenciales introducidas, CONTROLADOR Usuario, MÉTODO procesarSesion
// si existe, es que el usuario existe
    public function verificarCredenciales($usuario, $password)
    {
        // Se asume que 'nombre_usuario' y 'contraseña' son los nombres de columna
        return $this->where('nombre_usuario', $usuario)
                    ->where('contraseña', $password)
                    ->first(); // Devuelve el registro si existe, o null en caso contrario
    }

    public function obtenerEmpleadoPorUsuario($usuario_id)
    {
        return $this->db->table('empleados')
                        ->where('usuario_id', $usuario_id)
                        ->get()
                        ->getRowArray();
    }

    public function obtenerNombreTipo($id_tipo)
    {
        // Se asume que en la tabla 'tipo_usuario' se tienen al menos dos columnas: 'id' y 'nombre_tipo'
        $query = $this->db->table('tipo_usuario')
                          ->select('nombre_tipo')
                          ->where('id', $id_tipo)
                          ->get();
        return $query->getRowArray(); // Devuelve un array con 'nombre_tipo' o null si no se encuentra
    }

    // MÉTODO LLAMADO DESDE EL CONTROLADOR USUARIO, MÉTODO obtenerReservasEventosActuales, desde el js de eventoVista, perfilEvento, horarioEvento,
    // se encarga de obtener todas las reservas de eventos actuales del usuario con la sesión iniciada
    public function obtenerReservasEventosActuales($usuario_id)
{
    $builder = $this->db->table('reservas_eventos');
    $builder->select(
        'reservas_eventos.*, 
         eventos.nombre as nombre_evento, 
         eventos.descripcion, 
         eventos.imagen, 
         eventos.fecha, 
         eventos.franja_horaria_id,  
         tipo_evento.nombre as tipo_evento, 
         franjas_horarias.hora_inicio, 
         franjas_horarias.hora_fin, 
         reservas_eventos.total as total_pagado'
    );
    $builder->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left');
    $builder->join('tipo_evento', 'tipo_evento.id = eventos.tipo_evento_id', 'left');
    $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left'); // Cambiado a eventos.franja_horaria_id
    $builder->where('reservas_eventos.usuario_id', $usuario_id);
    $builder->where('eventos.fecha >=', date('Y-m-d')); // Cambiado a eventos.fecha
    $builder->orderBy('eventos.fecha', 'ASC'); // Cambiado a eventos.fecha
    return $builder->get()->getResultArray();
}

/* ESTE MÉTODO REFERENCIADO DESDE EL MÉTODO obtenerReservasCarrerasActuales DEL CONTROLADOR USUARIO
   SE ENCARGA DE OBTENER TODAS LAS RESERVAS QUE SEAN IGUALES O SUPERIORES AL DÍA ACTUAL DEL USUARIO,
   SE USA EN LOS js de carreraVista, perfilVista, horarioCarrera
*/
    public function obtenerReservasCarrerasActuales($usuario_id)
    {
        $builder = $this->db->table('carreras');
        $builder->select(
            'carreras.*, 
             pistas.nombre as nombre_pista, 
             carreras.cantidad as total_pagado, 
             franjas_horarias.hora_inicio, 
             franjas_horarias.hora_fin'
        );
        $builder->join('pistas', 'pistas.id = carreras.id_pistas', 'left');
        $builder->join('franjas_horarias', 'franjas_horarias.id = carreras.franja_horaria_id', 'left');
        $builder->where('carreras.id_usuario', $usuario_id);
        $builder->where('carreras.fecha >=', date('Y-m-d'));
        $builder->orderBy('carreras.fecha', 'ASC');
        return $builder->get()->getResultArray();
    }

    /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS DE EVENTOS
    PASADAS QUE TENGA EL USUARIO CON LA SESIÓN INICIADA
    Controlador: Usuario, Método: obtenerHistorialReservasEventos
    */
    public function obtenerHistorialReservasEventos($usuario_id)
    {
        $builder = $this->db->table('reservas_eventos');
        $builder->select(
            'reservas_eventos.*, 
             eventos.nombre as nombre_evento, 
             eventos.descripcion, 
             eventos.imagen, 
             eventos.fecha,                  
             eventos.franja_horaria_id,      
             tipo_evento.nombre as tipo_evento, 
             franjas_horarias.hora_inicio, 
             franjas_horarias.hora_fin, 
             reservas_eventos.total as total_pagado'
        );
        $builder->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left');
        $builder->join('tipo_evento', 'tipo_evento.id = eventos.tipo_evento_id', 'left');
        $builder->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left'); // Cambiado a eventos.franja_horaria_id
        $builder->where('reservas_eventos.usuario_id', $usuario_id);
        $builder->where('eventos.fecha <', date('Y-m-d')); // Cambiado a eventos.fecha
        $builder->orderBy('eventos.fecha', 'DESC'); // Cambiado a eventos.fecha
        $result = $builder->get()->getResultArray();
        log_message('debug', 'obtenerHistorialReservasEventos (usuario_id: ' . $usuario_id . '): ' . print_r($result, true));
        return $result;
    }

      /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS DE CARRERAS
    PASADAS QUE TENGA EL USUARIO CON LA SESIÓN INICIADA
    Controlador: Usuario, Método: obtenerHistorialReservasCarreras
    */
    public function obtenerHistorialReservasCarreras($usuario_id)
    {
        $builder = $this->db->table('carreras');
        $builder->select('carreras.*, pistas.nombre as nombre_pista, carreras.cantidad as total_pagado, franjas_horarias.hora_inicio, franjas_horarias.hora_fin');
        $builder->join('pistas', 'pistas.id = carreras.id_pistas', 'left');
        $builder->join('franjas_horarias', 'franjas_horarias.id = carreras.franja_horaria_id', 'left');
        $builder->where('carreras.id_usuario', $usuario_id);
        $builder->where('carreras.fecha <', date('Y-m-d'));
        $builder->orderBy('carreras.fecha', 'DESC');
        $result = $builder->get()->getResultArray();
        log_message('debug', 'obtenerHistorialReservasCarreras (usuario_id: ' . $usuario_id . '): ' . print_r($result, true));
        return $result;
    }

           /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DEL EVENTO
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    ESTE MÉTODO SE ENCARGA DE ESCOGER LA RESERVA DEL EVENTO SIMPLEMENTE, DESPUÉS POSTERIORMENTE EN OTRO MÉTODO SE BORRA
    Controlador: Usuario, Método: cancelarReservaEvento
    */
    public function obtenerReservaEvento($reservaId)
    {
        return $this->db->table('reservas_eventos')
                        ->select('reservas_eventos.*, 
                                 eventos.fecha, 
                                 eventos.franja_horaria_id, 
                                 franjas_horarias.hora_inicio, 
                                 franjas_horarias.hora_fin')
                        ->join('eventos', 'eventos.id = reservas_eventos.evento_id', 'left')
                        ->join('franjas_horarias', 'franjas_horarias.id = eventos.franja_horaria_id', 'left')
                        ->where('reservas_eventos.id', $reservaId)
                        ->get()
                        ->getRowArray();
    }

    /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DEL EVENTO
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    Controlador: Usuario, Método: obtenerHistorialReservasCarreras
    */
    public function eliminarReservaEvento($reservaId)
    {
        $this->db->table('reservas_eventos')->delete(['id' => $reservaId]);
        return ($this->db->affectedRows() > 0);
    }

       /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DE LA CARRERA
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    ESTE MÉTODO SE ENCARGA DE ESCOGER LA CARRERA SIMPLEMENTE, DESPUÉS POSTERIORMENTE EN OTRO MÉTODO SE BORRA
    Controlador: Usuario, Método: cancelarReservaCarrera
    */
    public function obtenerReservaCarrera($reservaId)
{
    return $this->db->table('carreras')
                    ->select('carreras.*, franjas_horarias.hora_inicio, franjas_horarias.hora_fin')
                    ->join('franjas_horarias', 'franjas_horarias.id = carreras.franja_horaria_id', 'left')
                    ->where('carreras.id', $reservaId)
                    ->get()
                    ->getRowArray();
}

    /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DE LA CARRERA
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    Controlador: Usuario, Método: cancelarReservaCarrera
    */
    public function eliminarReservaCarrera($reservaId)
    {
        $this->db->table('carreras')->delete(['id' => $reservaId]);
        return ($this->db->affectedRows() > 0);
    }

     // Usado en controlador empleado método actualizarCredencialesEmpleado
    // SE ACTUALIZAN LAS CREDENCIALES DEL USUARIO
    // SE USA EN JS de perfilVista y cambiarCredencialesEmpleado
    public function actualizarUsuario($id, array $data)
    {
        $builder = $this->db->table('usuarios');
        
        // 2. Establecer campos a actualizar
        $builder->set('nombre_usuario', $data['nombre_usuario']);
        $builder->set('nombre', $data['nombre']);
        $builder->set('apellidos', $data['apellidos']);
        $builder->set('email', $data['email']);
        $builder->set('telefono', $data['telefono']);
        
        // 3. Actualizar contraseña solo si se proporciona (ya viene con MD5 aplicado)
        if (!empty($data['contraseña'])) {
            $builder->set('contraseña', $data['contraseña']); // Ya viene hasheada del controller
        }
        
        // 4. Agregar condición WHERE
        $builder->where('id', $id);
        
        // 5. Ejecutar actualización
        $result = $builder->update();
        
        // 6. Retornar resultado (true/false)
        return $result;
    }
    
    public function obtenerContrasena($userId)
    {
        // Obtener la sesión actual
        $session = session();
        // Comprobar que la sesión tenga un usuario y que su ID coincida con el que se solicita
        if (!$session->has('id') || $session->get('id') != $userId) {
            // Si no hay sesión iniciada o el ID no coincide, se retorna null
            return null;
        }

        $builder = $this->db->table('usuarios');
        $builder->select('contraseña');
        $builder->where('id', $userId);
        $query = $builder->get();
        $result = $query->getRowArray();

        if ($result && isset($result['contraseña'])) {
            return $result['contraseña'];
        }
        return null;
    }

    // MÉTODO REFERENCIADO DESDE js gestionEmpleados, gestionUsuarios, CONTROLADOR ADMIN, MÉTODO verificarContrasenaActual(), 
    // se encarga de obtener la contraseña del admin, para después compararla con la nueva que se intenta
    // introducir
    public function obtenerContrasenaAdmin($userId)
{
    $builder = $this->db->table('usuarios');
    $builder->select('contraseña');
    $builder->where('id', $userId);
    $query = $builder->get();
    $result = $query->getRowArray();

    return $result['contraseña'] ?? null;
}

// MÉTODO REFERENCIADO DESDE js de gestionEmpleados, gestionUsuarios, CONTROLADOR ADMIN, MÉTODO cambiarContrasena, 
// se encarga de cambiar la contraseña por la nueva contraseña introducida
public function actualizarContrasena($userId, $nuevaContrasenaPlana)
{
    // Encriptar la nueva contraseña en MD5
    $nuevaContrasenaMd5 = md5($nuevaContrasenaPlana);

    $builder = $this->db->table('usuarios');
    $builder->where('id', $userId);
    return $builder->update([
        'contraseña' => $nuevaContrasenaMd5,
    ]);
}

// MÉTODO REFERENCIADO EN js de gestionUsuarios, CONTROLADOR ADMIN, MÉTODO obtenerUsuariosClientes, 
// que sirve para obtener a todos los usuarios que son de tipo cliente

public function obtenerUsuariosClientes()
{
    $builder = $this->db->table('usuarios');
    $builder->select('usuarios.id, usuarios.nombre_usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, usuarios.telefono, usuarios.estado');
    $builder->join('tipo_usuario', 'usuarios.id_tipo = tipo_usuario.id');
    $builder->where('tipo_usuario.nombre_tipo', 'cliente');

    $query = $builder->get();
    return $query->getResultArray(); // Devuelve un array de usuarios
}

// MÉTODO REFERENCIADO DESDE JS DE gestionCarreras (admin), nuevoCarrerasEmpleado (empleado), CONTROLADOR EMPLEADO, MÉTODO obtenerUsuariosClientesActivos, 
// se utiliza para obtener los usuarios clientes que están activos
// MÉTODO REFERENCIADO DESDE JS DE gestionEventos, CONTROLADOR EMPLEADO, MÉTODO obtenerUsuariosClientesActivos,se utiliza para obtener los usuarios
// clientes que están activos
public function obtenerUsuariosClientesActivos()
{
    $builder = $this->db->table('usuarios');
    $builder->select('usuarios.id, usuarios.nombre_usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, usuarios.telefono');
    $builder->join('tipo_usuario', 'usuarios.id_tipo = tipo_usuario.id');
    $builder->where('tipo_usuario.nombre_tipo', 'cliente');
    $builder->where('usuarios.estado', 1); // Solo usuarios activos
    $query = $builder->get();
    return $query->getResultArray();
}
    
}
