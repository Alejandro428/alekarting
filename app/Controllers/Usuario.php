<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use \DateTime;
use CodeIgniter\API\ResponseTrait; 

class Usuario extends BaseController
{
    use ResponseTrait; 

    protected $usuarioModel;

    public function __construct()
    {
        log_message('debug', 'Inicializando controlador Usuario');
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
{
    $session = session();

    // Verifica si hay sesión iniciada y que el tipo de usuario sea "empleado"
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
        // Si no está logueado o no es un empleado, redirige al inicio de sesión
        return redirect()->to(base_url());
    }

    // Si el empleado está logueado, redirige al inicio de empleado
    return redirect()->to(base_url('empleado/inicio'));
}

    // MÉTODO REFRENCIADO DESDE js de inactividad, o cualquiera de los navbar de la web disponibles, 
    // al hacer click, se lleva a la vista de inicio de sesión, también se redirije al cerrar la sesión
    // por cambios de credenciales, entre otros
    public function inicioSesionShow()
    {
        log_message('debug', 'Mostrando la vista de inicio de sesión');
        return view('usuario/inicioSesionVista');
    }

    public function registroShow()
    {
        log_message('debug', 'Mostrando la vista de registro');
        return view('usuario/registroVista');
    }

      public function recuperarContrasenaShow()
    {
        log_message('debug', 'Mostrando la vista de registro');
        return view('usuario/recuperarContrasena');
    }

    // Método AJAX para comprobar si ya existe un registro con ese correo o nombre de usuario
    public function comprobarExistencia()
    {
        log_message('debug', 'Iniciando comprobación de existencia de usuario');
    
        // Recibimos los datos en formato JSON
        $data = $this->request->getJSON(true);
        $email = isset($data['email']) ? $data['email'] : '';
        $nombre_usuario = isset($data['nombre_usuario']) ? $data['nombre_usuario'] : '';
    
        log_message('debug', "Datos recibidos - Email: {$email}, Nombre de usuario: {$nombre_usuario}");
    
        $errors = [];
        if (empty($email) || empty($nombre_usuario)) {
            $errors[] = 'Faltan datos: email y nombre de usuario son obligatorios.';
            return $this->fail('Faltan datos: email y nombre de usuario son obligatorios.');
        }
    
        $existeCorreo = $this->usuarioModel->existeCorreo($email);
        $existeNombre = $this->usuarioModel->existeNombreUsuario($nombre_usuario);
    
        if ($existeNombre) {
            $errors[] = "El nombre de usuario ya existe: {$nombre_usuario}";
        }
        if ($existeCorreo) {
            $errors[] = "El correo electrónico ya existe: {$email}";
        }
    
        if (!empty($errors)) {
            return $this->response->setJSON([
                'success'      => false,
                'errors'       => $errors,
                'existeCorreo' => $existeCorreo,
                'existeNombre' => $existeNombre
            ]);
        }
    
        return $this->response->setJSON([
            'success'      => true,
            'existeCorreo' => $existeCorreo,
            'existeNombre' => $existeNombre
        ]);
    }
   
// Método para crear un usuario
public function crearUsuario()
{
    log_message('debug', 'Iniciando el método crearUsuario');

    // Recibimos los datos en formato JSON
    $data = $this->request->getJSON(true);
    if (!$data) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No se recibieron datos.']
        ]);
    }

    log_message('debug', 'Datos recibidos en crearUsuario: ' . json_encode($data));

    $errors = [];

    // Validamos que se hayan enviado todos los campos requeridos
    $camposRequeridos = ['nombre_usuario', 'nombre', 'apellidos', 'email', 'contraseña', 'telefono'];
    foreach ($camposRequeridos as $campo) {
        if (empty($data[$campo])) {
            $errors[] = "El campo {$campo} es obligatorio.";
        }
    }

    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // Comprobar duplicidad de nombre de usuario y correo
    if ($this->usuarioModel->existeNombreUsuario($data['nombre_usuario'])) {
        $errors[] = "El nombre de usuario ya existe: " . $data['nombre_usuario'];
    }
    if ($this->usuarioModel->existeCorreo($data['email'])) {
        $errors[] = "El correo electrónico ya existe: " . $data['email'];
    }
    // Comprobar duplicidad de teléfono
    if ($this->usuarioModel->existeTelefono($data['telefono'])) {
        $errors[] = "El teléfono ya está registrado.";
    }

    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // Llamamos al método crearUsuario() del modelo, que se encarga de hashear la contraseña y hacer el insert
    $insertID = $this->usuarioModel->crearUsuario($data, 'cliente');
    if ($insertID) {
        return $this->response->setJSON([
            'success' => true,
            'id'      => $insertID,
            'message' => 'Usuario creado correctamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al registrar el usuario.']
        ]);
    }
}

// MÉTODO REFERENCIADO DE js inicioSesionVista, se encarga de iniciar la sesión
// con las credenciales introducidas, CONTROLADOR Usuario, MÉTODO procesarSesion
public function procesarSesion()
{
    log_message('debug', 'Procesando inicio de sesión');
    $data = $this->request->getJSON(true);
    
    if (!$data || empty($data['usuario']) || empty($data['password'])) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Se requieren usuario y contraseña.'
        ]);
    }

    $usuario = $data['usuario'];
    $password = md5($data['password']); // Se asume que se guardó con MD5

    // Verificar si el usuario existe
    if (!$this->usuarioModel->existeNombreUsuario($usuario)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'El usuario no existe.'
        ]);
    }

    // Verificar credenciales
    $user = $this->usuarioModel->verificarCredenciales($usuario, $password);
    if ($user) {
        // Verificar el estado del usuario (si está habilitado)
        if ($user['estado'] == 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tu cuenta está inhabilitada. Por favor, contacta con el administrador.'
            ]);
        }

        // Obtener el nombre del tipo de usuario a partir del id_tipo del usuario
        $tipo = $this->usuarioModel->obtenerNombreTipo($user['id_tipo']);
        $nombreTipo = isset($tipo['nombre_tipo']) ? strtolower($tipo['nombre_tipo']) : '';
        error_log('Tipo de usuario obtenido: ' . $nombreTipo);

        // Guardamos todos los datos del usuario (excepto la contraseña) en la sesión
        $sessionData = [
            'id'             => $user['id'],
            'nombre_usuario' => $user['nombre_usuario'],
            'nombre'         => $user['nombre'],
            'apellidos'      => $user['apellidos'],
            'email'          => $user['email'],
            'telefono'       => $user['telefono'],
            'id_tipo'        => $user['id_tipo'],
            'tipo_usuario'   => $nombreTipo,
            'sesion_iniciada'=> true
        ];

        // Si el usuario es empleado o administrador, obtener datos adicionales de la tabla "empleados"
        if ($nombreTipo === 'empleado' || $nombreTipo === 'admin') { // Cambio realizado
            $empleado = $this->usuarioModel->obtenerEmpleadoPorUsuario($user['id']);
            if ($empleado) {
                $sessionData['empleado_id'] = $empleado['id'];
                $sessionData['emp_noticia'] = $empleado['emp_noticia'];
                $sessionData['emp_evento']  = $empleado['emp_evento'];
                $sessionData['emp_carreras'] = $empleado['emp_carreras'];
                $sessionData['esAdmin'] = $empleado['esAdmin'];
            }
        }

        // Iniciar la sesión y guardar los datos
        $session = session();
        $session->set($sessionData);

        // Redirigir según el tipo de usuario
        if ($nombreTipo === 'cliente') {
            $redireccion = base_url('Inicio');
        } elseif ($nombreTipo === 'empleado') {
            $redireccion = base_url('Empleado');
        } elseif ($nombreTipo === 'admin') {
            $redireccion = base_url('Admin');
        } else {
            $redireccion = base_url('Inicio');
        }

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Inicio de sesión correcto.',
            'redireccion'   => $redireccion,
            'datos_usuario' => $sessionData
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Contraseña incorrecta.'
        ]);
    }
}

// MÉTODO REFRENCIADO DESDE js de inactividad, o al hacer click en el cerrar sesión de los navbar disponibles en la web, 
// se encarga de cerrar la sesión
public function cerrarSesion()
{
    $session = session();
    $session->destroy();

    // Obtenemos el motivo del cierre
    $mensaje = $this->request->getGet('mensaje');

    // Si se envía el parámetro ajax=true, devolveremos siempre JSON
    if ($this->request->getGet('ajax') === 'true') {
        $responseMessage = ($mensaje === 'expirada') ? 
            'Sesión cerrada por inactividad.' : 'Sesión cerrada manualmente.';
        return $this->response->setJSON([
            'success' => true,
            'message' => $responseMessage
        ]);
    }

    // En caso contrario, redirigimos a la página de inicio de sesión
    $redirectMensaje = ($mensaje === 'expirada') ? 'expirada' : 'cerrada';
    return redirect()->to(base_url('Iniciar_Sesion?mensaje=' . $redirectMensaje));
}

 /* 
    ESTE MÉTODO ES REFERENCIADO DESDE eventoVista, perfilEvento, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS
    QUE TENGA HECHAS EL USUARIO CON LA SESIÓN INICIADA SOBRE EVENTOS
    TAMBIÉN EN REFERENCIADO EN EL JS DE horarioEvento
*/
    public function obtenerReservasEventosActuales()
    {
        $session = session();
        if (!$session->get('sesion_iniciada')) {
            return $this->response
                        ->setStatusCode(403)
                        ->setJSON(['success' => false, 'message' => 'No has iniciado sesión.']);
        }

        $usuario_id = $session->get('id');
        $reservas = $this->usuarioModel->obtenerReservasEventosActuales($usuario_id);
        return $this->response->setJSON($reservas);
    }

    /* 
    ESTE MÉTODO ES REFERENCIADO DESDE carreraVista, perfilVista, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS
    QUE TENGA HECHAS EL USUARIO CON LA SESIÓN INICIADA SOBRE CARRERAS
    TAMBIÉN EN REFERENCIADO EN EL JS DE horarioCarrera
    */
    public function obtenerReservasCarrerasActuales()
    {
        $session = session();
        if (!$session->get('sesion_iniciada')) {
            return $this->response
                        ->setStatusCode(403)
                        ->setJSON(['success' => false, 'message' => 'No has iniciado sesión.']);
        }

        $usuario_id = $session->get('id');
        $reservas = $this->usuarioModel->obtenerReservasCarrerasActuales($usuario_id);
        return $this->response->setJSON($reservas);
    }

       /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS DE EVENTOS
    PASADAS QUE TENGA EL USUARIO CON LA SESIÓN INICIADA
    Controlador: Usuario, Método: obtenerHistorialReservasEventos
    */
    public function obtenerHistorialReservasEventos()
    {
        $session = session();
        if (!$session->get('sesion_iniciada')) {
            return $this->response->setStatusCode(403)
                        ->setJSON(['success' => false, 'message' => 'No has iniciado sesión.']);
        }
        
        $usuario_id = $session->get('id');
        $reservas = $this->usuarioModel->obtenerHistorialReservasEventos($usuario_id);
        return $this->response->setJSON($reservas);
    }

        /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE OBTENER TODAS LAS RESERVAS DE CARRERAS
    PASADAS QUE TENGA EL USUARIO CON LA SESIÓN INICIADA
    Controlador: Usuario, Método: obtenerHistorialReservasCarreras
    */
    public function obtenerHistorialReservasCarreras()
    {
        $session = session();
        if (!$session->get('sesion_iniciada')) {
            return $this->response->setStatusCode(403)
                        ->setJSON(['success' => false, 'message' => 'No has iniciado sesión.']);
        }
        
        $usuario_id = $session->get('id');
        $reservas = $this->usuarioModel->obtenerHistorialReservasCarreras($usuario_id);
        return $this->response->setJSON($reservas);
    }

    /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DEL EVENTO
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    Controlador: Usuario, Método: cancelarReservaEvento
    */
    public function cancelarReservaEvento()
    {
        $json = $this->request->getJSON(true);
        if (!isset($json['id'])) {
            return $this->fail('No se proporcionó un ID de reserva.');
        }
        $idReserva = $json['id'];
    
        // Obtener la reserva para validación
        $reserva = $this->usuarioModel->obtenerReservaEvento($idReserva);
        if (!$reserva) {
            return $this->failNotFound('Reserva no encontrada.');
        }
    
        // Construir la fecha completa usando la fecha y la hora de inicio
        // Asegúrate de que $reserva['hora_inicio'] esté definido y tenga el formato correcto
        $fechaReserva = new \DateTime($reserva['fecha'] . ' ' . $reserva['hora_inicio']);
        $fechaActual = new \DateTime();
    
        // Validar la política de cancelación: no se permite si faltan menos de 7 días
        // Usamos < 7 para que, si exactamente faltan 7 días, se permita la cancelación
        if ($fechaReserva > $fechaActual && $fechaActual->diff($fechaReserva)->days < 7) {
            return $this->fail('No se puede cancelar la reserva, quedan menos de 7 días para el evento.');
        }
    
        // Realizar DELETE (o UPDATE para marcar como cancelado)
        $resultado = $this->usuarioModel->eliminarReservaEvento($idReserva);
        if ($resultado) {
            return $this->respond(['message' => 'Reserva de evento cancelada correctamente.']);
        } else {
            return $this->failServerError('Error al cancelar la reserva de evento.');
        }
    }
    
        /* 
    ESTE MÉTODO ES REFERENCIADO DESDE perfilVista, Y SE ENCARGA DE CANCELAR LA RESERVA DE LA CARRERA
    SELECCIONADA DEL USUARIO CON LA SESIÓN INICIADA EN CASO DE QUE CUMPLA CON LOS MÍNIMOS PARA PODER ELIMINARSE,
    Controlador: Usuario, Método: cancelarReservaCarrera
    */
    public function cancelarReservaCarrera()
    {
        $json = $this->request->getJSON(true);
        if (!isset($json['id'])) {
            return $this->fail('No se proporcionó un ID de reserva.');
        }
        $idReserva = $json['id'];
    
        // Obtener la reserva para validación
        $reserva = $this->usuarioModel->obtenerReservaCarrera($idReserva);
        if (!$reserva) {
            return $this->failNotFound('Reserva no encontrada.');
        }
    
        // Construir la fecha completa usando la fecha y la hora de inicio
        $fechaReserva = new \DateTime($reserva['fecha'] . ' ' . $reserva['hora_inicio']);
        $fechaActual = new \DateTime();
    
        // Validar la política de cancelación: no se permite si faltan menos de 7 días
        if ($fechaReserva > $fechaActual && $fechaActual->diff($fechaReserva)->days < 7) {
            return $this->fail('No se puede cancelar la reserva, quedan menos de 7 días para la carrera.');
        }
    
        // Realizar DELETE
        $resultado = $this->usuarioModel->eliminarReservaCarrera($idReserva);
        if ($resultado) {
            return $this->respond(['message' => 'Reserva de carrera cancelada correctamente.']);
        } else {
            return $this->failServerError('Error al cancelar la reserva de carrera.');
        }
    }

// MÉTODO REFERENCIADO EN js perfilVista, se utiliza para actualizar las credenciales del usuario con la
// sesión actual
// Controlador: Usuario, Método: actualizarCredenciales
public function actualizarCredenciales()
{
    log_message('debug', 'Iniciando el método actualizarCredenciales');

    $session = session();
    if (!$session->has('id')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Usuario no autenticado.']
        ]);
    }

    $data = $this->request->getJSON(true);
    if (!$data) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No se recibieron datos.']
        ]);
    }

    log_message('debug', 'Datos recibidos en actualizarCredenciales: ' . json_encode($data));

    $errors = [];
    $camposRequeridos = ['nombre_usuario', 'nombre', 'apellidos', 'email', 'telefono'];
    foreach ($camposRequeridos as $campo) {
        if (empty($data[$campo])) {
            $errors[] = "El campo {$campo} es obligatorio.";
        }
    }

    // Obtener los datos del usuario actual desde la base de datos
    $usuarioID = $session->get('id');
    $usuarioActual = $this->usuarioModel->find($usuarioID);

    if (!$usuarioActual) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Usuario no encontrado.']
        ]);
    }

    // Validación de contraseña si se desea cambiarla
    if (!empty($data['contraseña'])) {
        if (empty($data['confirmar_contraseña'])) {
            $errors[] = "Debes confirmar la nueva contraseña.";
        } elseif ($data['contraseña'] !== $data['confirmar_contraseña']) {
            $errors[] = "Las contraseñas no coinciden.";
        }

        // Validar que la nueva contraseña no sea igual a la actual
        if (md5($data['contraseña']) === $usuarioActual['contraseña']) {
            $errors[] = "La nueva contraseña no puede ser igual a la actual.";
        }
    }

    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // Datos del usuario actual desde la sesión
    $currentUser = [
        'id'             => $session->get('id'),
        'nombre_usuario' => $session->get('nombre_usuario'),
        'email'          => $session->get('email'),
        'telefono'       => $session->get('telefono')
    ];

    // Validar duplicidad de nombre_usuario, email, telefono
    if ($data['nombre_usuario'] !== $currentUser['nombre_usuario']) {
        if ($this->usuarioModel->existeNombreUsuarioDiferente($data['nombre_usuario'], $currentUser['id'])) {
            $errors[] = "El nombre de usuario ya existe: " . $data['nombre_usuario'];
        }
    }
    if ($data['email'] !== $currentUser['email']) {
        if ($this->usuarioModel->existeCorreoDiferente($data['email'], $currentUser['id'])) {
            $errors[] = "El correo electrónico ya está en uso: " . $data['email'];
        }
    }
    if ($data['telefono'] !== $currentUser['telefono']) {
        if ($this->usuarioModel->existeTelefonoDiferente($data['telefono'], $currentUser['id'])) {
            $errors[] = "El teléfono ya está en uso: " . $data['telefono'];
        }
    }

    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // Actualización de datos
    if (!empty($data['contraseña'])) {
        $data['contraseña'] = md5($data['contraseña']);
    } else {
        unset($data['contraseña']);
    }
    unset($data['confirmar_contraseña']);

    $resultado = $this->usuarioModel->actualizarUsuario($usuarioID, $data);

    if ($resultado) {
        $session->destroy();
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Credenciales actualizadas correctamente. Por favor, inicia sesión nuevamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al actualizar las credenciales.']
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js de gestionEmpleados, gestionUsuarios, se encarga de obtener los datos
// del usuario con el id que se ha pasado al método, MÉTODO getUsuarioPorId, CONTROLADOR Usuario

// MÉTODO REFRENCIADO DESDE js de nuevoCarrerasEmpleado, nuevoEventosEmpleado, se encarga de obtener los datos
// del usuario con el id que se ha pasado al método, MÉTODO getUsuarioPorId, CONTROLADOR Usuario

// MÉTODO REFERENCIADO DESDE js de gestionEventos (admin), nuevoEventoEmpleado (empleado) se encarga de obtener los datos
// del usuario con el id que se ha pasado al método, MÉTODO getUsuarioPorId, CONTROLADOR Usuario

// MÉTODO REFERENCIADO DESDE js de perfilVista se encarga de obtener los datos
// del usuario con el id que se ha pasado al método, MÉTODO getUsuarioPorId, CONTROLADOR Usuario
public function getUsuarioPorId($id)
{
    $usuarioModel = new \App\Models\UsuarioModel();
    $usuario = $usuarioModel->find($id);

    if ($usuario) {
        return $this->response->setJSON($usuario);
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Usuario no encontrado']);
    }
}

// MÉTODO REFERENCIADO DESDE js de restablecerContrasena comprobar si la contraseña que 
// tiene ahora el usuario es igual a la anterior, MÉTODO verificarContrasenaActual, CONTROLADOR Usuario
public function verificarContrasenaActual()
{
    // Obtener datos del POST
    $data = $this->request->getJSON(true); // Cambiado a getJSON para coincidir con tu AJAX
    $token = $data['token'];
    $nuevaContrasena = $data['nueva_contrasena'];

    log_message('debug', 'Token recibido: ' . $token); // Log para depuración

    $usuarioModel = new UsuarioModel();
    
    // 1. Buscar usuario por token válido (con depuración)
    $usuario = $usuarioModel->where('token_recuperacion', $token)
                           ->where('expiracion_token >', date('Y-m-d H:i:s'))
                           ->first();

    log_message('debug', 'Resultado de búsqueda por token: ' . print_r($usuario, true)); // Depuración

    if (!$usuario) {
        // Verificar si al menos el token existe (sin considerar expiración)
        $tokenExiste = $usuarioModel->where('token_recuperacion', $token)->first();
        
        $mensajeError = 'Token inválido';
        if ($tokenExiste) {
            $mensajeError = 'Token expirado';
            log_message('debug', 'Token existe pero expiró: ' . $tokenExiste['expiracion_token']);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => $mensajeError,
            'debug_token' => $token, // Solo para desarrollo
            'debug_time' => date('Y-m-d H:i:s') // Solo para desarrollo
        ]);
    }

    // 2. Comparar la nueva contraseña (en MD5) con la actual
    $esIgual = (md5($nuevaContrasena) === $usuario['contraseña']);

    return $this->response->setJSON([
        'success' => true,
        'es_igual' => $esIgual,
        'message' => $esIgual ? 'La contraseña es igual a la actual' : 'La contraseña es diferente',
        'usuario_id' => $usuario['id']
    ]);
}
    
}
