<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Services\CorreoService;
use App\Models\UsuarioModel;

class CorreoController extends ResourceController
{
    protected $correoService;
    protected $usuarioModel;

    public function __construct()
    {
        $this->correoService = new CorreoService();
        $this->usuarioModel = new UsuarioModel();
    }

    // MÉTODO USADO EN UN MONTÓN DE JS PARA ENVIAR CORREOS, ENTRE LOS JS ESTÁN
    // cambiarCredencialesEmpleado, gestionCarreras (admin), nuevoCarrerasEmpleado (empleado),
    // gestionEmpleados, 
    public function enviar($inputData = null)
    {
        // Usar datos proporcionados como parámetro o JSON desde la request
        if (is_array($inputData)) {
            $data = $inputData;
        } else {
            $data = $this->request->getJSON(true);
        }

        // Enviar el correo a través del servicio
        $resultado = $this->correoService->enviar($data);

        // Devolver la respuesta adecuada
        if ($resultado['success']) {
            return $this->respond($resultado);
        } else {
            $status = isset($resultado['errors']['email']) ? 400 : 500;
            return $this->respond($resultado, $status);
        }
    }

    // MÉTODO USADO EN EL JS DE contactoVista PARA ENVIAR CORREOS AL ADMIN DE DUDAS DE LOS USUARIOS
    // SE SEPARA PORQUE ESTE CORREO SE QUIERE HACER LLEGAR SOLO AL ADMIN
    public function enviarContacto()
{
    // Obtener los datos del formulario (esperados como JSON)
    $data = $this->request->getJSON(true);

    // Llamar al método especializado para contacto
    $resultado = $this->correoService->enviarContacto($data);

    // Devolver la respuesta adecuada
    if ($resultado['success']) {
        return $this->respond($resultado);
    } else {
        $status = isset($resultado['errors']['email']) ? 400 : 500;
        return $this->respond($resultado, $status);
    }
}

    /*
    MÉTODO REFERENCIADO DESDE js de recuperarContraseña, este se encarga de crear el token para el cambio de contraseña,
    poner de límite 1 hora de tiempo de expiración, y enviar un correo al usuario para que tenga un botón desde el que,
    al hacer click, que sea redirigido a otra pantalla donde poner otra contraseña
    Controlador: CorreoController, Método: soliticitarRecuperación 
    */
    public function solicitarRecuperacion()
{
    // Establecer zona horaria española (aunque no se usará por date(), se mantiene por coherencia)
    date_default_timezone_set('Europe/Madrid');

    // Obtener datos JSON de la solicitud
    $data = $this->request->getJSON(true);
    
    // Validar que existe el campo email
    if (!isset($data['correo']) || empty($data['correo'])) {
        return $this->respond([
            'success' => false,
            'message' => 'El campo correo es requerido'
        ], 400);
    }

    $correo = $data['correo'];

    // Buscar usuario por email
    $usuario = $this->usuarioModel->where('email', $correo)->first();

    // Generar token de recuperación (aunque el email no exista, por seguridad)
    $token = bin2hex(random_bytes(32));

    // Corregido: calcular la hora con timezone explícito
    $zonaHorariaMadrid = new \DateTimeZone('Europe/Madrid');
    $fechaExpiracion = new \DateTime('now', $zonaHorariaMadrid);
    $fechaExpiracion->modify('+1 hour');
    $expiracion = $fechaExpiracion->format('Y-m-d H:i:s');

    if ($usuario) {
        // Actualizar usuario con token y expiración
        $this->usuarioModel->update($usuario['id'], [
            'token_recuperacion' => $token,
            'expiracion_token' => $expiracion
        ]);

        // Crear enlace de recuperación
        $enlace = base_url("restablecer-contrasena?token=$token");

        // Preparar datos para el correo
        $datosCorreo = [
            'email' => $correo,
            'nombre' => $usuario['nombre'] ?? 'Usuario',
            'asunto' => 'Recuperación de contraseña - AleKarting',
            'mensaje' => $this->crearMensajeRecuperacion($usuario['nombre'] ?? 'Usuario', $enlace)
        ];

        // Enviar correo usando el servicio existente
        $resultado = $this->correoService->enviar($datosCorreo);

        if (!$resultado['success']) {
            log_message('error', 'Error al enviar correo de recuperación: ' . $resultado['message'] ?? '');
        }
    }

    // Siempre devolver éxito (por seguridad no revelar si el email existe)
    return $this->respond([
        'success' => true,
        'message' => 'Si el email existe en nuestro sistema, recibirás un enlace de recuperación'
    ]);
}

   /*
    MÉTODO REFERENCIADO AL HACER CLICK DESDE EL CORREO AL INTENTAR RESTABLECER LA CONTRASEÑA,
    EN CASO DE HACER CLICK, Y QUE NO HAYA EXPIRADO EL TIEMPO PARA CAMBIAR LA CONTRASEÑA, SE
    REDIRIGE A LA PANTALLA DE CAMBIO DE CONTRASEÑA
    Controlador: CorreoController, Método: mostrarFormularioRestablecer
    */
public function mostrarFormularioRestablecer()
{
    date_default_timezone_set('Europe/Madrid');

    $token = $this->request->getGet('token');
    
    if (empty($token)) {
        return redirect()->to(base_url('Iniciar_Sesion'))->with('error', 'Token inválido');
    }

    // Verificar si el token existe y no ha expirado
    $usuario = $this->usuarioModel
        ->where('token_recuperacion', $token)
        ->where('expiracion_token >', date('Y-m-d H:i:s'))
        ->first();

    if (!$usuario) {
        return redirect()->to(base_url('Iniciar_Sesion'))->with('error', 'El enlace ha expirado o no es válido');
    }

    // Cambia $usuario->email por $usuario['email'] ya que es un array
    return view('usuario/restablecerContrasena', [
        'token' => $token,
        'email' => $usuario['email'] // Acceso como array
    ]);
}

   /*
    MÉTODO REFERENCIADO DESDE EL js de restablecerContraseña, se encarga de
    RESTABLECER LA CONTRASEÑA EN CASO DE SER UNA CONTRASEÑA VÁLIDA Y ESTAR
    DENTRO DEL TIEMPO PERMITIDO
    Controlador: CorreoController, Método: restablecerContrasena
    */
   public function restablecerContrasena()
{
    date_default_timezone_set('Europe/Madrid');

    $data = $this->request->getJSON(true);

    // Validar campos requeridos
    if (!isset($data['token']) || !isset($data['nueva_contrasena'])) {
        return $this->respond([
            'success' => false,
            'message' => 'Token y nueva contraseña son requeridos'
        ], 400);
    }

    // Buscar usuario por token válido
    $usuario = $this->usuarioModel
        ->where('token_recuperacion', $data['token'])
        ->where('expiracion_token >', date('Y-m-d H:i:s'))
        ->first();

    if (!$usuario) {
        return $this->respond([
            'success' => false,
            'message' => 'El enlace de recuperación no es válido o ha expirado'
        ], 400);
    }

    // Validar fortaleza de contraseña (ejemplo mínimo)
    if (strlen($data['nueva_contrasena']) < 10) {
        return $this->respond([
            'success' => false,
            'message' => 'La contraseña debe tener al menos 10 caracteres'
        ], 400);
    }

     // Actualizar contraseña
    $this->usuarioModel->update($usuario['id'], [
        'contraseña' => md5($data['nueva_contrasena']),
        'token_recuperacion' => null,
        'expiracion_token' => null
    ]);

    // Devolver además los datos del usuario para el correo
    return $this->respond([
        'success' => true,
        'message' => 'Contraseña actualizada correctamente',
        'email' => $usuario['email'], // Asegúrate que este campo existe
        'nombre' => $usuario['nombre'] // Asegúrate que este campo existe
    ]);
}

    /**
     * Crea el mensaje HTML para el correo de recuperación
     */
        private function crearMensajeRecuperacion($nombre, $enlace)
    {
        $logoUrl = 'https://alejandrojimenez.com.es/imagenLogo/logo_karting.png';
        
        return '
        <div style="
            font-family: \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        ">
            <!-- Encabezado con gradiente -->
            <div style="
                background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
                color: white;
                padding: 30px;
                text-align: center;
                border-bottom: 4px solid #3730a3;
            ">
                <img src="'.$logoUrl.'" alt="Logo AleKarting" style="
                    max-width: 200px;
                    height: auto;
                    margin: 0 auto;
                    display: block;
                    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
                ">
                <h1 style="
                    margin: 15px 0 0;
                    font-size: 26px;
                    font-weight: 700;
                    letter-spacing: 0.5px;
                    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                ">Recuperación de contraseña</h1>
            </div>
            
            <!-- Cuerpo del mensaje -->
            <div style="padding: 35px 30px;">
                <h2 style="color: #2d3748; margin-top: 0;">Hola '.htmlspecialchars($nombre).',</h2>
                <p style="margin-bottom: 15px; color: #4a5568;">Has solicitado restablecer tu contraseña en AleKarting.</p>
                <p style="margin-bottom: 20px; color: #4a5568;">Haz clic en el siguiente botón para continuar con el proceso:</p>
                
                <div style="text-align: center; margin: 25px 0;">
                    <a href="'.htmlspecialchars($enlace).'" style="
                        display: inline-block;
                        padding: 12px 24px;
                        background: #4f46e5;
                        color: #ffffff !important;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    ">Restablecer contraseña</a>
                </div>
                
                <p style="
                    margin-bottom: 10px;
                    font-size: 14px;
                    color: #64748b;
                ">
                    Si no solicitaste este cambio, puedes ignorar este mensaje.<br>
                    El enlace expirará en 1 hora.
                </p>
            </div>
            
            <!-- Pie de página -->
            <div style="
                background: #f1f5f9;
                padding: 25px 20px;
                text-align: center;
                font-size: 13px;
                color: #64748b;
                border-top: 1px solid #e2e8f0;
            ">
                <p style="margin: 5px 0;">Este mensaje fue enviado desde <a href="https://alejandrojimenez.com.es/proySolvKarAle658/codeigniterKarting/public/" style="color: #4f46e5; text-decoration: none;">AleKarting</a></p>
                <p style="margin: 5px 0;">&copy; '.date('Y').' AleKarting. Todos los derechos reservados.</p>
            </div>
        </div>';
    }
}