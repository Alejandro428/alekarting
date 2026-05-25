<?php

namespace App\Controllers;

use App\Models\EmpleadoCarrerasModel;

class EmpleadoCarreras extends BaseController
{

    public function __construct()
    {
        $this->empleadoCarrerasModel = new EmpleadoCarrerasModel();
    }

    /**
     * Muestra la vista principal de gestión de carreras para el empleado.
     */
    public function indexCarreras()
    {
        $session = session();

        // Verifica si hay sesión iniciada y que el tipo de usuario sea "empleado"
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return redirect()->to(base_url());
        }
        
        // Verifica si el empleado tiene permisos para gestionar carreras
        if ((int)$session->get('emp_carreras') !== 1) {
            $data['error'] = "No tienes permiso para gestionar Carreras.";
            return view('empleado/navbarEmpleado')
                . view('empleado/inicioEmpleado', $data)
                . view('templates/footer');
        }
        
        // Si todo está correcto, muestra la vista de carreras
        return view('empleado/navbarEmpleado')
            . view('empleado/carrerasEmpleado')
            . view('templates/footer');
    }
    
    /**
     * MÉTODO REFERENCIADO EN js de nuevoCarrerasEmpleado (empleado), se encarga de obtener todas
     * las reservas que tiene el empleado de carreras asociadas
     */
    public function obtenerReservasEmpleado()
{
    $session = session();

    // Verificación de sesión y permisos
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
        return $this->response->setJSON([
            'data' => [], // Array vacío para DataTables
            'success' => false,
            'message' => 'Sesión no iniciada o usuario no autorizado.'
        ]);
    }
    
    if ((int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'data' => [], // Array vacío para DataTables
            'success' => false,
            'message' => 'No tienes permiso para gestionar carreras.'
        ]);
    }
    
    $empleadoId = $session->get('empleado_id');
    $modelo = new EmpleadoCarrerasModel();
    $reservas = $modelo->obtenerReservasEmpleado($empleadoId);
    
    return $this->response->setJSON([
        'data' => $reservas ?: [], // Asegura array aunque sea vacío
        'success' => true,
        'message' => 'Datos cargados correctamente',
        'recordsTotal' => count($reservas), // Útil para paginación
        'recordsFiltered' => count($reservas) // Útil para paginación
    ]);
}

    // MÉTODO UTILIZADO EN EL JS carreraVista PARA COMPROBAR QUE HAY EMPLEADOS DE CARRERAS DISPONIBLES
    // PARA PODER DESIGNAR UNA RESERVA DE CARRERAS
    // SE RETORNA UN TRUE O UN FALSE
    public function verificarDisponibilidad()
    {
        // Cargamos el modelo de empleados de carreras
        $empleadoModel = new EmpleadoCarrerasModel();
        
        // Usamos el método 'existeEmpleadoCarreras()' para saber si hay al menos uno disponible
        $disponible = $empleadoModel->existeEmpleadoCarreras();
        
        // Retornamos la respuesta en formato JSON
        return $this->response->setJSON(['disponible' => $disponible]);
    }

    public function obtenerDiasDisponibles()
{
    log_message('debug', '*** [CONTROLADOR] Iniciando obtenerDiasDisponibles para carreras ***');

    $mes        = (int)$this->request->getGet('mes');
    $anio       = (int)$this->request->getGet('anio');
    $dia_actual = $this->request->getGet('dia_actual'); // Puede ser null o vacío

    log_message('debug', "Parámetros GET: mes={$mes}, anio={$anio}, dia_actual={$dia_actual}");

    if (!$mes || !$anio) {
        log_message('debug', 'Faltan parámetros mes/anio');
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Se requieren mes y año.'
        ]);
    }

    // Instancia el modelo localmente
    $modelo = new EmpleadoCarrerasModel();
    $dias = $modelo->obtenerDiasDisponiblesCarrera($mes, $anio, $dia_actual);

    log_message('debug', "Recibido del modelo: dias=" . implode(', ', $dias));

    return $this->response->setJSON([
        'success' => true,
        'dias'    => $dias
    ]);
}

// MÉTODO LLAMADO DESDE js gestionCarreras (admin), nuevoCarrerasEmpleado (empleado), ESTE MÉTODO SE ENCARGA DE OBTENER LOS HORARIOS DISPONIBLES
// TENIENDO EN CUENTA LA FECHA Y EL HORARIO QUE SE PASAN POR GET
public function obtenerHorariosDisponibles()
{
    // Obtener la fecha (formato YYYY-MM-DD) y, opcionalmente, el horario_actual (del registro en edición)
    $fecha         = $this->request->getGet('fecha');
    $horarioActual = $this->request->getGet('horario_actual'); // Puede ser nulo

    if (!$fecha) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'La fecha es requerida.'
        ]);
    }

    log_message('debug', 'Obteniendo horarios para carreras para fecha: ' . $fecha . ', horario_actual: ' . $horarioActual);

    // Instancia el modelo localmente
    $modelo = new EmpleadoCarrerasModel();
    $horarios = $modelo->obtenerHorariosDisponiblesCarrera($fecha, $horarioActual);

    log_message('debug', 'Horarios obtenidos: ' . print_r($horarios, true));

    return $this->response->setJSON([
        'success' => true,
        'horarios' => $horarios
    ]);
}

// MÉTODO REFERENCIADO DESDE js gestionCarreras (admin), nuevoCarrerasEmpleado, 
// este método se encarga de obtener la reserva de carrera seleccionada
public function obtenerReservaCarreraEdicion($id)
{
    $session = session();
    
    // Validación de sesión y tipo de usuario (empleado o admin)
    if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Acceso no autorizado'
        ]);
    }

    // Validación de permisos (solo para empleados, los admin tienen acceso completo)
    if (strtolower($session->get('tipo_usuario')) === 'empleado' && (int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permisos para gestionar carreras'
        ]);
    }

    // Consulta la reserva
    $model = new EmpleadoCarrerasModel();
    $reserva = $model->obtenerReservaCarreraEdicion($id);

    if ($reserva) {
        return $this->response->setJSON([
            'data' => $reserva,
            'success' => true
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Reserva no encontrada'
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js nuevoCarrerasEmpleado (empleado), este método se encarga
// de crear la reserva de carrera seleccionada
public function crearReservaCarrera()
{
    log_message('debug', 'Iniciando crearReservaCarrera');
    $session = session();

    // Verifica que haya sesión iniciada y que el usuario sea de tipo "empleado"
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado. Solo empleados pueden acceder.']
        ]);
    }

    // Verificar permisos específicos para empleados
    if ((int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para crear reservas de carreras.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();
    $errores = [];

    // Validar usuario
    if (empty($datos['id_usuario'])) {
        $errores[] = "El usuario es obligatorio.";
    }

    // Validar número de participantes
    if (empty($datos['num_participantes'])) {
        $errores[] = "El número de participantes es obligatorio.";
    } elseif (!is_numeric($datos['num_participantes']) || (int)$datos['num_participantes'] <= 0) {
        $errores[] = "El número de participantes debe ser un número mayor a 0.";
    } elseif ((int)$datos['num_participantes'] > 20) {
        $errores[] = "El número máximo de participantes es 20.";
    }

    // Validar fecha de la carrera
    if (empty($datos['fecha_carrera'])) {
        $errores[] = "La fecha de la carrera es obligatoria.";
    } else {
        // Validar que la fecha no sea más de 2 años en el futuro
        $fechaReserva = \DateTime::createFromFormat('Y-m-d', $datos['fecha_carrera']);
        $fechaMaxima = (new \DateTime())->modify('+2 years');
        
        if ($fechaReserva > $fechaMaxima) {
            $errores[] = 'No se pueden hacer reservas con más de 2 años de antelación';
        }
    }

    // Validar franja horaria
    if (empty($datos['franja_horaria_id'])) {
        $errores[] = "La franja horaria es obligatoria.";
    }

    // Validar fecha de pago
    if (empty($datos['fecha_pago'])) {
        $errores[] = "La fecha de pago es obligatoria.";
    }

    // Validar pista
    if (empty($datos['id_pistas'])) {
        $errores[] = "La pista es obligatoria.";
    }

    // Validar método de pago
    if (empty($datos['metodo_pago'])) {
        $errores[] = "El método de pago es obligatorio.";
    } else {
        $metodosPermitidos = ['card', 'paypal'];
        if (!in_array(strtolower($datos['metodo_pago']), $metodosPermitidos)) {
            $errores[] = "Método de pago no soportado: {$datos['metodo_pago']}";
        }
    }

    // Validar el precio (campo "cantidad")
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

    // Asignar empleado disponible
    $empleadoId = $this->empleadoCarrerasModel->obtenerEmpleadoCarrerasDisponible($datos['fecha_carrera'])['id'];
    if (!$empleadoId) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No hay empleados disponibles para esta fecha']
        ]);
    }

    // Calcular el total
    $precioUnitario = (float)$datos['cantidad'];
    $total = $precioUnitario * (int)$datos['num_participantes'];

    // Preparar los datos para inserción
    $datosInsertar = [
        'id_usuario'        => (int)$datos['id_usuario'],
        'empleado_id'       => (int)$empleadoId,
        'metodo_pago'       => $datos['metodo_pago'],
        'id_pistas'         => (int)$datos['id_pistas'],
        'num_participantes' => (int)$datos['num_participantes'],
        'fecha'             => $datos['fecha_carrera'],
        'franja_horaria_id' => (int)$datos['franja_horaria_id'],
        'fecha_pago'        => $datos['fecha_pago'],
        'cantidad'          => $precioUnitario,
        'total'             => $total,
        'payment_intent_id' => null // No requerido para reservas manuales
    ];

    // Insertar la nueva reserva
    if ($this->empleadoCarrerasModel->insert($datosInsertar)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reserva de carrera creada correctamente.',
            'id'      => $this->empleadoCarrerasModel->getInsertID()
        ]);
    } else {
        log_message('error', 'Error al crear reserva: ' . print_r($this->empleadoCarrerasModel->errors(), true));
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear la reserva de carrera.']
        ]);
    }
}

// MÉTODO REFERENCIADO DESDE js nuevoCarrerasEmpleado (empleado), este método se encarga
// de editar la reserva de carrera seleccionada
public function editarReservaCarrera($id)
{
    log_message('debug', 'Iniciando editarReservaCarrera para id: ' . $id);
    $session = session();

    // Verifica que haya sesión iniciada y que el usuario sea tipo "empleado"
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado. Solo empleados pueden acceder.']
        ]);
    }

    // Verificar permisos específicos del empleado
    if ((int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para gestionar reservas de carreras.']
        ]);
    }

    // Obtener la reserva de carrera
    $reservaExistente = $this->empleadoCarrerasModel->find($id);
    if (!$reservaExistente) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Reserva de carrera no encontrada.']
        ]);
    }

    // Validar que el empleado solo pueda editar sus propias reservas
    if ((int)$reservaExistente['empleado_id'] !== (int)$session->get('empleado_id')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para editar esta reserva.']
        ]);
    }

    // Recoger datos enviados por POST
    $datos = $this->request->getPost();
    $errores = [];

    // Validaciones
    if (empty($datos['id_usuario'])) {
        $errores[] = "El id de usuario es obligatorio.";
    }

    if (empty($datos['num_participantes'])) {
        $errores[] = "El número de participantes es obligatorio.";
    } elseif (!is_numeric($datos['num_participantes']) || (int)$datos['num_participantes'] <= 0) {
        $errores[] = "El número de participantes debe ser un número mayor a 0.";
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

    // Calcular el total
    $precioUnitario = (float)$datos['cantidad'];
    $total = $precioUnitario * (int)$datos['num_participantes'];

    // Preparar datos para actualización
    $datosActualizar = [
        'id_usuario'        => $datos['id_usuario'],
        'metodo_pago'       => $datos['metodo_pago'],
        'num_participantes' => (int)$datos['num_participantes'],
        'fecha'             => $datos['fecha_carrera'],
        'franja_horaria_id' => $datos['franja_horaria_id'],
        'fecha_pago'        => $datos['fecha_pago'],
        'id_pistas'         => $datos['id_pistas'],
        'cantidad'          => $precioUnitario,
        'total'             => $total
    ];

    // Ejecutar la actualización
    if ($this->empleadoCarrerasModel->update($id, $datosActualizar)) {
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
// Método llamado desde js gestionCarreras (admin) y nuevoCarrerasEmpleado (empleado),
// se encarga de eliminar la reserva de carrera seleccionada.
public function eliminarReservaCarrera($id)
{
    log_message('debug', 'Iniciando eliminarReservaCarrera para id: ' . $id);
    $session = session();

    // Verifica que la sesión esté iniciada y que el usuario sea de tipo "empleado" o "admin"
    if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    // Verificación específica para empleados
    if (strtolower($session->get('tipo_usuario')) === 'empleado' && (int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para gestionar reservas de carreras.']
        ]);
    }

    // Cargar modelo
    $empleadoCarrerasModel = new \App\Models\EmpleadoCarrerasModel();

    try {
        // 1. Verificar que la reserva existe y no está pagada
        $reserva = $empleadoCarrerasModel->find($id);
        
        if (!$reserva) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Reserva de carrera no encontrada.']
            ]);
        }

        if ($reserva['pagado'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['No se puede eliminar una reserva ya pagada.']
            ]);
        }

        // 2. Intentar eliminar la reserva
        if ($empleadoCarrerasModel->eliminarReservaCarrera($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reserva de carrera eliminada correctamente.'
            ]);
        }

        throw new \Exception('Error al eliminar la reserva');

    } catch (\Exception $e) {
        log_message('error', 'Error en eliminarReservaCarrera: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al eliminar la reserva de carrera.']
        ]);
    }
}

// MÉTODO LLAMADO DESDE js de gestionCarreras (admin) y nuevoCarrerasEmpleado (empleado),
// se encarga de confirmar el pago 
public function confirmarPago($idCarrera)
{
    log_message('debug', 'Iniciando confirmarPago para reserva: ' . $idCarrera);

    $session = session();

    // Validar sesión
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No autorizado'
        ]);
    }

    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    // Validar permisos
    if ($esEmpleado && (int)$session->get('emp_carreras') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permiso para gestionar pagos de carreras.'
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Rol no autorizado'
        ]);
    }

    // Cargar el modelo correcto
    $empleadoCarrerasModel = new \App\Models\EmpleadoCarrerasModel();

    try {
        // Llamar al método del modelo
        $resultado = $empleadoCarrerasModel->marcarComoPagado($idCarrera);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pago confirmado correctamente.'
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error en confirmarPago: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// MÉTODO LLAMADO DESDE EL JS DE gestionCarreras (Admin), este método se encarga de dar al admin todas
// las carreras que existen en AleKarting, tanto pasadas como futuras
public function obtenerTodasLasCarreras()
{
    log_message('debug', 'Iniciando obtenerTodasLasCarreras');
    $session = session();
    
    // Verificación de sesión y permisos (solo admin)
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    try {
        // Obtener todas las carreras usando el modelo
        $carreras = $this->empleadoCarrerasModel->obtenerTodasLasCarreras();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $carreras ?: [], // Asegura array vacío si es null
            'message' => 'Datos de carreras obtenidos correctamente',
            'recordsTotal' => count($carreras),
            'recordsFiltered' => count($carreras)
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en obtenerTodasLasCarreras: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al cargar las carreras.']
        ]);
    }
}

    
}
