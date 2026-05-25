<?php

namespace App\Controllers;
use App\Models\EventoModel;

class Eventos extends BaseController
{
    public function index()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no es un empleado o no está logueado, muestra la página de eventos
    return view('templates/navbar') . view('evento/eventoVista') . view('templates/footer');
}

    // MÉTODO REFERENCIADO DE JS gestionEventos (admin) que se encarga de obtener todos los eventos próximos
    public function getEventosProximos()
    {
        $eventoModel = new EventoModel();
        $eventos = $eventoModel->obtenerEventosProximos();
        
        if (empty($eventos)) {
            return $this->response->setJSON(['message' => 'No existen eventos próximos'])->setStatusCode(200);
        }
        
        return $this->response->setJSON($eventos)->setStatusCode(200);
    }

    // MÉTODO REFERENCIADO EN js nuevoEventoEmpleado (empleado), se encarga de obtener los eventos
    // que tiene asignados el empleado de eventos, CONTROLADOR Eventos, Método getEventosProximosEmpleado 
    public function getEventosProximosEmpleado()
{
    $empleadoId = session('empleado_id'); // o el método que uses para obtener el usuario logueado

    if (!$empleadoId) {
        return $this->response->setJSON(['error' => 'Empleado no autenticado'])->setStatusCode(401);
    }

    $eventoModel = new EventoModel();
    $eventos = $eventoModel->obtenerEventosProximosPorEmpleado($empleadoId);

    if (empty($eventos)) {
        return $this->response->setJSON(['message' => 'No hay eventos próximos para este empleado'])->setStatusCode(200);
    }

    return $this->response->setJSON(['success' => true, 'data' => $eventos])->setStatusCode(200);
}

}