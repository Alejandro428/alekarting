// URL PARA SOLICITAR LA RECUPERACIÓN DE LA CONTRASEÑA
const urlSolicitudRecuperacion = urlBase + "Correo/solicitarRecuperacion";

$(document).ready(function() {
    if ($('#formularioRecuperacion').length) {

        // VALIDADOR DEL FORMULARIO DE RECUPERACIÓN DE CONTRASEÑA
        var validadorRecuperacion = new FormValidator('formularioRecuperacion', {
            correo: {  
                required: true,
                pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                message: "Por favor ingresa un correo válido (ejemplo@dominio.com)"
            }
        });

        // EVENTO DE SUBMIT DEL FORMULARIO DE RECUPERACIÓN
        $(document).on('submit', '#formularioRecuperacion', function(evento) {
            evento.preventDefault();

            // VALIDAR EL FORMULARIO
            var formularioValido = validadorRecuperacion.validateForm(evento);

            if (!formularioValido) {
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

            var datosFormulario = {
                correo: $('#correo').val().trim()
            };

            // AJAX QUE ENVÍA LA SOLICITUD PARA RECUPERAR LA CONTRASEÑA
            // EL MÉTODO GENERA UN TOKEN EN EL USUARIO ASOCIADO A ESE CORREO, Y UNA HORA DE EXPIRACIÓN PARA CAMBIAR LA CONTRASEÑA
            $.ajax({
                url: urlSolicitudRecuperacion,
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(datosFormulario),
                dataType: "json",
                success: function(respuesta) {
                    if (respuesta.success) {
                        Swal.fire({
                            title: '¡Solicitud enviada!',
                            text: respuesta.message,
                            icon: 'success',
                            confirmButtonText: 'Entendido',
                            timer: 3000,
                            timerProgressBar: true,
                            willClose: () => {
                                $('#formularioRecuperacion')[0].reset();
                                // Redirigir si es necesario
                                // window.location.href = urlBase + "Iniciar_Sesion";
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: respuesta.message || 'Error al procesar la solicitud',
                            icon: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire({
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor. Intenta nuevamente.',
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                },
                complete: function() {
                    // Restaurar botón
                    botonEnvio.prop('disabled', false).html(textoOriginal);
                }
            });
        });
    }
});