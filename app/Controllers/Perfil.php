<?php

namespace App\Controllers;

use App\Models\PerfilModel;
use CodeIgniter\HTTP\ResponseInterface;

class Perfil extends BaseController
{
    protected $perfilModel;

    public function __construct()
    {
        $this->perfilModel = new PerfilModel();
    }

    // Muestra la vista del perfil con los datos del usuario, reservas actuales y historial
    public function index()
{
    $sesion = session();

    // Verifica si la sesión está iniciada
    if (!$sesion->get('sesion_iniciada')) {
        return redirect()->to(base_url('Iniciar_Sesion'));
    }

    // Verifica si el usuario está logueado como empleado
    if (strtolower($sesion->get('tipo_usuario')) === 'empleado') {
        return redirect()->to(base_url('Empleado'));  // Redirige a la página de inicio del empleado
    }

    // Verifica si el usuario está logueado como admin
    if (strtolower($sesion->get('tipo_usuario')) === 'admin') {
        return redirect()->to(base_url('Admin'));  // Redirige a la página de inicio del admin
    }

    $idUsuario = $sesion->get('id');

    // Obtiene las reservas actuales y el historial de reservas
    $datos['reservas_actuales'] = $this->perfilModel->obtenerReservasActuales($idUsuario);
    $datos['historial_reservas'] = $this->perfilModel->obtenerHistorialReservas($idUsuario);

    // Información del usuario
    $datos['usuario'] = [
        'id'             => $sesion->get('id'),
        'nombre_usuario' => $sesion->get('nombre_usuario'),
        'nombre'         => $sesion->get('nombre'),
        // Obtiene el nombre del tipo de usuario según el id_tipo
        'tipo'           => $this->perfilModel->obtenerTipoUsuarioPorId($sesion->get('id_tipo'))
    ];

    // Carga las vistas
    return view('templates/navbar') 
        . view('perfil/perfilVista', $datos)
        . view('templates/footer');
}

    // Actualiza las credenciales del usuario
    public function actualizarCredenciales()
    {
        $sesion = session();
        $idUsuario = $sesion->get('id');
        $datosFormulario = $this->request->getPost();

        if (empty($datosFormulario['nombre']) || empty($datosFormulario['correo'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON([
                    'exito'   => false,
                    'mensaje' => 'El nombre y el correo son obligatorios.'
                ]);
        }

        // Si se envía una nueva contraseña, la hasheamos; si no, la descartamos
        if (!empty($datosFormulario['contrasena'])) {
            $datosFormulario['contrasena'] = md5($datosFormulario['contrasena']);
        } else {
            unset($datosFormulario['contrasena']);
        }

        $resultado = $this->perfilModel->actualizarCredenciales($idUsuario, $datosFormulario);

        if ($resultado) {
            return $this->response->setJSON([
                'exito'   => true,
                'mensaje' => 'Credenciales actualizadas correctamente.'
            ]);
        } else {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'exito'   => false,
                    'mensaje' => 'Error al actualizar las credenciales.'
                ]);
        }
    }

}
