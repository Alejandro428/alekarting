window.onload = function() {
    // FUNCIÓN QUE LLAMO DEL AJAX PARA LIMPIAR LOS DATOS DE LA RESERVA DE LA SESIÓN
    function limpiarSesionPago() {
        $.ajax({
            url: base_url + "pago/limpiarSesionResidual",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    console.log("Variables de pago limpiadas correctamente");
                } else {
                    console.warn("Limpieza incompleta de variables de pago");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al limpiar variables de pago:", error);
            }
        });
    }

    // CONFIGURO LAS ACCIONES DE LOS BOTONES, TANTO AL IR AL PERFIL COMO AL IR AL INICIO
    function configurarBotones() {
        $('#btnPerfil').on('click', function(e) {
            e.preventDefault();
            limpiarSesionPago();
            window.location.href = $(this).attr('href');
        });

        $('#btnInicio').on('click', function(e) {
            e.preventDefault();
            limpiarSesionPago();
            window.location.href = $(this).attr('href');
        });
    }

    // Inicialización
    configurarBotones();
    
    // Opcional: Limpieza automática al cargar la página
    // limpiarSesionPago();
};