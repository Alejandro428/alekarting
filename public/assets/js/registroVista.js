// URLS QUE SE UTILIZAN EN EL REGISTRO
const urlComprobar    = base_url + "Usuario/comprobarExistencia";
const urlRegistrar     = base_url + "Usuario/crear";  // Método "crearUsuario" del controlador
const urlIniciarSesion = base_url + "Iniciar_Sesion";

$(document).ready(function() {

    // VALIDADOR DEL FORMULARIO DE REGISTRO
var formValidator = new FormValidator('formRegistro', {
    nombre_usuario: {
      required: true,
      pattern: /^[a-zA-Z0-9]{6,}$/,
      message: "El nombre de usuario debe tener al menos 6 caracteres y solo puede contener letras y números (sin espacios)."
    },
    nombre: {
      required: true,
      pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,
      message: "El nombre solo puede contener letras y espacios."
    },
    apellidos: {
      required: true,
      pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,
      message: "Los apellidos solo pueden contener letras y espacios."
    },
    email: {
      required: true,
      pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
      message: "Ingrese un correo válido (ejemplo@dominio.com)."
    },
    telefono: {
      required: true,
      pattern: /^\d{9}$/,
      message: "El teléfono debe contener 9 números."
    }
  });
  
  // MÉTODO PARA VALIDAR CONTRASEÑAS
  function validarContrasena(contrasena, confirmarContrasena, inputContrasenaSelector, inputConfirmarSelector) {
    $(inputContrasenaSelector + ', ' + inputConfirmarSelector).removeClass('is-invalid is-valid');
    $(inputContrasenaSelector).closest('.form-group').find('.invalid-feedback').hide();
    $(inputConfirmarSelector).closest('.form-group').find('.invalid-feedback').hide();
  
    let esValido = true;
  
    if (!contrasena) {
      mostrarError(inputContrasenaSelector, "La contraseña es obligatoria");
      esValido = false;
    } else if (contrasena.length < 10) {
      mostrarError(inputContrasenaSelector, "Debe tener al menos 10 caracteres");
      esValido = false;
    } else if (!/[A-Z]/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos una mayúscula");
      esValido = false;
    } else if (!/\d/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos un número");
      esValido = false;
    } else if (!/[@$!%*?&]/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos un carácter especial (@$!%*?&)");
      esValido = false;
    } else {
      $(inputContrasenaSelector).addClass('is-valid');
    }
  
    if (!confirmarContrasena) {
      mostrarError(inputConfirmarSelector, "Por favor confirme la contraseña");
      esValido = false;
    } else if (contrasena !== confirmarContrasena) {
      mostrarError(inputConfirmarSelector, "Las contraseñas no coinciden");
      esValido = false;
    } else {
      $(inputConfirmarSelector).addClass('is-valid');
    }
  
    return esValido;
  }
  
  // MÉTODO AUXILIAR PARA MOSTRAR ERRORES
  function mostrarError(selector, mensaje) {
    $(selector).addClass('is-invalid');
    $(selector).closest('.form-group').find('.invalid-feedback')
      .text(mensaje).show();
  }
  
  // VALIDACIÓN EN TIEMPO REAL DE CONTRASEÑAS
  $(document).on('input', '#contraseña, #confirmar_contraseña', function() {
    const contrasenaActual = $('#contraseña').val();
    const confirmacionActual = $('#confirmar_contraseña').val();
  
    if (contrasenaActual || confirmacionActual) {
      validarContrasena(contrasenaActual, confirmacionActual, '#contraseña', '#confirmar_contraseña');
    } else {
      $('#contraseña, #confirmar_contraseña').removeClass('is-invalid is-valid');
      $('#contraseña').closest('.form-group').find('.invalid-feedback').hide();
      $('#confirmar_contraseña').closest('.form-group').find('.invalid-feedback').hide();
    }
  });
  
  // MOSTRAR/OCULTAR CONTRASEÑA
  $('#verContraseña').on('click', function() {
    var passwordField = $('#contraseña');
    var type = passwordField.attr('type');
    if (type == 'password') {
      passwordField.attr('type', 'text');
      $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
      passwordField.attr('type', 'password');
      $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
  });
  
  // MOSTRAR/OCULTAR CONTRASEÑA (CONFIRMAR)
  $('#verConfirmarContraseña').on('click', function() {
    var passwordField = $('#confirmar_contraseña');
    var type = passwordField.attr('type');
    if (type == 'password') {
      passwordField.attr('type', 'text');
      $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
      passwordField.attr('type', 'password');
      $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
  });
  
  // EVENTO SUBMIT DEL FORMULARIO DE REGISTRO
  $(document).on('submit', '#formRegistro', function(event) {
  event.preventDefault();

  // SE RECOGEN TODOS LOS VALORES DEL FORMULARIO
  var nombre_usuario = $('#nombre_usuario').val().trim();
  var nombre = $('#nombre').val().trim();
  var apellidos = $('#apellidos').val().trim();
  var email = $('#email').val().trim();
  var telefono = $('#telefono').val().trim();
  var contrasena = $('#contraseña').val().trim();
  var confirmar_contraseña = $('#confirmar_contraseña').val().trim();

  var esContrasenaValida = validarContrasena(contrasena, confirmar_contraseña, '#contraseña', '#confirmar_contraseña');
  var esFormularioValido = formValidator.validateForm(event);

  // SE HACE LA VALIDACIÓN
  if (!esContrasenaValida || !esFormularioValido) {
    toastr.error("Corrige los errores en el formulario", "Error de Validación");
    return;
  }

  // SE VALIDA QUE NO EXISTAN CAMPOS ÚNICOS REPETIDOS (USUARIO, EMAIL, TELÉFONO)
  $.ajax({
    url: base_url + "Admin/validarCamposUnicos",
    type: "POST",
    data: { 
      nombre_usuario: nombre_usuario, 
      email: email, 
      telefono: telefono 
    },
    success: function(validacion) {
      if (!validacion.success) {
        let errores = [];

        if (validacion.error_nombre_usuario) {
          $('#nombre_usuario').addClass('is-invalid');
          $('#nombre_usuario').closest('.col-sm-9, .col-7').find('.invalid-feedback')
            .text(validacion.error_nombre_usuario).show();
          errores.push(validacion.error_nombre_usuario);
        }

        if (validacion.error_email) {
          $('#email').addClass('is-invalid');
          $('#email').closest('.col-sm-9, .col-7').find('.invalid-feedback')
            .text(validacion.error_email).show();
          errores.push(validacion.error_email);
        }

        if (validacion.error_telefono) {
          $('#telefono').addClass('is-invalid');
          $('#telefono').closest('.col-sm-9, .col-7').find('.invalid-feedback')
            .text(validacion.error_telefono).show();
          errores.push(validacion.error_telefono);
        }

        if (errores.length) {
          toastr.error(errores.join('<br>'), 'Errores');
        }
        return;
      }

      // SI TODO ESTA CORRECTO, SE FORMA EL OBJETO
      var formData = {
        nombre_usuario: nombre_usuario,
        nombre: nombre,
        apellidos: apellidos,
        email: email,
        telefono: telefono,
        contraseña: contrasena,
        confirmar_contraseña: confirmar_contraseña
      };

      // AJAX PARA CREAR AL USUARIO
      $.ajax({
        url: base_url + "Usuario/crear",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        dataType: "json",
        success: function(response) {
          if (response.success) {

            // Enviar correo de bienvenida
            const mensajeHtml = `
            ¡Bienvenido/a, ${nombre}!

            Gracias por registrarte en nuestra plataforma.

            Tu nombre de usuario es: ${nombre_usuario}

            Si tienes alguna pregunta, no dudes en contactarnos.
            ¡Esperamos que disfrutes de tu experiencia!

            Este es un mensaje automático. No respondas a este correo.
          `;

            const emailPayload = {
              email: email,
              nombre: nombre,
              asunto: "¡Bienvenido a la plataforma!",
              mensaje: mensajeHtml
            };
            // AJAX, PARA QUE CUANDO SE CREE AL USUARIO, SE LE NOTIFIQUE TAMBIÉN POR CORREO
            $.ajax({
              url: base_url + "Contactos/enviar",
              method: "POST",
              contentType: "application/json",
              data: JSON.stringify(emailPayload),
              dataType: "json",
              success: function() {
                Swal.fire({
                  title: '¡Registro exitoso!',
                  text: 'Te hemos enviado un correo de bienvenida.',
                  icon: 'success',
                  confirmButtonText: 'Aceptar',
                  timer: 3000,
                  timerProgressBar: true,
                  willClose: () => {
                    window.location.href = base_url + "Iniciar_Sesion"; 
                  }
                });
              },
              error: function() {
                Swal.fire({
                  title: 'Registro exitoso',
                  text: 'Tu cuenta fue creada, pero no se pudo enviar el correo de bienvenida.',
                  icon: 'warning',
                  confirmButtonText: 'Aceptar',
                  willClose: () => {
                    window.location.href = base_url + "Iniciar_Sesion"; 
                  }
                });
              }
            });

          } else {
            Swal.fire('Error', response.message || 'Error al registrar', 'error');
          }
        },
        error: function(xhr) {
          console.error(xhr);
          Swal.fire('Error', 'Error de comunicación con el servidor', 'error');
        }
      });

    },
    error: function(xhr) {
      console.error(xhr);
      Swal.fire('Error', 'Error al validar datos únicos', 'error');
    }
  });
});

      
});
