<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago - Karting</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/pasarela.css') ?>" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
    <?php include_once(FCPATH . 'assets/libreriasImportar/mainHead.php'); ?>
    <?php include_once(FCPATH . 'assets/libreriasImportar/mainJs.php'); ?>
</head>
<body>
    <?php 
    // Obtenemos los datos de la reserva desde la sesión
    $reserva = session('temp_reserva_data');
    $monto = $reserva['cantidad'] ?? 0;
    ?>
    <script>
    var base_url = "<?= base_url() ?>";
    </script>
<div class="container">
<div class="header position-relative">
  <h1>Finalizar Reserva</h1>
  <p>Seleccione su método de pago preferido</p>

  <!-- Botón de ayuda añadido -->
  <button
    id="btnAyudaPasarela"
    type="button"
    class="btn btn-outline-info"
    title="Ayuda con el pago">
    <i class="bi bi-question-circle"></i>
  </button>
</div>


    <div class="row">
    <!-- Columna izquierda - Detalles de la reserva -->
    <div class="col-md-6">

        <?php if ($reserva['tipo'] === 'evento'): ?>

            <!-- ====== DETALLES DE EVENTO ====== -->
            <div class="reserva-detalle">
                <h5 class="text-primary">Información del evento</h5>
                <p><strong>Evento:</strong> <?= esc($reserva['evento_nombre']) ?></p>
                <p><strong>Tipo de evento:</strong> <?= esc($reserva['tipo_evento']) ?></p> <!-- Muestra el tipo de evento -->
            </div>

            <div class="reserva-detalle">
                <h5 class="text-primary">Fecha y hora</h5>
                <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($reserva['fecha'])) ?></p>
                <p><strong>Horario:</strong> <?= esc($reserva['horario_texto']) ?></p>
                <p><strong>Duración:</strong> 1 hora</p>
            </div>

            <div class="reserva-detalle">
                <h5 class="text-primary">Participantes</h5>
                <p><strong>Número de participantes:</strong> <?= esc($reserva['num_participantes']) ?></p>
                <p><strong>Precio por persona:</strong> <?= number_format($reserva['precio_unitario'], 2) ?> €</p> <!-- Muestra el precio por persona -->
            </div>

            <div class="reserva-detalle">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Total a pagar:</h5>
                    <h4 class="mb-0 text-primary"><?= number_format($reserva['total'], 2) ?> €</h4> <!-- Muestra el total -->
                </div>
            </div>

            <?php elseif ($reserva['tipo'] === 'carrera'): ?>
            <!-- ====== DETALLES DE CARRERA ====== -->
            <div class="reserva-detalle">
                <h5 class="text-primary">Información de la carrera</h5>
                <p><strong>Carrera:</strong> <?= esc($reserva['pista_nombre']) ?></p> <!-- Muestra el nombre de la pista de la carrera -->
                <!-- Eliminada la línea 'Tipo' porque no es necesaria -->
            </div>

            <div class="reserva-detalle">
                <h5 class="text-primary">Fecha y hora</h5>
                <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($reserva['fecha'])) ?></p> <!-- Muestra la fecha de la carrera -->
                <p><strong>Hora:</strong> <?= esc($reserva['horario_texto']) ?></p> <!-- Muestra la hora de la carrera -->
                <p><strong>Duración:</strong> 1 hora</p> <!-- Duración fija de la carrera -->
            </div>

            <div class="reserva-detalle">
                <h5 class="text-primary">Participantes</h5>
                <p><strong>Número de participantes:</strong> <?= esc($reserva['num_participantes']) ?></p> <!-- Muestra el número de participantes -->
                <p><strong>Precio por persona:</strong> <?= number_format($reserva['precio_unitario'], 2) ?> €</p> <!-- Muestra el precio por persona de la carrera -->
            </div>

            <div class="reserva-detalle">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Total a pagar:</h5>
                    <h4 class="mb-0 text-primary"><?= number_format($reserva['total'], 2) ?> €</h4> <!-- Muestra el monto total -->
                </div>
            </div>
        <?php endif; ?>



    </div> <!-- fin col-md-6 izquierda -->

    <!-- Columna derecha - Métodos de pago -->
    <div class="col-md-6">
        <div class="payment-methods">
            <!-- Opción para tarjeta -->
            <div class="payment-method selected" data-method="card">
                <input type="radio" name="payment-method" id="card-method" checked class="d-none">
                <div class="icon card-icon"></div>
                <div class="details">
                    <div class="title">Tarjeta de crédito/débito</div>
                    <div class="description">Pague con Visa, Mastercard o American Express</div>
                </div>
            </div>

            <!-- Opción para PayPal -->
            <div class="payment-method" data-method="paypal">
                <input type="radio" name="payment-method" id="paypal-method" class="d-none">
                <div class="icon paypal-icon"></div>
                <div class="details">
                    <div class="title">PayPal</div>
                    <div class="description">Pago rápido y seguro con tu cuenta PayPal</div>
                </div>
            </div>
        </div>

        <!-- Formulario para tarjeta -->
        <form id="card-form" class="payment-form active">
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
            <button type="submit" id="submit-btn" class="btn btn-primary w-100">
                <span class="button-text">Pagar <?= number_format($reserva['total'], 2) ?> €</span>
                <span class="payment-spinner spinner-border spinner-border-sm hidden"></span>
            </button>
        </form>

        <!-- Formulario para PayPal -->
        <form id="paypal-form" class="payment-form">
            <p>Serás redirigido a PayPal para completar el pago de forma segura.</p>
            <button type="button" id="paypal-btn" class="btn btn-primary w-100">
                <span class="button-text">Pagar con PayPal</span>
                <span class="payment-spinner spinner-border spinner-border-sm hidden"></span>
            </button>
        </form>
    </div> <!-- fin col-md-6 derecha -->

</div> <!-- fin row -->
</div> <!-- fin container -->  
<?= view('pasarelapago/mostrarAyudaPasarela') ?>
    <script>
window.onload = function() {
    // Variables globales necesarias
    let stripe;
    let elementos;
    let tarjeta;

    // CONFIGURACIÓN BÁSICA Y INICIALIZACIÓN DE STRIPE
    function inicializarStripe() {
        // SE DECLARA LA VARIABLE STRIPE, QUE ES LA INSTANCIA DEL PROPIO STRIPE, INICIADA CON LA CLAVE PÚBLICA QUE TENGO GUARDADA EN MI PROYECTO
        // SE ENCARGA DE CREAR LOS MÉTODOS DE PAGO (createPaymentMethod)
        // CONFIRMAR PAGOS (confirmCardPayment)
        // MANEJAR AUTENTIFICACIONES POR 3D SECURE
        // GESTIONAR REDIRECCIONES A PAYPAL
        stripe = Stripe('<?= esc($reserva['publishable_key']) ?>');
        // SE INICIALIZA STRIPE ELEMENTS, QUE ES EL SISTEMA DE COMPONENTES SEGUROS DE STRIPE
        // ELEMENTS, ME PERMITE INSERTAR LOS CAMPOS DE TARJETA, FECHA DE EXPIRACIÓN Y CVC
        // DENTRO DEL FORMULARIO, DE FORMA SEGURA Y CUMPLIENDO CON LAS NORMAS PCI.
        elementos = stripe.elements();
        
        // LE DOY ESTILOS A LA TARJETA QUE VOY A UTILIZAR
        const estilo = {
            base: {
                color: '#32325d',
                fontFamily: '"Roboto", sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': { color: '#aab7c4' }
            },
            invalid: { color: '#e74c3c', iconColor: '#e74c3c' }
        };
        
        // SE CREA Y SE MONTA EL COMPONENTE DE LA TARJETA CON ELEMENTOS, EL SISTEMA DE COMPONENENTES DE STRIPE, ADEMÁS, LLEVA LINK INCORPORADO 
        // YA QUE LO TENGO HABILITADO EN EL STRIPE
        tarjeta = elementos.create('card', { style: estilo });
        tarjeta.mount('#card-element');

        // MANEJO EL CAMBIO DE TARJETA CUANDO EXISTE UN CAMBIO EN LA TARJETA
        tarjeta.addEventListener('change', manejarCambioTarjeta);
    }

        /**
     * MÉTODO PARA MANEJAR POSIBLES ERRORES EN EL CAMPO DE LA TARJETA.
     * STRIPE YA REALIZA UNA VALIDACIÓN EN TIEMPO REAL PARA DETECTAR
     * NÚMEROS DE TARJETA INVÁLIDOS, CVV INCORRECTO, FECHAS EXPIRADAS, ETC.
     * 
     * evento - EVENTO DE CAMBIO GENERADO POR STRIPE ELEMENTS
     */
    function manejarCambioTarjeta(evento) {
        const elementoError = document.getElementById('card-errors');
        elementoError.textContent = evento.error ? evento.error.message : '';
        elementoError.classList.toggle('hidden', !evento.error);
    }

    /**
     * MÉTODO QUE CONFIGURA LOS MANEJADORES DE EVENTOS DE LA PASARELA DE PAGO.
     * GUARDA LOS DOS FORMULARIOS QUE USO (TARJETA Y PAYPAL), Y DEPENDIENDO DEL
     * MÉTODO DE PAGO SELECCIONADO, MUESTRA UNO U OTRO.
     * TAMBIÉN MANEJA LOS EVENTOS DE SUBMIT O CLICK AL PROCESAR EL PAGO.
     */
    function configurarManejadoresEventos() {
         // GUARDO LOS FORMULARIOS DE PAGO (TARJETA Y PAYPAL) EN UNA VARIABLE
        const formulariosPago = [
            $("#card-form"),
            $("#paypal-form")
        ];

         // MANEJO LA VISUALIZACIÓN DEL FORMULARIO SEGÚN EL MÉTODO DE PAGO SELECCIONADO
        document.querySelectorAll('.payment-method').forEach(metodo => {
            metodo.addEventListener('click', function() {
                manejarSeleccionMetodoPago(this, formulariosPago);
            });
        });

        // MANEJO EL EVENTO DE ENVÍO DEL FORMULARIO DE TARJETA
        document.getElementById('card-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await procesarPago('card');
        });

        // MANEJO EL EVENTO DE CLICK DEL BOTÓN DE PAYPAL
        document.getElementById('paypal-btn').addEventListener('click', async function(e) {
            e.preventDefault();
            await procesarPago('paypal');
        });
    }

    /**
     * MÉTODO PARA CAMBIAR VISUALMENTE EL MÉTODO DE PAGO SELECCIONADO
     * Y MOSTRAR EL FORMULARIO CORRESPONDIENTE
     * elementoMetodo - EL BOTÓN QUE HA HECHO CLICK, SE RECOGE SU DATASET MÉTODO PARA SABER QUE METODO ES
     * formulariosPago - UNA LISTA CON LOS FORMULARIOS QUE TENGO
     */
    function manejarSeleccionMetodoPago(elementoMetodo, formulariosPago) {
        // ESCOJO EL MÉTODO CON EL DATASET DEL BOTÓN
        const metodoSeleccionado = elementoMetodo.dataset.method;
        console.log("Método seleccionado:", metodoSeleccionado);

        // QUITO LA ANTERIOR SELECCIÓN DEL BOTÓN QUE HABÍA Y PONGO LA DEL BOTÓN SELECCIONADO ACTUALMENTE
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
        elementoMetodo.classList.add('selected');

        // SE OCULTAN TODOS LOS FORMULARIOS
        formulariosPago.forEach(formulario => formulario.removeClass('active'));

        // ACTIVO EL FORMULARIO QUE CORRESPONDE SEGÚN EL MÉTODO QUE TENGA SELECCIONADO
        const idFormulario = `${metodoSeleccionado}-form`;
        const elementoFormulario = $(`#${idFormulario}`);
        if (elementoFormulario.length) {
            elementoFormulario.addClass('active');
        } else {
            console.error(`Formulario con id ${idFormulario} no encontrado.`);
        }
    }

    /**
     * MÉTODO QUE CONTROLA Y EJECUTA EL PROCESO DE PAGO SEGÚN EL MÉTODO QUE SE HAYA SELECCIONADO
     * metodo - MÉTODO DE PAGO ('card' o 'paypal')
     */
    async function procesarPago(metodo) {
        try {
            // CONFIGURACIÓN DE LA INTERFAZ DE DE PAGO
            const { botonEnvio, textoBoton, spinner, elementoError } = configurarInterfazPago(metodo);
            
            // VALIDO LA CANTIDAD DE DINERO, YA QUE PARA PAYPAL ES NECESARIO
            validarMontoPago(metodo);

            // RECOJO LOS DATOS DE EL EVENTO O CARRERA QUE SE VA A QUERER RESERVAR, LE PASO EL PARÁMETRO DE MÉTODO
            // PARA QUE ME PASE LOS DATOS DE EL EVENTO O DE LA CARRERA
            const datosPago = prepararDatosPago(metodo);

            // SE PROCESA DEPENDIENDO DE SI EL MÉTODO ES TARJETA O PAYPAL DE UNA U OTRA FORMA
            if (metodo === 'card') {
                await procesarPagoTarjeta(datosPago);
            } else if (metodo === 'paypal') {
                await procesarPagoPayPal(datosPago);
            }
        } catch (error) {
            // MANEJAR EL ERROR DE PAGO
            manejarErrorPago(error);
        } finally {
            // RESTAURAR LA INTERFAZ
            restaurarInterfazPago(metodo);
        }
    }

    /**
     * MÉTODO PARA CONFIGURAR LA INTERFAZ DE PAGO
     * metodo - MÉTODO DE PAGO
     */
    function configurarInterfazPago(metodo) {
        // RECOJO LOS DOS BOTONES
        const idsBotones = {
            card: 'submit-btn',
            paypal: 'paypal-btn'
        };

        // COMPRUEBO QUE EXISTEN LOS ELEMENTOS, Y EN CASO NEGATIVO LANZO UN ERROR
        const botonEnvio = document.getElementById(idsBotones[metodo]);
        if (!botonEnvio) throw new Error(`Botón para el método ${metodo} no encontrado.`);

        const textoBoton = botonEnvio.querySelector('.button-text');
        if (!textoBoton) throw new Error("Elemento .button-text no encontrado en el botón.");

        const spinner = botonEnvio.querySelector('.payment-spinner');
        if (!spinner) throw new Error("Elemento .payment-spinner no encontrado en el botón.");

        const elementoError = document.getElementById('card-errors');
        if (!elementoError) throw new Error("Elemento #card-errors no encontrado.");

        // PONGO EL COMPORTAMIENTO INICIAL QUE VAN A TENER LOS ELEMENTOS DE LA INTERFAZ
        botonEnvio.classList.add('processing');
        spinner.classList.remove('hidden');
        textoBoton.style.visibility = 'hidden';
        botonEnvio.disabled = true;
        elementoError.textContent = '';
        elementoError.classList.add('hidden');

        return { botonEnvio, textoBoton, spinner, elementoError };
    }

    /**
     * MÉTODO PARA RESTABLECER LA INTERFAZ DE USUARIO
     * metodo - Método de pago
     */
    function restaurarInterfazPago(metodo) {
        // SI NO SE HA REDIRIGIDO AL PAGO, SE RESTABLECE EL ESTADO INICIAL QUE TENÍAN LOS ELEMENTOS EN
        // LA INTERFAZ DEL USUARIO DESDE UN INICIO
        if (!window.location.href.includes('pago/')) {
            const idsBotones = {
                card: 'submit-btn',
                paypal: 'paypal-btn'
            };
            
            const botonEnvio = document.getElementById(idsBotones[metodo]);
            if (!botonEnvio) return;

            const textoBoton = botonEnvio.querySelector('.button-text');
            const spinner = botonEnvio.querySelector('.payment-spinner');

            try {
                botonEnvio.classList.remove('processing');
                if (spinner) spinner.classList.add('hidden');
                if (textoBoton) textoBoton.style.visibility = 'visible';
                botonEnvio.disabled = false;
            } catch (e) {
                console.error('Error restaurando UI:', e);
            }
        }
    }

    /**
     * MÉTODO PARA VALIDAR QUE EN CASO DE QUE EL MÉTODO USADO SEA PAYPAL,
     * QUE EL MONTO (CANTIDAD DE DINERO) SEA SUPERIOR A 50
     * metodo - Método de pago
     */
    function validarMontoPago(metodo) {
        const monto = <?= (int)($reserva['monto'] * 100) ?>;
        if ((metodo === 'paypal') && (monto < 50 || monto > 2500000)) {
            throw new Error(`${metodo}_amount_invalid`);
        }
    }

    /**
     * MÉTODO PARA PREPARAR LOS DATOS DE LA RESERVA, TENIENDO EN CUENTA QUE
     * SERÁN UNOS DATOS O OTROS DEPENDIENDO DE SI EL TIPO ES CARRERA O
     * ES EVENTO
     * metodo - Método de pago
     */
    function prepararDatosPago(metodo) {
        // Datos comunes del pago
        const datosPago = {
            amount: <?= (int)($reserva['monto'] * 100) ?>,
            tipo: <?= json_encode($reserva['tipo'] ?? 'carrera') ?>,
            metodo_pago: metodo,
            fecha: <?= json_encode($reserva['fecha'] ?? '') ?>,
            franja_horaria_id: <?= $reserva['franja_horaria_id'] ?? 0 ?>,
            num_participantes: <?= $reserva['num_participantes'] ?? 0 ?>,
            horario_texto: <?= json_encode($reserva['horario_texto'] ?? '') ?>,
            precio_unitario: <?= $reserva['precio_unitario'] ?? 0 ?>,
        };

        // Agregar campos específicos dependiendo del tipo
        if (datosPago.tipo === 'carrera') {
            datosPago.id_pistas = <?= $reserva['id_pistas'] ?? 0 ?>;
            datosPago.pista_nombre = <?= json_encode($reserva['pista_nombre'] ?? '') ?>;
        } else if (datosPago.tipo === 'evento') {
            datosPago.evento_id = <?= $reserva['evento_id'] ?? 0 ?>;
            datosPago.cantidad = <?= $reserva['cantidad'] ?? 0 ?>;
            datosPago.evento_nombre = <?= json_encode($reserva['evento_nombre'] ?? '') ?>;
            datosPago.tipo_evento = <?= json_encode($reserva['tipo_evento'] ?? '') ?>;
        }

        return datosPago;
    }

    /**
     * MÉTODO PARA PROCESAR EL PAGO DE TARJETA DE CRÉDITO
     * datosPago - Datos del pago
     */
    async function procesarPagoTarjeta(datosPago) {
        // CREO EL MÉTODO DE PAGO PARA LA TARJETA
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: tarjeta
        });
        // SI HAY ERROR AL CREARLO, LANZO UN ERROR PARA DESPUÉS LANZARLO
        // Y CARGAR UNA PANTALLA PONIENDO LA INFORMACIÓN DEL ERROR
        if (error) {
    const erroresDeTarjeta = ['incomplete_number','incomplete_expiry','incomplete_cvc','incorrect_cvc','invalid_number','postal_code_missing','postal_code_invalid'];

    // Si el código del error está en la lista, mostramos el mensaje y no lanzamos error
    if (erroresDeTarjeta.includes(error.code)) {
        const elementoError = document.getElementById('card-errors');
        if (elementoError) {
            elementoError.textContent = error.message;
            elementoError.classList.remove('hidden');
        }
        return; // Salir sin lanzar error
    }

    // Por si el código no está definido pero el mensaje es el de código postal
    if (error.message === "Tu código postal está incompleto.") {
        const elementoError = document.getElementById('card-errors');
        if (elementoError) {
            elementoError.textContent = error.message;
            elementoError.classList.remove('hidden');
        }
        return; // Salir sin lanzar error
    }

    // Otros errores los lanzamos para manejo externo
    throw error;
}



        // ME GUARDO EL PAYMENT METHOD ID QUE HA GENERADO EL CREATEPAYMENTMETHOD EN 
        // CASO DE QUE NO HAYAN ERRORES
        datosPago.payment_method_id = paymentMethod.id;

        // ENVIO LOS DATOS DE LA TARJETA JUNTO CON EL PAYMENT METHOD ID AL CONTROLADOR DE PAGO
        // RESPUESTA RETORNA INFORMACIÓN DE LA RESERVA, ADEMÁS SE GUARDA EN SESIÓN EL PAYMENT ID:
        
        // EN CASO DE CARRERA:

        //'status',
        //'payment_intent_id'.
        //'client_secret',
        //'requires_action''requires_action',
        //'success',
        
        // EN CASO DE EVENTOS

        //'redirect_url',
        //'session_id',
        //'expires_at',
        //'method'
        const respuesta = await enviarDatosPago(datosPago);

        // EN EL CASO DE QUE LA TARJETA NECESITO CONFIRMACIÓN, HAY QUE CONTROLARLO DE OTRA FORMA DISTINTA
        if (respuesta.requires_action) {
            // SE USA EL MÉTODO CONFIRM CARD PAYMENT
            const { error: errorConfirmacion, paymentIntent } = await stripe.confirmCardPayment(
                respuesta.client_secret,
                { payment_method: datosPago.payment_method_id }
            );
            // EN CASO DE NO CONFIRMAR EL PAGO, SE GENERA UN ERROR
            if (errorConfirmacion) {
                const codigoError = errorConfirmacion.code === 'payment_intent_authentication_failure' 
                    ? '3ds_authentication_failed' 
                    : errorConfirmacion.code;
                // SE REDIRIGE AL USUARIO A LA PANTALLA DE ERROR
                redirigirAFallo(codigoError);
                return;
            }   
            // SI EL PAGO ES EXITOSO SE REDIRIGE A LA PANTALLA DE ÉXITO
            if (paymentIntent.status === 'succeeded') {
                redirigirAExito(paymentIntent.id);
                return;
            }
            // SINO, SE LANZA UN ERROR DE ESTADO INESPERADO
            throw new Error('unexpected_payment_status');
            // SI LA RESPUESTA ES EXITOSA CUANDO EL MÉTODO DE TARJETA NO 
            // REQUIERE DE CONFIRMACIÓN, SE LE REDIRIGE A COMPLETADO
        } else if (respuesta.success) {
            redirigirAExito(respuesta.payment_intent_id);
            return;
        }
        // SINO ES NINGUNO DE LOS DOS CASOS, HAY UN ERROR DE ESTADO INESPERADO
        throw new Error('unexpected_payment_status');
    }

    /**
     * MÉTODO PARA PROCESAR EL PAGO CON PAYPAL
     * datosPago - Datos del pago, toda la información que se va a utilizar
     */
    async function procesarPagoPayPal(datosPago) {
       
        // ENVIO LOS DATOS DE EL PAYPAL JUNTO CON EL PAYMENT METHOD ID AL CONTROLADOR DE PAGO
        // RESPUESTA RETORNA INFORMACIÓN DE LA RESERVA, ADEMÁS SE GUARDA EN SESIÓN EL PAYMENT ID:
        
        // EN CASO DE CARRERA:

        //'status',
        //'payment_intent_id'.
        //'client_secret',
        //'requires_action''requires_action',
        //'success',
        
        // EN CASO DE EVENTOS

        //'redirect_url',
        //'session_id',
        //'expires_at',
        //'method'
        const respuesta = await enviarDatosPago(datosPago);
        // SI LA RESPUESTA QUE HA DADO ENVIARDATOSPAGO TIENE
        // REDIRECT URL, SE REDIRIGE LA PASARELA A LA PLATAFORMA
        // DE PAYPAL, EN CASO DE QUE NO EXISTA, HAY ERROR
        if (respuesta.redirect_url) {
            window.location.href = respuesta.redirect_url;
            return;
        }
        
        throw new Error('paypal_redirect_failed');
    }

    /**
     * MÉTODO PARA ENVIAR LOS DATOS DEL PAGO AL BACKEND
     * datosPago - Datos del pago
     */
    async function enviarDatosPago(datosPago) {
        // SE LLAMA A PAGO PROCESAR, MÉTODO DEL CONTROLADOR PAGOS, ESTE SE
        // ENCARGA DE RETORNAR UN OBJETO CON TODA LA INFORMACIÓN NECESARIA
        // PARA HACER EL PAGO, SEA DE TARJETA O DE PAYPAL, Y DIFERENCIA
        // ENTRE QUE LA INFORMACIÓN SEA DE EVENTO O DE CARRERA
        const respuesta = await fetch('<?= base_url('pago/procesar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(datosPago)
        });

        if (!respuesta.ok) {
            const datosError = await respuesta.json();
            throw new Error(datosError.error || 'payment_error');
        }

        return await respuesta.json();
    }

    /**
     * MÉTODO PARA MANEJAR LOS ERRORES DURANTE EL PROCESO DE PAGO
     * error - Error ocurrido
     */
    function manejarErrorPago(error) {
        console.error('Error en el pago:', error);

        // Mensajes de error específicos
        const mensajesError = {
            'paypal_amount_invalid': 'PayPal requiere montos entre 0.50€ y 25,000€',
            'default': obtenerMensajeErrorValidacion(error.message) || 'Error en el proceso de pago'
        };

        const mensajeError = mensajesError[error.message] || mensajesError.default;
        
        // EN CASO DE ERROR, SE REDIRIGE
        redirigirAFallo(error.message || error.code || 'unknown_error');
    }

    /**
     * MÉTODO PARA REDIRIGIR A LA PANTALLA DE ÉXITO
     * idPago - ID del pago
     */
    function redirigirAExito(idPago) {
        window.location.href = `<?= base_url('pago/completado') ?>?payment_intent=${idPago}`;
    }

    /**
     * MÉTODO PARA REDIRIGIR A LA PANTALLA DE FALLO
     * codigoError - Código del error
     * idPago - ID del pago (opcional)
     */
    function redirigirAFallo(codigoError, idPago = null) {
        let url = `<?= base_url('pago/fallo') ?>?error=${encodeURIComponent(codigoError)}`;
        if (idPago) {
            url += `&payment_intent=${idPago}`;
        }
        window.location.href = url;
    }

    /**
     * MÉTODO PARA OBTENER EL MENSAJE DE ERROR PARA MOSTRARLO AL USUARIO
     * codigo - Código del error
     */
    function obtenerMensajeErrorValidacion(codigo) {
        const mensajes = {
            'invalid_number': 'Número de tarjeta inválido',
            'invalid_expiry_month': 'Mes de expiración inválido',
            'invalid_expiry_year': 'Año de expiración inválido',
            'invalid_cvc': 'Código de seguridad inválido',
            'incorrect_number': 'Número de tarjeta incorrecto',
            'incomplete_number': 'Número de tarjeta incompleto',
            'incomplete_cvc': 'Código de seguridad incompleto',
            'incomplete_expiry': 'Fecha de expiración incompleta',
            'expired_card': 'Tarjeta expirada',
            'incorrect_cvc': 'Código de seguridad incorrecto',
            'incorrect_zip': 'Código postal incorrecto',
            'card_declined': 'Tarjeta rechazada',
            'payment_intent_authentication_failure': 'Autenticación 3D Secure fallida',
            '3ds_authentication_failed': 'Autenticación 3D Secure fallida o cancelada',
            'processing_error': 'Error procesando el pago',
            'rate_limit': 'Demasiadas solicitudes',
            'default': 'Error en el proceso de pago'
        };

        return mensajes[codigo] || mensajes.default;
    }

    // INICIALIZAR LA APLICACIÓN
    inicializarStripe();
    configurarManejadoresEventos();

    // Detectar click en el botón de ayuda y abrir el modal
    $(document).on('click', '#btnAyudaPasarela', function (event) {
    event.preventDefault();
    $('#modalAyudaPasarela').modal('show');
    });
};
    </script>
</body>
</html>