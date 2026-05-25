<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel; 
use App\Models\EmpleadoModel;

class Empleado extends BaseController
{
    protected $usuarioModel;
    
    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel(); 
    }

    public function index()
    {
        $session = session();
        // Verifica que la sesión esté iniciada y que el tipo de usuario sea "empleado"
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return redirect()->to(base_url());
        }
        
        // Si es empleado, carga la vista del área de empleado
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    public function indexCambiarCredenciales()
    {
        $session = session();
        // Verifica que la sesión esté iniciada y que el tipo de usuario sea "empleado"
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return redirect()->to(base_url('Iniciar_Sesion'));
        }
        
        // Si es empleado, carga la vista del área de empleado
        return view('empleado/navbarEmpleado') . view('empleado/cambiarCredencialesEmpleado') . view('templates/footer');
    }
        
 // MÉTODO USADO TANTO POR EL CAMBIAR CREDENCIALES DEL EMPLEADO COMO EL DEL ADMIN (js cambiarCredencialesEmpleado)
public function actualizarCredencialesEmpleado()
{
    log_message('debug', 'Iniciando el método actualizarCredenciales');

    // COMPRUEBO QUE EL USUARIO TIENE UN ID, SI NO, ES QUE NO TIENE LA SESIÓN O ALGO RARO OCURRE, ASÍ QUE SE LE HECHA ATRÁS
    $session = session();
    if (!$session->has('id')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Usuario no autenticado.']
        ]);
    }

    // RECIBO LOS DATOS DEL CAMBIO DE CREDENCIALES POR JSON
    $data = $this->request->getJSON(true);
    if (!$data) {
        // SINO, RETORNO UN ERROR DE SIN DATOS
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No se recibieron datos.']
        ]);
    }
    
    log_message('debug', 'Datos recibidos en actualizarCredenciales: ' . json_encode($data));

    $errors = [];
    // VALIDO TODOS LOS DATOS
    $camposRequeridos = ['nombre_usuario', 'nombre', 'apellidos', 'email', 'telefono'];
    foreach ($camposRequeridos as $campo) {
        if (empty($data[$campo])) {
            $errors[] = "El campo {$campo} es obligatorio.";
        }
    }
    
    // OBTENGO LOS DATOS DEL USUARIO USANDO EL MODELO Y APROVECHANDO EL ID DEL USUARIO PARA HACER UN FIND
    $usuarioID = $session->get('id');
    $usuarioActual = $this->usuarioModel->find($usuarioID);
    // SI NO HAY NINGÚN USUARIO, NO SE PUEDE HACER EL CAMBIO DE CREDENCIALES
    if (!$usuarioActual) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Usuario no encontrado.']
        ]);
    }

    // SI HAY UNA CONTRASEÑA EN EL OBJETO (QUE QUIERE CAMBIAR DE CONTRASEÑA)
    // SE COMPRUEBA QUE SEA VÁLIDA
    if (!empty($data['contraseña'])) {
        if (empty($data['confirmar_contraseña'])) {
            $errors[] = "Debes confirmar la nueva contraseña.";
    // SE COMPRUEBA QUE LA CONTRASEÑA SEA DISTINTA AL CONFIRMAR, EN ESE CASO NO COINCIDEN
        } elseif ($data['contraseña'] !== $data['confirmar_contraseña']) {
            $errors[] = "Las contraseñas no coinciden.";
        }

        // SE VALIDA QUE LA CONTRASEÑA SEA DIFERENTE A LA QUE TENÍA ANTES
        if (md5($data['contraseña']) === $usuarioActual['contraseña']) {
            $errors[] = "La nueva contraseña no puede ser igual a la actual.";
        }
    }
    // SI HAY ERRORES, LOS RETORNO PARA MOSTRARLOS
    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // OBTENGO LOS DATOS DEL USUARIO DE SU SESIÓN ACTUAL
    $currentUser = [
        'id'             => $session->get('id'),
        'nombre_usuario' => $session->get('nombre_usuario'),
        'email'          => $session->get('email'),
        'telefono'       => $session->get('telefono')
    ];

    // VALIDO EL NOMBRE DE USUARIO, CORREO Y TELÉFONO (EXCLUYO AL USUARIO ACTUAL QUE LO TIENE)
    if ($data['nombre_usuario'] !== $currentUser['nombre_usuario']) {
        if ($this->usuarioModel->existeNombreUsuarioDiferente($data['nombre_usuario'], $currentUser['id'])) {
            $errors[] = "El nombre de usuario ya existe: " . $data['nombre_usuario'];
        }
    }
    // SE COMPRUEBA EMAIL
    if ($data['email'] !== $currentUser['email']) {
        if ($this->usuarioModel->existeCorreoDiferente($data['email'], $currentUser['id'])) {
            $errors[] = "El correo electrónico ya está en uso: " . $data['email'];
        }
    }
    // SE COMPRUEBA TELÉFONO
    if ($data['telefono'] !== $currentUser['telefono']) {
        if ($this->usuarioModel->existeTelefonoDiferente($data['telefono'], $currentUser['id'])) {
            $errors[] = "El teléfono ya está en uso: " . $data['telefono'];
        }
    }
    // SI HAY ALGÚN ERROR, SALGO Y LO MUESTRO
    if (!empty($errors)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errors
        ]);
    }

    // SI TODO SALE BIEN, HASHEO LA CONTRASEÑA, YA QUE LA VOY A REGISTRAR
    if (!empty($data['contraseña'])) {
        $data['contraseña'] = md5($data['contraseña']);
    } else {
        // SI NO HAY QUE VACIARLA, SE QUITA DEL CAMPO DE DATA PARA NO METER NUNCA UNA CONTRASEÑA VACÍA
        unset($data['contraseña']);
    }
    // QUITO EL CONFIRMAR CONTRASEÑA, YA NO HACE FALTA
    unset($data['confirmar_contraseña']);

    // SE ACTUALIZA EL USUARIO, SE PASA PARA ELLO SU ID Y LA INFORMACIÓN NUEVA DEL USUARIO
    $resultado = $this->usuarioModel->actualizarUsuario($usuarioID, $data);

    if ($resultado) {
        // DESTRUYO LA SESIÓN PARA OBLIGAR AL USUARIO A INICIARLA DE NUEVO, Y QUE AHORA TENGA
        // LAS CREDENCIALES EN SESIÓN ACTUALIZADAS
        $session->destroy();
        // RETORNO UN MENSAJE POSITIVO
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Credenciales actualizadas correctamente. Por favor, inicia sesión nuevamente.'
        ]);
    } else {
        // SINO ES QUE HA HABIDO ALGÚN TIPO DE ERROR
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al actualizar las credenciales.']
        ]);
    }
}


        public function getUsuariosClientes()
{
    log_message('debug', 'Iniciando getUsuariosClientes');
    $session = session();

    // Verificación de sesión activa
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Acceso denegado: sesión no iniciada'],
            'data' => []
        ]);
    }

    try {
        $usuarioModel = new \App\Models\UsuarioModel();
        $clientes = $usuarioModel->obtenerUsuariosClientes();

        return $this->response->setJSON([
            'success' => true,
            'data' => $clientes ?: [],
            'message' => 'Listado de usuarios tipo cliente',
            'recordsTotal' => count($clientes),
            'recordsFiltered' => count($clientes)
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en getUsuariosClientes: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al obtener usuarios clientes'],
            'data' => []
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE JS DE gestionCarreras (admin), nuevoCarrerasEmpleado (empleado) se utiliza para obtener los usuarios
// clientes que están activos
// MÉTODO REFERENCIADO DESDE JS DE gestionEventos (admin) nuevoEventosEmpleado (empleado), se utiliza para obtener los usuarios
// clientes que están activos
public function getUsuariosClientesActivos()
{
    log_message('debug', 'Iniciando getUsuariosClientesActivos');
    $session = session();

    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Acceso denegado: sesión no iniciada'],
            'data' => []
        ]);
    }

    try {
        $usuarioModel = new \App\Models\UsuarioModel();
        $clientes = $usuarioModel->obtenerUsuariosClientesActivos();

        return $this->response->setJSON([
            'success' => true,
            'data' => $clientes ?: [],
            'message' => 'Listado de usuarios tipo cliente activos',
            'recordsTotal' => count($clientes),
            'recordsFiltered' => count($clientes)
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en getUsuariosClientesActivos: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al obtener usuarios clientes activos'],
            'data' => []
        ]);
    }
}


}