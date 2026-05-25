<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel; 
use App\Models\AdminModel;
use App\Models\ReservasEventosModel;
use App\Models\CarreraModel;
use App\Models\EmpleadoModel;
use App\Models\EmpleadoEventosModel;
use App\Models\EventoModel;
use App\Models\EmpleadoCarrerasModel;

class Admin extends BaseController
{

    public function index()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/inicioAdmin') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

public function indexGestionUsuarios()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/gestionUsuarios') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

public function indexGestionEmpleados()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/gestionEmpleados') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

public function indexGestionNoticias()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/gestionNoticias') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

public function indexGestionCarreras()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/gestionCarreras') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

public function indexGestionEventos()
{
    $session = session();
    
    // Verifica si la sesión está iniciada
    if (!$session->get('sesion_iniciada')) {
        return redirect()->to(base_url()); // Si no has iniciado sesión, te redirige al inicio normal
    }

    // Si el usuario es administrador, puedes redirigirlo a la página de administración o cualquier otra vista de admin
    if (strtolower($session->get('tipo_usuario')) === 'admin') {
        return view('admin/navbarAdmin') . view('admin/gestionEventos') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

// MÉTODO REFERENCIADO EN js de gestionUsuarios, que sirve para obtener a todos los usuarios que son de tipo cliente
public function obtenerUsuariosClientes()
{
    // 3. Obtener datos
    $model = new UsuarioModel();
    $usuarios = $model->obtenerUsuariosClientes();

    // 4. Respuesta estructurada
    if ($usuarios) {
        return $this->response->setJSON([
            'success' => true,
            'data' => $usuarios,  // Cambiado a 'data' para consistencia con DataTables
            'recordsTotal' => count($usuarios),
            'recordsFiltered' => count($usuarios)
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron usuarios clientes',
            'data' => []  // Asegura que dataTables reciba un array vacío
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionUsuarios, que sirve para desactivar al usuario seleccionado
public function desactivarUsuario($usuarioId)
{
    // Validación de permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    // Instanciar modelos
    $adminModel = new AdminModel();

    // 1. Desactivar usuario
    $adminModel->desactivarUsuario($usuarioId);

    // Respuesta simple
    return $this->response->setJSON([
        'success' => true,
        'message' => 'Operación completada: Usuario desactivado y reservas eliminadas'
    ]);
}

// MÉTODO REFERENCIADO EN js de gestionUsuarios, que sirve para activar al usuario seleccionado
public function activarUsuario($usuarioId)
{
    // Validación de permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    // Instanciar modelo
    $adminModel = new AdminModel();

    // 1. Activar usuario
    $activar = $adminModel->activarUsuario($usuarioId);

    if ($activar) {
        // Respuesta de éxito si la activación fue exitosa
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Operación completada: Usuario activado correctamente.'
        ]);
    } else {
        // Respuesta de error si algo salió mal
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al activar el usuario. Intente nuevamente.'
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js de gestionEmpleados, gestionUsuarios, se encarga de obtener todos 
// los datos del usuario con el id seleccionado
public function obtenerUsuarioEdicion($id)
{
    log_message('debug', 'Iniciando obtenerUsuarioEdicion para ID: ' . $id);

    $session = session();

    // Verificar si la sesión está iniciada y si el usuario tiene permisos de administrador
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No autorizado para realizar esta acción.'
        ]);
    }

    // Instanciar el modelo de usuario dentro del método
    $usuarioModel = new UsuarioModel();

    // Obtener los datos del usuario desde la base de datos
    $usuario = $usuarioModel->find($id);

    if (!$usuario) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Usuario no encontrado.'
        ]);
    }

    return $this->response->setJSON([
        'success' => true,
        'data'    => $usuario
    ]);
}

// MÉTODO QUE REFERENCIA A js gestionEmpleados, gestionUsuarios CONTROLADOR ADMIN, MÉTODO editarEmpleado se encarga de editar el usuario del empleado

public function editarUsuario($id)
{
    log_message('debug', 'Iniciando editarUsuario para id: ' . $id);

    $session = session();
    // Verifica que haya sesión iniciada y que el usuario sea administrador
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();

    // Validar campos obligatorios y su formato
    $errores = [];

    if (empty($datos['nombre_usuario'])) {
        $errores[] = "El nombre de usuario es obligatorio.";
    }
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($datos['apellidos'])) {
        $errores[] = "Los apellidos son obligatorios.";
    }
    if (empty($datos['email'])) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }
    if (empty($datos['telefono'])) {
        $errores[] = "El número de teléfono es obligatorio.";
    } elseif (!preg_match('/^\d{9}$/', $datos['telefono'])) {
        $errores[] = "El número de teléfono debe tener 9 dígitos.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    $usuarioModel = new UsuarioModel;

    // Verificar si el usuario existe
    $usuarioExistente = $usuarioModel->find($id);
    if (!$usuarioExistente) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Usuario no encontrado.']
        ]);
    }

    // Datos básicos a actualizar
    $datosActualizar = [
        'nombre_usuario' => $datos['nombre_usuario'],
        'nombre'         => $datos['nombre'],
        'apellidos'      => $datos['apellidos'],
        'email'          => $datos['email'],
        'telefono'       => $datos['telefono']
    ];

    // ✅ Lógica para cambiar contraseña solo si se envía una nueva
    if (!empty($datos['contraseña'])) {
        // Comprobar que la nueva contraseña sea distinta a la actual
        if (md5($datos['contraseña']) === $usuarioExistente['contraseña']) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['La nueva contraseña no puede ser igual a la anterior.']
            ]);
        }

        // Comprobar que la nueva contraseña y su confirmación coincidan
        if (empty($datos['confirmar_contraseña']) || $datos['contraseña'] !== $datos['confirmar_contraseña']) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Las contraseñas no coinciden.']
            ]);
        }

        // Guardar la nueva contraseña en MD5
        $datosActualizar['contraseña'] = md5($datos['contraseña']);
    }

    // Actualizar en la base de datos
    if ($usuarioModel->update($id, $datosActualizar)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al actualizar el usuario.']
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de obtener a todos los 
// empleados existentes en la web
public function obtenerEmpleados()
{
    // 3. Obtener datos
    $model = new EmpleadoModel();
    $empleados = $model->obtenerEmpleados();

    // 4. Respuesta estructurada
    if ($empleados) {
        return $this->response->setJSON([
            'success' => true,
            'data' => $empleados,  // Cambiado a 'data' para consistencia con DataTables
            'recordsTotal' => count($empleados),
            'recordsFiltered' => count($empleados)
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron empleados',
            'data' => []  // Asegura que dataTables reciba un array vacío
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de ver si al usuario
// que se le quiere deshabilitar el rol de evento, tiene ahora mismo eventos
// pendientes, en ese caso no se le puede deshabilitar hasta asignarselos a otro empleado
// con el rol de evento
public function verificarEventosPendientes($idEmpleado)
{
    try {
        $empleadoEventos = new EmpleadoEventosModel();
        $eventosPendientes = $empleadoEventos->obtenerEventosFuturos($idEmpleado);

        $response = [
            'success' => true,
            'tieneEventosPendientes' => false,
            'total' => 0,
            'empleado' => null,
        ];

        if (!empty($eventosPendientes)) {
            $response = [
                'success' => true,
                'tieneEventosPendientes' => true,
                'total' => count($eventosPendientes),
                'empleado' => [
                    'id' => $eventosPendientes[0]->empleados_id, // Acceso como objeto
                    'nombre_completo' => $eventosPendientes[0]->nombre_empleado ?? 'Empleado sin nombre'
                ]
            ];
        }

        return $this->response->setJSON($response);

    } catch (\Exception $e) {
        log_message('error', 'Error en verificarEventosPendientes - Empleado ID: '.$idEmpleado.' - Error: '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error técnico al verificar eventos',
            'total' => 0
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de 
// desactivar al empleado del rol de evento

public function desactivarEmpleadoEvento($empleadoId)
{
    // Verificación simple de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $empleadoModel = new EmpleadoModel();
        $resultado = $empleadoModel->desactivarRolEvento($empleadoId);
        
        return $this->response->setJSON([
            'success' => (bool)$resultado,
            'message' => $resultado ? 'Rol de eventos desactivado' : 'No se pudo desactivar'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Admin desactivar rol evento: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error en el servidor'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de 
// activar al empleado del rol de evento MÉTODO activarRolEvento
public function activarEmpleadoEvento($empleadoId)
{
    // Validación de permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        // Instanciar modelo
        $empleadoModel = new EmpleadoModel();
        
        // Activar rol de eventos
        $resultado = $empleadoModel->activarRolEvento($empleadoId);
        
        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rol de eventos activado correctamente'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo activar el rol de eventos'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error activando rol evento: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error interno al activar el rol'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, se encarga de desactivar el rol de noticia
// del empleado seleccionado
public function desactivarEmpleadoNoticia($empleadoId)
{
    // Verificación básica de admin (opcional si ya lo hiciste antes)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    try {
        // Desactivación directa
        $empleadoModel = new EmpleadoModel();
        $resultado = $empleadoModel->desactivarRolNoticia($empleadoId);
        
        return $this->response->setJSON([
            'success' => (bool)$resultado,
            'message' => $resultado ? 'Rol de noticias desactivado' : 'No se pudo desactivar'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error desactivando rol noticia: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error en el servidor'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, 
// se encarga de activar el rol de noticia del empleado seleccionado
public function activarEmpleadoNoticia($empleadoId)
{
    // Verificación de permisos (opcional pero recomendado)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    try {
        // Instanciar modelo y activar rol
        $empleadoModel = new EmpleadoModel();
        $resultado = $empleadoModel->activarRolNoticia($empleadoId);
        
        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rol de noticias activado correctamente'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo activar el rol de noticias'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error activando rol noticia - Empleado ID: '.$empleadoId.' - Error: '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error interno al activar el rol'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, 
// se encarga de verificar si el empleado de carreras tiene 
// asignadas carreras para un futuro, en ese caso no se le podrá 
// deshabilitar su rol, MÉTODO verificarCarrerasPendientes
public function verificarCarrerasPendientes($idEmpleado)
{
    try {
        $empleadoCarrerasModel = new EmpleadoCarrerasModel();
        $carrerasPendientes = $empleadoCarrerasModel->obtenerCarrerasFuturas($idEmpleado);
        
        $response = [
            'success' => true,
            'tieneCarrerasPendientes' => false,
            'total' => 0,
            'empleado' => null,
        ];

        if (!empty($carrerasPendientes)) {
            $response = [
                'success' => true,
                'tieneCarrerasPendientes' => true,
                'total' => count($carrerasPendientes),
                'empleado' => [
                    'id' => $carrerasPendientes[0]->empleado_id,
                    'nombre_completo' => $carrerasPendientes[0]->nombre_empleado ?? 'Empleado sin nombre'
                ]
            ];
        }

        return $this->response->setJSON($response);

    } catch (\Exception $e) {
        log_message('error', 'Error en verificarCarrerasPendientes - Empleado ID: '.$idEmpleado.' - Error: '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error técnico al verificar carreras',
            'total' => 0
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, se encarga de desactivar el rol de carrera
// del empleado seleccionado 
public function desactivarEmpleadoCarrera($empleadoId)
{
    // Verificación de admin (igual que en eventos)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $empleadoModel = new EmpleadoModel();
        $resultado = $empleadoModel->desactivarRolCarrera($empleadoId);
        
        return $this->response->setJSON([
            'success' => (bool)$resultado,
            'message' => $resultado ? 'Rol de carreras desactivado' : 'No se pudo desactivar'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Admin desactivar rol carrera: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error en el servidor'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, se encarga de activar el rol de carrera
// del empleado seleccionado MÉTODO activarEmpleadoCarrera
public function activarEmpleadoCarrera($empleadoId)
{
    // Verificación de permisos (opcional pero recomendado)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    try {
        // Instanciar modelo y activar rol
        $empleadoModel = new EmpleadoModel();
        $resultado = $empleadoModel->activarRolCarrera($empleadoId);
        
        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rol de carreras activado correctamente'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo activar el rol de carreras'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error activando rol carrera - Empleado ID: '.$empleadoId.' - Error: '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error interno al activar el rol'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de verificar si 
//el empleado que se quiere deshabilitar tiene roles activos actualmente
public function verificarRolesEmpleado($idEmpleado)
{
    try {
        $empleadoModel = new EmpleadoModel();
        $roles = $empleadoModel->obtenerRolesActivos($idEmpleado);
        
        $response = [
            'success' => true,
            'tiene_roles' => false,
            'total' => 0,
            'roles' => []
        ];

        if (!empty($roles)) {
            $response = [
                'success' => true,
                'tiene_roles' => true,
                'total' => count($roles),
                'roles' => $roles,
                'empleado_id' => $idEmpleado
            ];
        }

        return $this->response->setJSON($response);

    } catch (\Exception $e) {
        log_message('error', 'Error en verificarRolesEmpleado - Empleado ID: '.$idEmpleado.' - Error: '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error técnico al verificar roles del empleado',
            'total' => 0
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO DESDE JS gestionEmpleados, se encarga de desactivar
// al empleado seleccionado
public function desactivarEmpleado($usuario_id)
{
    // 1. Validar permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Acceso denegado. Se requiere rol de administrador'
        ])->setStatusCode(403);
    }

    try {
        $adminModel = new AdminModel();
        $empleadoModel = new EmpleadoModel();

        // 2. Buscar empleado asociado al usuario
        $empleado = $empleadoModel->where('usuario_id', $usuario_id)->first();
        
        // 3. Verificar roles si es empleado
        if ($empleado) {
            $roles = $empleadoModel->obtenerRolesActivos($empleado['id']);
            
            if (!empty($roles)) {
                $listaRoles = implode(', ', $roles);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "No se puede desactivar. Roles activos: $listaRoles"
                ])->setStatusCode(400);
            }
        }

        // 4. Usar el método específico del AdminModel
        $resultado = $adminModel->desactivarUsuario($usuario_id);

        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Usuario desactivado correctamente'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se pudo desactivar el usuario'
        ])->setStatusCode(400);

    } catch (\Exception $e) {
        log_message('error', 'Error al desactivar usuario ID '.$usuario_id.': '.$e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error técnico al desactivar'
        ])->setStatusCode(500);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de activar al empleado seleccionado
public function activarEmpleado($usuarioId)
{
    // Validación de permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    // Instanciar modelo
    $adminModel = new AdminModel();

    // 1. Activar usuario
    $activar = $adminModel->activarUsuario($usuarioId);

    if ($activar) {
        // Respuesta de éxito si la activación fue exitosa
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Operación completada: Usuario empleado activado correctamente.'
        ]);
    } else {
        // Respuesta de error si algo salió mal
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al activar al usuario empleado. Intente nuevamente.'
        ]);
    }
}

// MÉTODO LLAMADO DESDE JS DE cambiarCredencialesEmpleado, gestionEmpleados, gestionUsuarios, perfilVista se utiliza para verificar si los
// campos que se envían de usuario, email y contraseña son o no ya existentes en la 
// web de AleKarting
public function validarCamposUnicos()
{
    $id = $this->request->getPost('id') ?? 0;
    $username = $this->request->getPost('nombre_usuario');
    $email = $this->request->getPost('email');
    $telefono = $this->request->getPost('telefono');

    $usuarioModel = new UsuarioModel();

    $response = ['success' => true];
    $esNuevo = empty($id);

    // Validación para nombre de usuario
    if ($esNuevo) {
        if ($usuarioModel->existeNombreUsuario($username)) {
            $response['error_nombre_usuario'] = 'El nombre de usuario ya está registrado';
            $response['success'] = false;
        }
    } else {
        if ($usuarioModel->existeNombreUsuarioDiferente($username, $id)) {
            $response['error_nombre_usuario'] = 'El nombre de usuario ya está registrado';
            $response['success'] = false;
        }
    }

    // Validación para email
    if ($esNuevo) {
        if ($usuarioModel->existeCorreo($email)) {
            $response['error_email'] = 'El correo electrónico ya está registrado';
            $response['success'] = false;
        }
    } else {
        if ($usuarioModel->existeCorreoDiferente($email, $id)) {
            $response['error_email'] = 'El correo electrónico ya está registrado';
            $response['success'] = false;
        }
    }

    // Validación para teléfono
    if ($esNuevo) {
        if ($usuarioModel->existeTelefono($telefono)) {
            $response['error_telefono'] = 'El teléfono ya está registrado';
            $response['success'] = false;
        }
    } else {
        if ($usuarioModel->existeTelefonoDiferente($telefono, $id)) {
            $response['error_telefono'] = 'El teléfono ya está registrado';
            $response['success'] = false;
        }
    }

    return $this->response->setJSON($response);
}

// MÉTODO REFERENCIADO EN js de gestionUsuarios, que sirve para crear al usuario
public function crearUsuario()
{
    log_message('debug', 'Iniciando crearUsuario');

    $session = session();

    // Verificar si el usuario tiene permisos (solo administradores pueden crear usuarios)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();

    // Validar que las contraseñas coinciden
    if (empty($datos['contraseña']) || empty($datos['confirmar_contraseña'])) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas son obligatorias.']
        ]);
    }

    if ($datos['contraseña'] !== $datos['confirmar_contraseña']) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas no coinciden.']
        ]);
    }

    // Validar campos obligatorios
    $errores = [];

    if (empty($datos['nombre_usuario'])) {
        $errores[] = "El nombre de usuario es obligatorio.";
    }
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($datos['apellidos'])) {
        $errores[] = "Los apellidos son obligatorios.";
    }
    if (empty($datos['email'])) {
        $errores[] = "El correo electrónico es obligatorio.";
    }
    if (empty($datos['telefono'])) {
        $errores[] = "El número de teléfono es obligatorio.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    $usuarioModel = new UsuarioModel();

    // Intentar crear el usuario usando el método del modelo
    if ($usuarioModel->crearUsuario($datos, 'cliente')) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario creado correctamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear el usuario.']
        ]);
    }
}

// MÉTODO QUE REFERENCIA A js gestionEmpleados, se encarga de crear un nuevo empleado (para ello primero crea el usuario, y después el empleado y lo enlaza)
public function crearEmpleado()
{
    log_message('debug', 'Iniciando crearEmpleado');

    $session = session();

    // Verificar si el usuario tiene permisos (solo administradores pueden crear empleados)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();

    // Validar que las contraseñas coinciden (solo si es un nuevo empleado)
    if (empty($datos['contraseña']) || empty($datos['confirmar_contraseña'])) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas son obligatorias.']
        ]);
    }

    if ($datos['contraseña'] !== $datos['confirmar_contraseña']) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas no coinciden.']
        ]);
    }

    // Validar campos obligatorios
    $errores = [];

    if (empty($datos['nombre_usuario'])) {
        $errores[] = "El nombre de usuario es obligatorio.";
    }
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($datos['apellidos'])) {
        $errores[] = "Los apellidos son obligatorios.";
    }
    if (empty($datos['email'])) {
        $errores[] = "El correo electrónico es obligatorio.";
    }
    if (empty($datos['telefono'])) {
        $errores[] = "El número de teléfono es obligatorio.";
    }

    // Validar que al menos un rol esté seleccionado si es un nuevo empleado
    if (empty($datos['roles']) || !is_array($datos['roles']) || count($datos['roles']) === 0) {
        $errores[] = "Debe seleccionar al menos un rol.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // 1️ Crear el usuario
    $usuarioModel = new UsuarioModel();

    // Datos del usuario (SIN id_tipo, porque ahora se pasa como parámetro)
    $usuarioData = [
        'nombre_usuario' => $datos['nombre_usuario'],
        'nombre'         => $datos['nombre'],
        'apellidos'      => $datos['apellidos'],
        'email'          => $datos['email'],
        'telefono'       => $datos['telefono'],
        'contraseña'     => $datos['contraseña']
    ];

    // Intentar crear el usuario y pasar "empleado" como tipo
    $usuarioId = $usuarioModel->crearUsuario($usuarioData, 'empleado');

    if (!$usuarioId) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear el usuario.']
        ]);
    }

    // 2️ Crear el empleado usando el id del usuario recién creado
    $empleadoModel = new EmpleadoModel();

    // Preparar los datos del empleado
    $empleadoData = [
        'usuario_id'   => $usuarioId,
        'emp_noticia'  => in_array('emp_noticia', $datos['roles']) ? 1 : 0,
        'emp_evento'   => in_array('emp_evento', $datos['roles']) ? 1 : 0,
        'emp_carreras' => in_array('emp_carreras', $datos['roles']) ? 1 : 0
    ];

    // Intentar crear el empleado
    if ($empleadoModel->crearEmpleado($empleadoData)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Empleado creado correctamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear el empleado.']
        ]);
    }
}

// MÉTODO QUE REFERENCIA A js gestionEmpleados, se encarga de crear un nuevo admin
public function crearAdministrador()
{
    log_message('debug', 'Iniciando crearAdministrador');
    $session = session();
    
    // Verificar permisos (solo admins pueden crear otros admins)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();

    // Validar contraseñas (igual que en crearEmpleado)
    if (empty($datos['contraseña']) || empty($datos['confirmar_contraseña'])) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas son obligatorias.']
        ]);
    }
    if ($datos['contraseña'] !== $datos['confirmar_contraseña']) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Las contraseñas no coinciden.']
        ]);
    }

    // Validar campos obligatorios (igual que en crearEmpleado pero sin roles)
    $errores = [];
    if (empty($datos['nombre_usuario'])) {
        $errores[] = "El nombre de usuario es obligatorio.";
    }
    if (empty($datos['nombre'])) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($datos['apellidos'])) {
        $errores[] = "Los apellidos son obligatorios.";
    }
    if (empty($datos['email'])) {
        $errores[] = "El correo electrónico es obligatorio.";
    }
    if (empty($datos['telefono'])) {
        $errores[] = "El número de teléfono es obligatorio.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // 1 Crear el usuario como admin (usando tu método existente)
    $usuarioModel = new UsuarioModel();
    $usuarioData = [
        'nombre_usuario' => $datos['nombre_usuario'],
        'nombre'         => $datos['nombre'],
        'apellidos'      => $datos['apellidos'],
        'email'          => $datos['email'],
        'telefono'       => $datos['telefono'],
        'contraseña'     => $datos['contraseña']
    ];

    // Aquí usamos 'admin' como tipo
    $usuarioId = $usuarioModel->crearUsuario($usuarioData, 'admin');
    
    if (!$usuarioId) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear el usuario administrador.']
        ]);
    }

    // 2 Crear el empleado con todos los permisos (para acceso al sistema)
    $empleadoModel = new EmpleadoModel();
    $empleadoData = [
        'usuario_id'   => $usuarioId,
        'emp_noticia'  => 1, // Acceso total
        'emp_evento'   => 1, // Acceso total
        'emp_carreras' => 1,  // Acceso total
        'esAdmin' => 1
    ];

    if ($empleadoModel->crearEmpleado($empleadoData)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Administrador creado correctamente con acceso completo.'
        ]);
    } else {
        // Revertir si falla
        $usuarioModel->eliminarUsuario($usuarioId);
        
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear el perfil de empleado para el administrador.']
        ]);
    }
}

// MÉTODO LLAMADO DESDE EL JS DE gestionCarreras (admin), inicioAdmin se utiliza para obtener a todos los empleados
// de carreras activos
public function getEmpleadosCarreras()
{
    log_message('debug', 'Iniciando getEmpleadosCarreras');
    $session = session();
    
    // Verificación de sesión y permisos (solo admin)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Acceso denegado: se requiere ser administrador'],
            'data' => []
        ]);
    }

    try {
        $empleadoModel = new EmpleadoModel();
        $empleados = $empleadoModel->obtenerEmpleadosConPermisosCarreras();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $empleados ?: [],
            'message' => 'Listado de empleados con permisos de carreras',
            'recordsTotal' => count($empleados),
            'recordsFiltered' => count($empleados)
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en getEmpleadosCarreras: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al obtener el listado de empleados'],
            'data' => []
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionCarreras (admin), se encarga de hacer la reserva
// de la carrera para el usuario escogido
public function crearReservaCarreraAdmin()
{
    log_message('debug', 'Iniciando crearReservaCarreraAdmin');
    $session = session();

    // Verificar que haya sesión iniciada y que el usuario sea admin
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();

    // Validaciones
    $errores = [];

    if (empty($datos['id_usuario'])) {
        $errores[] = "El usuario es obligatorio.";
    }

    if (empty($datos['empleado_id'])) {
        $errores[] = "El empleado es obligatorio.";
    }

    if (empty($datos['num_participantes'])) {
        $errores[] = "El número de participantes es obligatorio.";
    } elseif (!is_numeric($datos['num_participantes']) || (int)$datos['num_participantes'] <= 0) {
        $errores[] = "El número de participantes debe ser mayor a 0.";
    } elseif ((int)$datos['num_participantes'] > 20) {
        $errores[] = "El número máximo de participantes es 20.";
    }

    if (empty($datos['fecha_carrera'])) {
        $errores[] = "La fecha de la carrera es obligatoria.";
    } else {
        $fechaReserva = \DateTime::createFromFormat('Y-m-d', $datos['fecha_carrera']);
        $fechaMaxima = (new \DateTime())->modify('+2 years');

        if (!$fechaReserva || $fechaReserva > $fechaMaxima) {
            $errores[] = 'No se pueden hacer reservas con más de 2 años de antelación.';
        }
    }

    if (empty($datos['franja_horaria_id'])) {
        $errores[] = "La franja horaria es obligatoria.";
    }

    if (empty($datos['fecha_pago'])) {
        $errores[] = "La fecha de pago es obligatoria.";
    }

    if (empty($datos['id_pistas'])) {
        $errores[] = "La pista es obligatoria.";
    }

    if (empty($datos['metodo_pago'])) {
        $errores[] = "El método de pago es obligatorio.";
    } else {
        $metodosPermitidos = ['card', 'paypal'];
        if (!in_array(strtolower($datos['metodo_pago']), $metodosPermitidos)) {
            $errores[] = "Método de pago no soportado: {$datos['metodo_pago']}";
        }
    }

    if (empty($datos['cantidad'])) {
        $errores[] = "El precio es obligatorio.";
    } elseif (!is_numeric($datos['cantidad'])) {
        $errores[] = "El precio debe ser un valor numérico.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // Validar que el empleado exista
    $empleadoId = (int)$datos['empleado_id'];
    $empleadoModel = new \App\Models\EmpleadoModel();
    $empleado = $empleadoModel->find($empleadoId);

    if (!$empleado) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['El empleado seleccionado no existe.']
        ]);
    }

    // Calcular el total
    $precioUnitario = (float)$datos['cantidad'];
    $total = $precioUnitario * (int)$datos['num_participantes'];

    // Preparar los datos para inserción
    $datosInsertar = [
        'id_usuario'        => (int)$datos['id_usuario'],
        'empleado_id'       => $empleadoId,
        'metodo_pago'       => $datos['metodo_pago'],
        'id_pistas'         => (int)$datos['id_pistas'],
        'num_participantes' => (int)$datos['num_participantes'],
        'fecha'             => $datos['fecha_carrera'],
        'franja_horaria_id' => (int)$datos['franja_horaria_id'],
        'fecha_pago'        => $datos['fecha_pago'],
        'cantidad'          => $precioUnitario,
        'total'             => $total,
        'payment_intent_id' => null
    ];

    $empleadoCarrerasModel = new \App\Models\EmpleadoCarrerasModel();

    if ($empleadoCarrerasModel->insert($datosInsertar)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reserva de carrera creada correctamente.',
            'id'      => $empleadoCarrerasModel->getInsertID()
        ]);
    } else {
        log_message('error', 'Error al crear reserva: ' . print_r($empleadoCarrerasModel->errors(), true));
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear la reserva de carrera.']
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionCarreras (admin), se encarga de editar la reserva
// de la carrera para el usuario escogido
public function editarReservaCarreraAdmin($id)
{
    log_message('debug', 'Iniciando editarReservaCarrera para id: ' . $id);
    $session = session();

    // Solo permitir admins
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado. Solo administradores pueden editar reservas.']
        ]);
    }

    // Recoger datos del POST
    $datos = $this->request->getPost();

    // Validaciones
    $errores = [];

    if (empty($datos['id_usuario'])) {
        $errores[] = "El id de usuario es obligatorio.";
    }

    if (empty($datos['empleado_id'])) {
        $errores[] = "El empleado es obligatorio.";
    }

    if (empty($datos['num_participantes'])) {
        $errores[] = "El número de participantes es obligatorio.";
    } elseif (!is_numeric($datos['num_participantes']) || (int)$datos['num_participantes'] <= 0) {
        $errores[] = "El número de participantes debe ser mayor a 0.";
    } elseif ((int)$datos['num_participantes'] > 20) {
        $errores[] = "El número máximo de participantes es 20.";
    }

    if (empty($datos['fecha_carrera'])) {
        $errores[] = "La fecha de la carrera es obligatoria.";
    }

    if (empty($datos['franja_horaria_id'])) {
        $errores[] = "La franja horaria es obligatoria.";
    }

    if (empty($datos['fecha_pago'])) {
        $errores[] = "La fecha de pago es obligatoria.";
    }

    if (empty($datos['id_pistas'])) {
        $errores[] = "La pista es obligatoria.";
    }

    if (empty($datos['metodo_pago'])) {
        $errores[] = "El método de pago es obligatorio.";
    } else {
        $metodosPermitidos = ['card', 'paypal'];
        if (!in_array(strtolower($datos['metodo_pago']), $metodosPermitidos)) {
            $errores[] = "Método de pago no soportado: {$datos['metodo_pago']}";
        }
    }

    if (empty($datos['cantidad'])) {
        $errores[] = "El precio es obligatorio.";
    } elseif (!is_numeric($datos['cantidad'])) {
        $errores[] = "El precio debe ser un valor numérico.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // Obtener la reserva
    $empleadoCarrerasModel = new \App\Models\EmpleadoCarrerasModel();
    $reservaExistente = $empleadoCarrerasModel->find($id);

    if (!$reservaExistente) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Reserva de carrera no encontrada.']
        ]);
    }

    // Validar que el empleado exista
    $empleadoModel = new \App\Models\EmpleadoModel();
    $empleado = $empleadoModel->find((int)$datos['empleado_id']);

    if (!$empleado) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['El empleado seleccionado no existe.']
        ]);
    }

    // Calcular el total
    $precioUnitario = (float)$datos['cantidad'];
    $total = $precioUnitario * (int)$datos['num_participantes'];

    // Armar los datos
    $datosActualizar = [
        'id_usuario'        => (int)$datos['id_usuario'],
        'empleado_id'       => (int)$datos['empleado_id'],
        'metodo_pago'       => $datos['metodo_pago'],
        'num_participantes' => (int)$datos['num_participantes'],
        'fecha'             => $datos['fecha_carrera'],
        'franja_horaria_id' => (int)$datos['franja_horaria_id'],
        'fecha_pago'        => $datos['fecha_pago'],
        'id_pistas'         => (int)$datos['id_pistas'],
        'cantidad'          => $precioUnitario,
        'total'             => $total
    ];

    // Actualizar reserva
    if ($empleadoCarrerasModel->update($id, $datosActualizar)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reserva de carrera actualizada correctamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al actualizar la reserva de carrera.']
        ]);
    }
}


// MÉTODO REFERENCIADO DE js gestionEventos (admin), inicioAdmin, se encarga de obtener a todos los empleados
// con el rol de eventos activo, MÉTODO getEmpleadosEventos
public function getEmpleadosEventos()
{
    log_message('debug', 'Iniciando getEmpleadosEventos');
    $session = session();
    
    // Verificación de sesión y permisos (solo admin)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Acceso denegado: se requiere ser administrador'],
            'data' => []
        ]);
    }

    try {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $empleados = $empleadoModel->obtenerEmpleadosConPermisosEventos();
        
        log_message('debug', 'Número de empleados con permisos de eventos: ' . count($empleados));
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $empleados ?: [],
            'message' => 'Listado de empleados con permisos de eventos',
            'recordsTotal' => count($empleados),
            'recordsFiltered' => count($empleados)
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en getEmpleadosEventos: ' . $e->getMessage());
        log_message('error', 'Traza del error: ' . $e->getTraceAsString());
        
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al obtener el listado de empleados: ' . $e->getMessage()],
            'data' => []
        ]);
    }
}

// MÉTODO REFERENCIADO DE js gestionNoticias (admin), inicioAdmin, se encarga de obtener a todos los empleados
// con el rol de noticias activo, CONTROLADOR ADMIN, MÉTODO getEmpleadosNoticias
public function getEmpleadosNoticias()
{
    log_message('debug', 'Iniciando getEmpleadosNoticias');
    $session = session();
    
    // Verificación de sesión y permisos (solo admin)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Acceso denegado: se requiere ser administrador'],
            'data' => []
        ]);
    }

    try {
        $empleadoModel = new \App\Models\EmpleadoModel();
        $empleados = $empleadoModel->obtenerEmpleadosConPermisosNoticias();
        
        log_message('debug', 'Número de empleados con permisos de noticias: ' . count($empleados));
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $empleados ?: [],
            'message' => 'Listado de empleados con permisos de noticias',
            'recordsTotal' => count($empleados),
            'recordsFiltered' => count($empleados)
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en getEmpleadosNoticias: ' . $e->getMessage());
        
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al obtener el listado de empleados'],
            'data' => []
        ]);
    }
}

public function indexCambiarCredenciales()
    {
        $session = session();
        // Verifica que la sesión esté iniciada y que el tipo de usuario sea "empleado"
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
            return redirect()->to(base_url('Iniciar_Sesion'));
        }
        
        // Si es empleado, carga la vista del área de empleado
        return view('admin/navbarAdmin') . view('admin/cambiarCredencialesAdmin') . view('templates/footer');
    }

// MÉTODO REFERENCIADO DESDE js gestionEmpleados, gestionUsuarios, se encarga de verificar
// si la nueva contraseña es repetida o no
    public function verificarContrasenaActual()
{
    // Obtener datos del POST
    $userId = $this->request->getPost('usuario_id');
    $nuevaContrasena = $this->request->getPost('nueva_contrasena');

    $usuarioModel = new UsuarioModel();
    // Obtener la contraseña actual (en MD5) desde la base de datos
    $contrasenaActual = $usuarioModel->obtenerContrasenaAdmin($userId);

    if (!$contrasenaActual) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }

    // Comparar la nueva contraseña (sin encriptar) con la actual (en MD5)
    $esIgual = (md5($nuevaContrasena) === $contrasenaActual);

    return $this->response->setJSON([
        'success' => true,
        'es_igual' => $esIgual,
        'message' => $esIgual ? 'La contraseña es igual a la actual' : 'La contraseña es diferente'
    ]);
}

// MÉTODO REFERENCIADO DESDE js de gestionEmpleados, gestionUsuarios, se encarga de cambiar la contraseña por la nueva
// contraseña introducida
public function cambiarContrasena()
{
    // Obtener datos del POST
    $userId = $this->request->getPost('usuario_id');
    $nuevaContrasena = $this->request->getPost('nueva_contrasena');

    // Validaciones básicas
    if (empty($userId)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'ID de usuario no proporcionado'
        ]);
    }

    if (empty($nuevaContrasena)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'La nueva contraseña no puede estar vacía'
        ]);
    }

    $usuarioModel = new UsuarioModel();
    // Cambiar la contraseña en el modelo
    $resultado = $usuarioModel->actualizarContrasena($userId, $nuevaContrasena);

    if ($resultado) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al actualizar la contraseña'
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEmpleados, se encarga de obtener a todos los 
// admins en la web
public function obtenerAdmins()
{
    $model = new AdminModel();
    $admins = $model->obtenerAdmins();

    if ($admins) {
        return $this->response->setJSON([
            'success' => true,
            'data' => $admins,
            'recordsTotal' => count($admins),
            'recordsFiltered' => count($admins)
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron administradores',
            'data' => []
        ]);
    }
}

}