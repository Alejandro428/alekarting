<?php 
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel; // Importamos el modelo de usuario
use App\Models\AdminModel;
use App\Models\ReservasEventosModel;
use App\Models\CarreraModel;
use App\Models\EmpleadoModel;
use App\Models\EmpleadoEventosModel;
use App\Models\EventoModel;
use App\Models\EmpleadoCarrerasModel;

class RegistroPagos extends BaseController
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
        return view('admin/navbarAdmin') . view('admin/gestionPagos') . view('templates/footer');
    }

    // Si el usuario es un empleado
    if (strtolower($session->get('tipo_usuario')) === 'empleado') {
        return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado') . view('templates/footer');
    }

    // Si el usuario es un usuario normal
    return view('templates/navbar') . view('inicio/inicioVista') . view('templates/footer');
}

// MÉTODO REFERENCIADO DESDE js de gestionPagos que se encarga de obtener todos los días que
// tengan pagos realizados, tengan o solo pagos de reservas de carreras, de eventos, o ambos
public function obtenerTodosLosDiasConPagos()
{
    // Validación de permisos
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado']);
    }

    // Obtener datos del modelo
    $model = new AdminModel();
    $diasConPagos = $model->obtenerDiasConPagos();

    // Respuesta estructurada
    if ($diasConPagos) {
        return $this->response->setJSON([
            'success' => true,
            'data' => $diasConPagos,
            'recordsTotal' => count($diasConPagos),
            'recordsFiltered' => count($diasConPagos)
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron pagos registrados',
            'data' => []
        ]);
    }
}

}