$(document).ready(function () {
    // VALIDADOR DEL FORMULARIO DE CONTACTOS
    var formValidator = new FormValidator('formContacto', {
        nombre: {
            required: true,
        },
        email: {
            required: true,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,  
        },
        asunto: {
            required: true,
        },
        mensaje: {
            required: true,
        },
    });

    // MÉTODO PARA EL ENVIO DLE FORMULARIO
    $(document).on('submit', '#formContacto', function(event) {
        event.preventDefault();

        // RECOJO TODOS LOS DATOS DEL FORMULARIO DE CONTACTO
        var nombreC = $('#formContacto').find('input[name="nombre"]').val();
        var correoC = $('#formContacto').find('input[name="email"]').val();
        var asuntoC = $('#formContacto').find('input[name="asunto"]').val();
        var mensajeC = $('#formContacto').find('textarea[name="mensaje"]').val();

        // LOS VALIDO
        var esFormularioValido = formValidator.validateForm(event);

        // SI HAY ERRORES, SACO UN TOAST CON EL ERROR
        if (!esFormularioValido) {
            toastr.error("Corrija los errores en el formulario", "Error de Validación");
            return;
        }

        // SI LA VALIDACIÓN ES EXITOSA, PREPARO LOS DATOS PARA MANDARLOS EN UN OBJETO
        var formData = {
            nombre: nombreC,
            email: correoC,
            asunto: asuntoC,
            mensaje: mensajeC
        };

        // LLAMO A UN AJAX QUE HACE LA ACCIÓN DE ENVIAR EL CORREO DE CONTACTO AL CORREO DE ADMIN
        $.ajax({
            url: base_url + "Contactos/enviarContacto",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            dataType: "json",
            success: function(json) {
                if (json.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: json.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        timer: 3000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.reload(); // SE RECARGA LA PÁGINA
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        html: "Error: " + (json.message || "Ocurrió un error al actualizar") + 
                             (json.errors ? "<br><br>" + json.errors.join("<br>") : ""),
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                }
            },
            error: function(xhr) {
                console.error("Error al actualizar las credenciales:", xhr);
                Swal.fire({
                    title: 'Error',
                    text: 'Error al conectar con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }); // FINAL DEL MÉTODO

}); // FIN DEL JS
