<?php

namespace App\Controllers;

use App\Models\CarreraModel;
use App\Models\EmpleadoModel;
use App\Models\EmpleadoCarrerasModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use \DateTime;

class Carreras extends BaseController
{
    use ResponseTrait;

    public function index()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al inicio de empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no está logueado como empleado, muestra la página de carrera
    return view('templates/navbar') . view('carrera/carreraVista') . view('templates/footer');
}

    public function reservar()
{
    $session = session();
    $id_usuario = $session->get('id'); // Se usa el ID del usuario de la sesión actual

    if (!$id_usuario) {
        return $this->fail('Debes iniciar sesión para reservar', 403);
    }

    $datos = $this->request->getJSON(true);

    if (!$datos) {
        return $this->fail('No se han recibido datos', 400);
    }

    // Log para verificar datos recibidos
    log_message('debug', 'Datos recibidos en reservar: ' . print_r($datos, true));

    // Validar que se envíen los campos obligatorios que requiere la tabla
    $requiredFields = ['id_pistas', 'fecha', 'franja_horaria_id', 'num_participantes', 'cantidad'];
    foreach ($requiredFields as $field) {
        if (empty($datos[$field])) {
            return $this->fail("Falta el campo obligatorio: $field", 400);
        }
    }

    // Validación de la fecha: no se permiten reservas a más de dos años desde hoy
    $fechaReserva = DateTime::createFromFormat('Y-m-d', $datos['fecha']);
    if (!$fechaReserva) {
        return $this->fail('Formato de fecha incorrecto. Se espera YYYY-MM-DD', 400);
    }
    $fechaHoy = new DateTime();
    $fechaMaxima = clone $fechaHoy;
    $fechaMaxima->modify('+2 years');
    if ($fechaReserva > $fechaMaxima) {
        return $this->fail('No se puede hacer una reserva para una fecha superior a dos años desde hoy', 400);
    }

    $datos['id_usuario'] = $id_usuario;

    // Instanciamos el modelo que gestiona empleados de carreras
    $empleadoCarrerasModel = new EmpleadoCarrerasModel();

    // Primero comprobamos si existe al menos un empleado con permiso (emp_carreras = 1)
    if (!$empleadoCarrerasModel->existeEmpleadoCarreras()) {
        return $this->fail('No hay empleados disponibles para gestionar carreras', 400);
    }

    // Obtenemos el empleado con menos carreras asignadas para la fecha indicada
    $empleadoDisponible = $empleadoCarrerasModel->obtenerEmpleadoCarrerasDisponible($datos['fecha']);

    if (!$empleadoDisponible) {
        return $this->fail('No se pudo determinar un empleado disponible para esta fecha', 400);
    }
    $datos['empleado_id'] = $empleadoDisponible['id'];

    // Resto de asignaciones
    $datos['metodo_pago'] = 'paypal';
    $datos['fecha_pago'] = date('Y-m-d');

    // Inserta la reserva de carrera utilizando el modelo CarreraModel
    $carreraModel = new CarreraModel();
    if ($carreraModel->insertarCarrera($datos)) {
        return $this->respondCreated(['message' => 'Reserva realizada con éxito']);
    } else {
        return $this->failServerError('Error al realizar la reserva');
    }
}

// VERIFICO QUE EL DIA Y HORARIO SELECCIONADO QUE SE PASA DESDE LA URL DESDE
// EL JS DE horarioCarrera ESTA DISPONIBLE, MÉTODO USADO POR EL JS DE carreraVista

public function verificarDisponibilidadHorario()
{
    $fecha = $this->request->getPost('fecha');
    $franja_id = $this->request->getPost('franja_horaria_id');

    if (!$fecha || !$franja_id) {
        return $this->fail('Parámetros incompletos', 400);
    }

    $model = new CarreraModel();
    $disponible = $model->verificarDisponibilidad($fecha, $franja_id);

    return $this->respond([
        'disponible' => $disponible,
        'mensaje' => $disponible ? 'Horario disponible' : 'Horario no disponible'
    ], 200);
}
    
}