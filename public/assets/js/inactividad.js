// DEFINIR EL TIEMPO DE INACTIVIDAD EN MILISEGUNDOS (15 minutos)
const TIEMPO_INACTIVIDAD = 15 * 60 * 1000;
let temporizador;
let sesionExpirada = false;
let redireccionEnProceso = false;  

// REINICIAR EL TEMPORIZADOR DE INACTIVIDAD
function reiniciarTemporizador() {
    if (sesionExpirada) return; // SI YA SE MARCÓ LA EXPIRACIÓN, NO REINICIAR
    clearTimeout(temporizador);
    temporizador = setTimeout(function() {
        sesionExpirada = true;
        console.log("Tiempo de inactividad alcanzado. Esperando la siguiente acción para cerrar sesión.");
    }, TIEMPO_INACTIVIDAD);
}

// MÉTODO QUE MANEJA LA ACTIVIDAD DEL USUARIO
function manejarActividadUsuario() {
    // Si no hay sesión iniciada, no hacemos nada
    if (!datosSesion.sesion_iniciada) return;
    
    if (sesionExpirada && !redireccionEnProceso) {
        redireccionEnProceso = true; // Evitar redirecciones múltiples
        cerrarSesionPorInactividad();
    } else {
        reiniciarTemporizador();
    }
}

// MÉTODO PARA CERRAR SESIÓN POR INACTIVIDAD MEDIANTE AJAX
function cerrarSesionPorInactividad() {
    // AJAX PARA CERRAR LA SESIÓN, Y ENVIAR EL MENSAJE PARA EXPLICAR AL USUARIO QUE SE CERRÓ POR INACTIVIDAD
    $.ajax({
        url: base_url + "Cerrar_Sesion?mensaje=expirada&ajax=true",
        type: "GET",
        dataType: "json",
        success: function(resultado) {
            if (resultado.success) {
                console.log(resultado.message);
                // REDIRIGIR AL INICIAR SESIÓN Y MOSTRANDO AL USUARIO EL MENSAJE DE EXPIRACIÓN
                window.location.href = base_url + "Iniciar_Sesion?mensaje=expirada";
            } else {
                console.error("Error al cerrar sesión:", resultado.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición de cierre de sesión:", error);
        }
    });
}

// SI EL USUARIO TIENE LA SESIÓN INICIADA, SE INICIAN LOS EVENTOS DE INACTIVIDAD
if (datosSesion.sesion_iniciada) {
    $(window).on("load", reiniciarTemporizador);
    $(document).on("mousemove keydown click scroll", manejarActividadUsuario);
}
