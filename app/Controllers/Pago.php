<?php namespace App\Controllers;

use DateTime;
use App\Models\CarreraModel;
use App\Models\EmpleadoCarrerasModel;
use App\Models\ReservasEventosModel;
use App\Models\EventoModel;
use Config\StripeConfig;
use CodeIgniter\HTTP\RedirectResponse;
use AmazonPay\Checkout\Session;

class Pago extends BaseController
{
    protected $stripeConfig;

    public function __construct()
    {
        // Stripe SDK se carga via Composer autoload (composer require stripe/stripe-php)
        $this->stripeConfig = config('StripeConfig');
        \Stripe\Stripe::setApiKey($this->stripeConfig->secret_key);
    }

    public function indexPasarela()
    {
        $session = session();

        if (!$session->get('sesion_iniciada')) {
            return redirect()->to(base_url());
        }

        
        // 2. Verificar que existen datos de reserva temporal
        if (!$session->has('temp_reserva_data')) {
            // Redirigir a la página principal de reservas con mensaje
            return redirect()->to(base_url())->with('error', 'No hay una reserva en proceso');
        }
        
        // Si todo está correcto, muestra la vista de carreras
        return view('templates/navbar')
            . view('pasarelapago/pasarela')
            . view('templates/footer');
    }

// MÉTODO USADO POR EL JS DE carreraVista o eventoVista, SIRVE PARA GUARDAR EN SESIÓN
// LAS VARIABLES DE LA RESERVA QUE SE HAYA HECHO, TANTO SI ES DE EVENTOS COMO SI ES DE
// CARRERAS, SOLO LAS GUARDA EN SESIÓN POR AHORA, ASÍ SE TIENE DE MANERA SENCILLA LA 
// INFORMACIÓN A MANO
    public function guardar_reserva(): \CodeIgniter\HTTP\Response 
{
    $request = $this->request;
    $session = session();

    // COMPROBAR QUE USE FORMATO CORRECTO
    if (!$request->isAJAX() || !$request->getMethod() === 'post') {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Solicitud no válida (debe ser AJAX y POST)'
        ]);
    }

    // COMPROBAR QUE HAYA SESIÓN INICIADA
    if (!$session->has('id')) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Debes iniciar sesión primero'
        ]);
    }

    // COMPROBAR QUE SEA O CARRERA O EVENTO
    $tipo = strtolower($request->getPost('tipo'));
    if (!in_array($tipo, ['carrera', 'evento'])) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Tipo de reserva no válido'
        ]);
    }

    // DATOS BÁSICOS DE LA RESERVA, POR AHORA TIPO (EVENTO O CARRERA), EL ID DE LA SESIÓN ACTUAL,
    // EL MÉTODO DE PAGO (AÚN NO ESCOGIDO) Y LA CLAVE PÚBLICA DEL STRIPE, GUARDADA EN EL STRIPE CONFIG
    $datosReserva = [
        'tipo' => $tipo,
        'id_usuario' => $session->get('id'),
        'metodo_pago' => null,
        'publishable_key' => $this->stripeConfig->publishable_key, // Clave pública de Stripe
    ];

    // SI ES UNA RESERVA DE CARRERA, SE GUARDA UNA INFORMACIÓN
    if ($tipo === 'carrera') {
        $requiredFields = [
            'id_pistas', 
            'cantidad', 
            'pista_nombre', 
            'fecha', 
            'franja_horaria_id', 
            'num_participantes',
            'precio_unitario' 
        ];
    
        foreach ($requiredFields as $field) {
            if (empty($request->getPost($field))) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => "Falta el campo requerido: $field"
                ]);
            }
        }
    
        $cantidad = (float)$request->getPost('cantidad'); 
        $precioUnitario = (float)$request->getPost('precio_unitario'); 
        $numParticipantes = (int)$request->getPost('num_participantes'); 
        $total = $precioUnitario * $numParticipantes; 
    
        $datosReserva += [
            'id_pistas' => $request->getPost('id_pistas'),
            'fecha' => $request->getPost('fecha'),
            'franja_horaria_id' => $request->getPost('franja_horaria_id'),
            'num_participantes' => $numParticipantes,
            'cantidad' => $cantidad,
            'pista_nombre' => $request->getPost('pista_nombre'),
            'horario_texto' => $request->getPost('horario_texto'),
            'precio_unitario' => $precioUnitario, // Guardamos el precio unitario
            'monto' => $cantidad,  // Monto de la reserva (se mantiene)
            'total' => $total,  // Total calculado para la reserva
            'descripcion' => "Reserva en " . $request->getPost('pista_nombre')
        ];// SI ES UN EVENTO, SE GUARDA OTRA INFORMACIÓN
    } elseif ($tipo === 'evento') {
        $requiredFields = [
            'evento_id', 
            'evento_nombre',
            'tipo_evento',
            'fecha',        
            'franja_horaria_id',
            'num_participantes',
            'horario_texto',
            'precio_unitario',
            'total', 
            'cantidad' 
        ];
        
        // Comprobar que todos los campos requeridos estén presentes
        foreach ($requiredFields as $field) {
            if (empty($request->getPost($field))) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => "Falta el campo requerido: $field"
                ]);
            }
        }
    
        // Datos de la reserva
        $datosReserva += [
            'evento_id'         => $request->getPost('evento_id'),
            'evento_nombre'     => $request->getPost('evento_nombre'),
            'tipo_evento'       => $request->getPost('tipo_evento'),
            'fecha'             => $request->getPost('fecha'),
            'franja_horaria_id' => $request->getPost('franja_horaria_id'),
            'num_participantes' => (int) $request->getPost('num_participantes'), 
            'horario_texto'     => $request->getPost('horario_texto'),
            'precio_unitario'   => (float) $request->getPost('precio_unitario'),  
            'total'             => (float) $request->getPost('total'),          
            'cantidad'          => (float) $request->getPost('cantidad'), 
            'monto'             => (float) $request->getPost('total'),         
            'descripcion'       => "Reserva para evento: " . $request->getPost('evento_nombre')
        ];
    }    

    // OBJETO EN EL QUE VOY A GUARDAR LA INFORMACIÓN DE LA RESERVA
    $session->set('temp_reserva_data', $datosReserva);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Reserva validada y guardada en sesión'
    ]);
}

// MÉTODO LLAMADO DESDE JS DE carreraVista y eventoVista, sirve para
// redirigir al usuario a la pasarela, antes comprobando que el objeto
// de la reserva que quiere hacer existe en sesión
public function pasarela()
{
    $session = session();
    
    // 1. Verificar que existan datos de reserva en sesión
    if (!$session->has('temp_reserva_data')) {
        log_message('error', 'Datos de reserva no encontrados en sesión');
        return $this->response->setJSON([
            'success' => false,
            'error' => 'La sesión de reserva ha expirado'
        ]);
    }

    // 2. Obtener datos de la reserva directamente desde la sesión
    $reserva = $session->get('temp_reserva_data');

    return $this->response->setJSON([
        'success' => true,
        'redirect' => base_url('pasarela')
    ]);
}
/*   
* MÉTODO PARA PROCESAR PAGOS DE RESERVAS EN TARJETAS/PAYPAL
 * 
 * Responsabilidades:
 * 1. Valida autenticación y datos de entrada
 * 2. Prepara metadatos para Stripe según tipo de reserva
 * 3. Delega el procesamiento según método de pago
 * 4. Maneja errores y respuestas
 * 
 * Flujo:
 * - Recibe payload JSON con datos de reserva y pago
 * - Valida estructura y reglas de negocio
 * - Crea sesión de pago en Stripe (tarjeta/PayPal)
 * - Guarda contexto en sesión PHP para completar reserva posteriormente
 * 
 */
public function procesar() 
{
    // SE VALIDA QUE HAY SESIÓN INICIADA
    $session = session();
    if (!$session->has('id')) {
        return $this->response->setJSON(['error' => 'No autenticado'])->setStatusCode(401);
    }
    // SE RECOGE LA INFORMACIÓN QUE LLEGA POR EL JSON

    $payload = $this->request->getJSON(true);

    // REGLAS PARA LOS DATOS QUE LLEGAN 
    $rules = [
        'payment_method_id' => $payload['metodo_pago'] === 'card' ? 'required|string' : 'permit_empty',
        'amount' => 'required|numeric|greater_than[0]',
        'tipo' => 'required|in_list[carrera,evento]',
        'metodo_pago' => 'required|in_list[card,paypal]',
        'fecha' => 'required|valid_date[Y-m-d]',
        'franja_horaria_id' => 'required|numeric',
        'num_participantes' => 'required|numeric|greater_than[0]',
        'horario_texto' => 'permit_empty',
        'precio_unitario' => 'required|numeric|greater_than[0]',
    ];

    // DEPENDE DE SI ES UNA CARRERA O UN EVENTO, SE AÑADEN CIERTAS REGLAS A LA INFORMACIÓN FALTANTE
    if ($payload['tipo'] === 'carrera') {
        $rules += [
            'id_pistas' => 'required|numeric',
            'pista_nombre' => 'permit_empty',
        ];
    } elseif ($payload['tipo'] === 'evento') {
        $rules += [
            'evento_id' => 'required|numeric',
            'cantidad' => 'required|numeric|greater_than[0]',
            'evento_nombre' => 'permit_empty',
            'tipo_evento' => 'permit_empty',
        ];
    }

    // SE VALIDA LA INFORMACIÓN
    $validation = \Config\Services::validation();
    $validation->setRules($rules);

    // EN CASO DE ERROR AL VALIDAR, SE DEVUELVE UN ERROR
    if (!$validation->run($payload)) {
        log_message('error', 'Validación fallida: ' . print_r($validation->getErrors(), true));
        return $this->response->setJSON([
            'error' => 'Datos inválidos',
            'errors' => $validation->getErrors()
        ])->setStatusCode(400);
    }

    // VALIDACIÓN NECESARIA PARA CUANDO EL MÉTODO ES PAYPAL
    if ($payload['metodo_pago'] === 'paypal') {
        if ($payload['amount'] < 50 || $payload['amount'] > 2500000) {
            return $this->response->setJSON([
                'error' => 'Para PayPal, el monto debe estar entre 0.50€ y 25,000€',
                'min_amount' => 50,
                'max_amount' => 2500000
            ])->setStatusCode(400);
        }
    }

    try {
        // SE PONE LA CLAVE PRIVADA EN LA API
        \Stripe\Stripe::setApiKey($this->stripeConfig->secret_key);
        // SE DECLARA LA FECHA EN FORMATO D-M-Y
        $fechaFormateada = date('d-m-Y', strtotime($payload['fecha']));

        // PREPARO LA METADATA QUE HABRÁ REGISTRADA EN EL STRIPE CUANDO SE 
        // COMPLETE EL PAGO
        // SI ES UNA CARRERA SE GUARDA UNA INFORMACIÓN, Y SI ES UN EVENTO, OTRA
        if ($payload['tipo'] === 'carrera') {
            $metadata = [
                'user_id' => $session->get('id'),
                'tipo' => $payload['tipo'],
                'id_pistas' => $payload['id_pistas'],
                'fecha' => $payload['fecha'],
                'franja_horaria_id' => $payload['franja_horaria_id'],
                'num_participantes' => $payload['num_participantes'],
                'metodo_pago' => $payload['metodo_pago'],
                'pista_nombre' => $payload['pista_nombre'] ?? null,
                'horario_texto' => $payload['horario_texto'] ?? null,
                'nombre_usuario' => $session->get('nombre'),
                'amount' => (int)$payload['amount'],
                'source' => 'web_v3',
                'fecha_formateada' => $fechaFormateada
            ];
        } elseif ($payload['tipo'] === 'evento') {
            $metadata = [
                'user_id' => $session->get('id'),
                'tipo' => $payload['tipo'],
                'evento_id' => $payload['evento_id'],
                'cantidad' => $payload['cantidad'],
                'evento_nombre' => $payload['evento_nombre'] ?? null,
                'tipo_evento' => $payload['tipo_evento'] ?? null,
                'fecha' => $payload['fecha'],
                'franja_horaria_id' => $payload['franja_horaria_id'],
                'num_participantes' => $payload['num_participantes'],
                'metodo_pago' => $payload['metodo_pago'],
                'horario_texto' => $payload['horario_texto'] ?? null,
                'nombre_usuario' => $session->get('nombre'),
                'amount' => (int)$payload['amount'],
                'source' => 'web_v3',
                'fecha_formateada' => $fechaFormateada
            ];
        }

        // DEPENDE DEL MÉTODO DE PAGO QUE SE ESTÉ UTILIZANDO, SE USA UN MÉTODO DE
        // TARJETA O UNO DE PAYPAL, EN ELLOS SE DEVUELVE EL OBJETO QUE SE NECESITARÁ
        // PARA HACER LA RESERVA Y SE GUARDARÁ EN SESIÓN ESA INFORMACIÓN
        switch ($payload['metodo_pago']) {
            case 'card':
                return $this->procesarTarjeta($payload, $metadata, $session);
            case 'paypal':
                return $this->procesarPayPal($payload, $metadata, $session);
            default:
                throw new \Exception('Método de pago no soportado');
        }
        // EN CASO DE ERROR, CONTROLARLOS
    } catch (\Stripe\Exception\CardException $e) {
        // Versión segura que funciona incluso si getError() devuelve null
        $stripeCode = method_exists($e, 'getStripeCode') ? $e->getStripeCode() : 'card_declined';
        $errorMessage = $e->getMessage();
        log_message('error', "Error Stripe (Card): {$stripeCode} - {$errorMessage}");
        return $this->response->setJSON([
            'error' => $stripeCode, // Usamos el código seguro
            'message' => $errorMessage,
            'type' => 'card_error'
        ])->setStatusCode(400);        
    } catch (\Exception $e) {
        log_message('error', "Error inesperado: {$e->getMessage()}\n" . $e->getTraceAsString());
        return $this->response->setJSON([
            'error' => 'Error interno del servidor',
            'code' => 'server_error'
        ])->setStatusCode(500);
    }
}

/**
 * MÉTODO QUE SIRVE PARA PAGOS CON TARJETA:
 * 
 * Funcionalidades clave:
 * 1. Crea un PaymentIntent en Stripe para procesar el pago con tarjeta
 * 2. Maneja automáticamente autenticación 3D Secure cuando es requerida
 * 3. Almacena el ID del pago en sesión para verificación posterior
 * 4. Proporciona datos necesarios para el flujo de pago en frontend
 * 
 * payload - Datos de la reserva (tipo, monto, payment_method_id, etc.)
 * metadata - Metadatos para Stripe (ID usuario, detalles reserva)
 * session - Instancia de sesión CI para persistencia
 * 
 * return JSON con:
 *   - status: Estado actual del pago (succeeded, requires_action, etc.)
 *   - payment_intent_id: ID único del pago en Stripe
 *   - client_secret: Cliente secreto para confirmación en frontend
 *   - requires_action: Booleano que indica si se necesita 3D Secure
 *   - success: Booleano que indica si el pago fue exitoso o está pendiente
 */
protected function procesarTarjeta($payload, $metadata, $session)
{
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => (int)$payload['amount'],
        'currency' => 'eur',
        'payment_method' => $payload['payment_method_id'],
        'confirm' => true,
        'metadata' => $metadata,
        'description' => 'Reserva con tarjeta - ' . $payload['tipo'],
        'capture_method' => 'automatic',
        'automatic_payment_methods' => [
            'enabled' => true,
            'allow_redirects' => 'never'
        ],
        'use_stripe_sdk' => true
    ]);
    
    $session->set('temp_payment_id', $paymentIntent->id);
    log_message('info', "PaymentIntent creado [Tarjeta]: {$paymentIntent->id}, Estado: {$paymentIntent->status}");
    
    return $this->response->setJSON([
        'status' => $paymentIntent->status,
        'payment_intent_id' => $paymentIntent->id,
        'client_secret' => $paymentIntent->client_secret,
        'requires_action' => $paymentIntent->status === 'requires_action',
        'success' => in_array($paymentIntent->status, ['succeeded', 'requires_action'])
    ]);
}
/*
MÉTODO QUE SIRVE PARA PAYPAL:

 * Funcionalidades clave:
 * 1. Configurar la información del producto que se va a mostrar en PayPal (nombre/descripción)
 * 2. Crear sesión de pago en Stripe con redirección a PayPal
 * 3. Almacenar datos críticos en sesión para completar reserva post-pago
 * 4. Genera URLs de éxito/fracaso con seguimiento
 * 
 * payload - Datos de la reserva (tipo, monto, fechas, etc.)
 * metadata - Metadatos para Stripe (ID usuario, detalles reserva)
 * session - Instancia de sesión CI para persistencia
 * 
 * return JSON con:
 *   - redirect_url: URL de Stripe para redirigir a PayPal
 *   - session_id: ID para seguimiento
 *   - expires_at: Tiempo límite de la sesión
*/
protected function procesarPayPal($payload, $metadata, $session)
{
    $productName = "Reserva de " . $payload['tipo'];
    $fechaFormateada = date('d-m-Y', strtotime($payload['fecha']));
    
    if ($payload['tipo'] === 'carrera') {
        $productDescription = "CLIENTE: " . strtoupper($session->get('nombre')) . " | " .
                             "PISTA: " . strtoupper($payload['pista_nombre'] ?? 'N/A') . " | " .
                             "FECHA: " . $fechaFormateada . " | " .
                             "HORARIO: " . ($payload['horario_texto'] ?? 'N/A');
    } else {
        $productDescription = "EVENTO: " . strtoupper($payload['evento_nombre'] ?? 'Evento especial') . " | " .
                             "FECHA: " . $fechaFormateada . " | " .
                             "HORARIO: " . ($payload['horario_texto'] ?? 'N/A') . " | " .
                             "PARTICIPANTES: " . $payload['num_participantes'];
    }
    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['paypal'],
        'mode' => 'payment',
        'customer_email' => $session->get('email'),
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => (int)$payload['amount'],
                'product_data' => [
                    'name' => $productName,
                    'description' => $productDescription,
                    'metadata' => ['tipo_reserva' => $payload['tipo']]
                ],
            ],
            'quantity' => 1,
        ]],
        'metadata' => $metadata,
        'success_url' => base_url('pago/completado').'?session_id={CHECKOUT_SESSION_ID}&via=paypal',
        'cancel_url' => base_url('pago/fallo').'?error=paypal_cancelled&session_id={CHECKOUT_SESSION_ID}',
        'payment_intent_data' => [
            'metadata' => $metadata,
            'description' => 'PayPal - ' . ($payload['tipo'] === 'carrera' 
                ? ($payload['pista_nombre'] ?? 'Carrera')
                : ($payload['nombre_evento'] ?? 'Evento'))
        ],
        'expires_at' => time() + 3600
    ]);
    
    $session->set('paypal_session_'.$checkoutSession->id, [
        'payment_intent_id' => $checkoutSession->payment_intent,
        'metadata' => $metadata,
        'expires_at' => time() + 3500
    ]);
    
    log_message('info', "Checkout Session creada [PayPal]: {$checkoutSession->id}");
    
    return $this->response->setJSON([
        'redirect_url' => $checkoutSession->url,
        'session_id' => $checkoutSession->id,
        'expires_at' => $checkoutSession->expires_at,
        'method' => 'paypal'
    ]);
}

/**
 * MÉTODO PARA LA FINALIZACIÓN EXITOSA DE UN PAGO
 * 
  * Este método procesa la confirmación de pagos exitosos desde diferentes fuentes:
 * - Pagos con tarjeta (flujo directo)
 * - Pagos con PayPal (redirección)
 * - Recargas de página (recuperación de estado)
 * 
 * Parámetros GET (según flujo):
 * 
 * 1. Para pagos con PayPal (requeridos):
 *    ?via=paypal&session_id=cs_test_abc123
 * 
 * 2. Para recargas de página (opcional):
 *    ?payment_intent=pi_xyz987
 * 
 * 3. Para pagos con tarjeta :
 *    (usa datos de sesión)
 * 
 * Flujo de trabajo:
 * 1. Configuración inicial (API Stripe + DB)
 * 2. Obtención del PaymentIntent desde:
 *    - Parámetros GET (PayPal/recarga)
 *    - Sesión PHP (pagos con tarjeta)
 * 3. Validaciones:
 *    - Estado del pago en Stripe
 *    - Prevención de duplicados
 * 4. Registro en base de datos
 * 5. Limpieza de sesión
 * 6. Visualización de confirmación
 * 
 * Seguridad:
 * - Verifica autenticación con $session->has('id')
 * - Valida estado real con Stripe (no solo datos locales)
 * - Previene duplicados con checks en DB
 * 
 * return
 *    - Vista de éxito con datos de reserva
 *    - Redirección a fallo en caso de error
 */

 public function completado()
 {
     $session = session();
     try {
         // 1. CONFIGURACIÓN INICIAL
         \Stripe\Stripe::setApiKey($this->stripeConfig->secret_key);
         $db = \Config\Database::connect();
 
         // 2. OBTENCIÓN DE PARÁMETROS
         // Nota: Todos estos parámetros son opcionales según el flujo
         $paymentMethod = $this->request->getGet('via');  // Solo presente en flujo PayPal
         $isPaypal = $paymentMethod === 'paypal';         // Bandera (variable booleana) para lógica PayPal
         $checkoutSessionId = $this->request->getGet('session_id');  // ID de sesión Stripe (PayPal)
         $paymentIntentId = $this->request->getGet('payment_intent'); // Para recargas de página
 
         // 3. RECUPERACIÓN DEL PAYMENT INTENT
         // Delegamos en obtenerPaymentIntent que maneja:
         // - Flujo PayPal (usa checkoutSessionId)
         // - Recargas (usa paymentIntentId)
         // - Pagos con tarjeta (usa temp_payment_id de sesión)
         $paymentIntent = $this->obtenerPaymentIntent(
             $session,
             $isPaypal,
             $checkoutSessionId,
             $paymentIntentId
         );
 
         // 4. VERIFICACIÓN DE DUPLICADOS
         // Comprueba en DB si ya existe una reserva con este payment_intent_id
         // para evitar procesamiento duplicado
         // SI YA SE HA HECHO EL PAGO Y SE INTENTA VOLVER A HACER, SIMPLEMENTE SE LLAMA AL MOSTRAR VISTA ÉXITO Y NO 
         // SE HACE NI EL REGISTRO EN STRIPE NI EN LA BASE DE DATOS
         if ($this->esPagoDuplicado($paymentIntent)) {
             return $this->mostrarVistaExito($paymentIntent, true); // Vista con bandera (variable booleana) de duplicado
         }
 
         // 5. VALIDACIÓN DEL ESTADO DEL PAGO
         // Verifica que el pago tenga estado 'succeeded' en Stripe
         // Hace doble verificación contra la API de Stripe
         // POR SI OCURRIERA ALGO RARO, TAMBIÉN COMPRUEBO EL PAGO SI YA EXISTE EN STRIPE, EN ESE CASO, LANZO DIRECTAMENTE UN ERROR
         $this->validarEstadoPago($paymentIntent);
 
         // 6. PROCESAMIENTO DE LA RESERVA
         // - Crea registro en base de datos
         // - Maneja transacción para integridad
         // - Diferenciación entre carreras/eventos
         $this->procesarReserva($db, $paymentIntent);
 
         // 7. ENVÍO DE CORREO DE CONFIRMACIÓN DE RESERVA (solo si la reserva es nueva y no ha sido enviada antes)
         if ($session->get('temp_reserva_data')) {
             // Obtén los datos de la reserva temporal para enviarlos al controlador de correo
             $reservaData = $session->get('temp_reserva_data');
             // Llamada al método del controlador de CorreoController para enviar el correo
             $this->enviarCorreoReserva($reservaData, $isPaypal);
         }
 
         // 8. LIMPIEZA DE SESIÓN
         // Elimina datos temporales y establece banderas (variable booleana)
         // para manejar posibles recargas
         $this->limpiarSesionPostPago($session, $isPaypal, $checkoutSessionId, $paymentIntent);
 
         // LOG Y RESPUESTA FINAL
         log_message('info', "Pago completado. ID: {$paymentIntent->id}, Método: {$paymentMethod}");
         return $this->mostrarVistaExito($paymentIntent);
 
     } catch (\Stripe\Exception\ApiErrorException $e) {
         // ERRORES ESPECÍFICOS DE STRIPE
         $error = $e->getError();
         log_message('error', "Error Stripe: {$error->code} - {$error->message}");
         return redirect()->to('pago/fallo?error=stripe_error&code='.$error->code);
         
     } catch (\Exception $e) {
         // ERRORES GENERALES
         log_message('error', "Error en completado: {$e->getMessage()}");
         
         // Manejo especial para recargas de páginas ya procesadas
         return $this->manejarErrorRecarga($e, $session, $paymentIntentId ?? null);
     }
 }
 

// MÉTODO PARA FORMAR LA ESTRUCTURA DEL CORREO Y ENVIARLO 
public function enviarCorreoReserva(array $reservaData, bool $isPaypal)
{
    $session = session();  // Necesario para obtener datos del usuario de la sesión

    if (!$reservaData) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontraron datos de la reserva'
        ])->setStatusCode(400);
    }

    // Determinar el método de pago
    $metodoPago = $isPaypal ? 'Paypal' : 'Tarjeta';

    // Formatear el total para tener siempre dos decimales
    $total = number_format($reservaData['total'], 2, '.', '');

    // Cambiar el formato de la fecha de YYYY-MM-DD a DD-MM-YYYY
    $fecha = \DateTime::createFromFormat('Y-m-d', $reservaData['fecha']);
    $fechaFormateada = $fecha ? $fecha->format('d-m-Y') : $reservaData['fecha'];  // Validación por si el formato falla

    // Definir el asunto y el mensaje
    $subject = "Confirmación de tu reserva";
    $message = "Gracias por realizar tu reserva.\n\n";
    $message .= "El pago de tu reserva ya ha sido realizado correctamente.\n\n";  // AVISO AGREGADO
    $message .= "Detalles de tu reserva:\n";
    $message .= "Tipo de reserva: " . ucfirst($reservaData['tipo']) . "\n";

    if ($reservaData['tipo'] === 'carrera') {
        $message .= "Pista: " . $reservaData['pista_nombre'] . "\n";
        $message .= "Fecha: " . $fechaFormateada . "\n";
        $message .= "Horario: " . $reservaData['horario_texto'] . "\n";
        $message .= "Número de participantes: " . $reservaData['num_participantes'] . "\n";
        $message .= "Total: " . $total . " €\n";
    } elseif ($reservaData['tipo'] === 'evento') {
        $message .= "Evento: " . $reservaData['evento_nombre'] . "\n";
        $message .= "Fecha: " . $fechaFormateada . "\n";
        $message .= "Horario: " . $reservaData['horario_texto'] . "\n";
        $message .= "Número de participantes: " . $reservaData['num_participantes'] . "\n";
        $message .= "Total: " . $total . " €\n";
    }

    $message .= "Método de pago: " . $metodoPago . "\n";

    // Preparar el array de datos del correo
    $correoData = [
        'email'   => $session->get('email'),
        'asunto'  => $subject,
        'mensaje' => $message,
        'nombre'  => $session->get('nombre') . ' ' . $session->get('apellidos'),
    ];

    // Usar el servicio de correo
    $correoService = new \App\Services\CorreoService();
    $result = $correoService->enviar($correoData);
}


/** Métodos auxiliares (implementación completa) */

/**
 * METODO QUE OBTIENE EL PAYMENTINTENT DESDE DIFERENTES FUENTES
 * 
 * Maneja 3 flujos distintos:
 * 1. Recarga de página (payment_intent en URL)
 * 2. Pago con PayPal (session_id en URL)
 * 3. Pago con tarjeta (sesión PHP)
 * 
 * session Instancia de sesión CI
 * isPaypal Indica si es pago con PayPal
 * checkoutSessionId ID de sesión de Stripe (PayPal)
 * paymentIntentId ID del pago (para recargas)
 * 
 * return  Objeto del pago
 */

private function obtenerPaymentIntent($session, $isPaypal, $checkoutSessionId, $paymentIntentId)
{
    // Variable para almacenar el objeto PaymentIntent
    $paymentIntent = null;

    // ---------------------------------------------------------------
    // FLUJO 1: RECARGA DE PÁGINA (payment_intent en URL)
    // ---------------------------------------------------------------
    // Cuando el usuario recarga la página, el payment_intent viene en la URL
    // Ejemplo: ?payment_intent=pi_123456789
    if ($paymentIntentId) {
        // Recupera el PaymentIntent directamente desde la API de Stripe
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        // Registra en logs para debugging
        log_message('info', "PaymentIntent recuperado desde URL: {$paymentIntentId}");
    }
    // ---------------------------------------------------------------
    // FLUJO 2: PAGO CON PAYPAL
    // ---------------------------------------------------------------
    // Cuando Stripe redirige después de un pago exitoso con PayPal
    // Ejemplo: ?via=paypal&session_id=cs_test_123
    elseif ($isPaypal && $checkoutSessionId) {
        // Método auxiliar que maneja la lógica específica de PayPal
        $paymentIntent = $this->getPayPalPaymentIntent($session, $checkoutSessionId);
    }
  
    // ---------------------------------------------------------------
    // FLUJO 3: PAGO CON TARJETA (PRIMERA CARGA)
    // ---------------------------------------------------------------
    // Cuando es la primera carga después de un pago con tarjeta exitoso
    elseif ($paymentId = $session->get('temp_payment_id')) {
        // Recupera el PaymentIntent desde Stripe usando el ID guardado en sesión
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentId);
        log_message('info', "PaymentIntent recuperado desde sesión PHP: {$paymentId}");
    }

    // ---------------------------------------------------------------
    // VALIDACIÓN FINAL: ¿SE ENCONTRÓ EL PAGO?
    // ---------------------------------------------------------------
    if (!$paymentIntent) {
        // Caso especial: Si es una recarga y ya marcamos este pago como procesado
        if ($session->get('payment_processed_'.$paymentIntentId)) {
            // Lanza una excepción con código 200 (manejada después como recarga segura)
            throw new \Exception("Recarga detectada", 200);
        }
        // Error general si no se encontró el pago
        throw new \Exception("No se encontró información de pago válida");
    }

    return $paymentIntent;
}

/**
 * MÉTODO QUE OBTIENE PAYMENTINTENT PARA PAGOS CON PAYPAL
 * 
 * Busca en:
 * 1. Sesión PHP (cache local)
 * 2. API de Stripe (fuente primaria)
 * 
 * session - Instancia de sesión CI
 * checkoutSessionId - ID de sesión de Checkout
 * 
 */
private function getPayPalPaymentIntent($session, $checkoutSessionId)
{
    // Registra en el log para diagnóstico (nivel info)
    log_message('info', "Procesando PayPal. Session ID: {$checkoutSessionId}");
    
    // ---------------------------------------------------------------
    // INTENTO 1: BUSCAR EN SESIÓN PHP (CACHE LOCAL)
    // ---------------------------------------------------------------
    // Busca datos previamente guardados en la sesión con la clave única
    // Ejemplo: paypal_session_cs_test_123
    $sessionData = $session->get('paypal_session_'.$checkoutSessionId);
    
    // Si existe y tiene payment_intent_id válido...
    if ($sessionData && !empty($sessionData['payment_intent_id'])) {
        // Recupera el PaymentIntent directamente desde Stripe
        $paymentIntent = \Stripe\PaymentIntent::retrieve($sessionData['payment_intent_id']);
        
        // Registra éxito en log (nivel debug para detalles técnicos)
        log_message('debug', 'PaymentIntent recuperado desde sesión PHP');
        
        return $paymentIntent; // Retorna tempranamente si tuvo éxito
    }

    // ---------------------------------------------------------------
    // INTENTO 2: BUSCAR EN API DE STRIPE (FUENTE PRINCIPAL)
    // ---------------------------------------------------------------
    // Si no se encontró en sesión, consulta directamente a Stripe
    $checkoutSession = \Stripe\Checkout\Session::retrieve([
        'id' => $checkoutSessionId, // ID de la sesión de checkout
        'expand' => ['payment_intent'] // Incluye el PaymentIntent asociado
    ]);

    // Validación 1: ¿Existe la sesión en Stripe?
    if (!$checkoutSession) {
        throw new \Exception("Sesión de PayPal no encontrada");
    }

    // Validación 2: ¿Tiene un PaymentIntent vinculado?
    if (empty($checkoutSession->payment_intent)) {
        throw new \Exception("No hay PaymentIntent asociado a PayPal");
    }

    // ---------------------------------------------------------------
    // POST-PROCESAMIENTO
    // ---------------------------------------------------------------
    // Guarda el ID en sesión PHP para futuras recargas
    $session->set('temp_payment_id', $checkoutSession->payment_intent->id);
    
    // Retorna el PaymentIntent completo desde Stripe
    return $checkoutSession->payment_intent;
}

/**
 * MÉTODO QUE VERIFICA SI UN PAGO YA FUE PROCESADO
 * 
 * Consulta la base de datos para evitar:
 * - Procesamiento duplicado
 * - Cargos múltiples
 * 
 * - paymentIntent - Payment Intent que se va a buscar para ver si ya está existente en la base de datos
 */

private function esPagoDuplicado($paymentIntent)
{
    // ---------------------------------------------------------------
    // 1. SELECCIÓN DEL MODELO SEGÚN TIPO DE RESERVA
    // ---------------------------------------------------------------
    // Determina si usará CarreraModel o EventoModel basado en los metadatos
    $modelType = ($paymentIntent->metadata->tipo === 'carrera') 
        ? new CarreraModel()  // Modelo para reservas de carreras
        : new ReservasEventosModel();  // Modelo para reservas de eventos

    // ---------------------------------------------------------------
    // 2. BÚSQUEDA DE PAGO DUPLICADO
    // ---------------------------------------------------------------
    // Consulta en la base de datos si ya existe una reserva con este payment_intent_id
    $registroExistente = $modelType->where('payment_intent_id', $paymentIntent->id)->first();

    // ---------------------------------------------------------------
    // 3. VALIDACIÓN Y RESPUESTA
    // ---------------------------------------------------------------
    if ($registroExistente) {
        // Registra en el log con nivel NOTICE (para alertas importantes pero no críticas)
        log_message('notice', "Pago duplicado detectado. ID: {$paymentIntent->id}");
        return true; // Indica que es un duplicado
    }

    return false; // Indica que no es duplicado
}

/**
 * MÉTODO QUE VALIDA EL ESTADO ACTUAL DEL PAGO
 * 
 * Verifica que el pago tenga estado 'succeeded' y:
 * 1. Consulta el estado actualizado si es necesario
 * 2. Lanza excepción si el pago no está completado
 * 
 * paymentIntent - Objeto de Stripe que representa 
 *         el intento de pago con todos sus metadatos y estado actual.
 *         Contiene:
 *         - ID único de la transacción
 *         - Estado actual (succeeded, requires_action, etc.)
 *         - Método de pago usado
 *         - Monto y moneda
 *         - Metadatos personalizados de la reserva
 */

private function validarEstadoPago($paymentIntent)
{
    // ---------------------------------------------------------------
    // 1. PRIMERA VERIFICACIÓN DEL ESTADO
    // ---------------------------------------------------------------
    // Revisa si el estado actual del pago NO es 'succeeded' (éxito)
    if ($paymentIntent->status !== 'succeeded') {
        
        // -----------------------------------------------------------
        // 2. ACTUALIZACIÓN DEL ESTADO (DOBLE VERIFICACIÓN)
        // -----------------------------------------------------------
        // Vuelve a consultar el estado directamente a Stripe
        // para evitar usar datos cacheados o desactualizados
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntent->id);
        
        // -----------------------------------------------------------
        // 3. VALIDACIÓN FINAL
        // -----------------------------------------------------------
        // Si después de actualizar sigue sin ser 'succeeded'
        if ($paymentIntent->status !== 'succeeded') {
            // Lanza una excepción con detalles del estado actual
            throw new \Exception("Pago no completado. Estado: {$paymentIntent->status}");
        }
    }
    
    // Si pasa ambas validaciones, el método termina silenciosamente
    // indicando que el pago es válido
}

/**
 * MÉTODO PARA REGISTRAR LA RESERVA EN LA BASE DE DATOS
 * 
 * Maneja transacción para:
 * - Reservas de carrera
 * - Reservas de evento
 * 
 * db Conexión a base de datos
 * paymentIntent - Objeto de Stripe que representa 
*                  el objeto del pago con todos sus metadatos y estado actual.
*                  Contiene:
*                - ID único de la transacción
*                - Estado actual (succeeded, requires_action, etc.)
*                - Método de pago usado
*                - Monto y moneda
*                - Metadatos personalizados de la reserva
 * @throws \Exception Si falla la transacción
 */

private function procesarReserva($db, $paymentIntent)
{
    $db->transStart(); // Inicia transacción para integridad de datos

    // ---------------------------------------------------------------
    // 1. PREPARAR DATOS SEGÚN TIPO DE RESERVA
    // ---------------------------------------------------------------
    if ($paymentIntent->metadata->tipo === 'carrera') {
        // ============================================================
        // ESTRUCTURA PARA CARRERAS
        // ============================================================
        // FORMO EL OBJETO QUE VOY A NECESITAR PARA HACER EL INSERT DE LA CARRERA
        $reservaData = [
            'user_id' => $paymentIntent->metadata->user_id,
            'id_pistas' => $paymentIntent->metadata->id_pistas,
            'fecha' => $paymentIntent->metadata->fecha,
            'franja_horaria_id' => $paymentIntent->metadata->franja_horaria_id,
            'num_participantes' => $paymentIntent->metadata->num_participantes,
            'amount' => $paymentIntent->metadata->amount,
            'payment_intent_id' => $paymentIntent->id,
            'metodo_pago' => $paymentIntent->metadata->metodo_pago,
            'pista_nombre' => $paymentIntent->metadata->pista_nombre ?? null,
            'horario_texto' => $paymentIntent->metadata->horario_texto ?? null,
            'fecha_pago' => date('Y-m-d H:i:s')
        ];
        // GUARDO LA CARRERA EN LA BASE DE DATOS EN ESTE MÉTODO
        $this->guardarReservaCarrera(
            $reservaData,
            $paymentIntent->id,
            $paymentIntent->metadata->metodo_pago
        );
    } else {
        // SI ES UN EVENTO, CREO EL OBJETO DE LA RESERVA DEL EVENTO Y LO INSERTO
        // ============================================================
        // ESTRUCTURA PARA RESERVA DE EVENTOS
        // ============================================================
        // CREACIÓN DEL OBJETO
        $reservaData = [
            'user_id'        => $paymentIntent->metadata->user_id,
            'evento_id'      => $paymentIntent->metadata->evento_id,
            'fecha_evento'   => $paymentIntent->metadata->fecha, // o fecha_evento si lo mandas así
            'cantidad'       => $paymentIntent->metadata->num_participantes,
            'amount'         => $paymentIntent->metadata->amount,
            'franja_horaria_id' => $paymentIntent->metadata->franja_horaria_id, // si lo necesitas
            'num_participantes' => $paymentIntent->metadata->num_participantes
        ];        
        // INSERCIÓN DEL OBJETO DE RESERVA EVENTO EN LA BASE DE DATOS EN ESTE MÉTODO
        $this->guardarReservaEvento(
            $reservaData,
            $paymentIntent->id,
            $paymentIntent->metadata->metodo_pago
        );
        
    }

    $db->transCommit(); // Confirma la transacción
}

/**
 * MÉTODO PARA LIMPIAR DATOS TEMPORALES DE SESIÓN
 * 
 * Elimina:
 * 1. IDs de pago temporales
 * 2. Datos de reserva temporales
 * 3. Datos específicos de PayPal
 * 
 * session - Instancia de sesión
 * isPaypal - Indica si es pago PayPal
 * checkoutSessionId - ID de sesión
 * paymentIntent - Objeto de Stripe que representa 
 *                  el objeto del pago con todos sus metadatos y estado actual.
 *                  Contiene:
 *                - ID único de la transacción
 *                - Estado actual (succeeded, requires_action, etc.)
 *                - Método de pago usado
 *                - Monto y moneda
 *                - Metadatos personalizados de la reserva
 */
private function limpiarSesionPostPago($session, $isPaypal, $checkoutSessionId, $paymentIntent)
{
    // ---------------------------------------------------------------
    // 1. LIMPIEZA DE DATOS TEMPORALES BÁSICOS
    // ---------------------------------------------------------------
    // Elimina el ID de pago temporal almacenado en sesión
    $session->remove('temp_payment_id');
    // Elimina los datos temporales de la reserva (si existen)
    $session->remove('temp_reserva_data');

    // ---------------------------------------------------------------
    // 2. MARCA EL PAGO COMO PROCESADO (PARA RECARGAS)
    // ---------------------------------------------------------------
    // Crea una bandera (variable booleana) específica para este pago
    $session->set('payment_processed_'.$paymentIntent->id, true);

    // ---------------------------------------------------------------
    // 3. LIMPIEZA ESPECÍFICA PARA PAYPAL
    // ---------------------------------------------------------------
    if ($isPaypal && $checkoutSessionId) {
        $session->remove('paypal_session_'.$checkoutSessionId);
    }
}

/**
 * MÉTODO PARA LIMPIAR DATOS TEMPORALES DE SESIÓN
 * 
 * Elimina:
 * 1. IDs de pago temporales
 * 2. Datos de reserva temporales
 * 3. Datos específicos de PayPal
 * 
 * session - Instancia de sesión
 * isPaypal - Indica si es pago PayPal
 * checkoutSessionId - ID de sesión
 * paymentIntent - Objeto de Stripe que representa 
 *                  el objeto del pago con todos sus metadatos y estado actual.
 *                  Contiene:
 *                - ID único de la transacción
 *                - Estado actual (succeeded, requires_action, etc.)
 *                - Método de pago usado
 *                - Monto y moneda
 *                - Metadatos personalizados de la reserva
 * MÉTODO REFERENCIADO EN EL JS DE COMPLETADO, SE UTILIZA PARA ELIMINAR TODOS LOS
 * DATOS QUE HAYAN DE LA RESERVA.
 */
public function limpiarSesionResidual()
{
    $session = session();
    
    // ---------------------------------------------------------------
    // 1. ELIMINAR VARIABLES ESPECÍFICAS DIRECTAMENTE
    // ---------------------------------------------------------------
    $session->remove('temp_payment_id');
    $session->remove('temp_reserva_data');

    // ---------------------------------------------------------------
    // 2. ELIMINAR POR PATRONES (MÁS EFICIENTE)
    // ---------------------------------------------------------------
    // Actualizado para incluir prefijo de Amazon Pay
    $prefixes = ['paypal_session_', 'payment_processed_'];
    $sessionKeys = array_keys($_SESSION);
    
    foreach ($prefixes as $prefix) {
        foreach ($sessionKeys as $key) {
            if (strpos($key, $prefix) === 0) {
                $session->remove($key);
            }
        }
    }
}

/**
 * MÉTODO PARA MANEJAR ERRORES POR RECARGA DE PÁGINA
 * 
 * Distingue entre:
 * 1. Recargas válidas (pago ya procesado)
 * 2. Errores genuinos
 * 
 * e - Excepción original
 * session - Instancia de sesión
 * paymentIntentId - ID del pago
 * 
 */

private function manejarErrorRecarga($e, $session, $paymentIntentId)
{
    // ---------------------------------------------------------------
    // 1. DETECCIÓN DE ERRORES POR RECARGA VÁLIDA
    // ---------------------------------------------------------------
    // Condición 1: Verifica si el mensaje de error contiene "No se encontró información de pago válida"
    // Condición 2: Comprueba si existe una bandera (variable booleana) que indique que el pago ya fue procesado
    if (strpos($e->getMessage(), 'No se encontró información de pago válida') !== false && 
        $session->get('payment_processed_'.$paymentIntentId)) {
        
        // Si ambas condiciones se cumplen:
        // - Es una recarga legítima (el pago ya se procesó anteriormente)
        // - Muestra la vista de éxito sin reprocesar
        return $this->mostrarVistaExito(null, true);
    }

    // ---------------------------------------------------------------
    // 2. MANEJO DE ERRORES GENÉRICOS
    // ---------------------------------------------------------------
    // Si no es una recarga válida, redirige a la página de fallo
    // con un código de error genérico "processing_error"
    return redirect()->to('pago/fallo?error=processing_error');
}

/**
 * MÉTODO PARA MOSTRAR LA VISTA DE PAGO EXITOSO
 * 
 * paymentIntent - Objeto de Stripe que representa 
 *                  el objeto del pago con todos sus metadatos y estado actual.
 *                  Contiene:
 *                - ID único de la transacción
 *                - Estado actual (succeeded, requires_action, etc.)
 *                - Método de pago usado
 *                - Monto y moneda
 *                - Metadatos personalizados de la reserva
 * 
 * duplicado - Indica si es recarga
 * 
 * return Vista con datos:
 * - success: Estado
 * - duplicado: Bandera (variable booleana) recarga
 * - payment_intent_id: ID del pago
 * - metodo_pago: card/paypal
 * - amount: Monto en euros
 * - fecha_pago: Fecha formateada
 * - pista_nombre: Nombre de pista (carreras)
 */
private function mostrarVistaExito($paymentIntent, $duplicado = false)
{
    return view('pasarelapago/completado', [
        'success' => true,
        'duplicado' => $duplicado,
        'payment_intent_id' => $paymentIntent->id,
        'metodo_pago' => $paymentIntent->metadata->metodo_pago,
        'amount' => $paymentIntent->metadata->amount / 100,
        'fecha_pago' => date('d/m/Y H:i'),
        'pista_nombre' => $paymentIntent->metadata->pista_nombre ?? 'No especificado'
    ]);
}

/**
 * MÉTODO PARA MOSTRAR VISTA DE ERROR DE PAGO
 * 
 * Proporciona:
 * - Mensajes de error específicos
 * - Códigos para diagnóstico
 * - Opciones de reintento cuando aplica
 * 
 */

public function fallo()
{
    // AL PRODUCIRSE UN FALLO, LIMPIO LOS DATOS DE LA RESERVA
    $this->limpiarSesionResidual();

    // SE OBTIENEN LAS VARIABLES DEL ERROR
    $errorCode = $this->request->getGet('error');
    $paymentId = $this->request->getGet('payment_id');
    $stripeCode = $this->request->getGet('code');
    
    // POSIBLES MENSAJES DE ERROR
    $errorMessages = [
        // Errores generales
        'payment_not_succeeded' => 'El pago no se completó correctamente',
        'verification_error' => 'Error al verificar el estado del pago',
        'payment_cancelled' => 'Pago cancelado por el usuario',
        'default' => 'Ocurrió un error durante el proceso de pago',
        // Errores de tarjeta
        'card_declined' => 'Tarjeta rechazada',
        'expired_card' => 'Tarjeta vencida',
        'insufficient_funds' => 'Fondos insuficientes',
        'authentication_required' => 'Autenticación fallida',
        'incomplete_number' => 'Tarjeta incompleta, faltan datos',
        'incomplete_zip' => 'Tarjeta incompleta, faltan datos',
        '3ds_authentication_failed' => 'Autenticación 3D Secure fallida o cancelada',
        // Errores de PayPal
        'paypal_cancelled' => 'Has cancelado el pago con PayPal',
        'paypal_redirect_failed' => 'Error al redirigir a PayPal',
        'paypal_amount_invalid' => 'El monto no es válido para PayPal',
        'user_cancelled' => 'Cancelaste el pago en PayPal',
        // Errores de Stripe
        'stripe_error' => 'Error en el procesamiento del pago',
    ];

    // Mensaje de error principal
    $errorMessage = $errorMessages[$errorCode] ?? $errorMessages['default'];
    
    // Añadir código Stripe si está disponible
    if ($errorCode === 'stripe_error' && $stripeCode) {
        $errorMessage .= " (Código: {$stripeCode})";
    }

    $data = [
        'error' => $errorMessage,
        'error_code' => $errorCode,
        'payment_id' => $paymentId,
        'stripe_code' => $stripeCode,
        'is_paypal_error' => strpos($errorCode, 'paypal_') === 0
    ];
    
    return view('pasarelapago/fallo', $data);
}


/**
 * MÉTODO PARA GUARDAR LA RESERVA DE CARRERA EN BASE DE DATOS
 * 
 * Validaciones:
 * 1. Campos obligatorios
 * 2. Formato de fechas
 * 3. Métodos de pago permitidos
 * 4. Límites temporales (2 años)
 * 
 * data - Datos de reserva
 * paymentId - ID de pago Stripe
 * metodoPago - card/paypal
 * 
 */
    private function guardarReservaCarrera(array $data, string $paymentId, string $metodoPago): bool
    {
        // 1. CAMPOS OBLIGATORIOS
        $requiredFields = [
            'user_id', 
            'id_pistas', 
            'fecha', 
            'franja_horaria_id', 
            'num_participantes',
            'amount'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \InvalidArgumentException("Campo requerido faltante: {$field}");
            }
        }
    
        // 2. VALIDACIÓN DEL PAYMENT INTENT ID
        if (empty($paymentId)) {
            throw new \InvalidArgumentException("ID de pago de Stripe inválido");
        }
    
        // 3. VALIDACIÓN DEL MÉTODO DE PAGO
        $metodosPermitidos = ['card', 'paypal'];
        if (!in_array(strtolower($metodoPago), $metodosPermitidos)) {
            throw new \InvalidArgumentException("Método de pago no soportado: {$metodoPago}");
        }
    
        // 4. VALIDACIÓN DE FECHAS (QUE HAYAN MENOS DE 2 AÑOS PARA LA RESERVA, SINO, SE GENERA UN ERROR)
        $fechaReserva = DateTime::createFromFormat('Y-m-d', $data['fecha']);
        $fechaMaxima = (new DateTime())->modify('+2 years');
        
        if ($fechaReserva > $fechaMaxima) {
            throw new \RuntimeException('No se pueden hacer reservas con más de 2 años de antelación');
        }
    
        // 5. ASIGNACIÓN DEL EMPLEADO DESIGNADO PARA LA CARRERA
        $empleadoModel = new EmpleadoCarrerasModel();
        $empleadoId = $empleadoModel->obtenerEmpleadoCarrerasDisponible($data['fecha'])['id'];
        
        if (!$empleadoId) {
            throw new \RuntimeException('No hay empleados disponibles para esta fecha');
        }
    
        // 6. PREPARO LOS DATOS PARA HACER LA INSERCIÓN DE LA CARRERA EN LA BASE DE DATOS
        $insertData = [
            'id_usuario'       => (int)$data['user_id'],
            'empleado_id'      => (int)$empleadoId,
            'id_pistas'        => (int)$data['id_pistas'],
            'fecha'            => $data['fecha'],
            'franja_horaria_id'=> (int)$data['franja_horaria_id'],
            'num_participantes'=> (int)$data['num_participantes'],
            'cantidad'         => (float)($data['amount'] / 100), // Convertir a euros
            'metodo_pago'      => $metodoPago, // Usamos el parámetro recibido
            'fecha_pago'       => date('Y-m-d'),
            'pagado'       => 1,
            'payment_intent_id'=> $paymentId
        ];
    
        // 7. INSERTAR EN LA BASE DE DATOS
        $model = new CarreraModel();
        
        if (!$model->insert($insertData)) {
            log_message('error', 'Error al insertar reserva: ' . print_r($model->errors(), true));
            throw new \RuntimeException('Error al guardar la reserva en la base de datos');
        }
    
        return true;
    }

    /**
 * MÉTODO PARA GUARDAR LA RESERVA DE EVENTO EN BASE DE DATOS
 * 
 * Similar a guardarReservaCarrera pero para eventos
 * 
 * data - Datos específicos de evento
 * paymentId - ID de pago Stripe
 * metodoPago - card/paypal
 * 
 */
    private function guardarReservaEvento(array $data, string $paymentId, string $metodoPago): bool
{
    // 1. VALIDACIÓN DE CAMPOS OBLIGATORIOS
    $requiredFields = [
        'user_id', 
        'evento_id', 
        'cantidad', 
        'amount'
    ];
    
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new \InvalidArgumentException("Campo requerido faltante: {$field}");
        }
    }

    // 2. VALIDACIÓN DEL PAYMENT INTENT ID
    if (empty($paymentId)) {
        throw new \InvalidArgumentException("ID de pago de Stripe inválido");
    }

    // 3. VALIDACIÓN DEL MÉTODO DE PAGO
    $metodosPermitidos = ['card', 'paypal']; 
    if (!in_array(strtolower($metodoPago), $metodosPermitidos)) {
        throw new \InvalidArgumentException("Método de pago no soportado: {$metodoPago}");
    }

    // 4. PREPARO LOS DATOS PARA HACER LA INSERCIÓN DE LA RESERVA DEL EVENTO EN LA BASE DE DATOS
    $insertData = [
        'usuario_id'        => (int)$data['user_id'],
        'evento_id'         => (int)$data['evento_id'],
        'cantidad'          => (int)$data['num_participantes'],
        'total'             => (float)($data['amount'] / 100),  // Convertir a euros
        'metodo_pago'       => $metodoPago, // Usamos el parámetro recibido
        'fecha_pago'        => date('Y-m-d'),  // Fecha de pago actual
        'pagado'       => 1,
        'payment_intent_id' => $paymentId
    ];

    // 5. Insertar en la base de datos utilizando el modelo ReservasEventosModel
    $reservasEventosModel = new ReservasEventosModel();

    if (!$reservasEventosModel->insert($insertData)) {
        log_message('error', 'Error al insertar reserva: ' . print_r($reservasEventosModel->errors(), true));
        throw new \RuntimeException('Error al guardar la reserva en la base de datos');
    }

    return true;
}

}