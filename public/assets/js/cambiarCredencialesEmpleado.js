$(document).ready(function () {

// FORM VALIDATOR QUE USO PARA VALIDAR LOS CAMPOS DEL FORMULARIO DE CAMBIO DE CREDENCIALES 
var formValidator = new FormValidator('formCredenciales', {
    nombre_usuario: {
    required: true,
    pattern: /^[a-zA-Z0-9]{6,}$/,  // Letras y números, mínimo 6 caracteres
    message: "El nombre de usuario debe tener al menos 6 caracteres y solo puede contener letras y números (sin espacios)."
},
nombre: {
    required: true,
    pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,  // Letras con tildes y espacios
    message: "El nombre solo puede contener letras y espacios."
},
apellidos: {
    required: true,
    pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,  // Igual que nombre
    message: "Los apellidos solo pueden contener letras y espacios."
},
email: {
    required: true,
    pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
    message: "Ingrese un correo válido (ejemplo@dominio.com)."
},
telefono: {
    required: true,
    pattern: /^\d{9}$/,  // 9 dígitos exactos
    message: "El teléfono debe contener 9 números."
}

});

// 2. FUNCIÓN PARA PODER VALIDAR CONTRASEÑAS
function validarContrasena(contrasena, confirmarContrasena, inputContrasenaSelector, inputConfirmarSelector) {
    // LIMPIO LOS POSIBLES ERRORES Y MENSAJES QUE TIENEN LAS CONTRASEÑAS AHORA MISMO
    $(inputContrasenaSelector + ', ' + inputConfirmarSelector).removeClass('is-invalid is-valid');
    $(inputContrasenaSelector).closest('.form-group').find('.invalid-feedback').hide();
    $(inputConfirmarSelector).closest('.form-group').find('.invalid-feedback').hide();
    // CREO UNA VARIABLE DE ES VALIDO, Y DEPENDIENDO DE COMO SEA EL ESTADO DE LAS CONTRASEÑAS, ES TRUE O FALSE
    let esValido = true;

    // ===== VALIDAR CONTRASEÑA PRINCIPAL =====
    // SI NO HAY CONTRASEÑA PUESTO ACTUALMENTE, SE PONE QUE LA CONTRASEÑA NO ES VÁLIDA
    if (!contrasena) {
        mostrarError(inputContrasenaSelector, "La contraseña es obligatoria");
        esValido = false;
    // SI TIENE MENOS DE 10 CARÁCTERES, TAMPOCO ES VÁLIDA 
    } else if (contrasena.length < 10) {
        mostrarError(inputContrasenaSelector, "Debe tener al menos 10 caracteres");
        esValido = false;
    // SI NO CUMPLE EL TEST MÍNIMO, TAMPOCO ES VÁLIDA
    } else if (!/[A-Z]/.test(contrasena)) {
        mostrarError(inputContrasenaSelector, "Debe contener al menos una mayúscula");
        esValido = false;
    // SI NO CUMPLE EL TEST INTERMEDIO, TAMPOCO ES VÁLIDA
    } else if (!/\d/.test(contrasena)) {
        mostrarError(inputContrasenaSelector, "Debe contener al menos un número");
        esValido = false;
    // SI NO CUMPLE EL TEST FUERTE, TAMPOCO ES VÁLIDA
    } else if (!/[@$!%*?&]/.test(contrasena)) {
        mostrarError(inputContrasenaSelector, "Debe contener al menos un carácter especial (@$!%*?&)");
        esValido = false;
    } else {
    // SI NO ENTRA EN NADA ANTERIOR, SIGNIFICA QUE EL CAMPO CONTRASEÑA ES CORRECTO, Y LO PINTO DE VERDE
        $(inputContrasenaSelector).addClass('is-valid');
    }

    // ===== VALIDAR CONFIRMACIÓN =====
    // SI EL CAMPO DE CONFIRMAR CONTRASEÑA NO EXISTE, ES QUE ESTARÁ VACÍO, POR LO QUE EL CONFIRMAR CONTRASEÑA SERÁ INCORRECTO
    if (!confirmarContrasena) {
        mostrarError(inputConfirmarSelector, "Por favor confirme la contraseña");
        esValido = false;
    // SI LA CONTRASEÑA ES DISTINTA AL CONFIRMAR CONTRASEÑA, TAMBIÉN ES INCORRECTA
    } else if (contrasena !== confirmarContrasena) {
        mostrarError(inputConfirmarSelector, "Las contraseñas no coinciden");
        esValido = false;
    } else {
    // SINO, EL CONFIRMAR CONTRASEÑA ES CORRECTO
        $(inputConfirmarSelector).addClass('is-valid');
    }
    // SE RETURNA SI ES O NO VÁLIDO ENTONCES LOS CAMPOS DE CONTRASEÑAS
    return esValido;
}

// FUNCIÓN AUXILIAR QUE USO PARA MOSTRAR ERRORES
function mostrarError(selector, mensaje) {
    $(selector).addClass('is-invalid');
    // CON ESTO, MUESTRO EL MENSAJE QUE HAY DEBAJO DE CADA CONTRASEÑA, PARA ENSEÑAR SU ERROR
    $(selector).closest('.form-group').find('.invalid-feedback')
        .text(mensaje).show();
}

// VALIDACIÓN DE LAS CONTRASEÑAS AL ESCRIBIR
$(document).on('input', '#contraseña, #confirmar_contraseña', function() {
    const contrasenaActual = $('#contraseña').val();
    const confirmacionActual = $('#confirmar_contraseña').val();

    // SI ALGUNA DE LAS CONTRASEÑAS YA TIENEN CONTENIDO, HAY QUE EMPEZAR A VALIDARLAS
    if (contrasenaActual || confirmacionActual) {
        validarContrasena(contrasenaActual, confirmacionActual, '#contraseña', '#confirmar_contraseña');
    } else {
        // SINO, SE LES QUITAN LOS ERRORES
        // Limpiar validaciones manteniendo la estructura de 4 parámetros
        $('#contraseña, #confirmar_contraseña').removeClass('is-invalid is-valid');
        $('#contraseña').closest('.form-group').find('.invalid-feedback').hide();
        $('#confirmar_contraseña').closest('.form-group').find('.invalid-feedback').hide();
    }
});


// 4. BOTÓN CON EL QUE AL HACERLE CLICK, SE PUEDE VER LA CONTRASEÑA
$('#verContraseña').on('click', function() {
    var passwordField = $('#contraseña');
    var passwordFieldType = passwordField.attr('type');
    if (passwordFieldType == 'password') {
        passwordField.attr('type', 'text');
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
        passwordField.attr('type', 'password');
        $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
});

// 4. BOTÓN CON EL QUE AL HACERLE CLICK, SE PUEDE VER EL CONFIRMAR CONTRASEÑA
// SIMPLEMENTE CAMBIO EL TIPO DEL INPUT A INPUT TEXT, Y SI SE LE VUELVE A PULSAR, SE
// PONE COMO PASSWORD DE NUEVO
$('#verConfirmarContraseña').on('click', function() {
    var passwordField = $('#confirmar_contraseña');
    var passwordFieldType = passwordField.attr('type');
    if (passwordFieldType == 'password') {
        passwordField.attr('type', 'text');
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
        passwordField.attr('type', 'password');
        $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
});

// 5. MANEJAR EL ENVÍO DEL FORMULARIO
$(document).on('submit', '#formCredenciales', function(event) {
    event.preventDefault();

    // RECOJO TODOS LOS CAMPOS DEL FORMULARIO INDIVIDUALMENTE
    var id = $('input[name="id"]').val().trim();
    var nombre_usuario = $('input[name="nombre_usuario"]').val().trim();
    var nombre = $('input[name="nombre"]').val().trim();
    var apellidos = $('input[name="apellidos"]').val().trim();
    var email = $('input[name="email"]').val().trim();
    var telefono = $('input[name="telefono"]').val().trim();
    var contrasena = $('input[name="contraseña"]').val().trim();
    var confirmar_contrasena = $('input[name="confirmar_contraseña"]').val().trim();

    // VALIDO LAS CONTRASEÑAS, AL SER EL CAMBIAR CREDENCIALES EXISTE LA POSIBILIDAD DE QUE EL USUARIO
    // NO QUIERA CAMBIAR LA CONTRASEÑA, ASÍ QUE SOLO SE LE PONDRÁ COMO ERROR, SI ESTA PONIENDO INFORMACIÓN
    // EN LAS CONTRASEÑAS
    var esContrasenaValida = true;
    if (contrasena || confirmar_contrasena) {
        esContrasenaValida = validarContrasena(contrasena, confirmar_contrasena, '#contraseña', '#confirmar_contraseña');
    }

    // SE VALIDA TODO EL FORMULARIO
    var esFormularioValido = formValidator.validateForm(event);

    // SI ALGUNA DE LAS DOS COSAS DA ERROR, SACO UN TOAST Y NO DEJO AVANZAR
    if (!esContrasenaValida || !esFormularioValido) {
        toastr.error("Corrija los errores en el formulario", "Error de Validación");
        return;
    }

    // DESPUÉS, VALIDO QUE NINGUNO DE LOS CAMPOS USUARIOS, TELÉFONO Y CORREO NO ESTÉN YA EXISTENTES
    $.ajax({
        url: base_url + "Admin/validarCamposUnicos",
        type: "POST",
        data: { 
            id: id, 
            nombre_usuario: nombre_usuario, 
            email: email, 
            telefono: telefono 
        },
        success: function(validacion) {
            if (!validacion.success) {
                let errores = [];
                // SI EL NOMBRE DE USUARIO YA EXISTE, LO AÑADO COMO ERROR
                if (validacion.error_nombre_usuario) {
                    $('#nombre_usuario').addClass('is-invalid');
                    $('#nombre_usuario').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                        .text(validacion.error_nombre_usuario).show();
                    errores.push(validacion.error_nombre_usuario);
                }
                // SI EL CORREO YA EXISTE, LO AÑADO COMO ERROR
                if (validacion.error_email) {
                    $('#email').addClass('is-invalid');
                    $('#email').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                        .text(validacion.error_email).show();
                    errores.push(validacion.error_email);
                }
                // SI EL TELÉFONO YA EXISTE, LO AÑADO COMO ERROR
                if (validacion.error_telefono) {
                    $('#telefono').addClass('is-invalid');
                    $('#telefono').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                        .text(validacion.error_telefono).show();
                    errores.push(validacion.error_telefono);
                }
                // SI HAY ERRORES, LOS UNO Y LOS MUESTRO TODOS Y NO DEJO QUE EL USUARIO PUEDA CONTINUAR
                if (errores.length) {
                    toastr.error(errores.join('<br>'), 'Errores');
                }
                return;
            }

            // SI TODO ES EXITOSO, PREPARO EL OBJETO PARA MANDARSELO AL BACKEND Y QUE ACTUALICE LAS CREDENCIALES
            var formData = {
                id: id,
                nombre_usuario: nombre_usuario,
                nombre: nombre,
                apellidos: apellidos,
                email: email,
                telefono: telefono
            };

            // AÑADO LA CONTRASEÑA SI EXISTE
            if (contrasena) {
                formData.contraseña = contrasena;
                formData.confirmar_contraseña = confirmar_contrasena;
            }

            // ENVIO LOS DATOS AL SERVIDOR
            $.ajax({
                url: base_url + "Empleado/actualizarCredenciales",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(formData),
                dataType: "json",
                success: function(json) {
                    if (json.success) {

                        // PREPARO EL MENSAJE PARA EL CORREO
                        let mensajeTexto = `
                        Hola, ${nombre}:

                        Se han actualizado tus credenciales de acceso con los siguientes datos:

                        - Nombre de usuario: ${nombre_usuario}
                        - Nombre: ${nombre}
                        - Apellidos: ${apellidos}
                        - Email: ${email}
                        - Teléfono: ${telefono}
                        `;

                        if (contrasena) {
                            mensajeTexto += `

                        - La contraseña ha sido cambiada (por seguridad no se muestra aquí).
                        `;
                        }

                        mensajeTexto += `

                        Si tú no solicitaste este cambio, contacta con el administrador de inmediato.

                        Este es un mensaje automático. No respondas a este correo.
                        `;

                        // PREPARO EL OBJETO COMPLETO PARA HACER EL CORREO
                        const emailPayload = {
                            email: email,
                            nombre: nombre,
                            asunto: "Actualización de credenciales",
                            mensaje: mensajeTexto
                        };

                        // ENVÍO EL CORREO Y ESPERO LA RESPUESTA PARA MOSTRAR EL SWAL
                        $.ajax({
                            url: base_url + "Contactos/enviar",
                            method: "POST",
                            contentType: "application/json",
                            data: JSON.stringify(emailPayload),
                            dataType: "json",
                            success: function(emailResponse) {
                                // SI SE HA ENVIADO EL CORREO, SE MUESTRA QUE SE HA ENVIADO
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: json.message + "\n\nSe ha enviado un correo de notificación.",
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        window.location.reload();
                                    }
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    // SINO, MUESTRO QUE TODO HA SIDO EXITOSO PERO QUE EL CORREO HA FALLADO 
                                    title: '¡Éxito!',
                                    text: json.message + "\n\nNo se pudo enviar el correo de notificación.",
                                    icon: 'warning',
                                    confirmButtonText: 'Aceptar',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        window.location.reload();
                                    }
                                });
                            }
                        });

                    } else {
                        // AQUÍ DIRECTAMENTE MUESTRO EL ERROR SI NO HA ACTUALIZADO LAS CREDENCIALES
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
        },
        error: function() {
            Swal.fire('Error', 'Error al validar datos', 'error');
        }
    });
});

}); // de document.ready