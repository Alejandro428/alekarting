<?php

namespace App\Controllers;

use App\Models\EmpleadoEventosModel;
use Config\Database;

class EmpleadoEventos extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->empleadoEventosModel = new EmpleadoEventosModel();
    }

    public function indexEventos()
    {
        $session = session();
        // Validar que la sesión esté iniciada y que el usuario sea empleado
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return redirect()->to(base_url());
        }
        
        // Comprobar si el empleado tiene permiso para gestionar eventos (1 = permitido)
        if ((int)$session->get('emp_evento') !== 1) {
            $data['error'] = "No tienes permiso para gestionar Eventos.";
            return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado', $data) . view('templates/footer');
        }
        
        return view('empleado/navbarEmpleado') . view('empleado/eventosEmpleado') . view('templates/footer');
    }

       // MÉTODO REFERENCIADO DE js nuevoEventosEmpleado (empleado), se encarga de obtener los eventos
       // del empleado
    public function obtenerEventosEmpleado() {
        $session = session();
        
        if (!$session->get('sesion_iniciada') || 
            strtolower($session->get('tipo_usuario')) !== 'empleado' || 
            (int)$session->get('emp_evento') !== 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso no autorizado'
            ]);
        }
    
        $id_empleado = $session->get('empleado_id');
        if (!$id_empleado) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de empleado no disponible'
            ]);
        }
    
        try {
            $model = new EmpleadoEventosModel();
            $eventos = $model->obtenerEventosEmpleado($id_empleado);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $eventos ?: []  // Asegura array aunque sea vacío
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerEventosEmpleado: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    // MÉTODO REFERENCIADO DE js gestionEventos (admin), nuevoEventoEmpleado (empleado), se encarga de obtener el evento
    // seleccionado con su id
    public function obtenerEventoEdicion($id)
{
    log_message('debug', 'Iniciando obtenerEventoEdicion para ID: ' . $id);
    $session = session();
    
    // Validación básica de sesión
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Acceso no autorizado: sesión no iniciada'
        ]);
    }

    // Determinar tipo de usuario
    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    // Validar permisos según rol
    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permisos para gestionar eventos'
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Rol no autorizado'
        ]);
    }

    try {
        $model = new EmpleadoEventosModel();
        $evento = $model->find($id);

        // Verificar si el evento existe
        if (!$evento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Evento no encontrado'
            ]);
        }

        // Para empleados: verificar que el evento les pertenece
        if ($esEmpleado && $evento['empleados_id'] != $session->get('empleado_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo puedes editar tus propios eventos'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'evento' => $evento
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en obtenerEventoEdicion: ' . $e->getMessage());
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al obtener el evento'
        ]);
    }
}
    // MÉTODO REFERENCIADO EN js gestionEventos (admin), nuevoEventoEmpleado (empleado), se encarga de obtener los horarios disponibles, teniendo 
    // en cuenta la fecha y horario que se pasan por método get, CONTROLADOR EmpleadoEventos, METODO obtenerHorariosDisponibles
    public function obtenerHorariosDisponibles()
    {
        // Obtener la fecha (formato YYYY-MM-DD) y, opcionalmente, el horario_actual (del evento en edición)
        $fecha = $this->request->getGet('fecha');
        $horarioActual = $this->request->getGet('horario_actual'); // Puede ser nulo
    
        if (!$fecha) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La fecha es requerida.'
            ]);
        }
        
        // Instanciar el modelo (o usar el ya declarado en el constructor, si lo prefieres)
        $model = new EmpleadoEventosModel();
    
        // (Opcional) Registrar en el log lo que se recibe
        log_message('debug', 'Obteniendo horarios para fecha: ' . $fecha . ', horario_actual: ' . $horarioActual);
        
        // Llamar al método del modelo
        $horarios = $model->obtenerHorariosDisponibles($fecha, $horarioActual);
        
        // Log para ver qué horarios se han obtenido
        log_message('debug', 'Horarios obtenidos: ' . print_r($horarios, true));
        
        return $this->response->setJSON([
            'success' => true,
            'horarios' => $horarios
        ]);
    }

// MÉTODO REFERENCIADO EN js gestionEventos (admin), nuevoEventoEmpleado (empleado) se encarga de crear un nuevo evento, CONTROLADOR EmpleadoEventos, METODO crearEvento
public function crearEvento()
{
    log_message('debug', 'Iniciando el método crearEvento');
    $session = session();
    
    // 1. Validación de sesión y permisos
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado: sesión no iniciada']
        ]);
    }

    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    // Validar permisos según tipo de usuario
    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para gestionar eventos']
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado: rol no válido']
        ]);
    }

    // 2. Validación de datos del formulario
    $datos = $this->request->getPost();
    $errores = [];
    
    // Campos obligatorios
    $camposRequeridos = [
        'nombre' => 'El campo nombre es obligatorio.',
        'descripcion' => 'El campo descripción es obligatorio.',
        'fecha' => 'El campo fecha es obligatorio.',
        'capacidad' => 'El campo capacidad es obligatorio.',
        'precio' => 'El campo precio es obligatorio.',
        'tipo_evento_id' => 'El campo tipo de evento es obligatorio.',
        'franja_horaria_id' => 'El campo franja horaria es obligatorio.'
    ];
    
    foreach ($camposRequeridos as $campo => $mensaje) {
        if (empty($datos[$campo])) {
            $errores[] = $mensaje;
        }
    }

    // Validación específica para admin (debe seleccionar empleado)
    if ($esAdmin && empty($datos['empleado_id'])) {
        $errores[] = 'Debe seleccionar un empleado responsable';
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // 3. Procesamiento de la imagen
    $nombreImagen = "";
    $imagen = $this->request->getFile('imagen');
    
    if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
        // Validar tipo de imagen
        $reglaImagen = 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]';
        if (!$this->validate(['imagen' => $reglaImagen])) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }
        
        // Validar dimensiones
        $imgTempPath = $imagen->getTempName();
        $dimensions = getimagesize($imgTempPath);
        if ($dimensions === false || $dimensions[0] < 800 || $dimensions[1] < 600) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['La imagen debe tener al menos 800x600 píxeles']
            ]);
        }
        
        // Mover la imagen
        $nombreImagen = $imagen->getRandomName();
        if (!$imagen->move(FCPATH . 'assets/imagenes/eventos/', $nombreImagen)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Error al guardar la imagen']
            ]);
        }
    }

    // 4. Determinar empleado responsable
    $empleadoId = $esAdmin ? $datos['empleado_id'] : $session->get('empleado_id');
    
    if (empty($empleadoId)) {
        // Eliminar imagen si ya se subió
        if (!empty($nombreImagen)) {
            unlink(FCPATH . 'assets/imagenes/eventos/' . $nombreImagen);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No se pudo determinar el empleado responsable']
        ]);
    }

    // 5. Guardar el evento
    $datosAGuardar = [
        'nombre'             => $datos['nombre'],
        'descripcion'        => $datos['descripcion'],
        'tipo_evento_id'     => $datos['tipo_evento_id'],
        'imagen'             => $nombreImagen,
        'precio'             => $datos['precio'],
        'fecha'              => $datos['fecha'],
        'franja_horaria_id'  => $datos['franja_horaria_id'],
        'capacidad'          => $datos['capacidad'],
        'empleados_id'       => $empleadoId
    ];
    
    $model = new EmpleadoEventosModel();
    $idInsertado = $model->insert($datosAGuardar);
    
    if ($idInsertado) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Evento guardado correctamente.',
            'id'      => $idInsertado
        ]);
    } else {
        // Eliminar imagen si falló el guardado
        if (!empty($nombreImagen)) {
            unlink(FCPATH . 'assets/imagenes/eventos/' . $nombreImagen);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al guardar el evento en la base de datos']
        ]);
    }
}

// MÉTODO REFERENCIADO EN js gestionEventos (admin), nuevoEventoEmpleado (empleado) se encarga de editar un evento, CONTROLADOR EmpleadoEventos, METODO editarEvento
public function editarEvento($id)
{
    log_message('debug', 'Iniciando editarEvento para id: ' . $id);
    $session = session();
    
    // 1. Validación de sesión
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Sesión no iniciada']
        ]);
    }

    // 2. Validación de permisos según tipo de usuario
    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['No tienes permiso para esta acción']
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Rol no autorizado']
        ]);
    }

    // 3. Validación de campos obligatorios
    $requiredFields = [
        'nombre' => 'nombre',
        'descripcion' => 'descripción',
        'fecha' => 'fecha',
        'capacidad' => 'capacidad',
        'precio' => 'precio',
        'tipo_evento_id' => 'tipo de evento',
        'franja_horaria_id' => 'franja horaria'
    ];
    
    $errores = [];
    $datos = $this->request->getPost();
    
    foreach ($requiredFields as $field => $name) {
        if (empty($datos[$field])) {
            $errores[] = "El campo {$name} es obligatorio.";
        }
    }

    // Validación adicional para admin (debe seleccionar empleado)
    if ($esAdmin && empty($datos['empleado_id'])) {
        $errores[] = 'Debe seleccionar un empleado responsable';
    }
    
    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => $errores
        ]);
    }

    // 4. Obtener el evento existente
    $model = new \App\Models\EmpleadoEventosModel();
    $eventoExistente = $model->find($id);
    
    if (!$eventoExistente) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Evento no encontrado']
        ]);
    }

    // 5. Verificar permisos sobre el evento (empleado solo puede editar sus propios eventos)
    if ($esEmpleado && $eventoExistente['empleados_id'] != $session->get('empleado_id')) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Solo puedes editar tus propios eventos']
        ]);
    }

    // 6. Procesar imagen si se proporciona
    $nuevoNombreImagen = $eventoExistente['imagen'];
    $img = $this->request->getFile('imagen');
    
    if ($img && $img->isValid() && !$img->hasMoved()) {
        $reglaValidacion = 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]';
        
        if (!$this->validate(['imagen' => $reglaValidacion])) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Validar dimensiones de la imagen
        $imgTempPath = $img->getTempName();
        $dimensions = getimagesize($imgTempPath);
        
        if ($dimensions === false || $dimensions[0] < 800 || $dimensions[1] < 600) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['La imagen debe tener al menos 800x600 píxeles']
            ]);
        }
        
        // Mover la nueva imagen
        $nuevoNombreImagen = $img->getRandomName();
        if (!$img->move(FCPATH . 'assets/imagenes/eventos/', $nuevoNombreImagen)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['Error al guardar la imagen']
            ]);
        }
        
        // Eliminar la imagen anterior si existe y es diferente
        if (!empty($eventoExistente['imagen']) && 
            file_exists(FCPATH . 'assets/imagenes/eventos/' . $eventoExistente['imagen']) &&
            $eventoExistente['imagen'] !== $nuevoNombreImagen) {
            unlink(FCPATH . 'assets/imagenes/eventos/' . $eventoExistente['imagen']);
        }
    }

    // 7. Preparar datos para actualizar
    $datosActualizar = [
        'nombre' => $datos['nombre'],
        'descripcion' => $datos['descripcion'],
        'fecha' => $datos['fecha'],
        'capacidad' => $datos['capacidad'],
        'precio' => $datos['precio'],
        'tipo_evento_id' => $datos['tipo_evento_id'],
        'franja_horaria_id' => $datos['franja_horaria_id'],
        'imagen' => $nuevoNombreImagen
    ];

    // Solo admin puede cambiar el empleado responsable
    if ($esAdmin) {
        $datosActualizar['empleados_id'] = $datos['empleado_id'];
    }

    // 8. Actualizar el evento
    if ($model->update($id, $datosActualizar)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Evento actualizado correctamente'
        ]);
    } else {
        // Si falló la actualización, eliminar la nueva imagen si se subió
        if (isset($img) && $nuevoNombreImagen !== $eventoExistente['imagen']) {
            (FCPATH . 'assets/imagenes/eventos/' . $nuevoNombreImagen);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['Error al actualizar el evento']
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEventos, nuevoEventosEmpleado (empleado) se encarga de comprobar si el evento tiene reservas hechas
// en ese caso, no se podrá eliminar el evento, CONTROLADOR EmpleadoEventos, Método verificarReservasEvento
public function verificarReservasEvento($evento_id)
{
    $model = new EmpleadoEventosModel();
    $totalReservas = $model->obtenerTotalReservasEvento($evento_id); // Nuevo método que crearemos
    
    return $this->response->setJSON([
        'success' => true,
        'totalReservas' => $totalReservas
    ]);
}

// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) se encarga de eliminar el evento seleccionado
    public function eliminarEvento($id)
    {
        $session = session();
        
        // 1. Validación básica de sesión
        if (!$session->get('sesion_iniciada')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autorizado: sesión no iniciada'
            ]);
        }
    
        // 2. Determinar tipo de usuario
        $tipoUsuario = strtolower($session->get('tipo_usuario'));
        $esEmpleado = ($tipoUsuario === 'empleado');
        $esAdmin = ($tipoUsuario === 'admin');
    
        // 3. Validar permisos según rol
        if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permiso para gestionar Eventos.'
            ]);
        } elseif (!$esEmpleado && !$esAdmin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rol no autorizado'
            ]);
        }
    
        // 4. Obtener el evento a eliminar
        $evento = $this->empleadoEventosModel->find($id);
        if (!$evento) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Evento no encontrado.'
            ]);
        }
    
        // 5. Para empleados: verificar que el evento les pertenece
        if ($esEmpleado && $evento['empleados_id'] != $session->get('empleado_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo puedes eliminar tus propios eventos'
            ]);
        }
    
        // 6. Comprobar si existen reservas asociadas al evento
        $totalReservas = $this->empleadoEventosModel->obtenerTotalReservasEvento($id);
        
        if ($totalReservas > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No se puede eliminar el evento, existen $totalReservas reserva(s) asociada(s).",
                'totalReservas' => $totalReservas
            ]);
        }
        
        // 7. Eliminar imagen asociada si existe
        if (!empty($evento['imagen'])) {
            $rutaImagen = FCPATH . 'assets/imagenes/eventos/' . $evento['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
        
        // 8. Proceder a eliminar el registro del evento
        if ($this->empleadoEventosModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Evento eliminado correctamente.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al eliminar el evento.'
            ]);
        }
    }

  
// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado), obtener las reservas del evento escogido que existan
// CONTROLADOR EmpleadoEventos, MÉTODO obtenerReservasEventoEscogido
public function obtenerReservasEventoEscogido($idEvento)
{
    $model = new EmpleadoEventosModel();
    $reservas = $model->obtenerReservasDelEvento($idEvento);
    
    // Cambio clave: Devuelve directamente el array cuando es exitoso
    return $this->response->setJSON(!empty($reservas) ? $reservas : []);
}

    public function obtenerReservaEventoUsuario($idReserva) {
        $reserva = $this->empleadoEventosModel->obtenerReservaEventoUsuario($idReserva);
        if ($reserva) {
            return $this->response->setJSON([
                'success' => true,
                'evento' => $reserva  
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Reserva no encontrada'
        ]);
    }

      
// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) crear la reserva del evento

    public function crearReserva()
{
    log_message('debug', 'Iniciando crearReserva');

    $session = session();

    // Validar sesión
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado']
        ]);
    }

    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para gestionar Reservas.']
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Rol no autorizado']
        ]);
    }

    $datos = $this->request->getPost();
    $errores = [];

    // Validaciones
    if (empty($datos['evento_id'])) {
        $errores[] = "El campo evento es obligatorio.";
    }

    if (empty($datos['usuario_id'])) {
        $errores[] = "El campo usuario es obligatorio.";
    }

    if (empty($datos['cantidad']) || !is_numeric($datos['cantidad']) || $datos['cantidad'] <= 0) {
        $errores[] = "La cantidad debe ser un número positivo.";
    }

    if (empty($datos['metodo_pago'])) {
        $errores[] = "El campo método de pago es obligatorio.";
    }

    if (empty($datos['fecha_pago']) || !strtotime($datos['fecha_pago'])) {
        $errores[] = "Formato de fecha de pago inválido.";
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => $errores
        ]);
    }

    // Validar que el evento existe y obtener su precio
    $evento = $this->db->table('eventos')
                      ->select('precio, capacidad')
                      ->where('id', $datos['evento_id'])
                      ->get()
                      ->getRow();

    if (!$evento) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Evento no encontrado.']
        ]);
    }

    // Opcional: validar plazas disponibles
    $reservadas = $this->db->table('reservas_eventos')
                           ->selectSum('cantidad')
                           ->where('evento_id', $datos['evento_id'])
                           ->get()
                           ->getRow()
                           ->cantidad ?? 0;

    $capacidadDisponible = (int)$evento->capacidad - (int)$reservadas;

    if ($datos['cantidad'] > $capacidadDisponible) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No hay suficientes plazas disponibles.']
        ]);
    }

    // Calcular total
    $precio = (float)$evento->precio;
    $cantidad = (int)$datos['cantidad'];
    $total = round($precio * $cantidad, 2);

    $datosInsertar = [
        'evento_id'     => (int)$datos['evento_id'],
        'usuario_id' => (int)$datos['usuario_id'],
        'cantidad'      => $cantidad,
        'metodo_pago'   => $datos['metodo_pago'],
        'fecha_pago'    => date('Y-m-d', strtotime($datos['fecha_pago'])),
        'total'         => $total,
    ];

    try {
        $this->db->table('reservas_eventos')->insert($datosInsertar);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reserva creada correctamente.',
            'data' => [
                'precio_evento' => $precio,
                'total' => number_format($total, 2, '.', '')
            ]
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error al crear reserva: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al crear la reserva en la base de datos.']
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) comprobar si al usuario que se le
// quiere hacer la reserva ya esta registrado en el evento, CONTROLADOR EmpleadoEventos, MÉTODO comprobarReservaExistente
public function comprobarReservaExistente()
{
    log_message('debug', 'Comprobando reserva existente...');

    $session = session();
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON(['existe' => false, 'error' => 'Sesión no iniciada']);
    }

    $usuario_id = $this->request->getPost('usuario_id');
    $evento_id = $this->request->getPost('evento_id');
    $id_reserva_actual = $this->request->getPost('id_reserva'); // Puede venir vacío

    $reservasModel = new \App\Models\ReservasEventosModel();

    $existe = $reservasModel->existeReservaDuplicada($usuario_id, $evento_id, $id_reserva_actual);

    return $this->response->setJSON(['existe' => $existe]);
}
/*  COMENTADO PORQUE NO SE DEBE EDITAR UNA RESERVA HECHA
    public function editarReserva($id)
    {
        log_message('debug', 'Iniciando editarReserva para id: ' . $id);
    
        $session = session();
    
        if (!$session->get('sesion_iniciada')) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['No autorizado']
            ]);
        }
    
        $tipoUsuario = strtolower($session->get('tipo_usuario'));
        $esEmpleado = ($tipoUsuario === 'empleado');
        $esAdmin = ($tipoUsuario === 'admin');
    
        if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['No tienes permiso para gestionar Reservas.']
            ]);
        } elseif (!$esEmpleado && !$esAdmin) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Rol no autorizado']
            ]);
        }
    
        $datos = $this->request->getPost();
        $errores = [];
    
        // Validaciones básicas
        if (empty($datos['cantidad']) || !is_numeric($datos['cantidad']) || $datos['cantidad'] <= 0) {
            $errores[] = "La cantidad debe ser un número positivo.";
        }
    
        if (empty($datos['metodo_pago'])) {
            $errores[] = "El campo método de pago es obligatorio.";
        }
    
        if (empty($datos['fecha_pago']) || !strtotime($datos['fecha_pago'])) {
            $errores[] = "Formato de fecha de pago inválido.";
        }
    
        if (empty($datos['evento_id'])) {
            $errores[] = "El campo evento es obligatorio.";
        }

        if (empty($datos['usuario_id'])) {
            $errores[] = "El campo usuario es obligatorio.";
        }
    
        if (!empty($errores)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $errores
            ]);
        }
    
        $builderReserva = $this->db->table('reservas_eventos');
        $reservaExistente = $builderReserva->where('id', $id)->get()->getRow();
    
        if (!$reservaExistente) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Reserva no encontrada.']
            ]);
        }
    
        // Obtener precio del nuevo evento seleccionado
        $evento = $this->db->table('eventos')
                        ->select('precio')
                        ->where('id', $datos['evento_id'])
                        ->get()
                        ->getRow();
    
        if (!$evento) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Evento no encontrado.']
            ]);
        }
    
        $precioEvento = (float)$evento->precio;
        $cantidad = (int)$datos['cantidad'];
        $total = round($precioEvento * $cantidad, 2);
    
        $datosActualizar = [
            'evento_id'     => (int)$datos['evento_id'], // Nuevo campo editable
            'usuario_id'    => $datos['usuario_id'],
            'cantidad'      => $cantidad,
            'metodo_pago'   => $datos['metodo_pago'],
            'fecha_pago'    => date('Y-m-d', strtotime($datos['fecha_pago'])),
            'total'         => $total,
        ];
    
        try {
            $builderReserva->where('id', $id)->update($datosActualizar);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reserva actualizada correctamente.',
                'data' => [
                    'nuevo_total' => number_format($total, 2, '.', ''),
                    'precio_evento' => $precioEvento
                ]
            ]);
    
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar reserva: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Error al actualizar la reserva en la base de datos.']
            ]);
        }
    }
*/

// MÉTODO REFERENCIADO EN js de gestionEventos, nuevoEventoEmpleado, eliminar la reserva del evento
// CONTROLADOR EmpleadoEventos, MÉTODO eliminarReserva

public function eliminarReserva($id)
{
    log_message('debug', 'Iniciando eliminarReserva para id: ' . $id);
    $session = session();
    
    // 1. Validación básica de sesión
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado']
        ]);
    }

    // 2. Determinar tipo de usuario
    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    // 3. Validar permisos según rol
    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No tienes permiso para gestionar Reservas.']
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Rol no autorizado']
        ]);
    }

    // Cargar modelo CORRECTO
    $model = new \App\Models\ReservasEventosModel();

    try {
        // 4. Verificar que la reserva existe y no está pagada
        $reserva = $model->find($id);
        
        if (!$reserva) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Reserva no encontrada']
            ]);
        }

        if (!empty($reserva['pagado']) && $reserva['pagado'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['No se puede eliminar una reserva ya pagada']
            ]);
        }

        // 5. Intentar eliminar la reserva
        $resultado = $model->eliminarReserva($id);
        
        if ($resultado === false || $resultado === 0) {
            throw new \Exception('No se pudo eliminar la reserva (posiblemente no existe)');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Reserva eliminada correctamente.'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en eliminarReserva - ID: ' . $id . ' - ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al eliminar la reserva: ' . $e->getMessage()]
        ]);
    }
}

// MÉTODO REFERENCIADO EN js de gestionEventos (admin), nuevoEventoEmpleado (empleado) confirmar el pago de la reserva del evento
// CONTROLADOR EmpledoEventos, MÉTODO confirmarPago
public function confirmarPago($idReserva)
{
    log_message('debug', 'Iniciando confirmarPago para reserva: ' . $idReserva);

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
    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permiso para gestionar pagos de eventos.'
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Rol no autorizado'
        ]);
    }

    // Cargar el modelo correcto
    $reservasEventosModel = new \App\Models\ReservasEventosModel();

    try {
        // Llamar al método del modelo
        $resultado = $reservasEventosModel->marcarComoPagado($idReserva);

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

// MÉTODO REFERENCIADO EN js de gestionEventos, nuevoEventoEmpleado, obtener los datos del evento que no tiene
// la reserva del evento por su cuenta, CONTROLADOR EmpleadoEventos, MÉTODO obtenerFechaEvento
public function obtenerFechaEvento($evento_id)
{
    log_message('debug', 'Iniciando obtenerFechaEvento para ID: ' . $evento_id);
    $session = session();
    
    // Verificación de sesión y permisos (solo admin o empleado autorizado)
    if (!$session->get('sesion_iniciada')) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado.']
        ]);
    }

    $tipoUsuario = strtolower($session->get('tipo_usuario'));
    $esEmpleado = ($tipoUsuario === 'empleado');
    $esAdmin = ($tipoUsuario === 'admin');

    if ($esEmpleado && (int)$session->get('emp_evento') !== 1) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permiso para gestionar pagos de eventos.'
        ]);
    } elseif (!$esEmpleado && !$esAdmin) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Rol no autorizado'
        ]);
    }

    try {
        $eventosModel = new \App\Models\EventoModel();
        $evento = $eventosModel->obtenerEventoPorId($evento_id);

        if (!$evento) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Evento no encontrado.']
            ]);
        }

        // Formatear fecha y precio
        $evento->fecha = date('d-m-Y', strtotime($evento->fecha));
        $evento->precio = number_format($evento->precio, 2, '.', '');

        return $this->response->setJSON([
            'success' => true,
            'evento'  => $evento,
            'message' => 'Datos del evento obtenidos correctamente'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en obtenerFechaEvento: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Error al obtener los datos del evento.']
        ]);
    }
}
    // MÉTODO REFERENCIADO DESDE EL js de gestionEventos (admin), se encarga de obtener
    // todos los eventos que tiene la web, MÉTODO obtenerTodosLosEventos
    public function obtenerTodosLosEventos()
    {
        log_message('debug', 'Iniciando obtenerTodosLosEventos');
        $session = session();
        
        // Verificación de sesión y permisos (solo admin)
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['No autorizado.']
            ]);
        }
        try {
            // Obtener todos los eventos usando el modelo
            $eventos = $this->empleadoEventosModel->obtenerTodosLosEventos();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $eventos ?: [],
                'message' => 'Todos los eventos obtenidos correctamente',
                'recordsTotal' => count($eventos),
                'recordsFiltered' => count($eventos)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en obtenerTodosLosEventos: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Error al cargar los eventos.']
            ]);
        }
    }

}
