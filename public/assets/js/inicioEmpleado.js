$(document).ready(function() {
    var $tarjetaEventos = $("#tarjeta-eventos");
    var $tarjetaCarreras = $("#tarjeta-carreras");
    var $tarjetaNoticias = $("#tarjeta-noticias");

    // MÉTODO PARA MOSTRAR EL ERROR CON FADEÑIN Y FADE-OUT
    function mostrarError(mensaje) {
        var $errorContainer = $("#error-container");
        $errorContainer.html(mensaje).addClass("show");
        setTimeout(function() {
            $errorContainer.removeClass("show");
        }, 4000);
    }

    // MÉTODO PARA ASIGNAR EVENTOS A CADA TARJETA
    function asignarEventosTarjeta($tarjeta, permiso, seccion) {
        $tarjeta.on("mouseover", function() {
            var $info = $(this).find(".tarjeta-info");
            if ($info.length) {
                var background = permiso 
                    ? "linear-gradient(to top, rgba(0,128,0,0.7), transparent)" 
                    : "linear-gradient(to top, rgba(128,0,0,0.7), transparent)";
                $info.css("background", background);
            }
        }).on("mouseout", function() {
            var $info = $(this).find(".tarjeta-info");
            if ($info.length) {
                $info.css("background", "linear-gradient(to top, rgba(0,0,0,0.8), transparent)");
            }
        }).on("click", function() {
            if (!permiso) {
                mostrarError("No tienes permiso para gestionar " + seccion + ".");
            }
        });
    }

    if ($tarjetaEventos.length) {
        asignarEventosTarjeta($tarjetaEventos, emp_evento, "Eventos");
    }
    if ($tarjetaCarreras.length) {
        asignarEventosTarjeta($tarjetaCarreras, emp_carreras, "Carreras");
    }
    if ($tarjetaNoticias.length) {
        asignarEventosTarjeta($tarjetaNoticias, emp_noticia, "Noticias");
    }

    // SI HAY UN ERROR ENVIADO DESDE EL CONTROLADOR, MOSTRARLO CON FADE-IN
    var $errorContainer = $("#error-container");
    if ($errorContainer.length && $.trim($errorContainer.html()) !== "") {
        $errorContainer.addClass("show");
        setTimeout(function() {
            $errorContainer.removeClass("show");
        }, 4000);
    }
});
