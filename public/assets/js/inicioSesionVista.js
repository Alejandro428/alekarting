// URL PARA INICIAR LA SESIÓN
const urlProcesarSesion = base_url + "Procesar_Sesion"; // Ruta POST para procesar el inicio de sesión

$(document).ready(function() {
    // OBTENER LOS DATOS DEL USUARIO DEL FORMULARIO
    var $inputUsuario = $("#usuario");
    var $inputPassword = $("#password");
    var $formLogin = $(".form-login");
    var $contenedorErrores = $("#mensajeErrorGeneral");

    // MÉTODO PARA OBTENER O CREAR UN SPAN DE ERROR AL LADO DEL INPUT
    function obtenerElementoError(id) {
        var $elem = $("#" + id);
        if ($elem.length === 0) {
            $elem = $("<span>", { id: id, class: "mensaje-error" });
        }
        return $elem;
    }

    // MÉTODO PARA REMOVER EL SPAN DE ERROR
    function removerElementoError($input, id) {
        var $elem = $("#" + id);
        if ($elem.length && $input.parent().find("#" + id).length) {
            $elem.remove();
        }
    }

    // VALIDACIÓN INTERACTIVA PARA EL CAMPO "USUARIO"
    $inputUsuario.on("input", function() {
        var $spanError = obtenerElementoError("error-usuario");
        if ($inputUsuario.val().trim() === "") {
            $spanError.text("El usuario es obligatorio").css("color", "red");
            $inputUsuario.css("border", "2px solid red");
        } else {
            $spanError.text("");
            $inputUsuario.css("border", "");
            removerElementoError($inputUsuario, "error-usuario");
        }
        if ($inputUsuario.parent().find("#error-usuario").length === 0) {
            $inputUsuario.parent().append($spanError);
        }
    });

    // VALIDACIÓN INTERACTIVA PARA EL CAMPO "PASSWORD"
    $inputPassword.on("input", function() {
        var $spanError = obtenerElementoError("error-password");
        if ($inputPassword.val().trim() === "") {
            $spanError.text("La contraseña es obligatoria").css("color", "red");
            $inputPassword.css("border", "2px solid red");
        } else {
            $spanError.text("");
            $inputPassword.css("border", "");
            removerElementoError($inputPassword, "error-password");
        }
        if ($inputPassword.parent().find("#error-password").length === 0) {
            $inputPassword.parent().append($spanError);
        }
    });

    // EVENTO DE ENVÍO DEL FORMULARIO
    $formLogin.on("submit", function(e) {
    e.preventDefault();

    // REINICIAR ESTILOS Y EL CONTENEDOR DE ERRORES GLOBAL
    $inputUsuario.css("border", "");
    $inputPassword.css("border", "");
    $contenedorErrores.hide().html("").removeClass().addClass("mensaje-registro mensaje-negativo");

    var errores = [];
    if ($inputUsuario.val().trim() === "") {
        errores.push("El usuario es obligatorio");
        $inputUsuario.css("border", "2px solid red");
    }
    if ($inputPassword.val().trim() === "") {
        errores.push("La contraseña es obligatoria");
        $inputPassword.css("border", "2px solid red");
    }
    if (errores.length > 0) {
        $contenedorErrores.html(errores.join("<br>")).show();
        return false;
    }

    // DESHABILITAR EL BOTÓN PARA EVITAR MÚLTIPLES ENVÍOS
    var $btnSubmit = $formLogin.find('button[type="submit"]');
    $btnSubmit.prop('disabled', true);

    // PREPARAR OBJETO DE CREDENCIALES
    var datos = {
        usuario: $inputUsuario.val().trim(),
        password: $inputPassword.val().trim()
    };

    // INICIAR SESIÓN LLAMANDO AL AJAX
    $.ajax({
        url: urlProcesarSesion,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(datos),
        dataType: "json",
        success: function(result) {
            if (result.success) {
                // EN CASO DE ÉXITO, REDIRIGIR AL USUARIO AL INICIO PERO CON LAL SESIÓN INICIADA
                $contenedorErrores.html("¡Inicio de sesión correcto! Redirigiendo...")
                    .removeClass().addClass("mensaje-registro mensaje-positivo").show();
                $formLogin[0].reset();

                setTimeout(function() {
                    console.log(result.redireccion);
                    window.location.href = result.redireccion;
                }, 1500);
            } else {
                $contenedorErrores.html(result.message || "Error al iniciar sesión.").show();
                // Volver a habilitar el botón para intentar de nuevo
                $btnSubmit.prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            $contenedorErrores.html("Error en la comunicación con el servidor.").show();
            console.error("Error:", error);
            // Volver a habilitar el botón ante fallo
            $btnSubmit.prop('disabled', false);
        }
    });
    return true;
});

});
