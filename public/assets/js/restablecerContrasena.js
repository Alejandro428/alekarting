$(document).ready(function() {

    // MOSTRAR/OCULTAR CONTRASEÑA PRINCIPAL
    $('#toggleNuevaContrasena').on('click', function() {
        const input = $('#nueva_contrasena');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // MOSTRAR/OCULTAR CONFIRMACIÓN DE CONTRASEÑA
    $('#toggleConfirmarContrasena').on('click', function() {
        const input = $('#confirmar_contrasena');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    /**
     * Valida la contraseña y su confirmación
     */
    function validarContrasena() {
        const contrasena = $('#nueva_contrasena').val();
        const confirmacion = $('#confirmar_contrasena').val();
        
        // Limpiar estados anteriores
        $('#nueva_contrasena, #confirmar_contrasena').removeClass('is-invalid is-valid');
        $('#error-contrasena, #error-confirmacion').hide();

        let esValido = true;

        // Validar contraseña principal
        if (!contrasena) {
            $('#nueva_contrasena').addClass('is-invalid');
            $('#error-contrasena').text("La contraseña es obligatoria").show();
            esValido = false;
        } else if (contrasena.length < 10) {
            $('#nueva_contrasena').addClass('is-invalid');
            $('#error-contrasena').text("Debe tener al menos 10 caracteres").show();
            esValido = false;
        } else if (!/[A-Z]/.test(contrasena)) {
            $('#nueva_contrasena').addClass('is-invalid');
            $('#error-contrasena').text("Debe contener al menos una mayúscula").show();
            esValido = false;
        } else if (!/\d/.test(contrasena)) {
            $('#nueva_contrasena').addClass('is-invalid');
            $('#error-contrasena').text("Debe contener al menos un número").show();
            esValido = false;
        } else if (!/[@$!%*?&]/.test(contrasena)) {
            $('#nueva_contrasena').addClass('is-invalid');
            $('#error-contrasena').text("Debe contener al menos un carácter especial (@$!%*?&)").show();
            esValido = false;
        } else {
            $('#nueva_contrasena').addClass('is-valid');
        }

        // Validar confirmación
        if (!confirmacion) {
            $('#confirmar_contrasena').addClass('is-invalid');
            $('#error-confirmacion').text("Por favor confirme la contraseña").show();
            esValido = false;
        } else if (contrasena !== confirmacion) {
            $('#confirmar_contrasena').addClass('is-invalid');
            $('#error-confirmacion').text("Las contraseñas no coinciden").show();
            esValido = false;
        } else if (contrasena) {
            $('#confirmar_contrasena').addClass('is-valid');
        }

        return esValido;
    }

    // VALIDACIÓN EN TIEMPO REAL DE CONTRASEÑA
    $('#nueva_contrasena, #confirmar_contrasena').on('input', function() {
        validarContrasena();
    });

    // EVENTO SUBMIT DEL FORMULARIO RESTABLECER
$(document).on('submit', '#formRestablecer', function(evento) {
    evento.preventDefault();

    // Validar formulario
    if (!validarContrasena()) {
        Swal.fire({
            title: 'Error de Validación',
            text: 'Por favor corrige los errores en el formulario',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Mostrar estado de carga
    var botonEnvio = $(this).find('button[type="submit"]');
    var textoOriginal = botonEnvio.html();
    botonEnvio.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando...');

    // Obtener el token correctamente
    var token = $('input[name="token"]').val();
    console.log('Token a enviar:', token); // Depuración en consola

    // VERIFICAR QUE LA NUEVA CONTRASEÑA ES DISTINTA A LA ANTERIOR
    $.ajax({
        url: urlBase + 'Usuario/verificarContrasenaActual',
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            token: token,
            nueva_contrasena: $('#nueva_contrasena').val()
        }),
        dataType: "json",
        success: function(verificacion) {
            console.log('Respuesta del servidor:', verificacion);
            if (verificacion.success) {
                if (verificacion.es_igual) {
                    // SI ES IGUAL, SE NOTIFICA AL USUARIO DEL ERROR
                    botonEnvio.prop('disabled', false).html(textoOriginal);
                    Swal.fire({
                        title: 'Error',
                        text: 'La nueva contraseña no puede ser igual a la actual',
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }
                
                // SI ES DIFERENTE, SE PROCEDE CON EL CAMBIO DE CONTRASEÑA
                cambiarContrasena({
                    token: token,
                    nueva_contrasena: $('#nueva_contrasena').val()
                });
            } else {
                botonEnvio.prop('disabled', false).html(textoOriginal);
                Swal.fire({
                    title: 'Error',
                    html: verificacion.message + 
                         '<br><small>Debug: ' + (verificacion.debug_token || '') + '</small>',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }
        },
        error: function(xhr) {
            console.error('Error en la solicitud:', xhr.responseText); // Depuración
            botonEnvio.prop('disabled', false).html(textoOriginal);
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo verificar la contraseña actual',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        }
    });
});

// MÉTODO PARA CAMBIAR LA CONTRASEÑA
function cambiarContrasena(datos) {
    // AJAX QUE CAMBIA LA CONTRASEÑA DEL USUARIO
    $.ajax({
        url: urlBase + 'restablecer-contrasena',
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(datos),
        dataType: "json",
        success: function(respuesta) {
            if (respuesta.success) {
                Swal.fire({
                    title: '¡Contraseña actualizada!',
                    text: respuesta.message,
                    icon: 'success',
                    confirmButtonText: 'Entendido',
                    timer: 3000,
                    timerProgressBar: true
                });

                // Enviar correo de confirmación...
                const emailPayload = {
                    email: respuesta.email || '',
                    nombre: respuesta.nombre || 'Usuario',
                    asunto: "Confirmación de cambio de contraseña",
                    mensaje: `Hola ${respuesta.nombre || 'Usuario'},\n\nTu contraseña ha sido cambiada exitosamente.`
                };
                // AJAX PARA NOTIFICAR AL USUARIO DE QUE SE HA CAMBIADO SU CONTRASEÑA
                $.ajax({
                    url: urlBase + "Contactos/enviar",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(emailPayload),
                    dataType: "json",
                    success: function() {
                        window.location.href = urlBase + "Iniciar_Sesion";
                    },
                    error: function() {
                        window.location.href = urlBase + "Iniciar_Sesion";
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: respuesta.message,
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error de conexión',
                text: 'Error al cambiar la contraseña',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        },
        complete: function() {
            $('button[type="submit"]').prop('disabled', false).html('Cambiar Contraseña');
        }
    });
}

});