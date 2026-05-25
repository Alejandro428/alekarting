$(document).ready(function() {
    // CONFIGURACIÓN DE URLS
    const URLS = {
        eventos: base_url + 'Admin/getEmpleadosEventos',
        carreras: base_url + 'Admin/getEmpleadosCarreras',
        noticias: base_url + 'Admin/getEmpleadosNoticias'
    };

    // ELEMENTOS A ACTUALIZAR
    const elementos = [
        { id: 'empleados-eventos', tipo: 'eventos' },
        { id: 'empleados-carreras', tipo: 'carreras' },
        { id: 'empleados-noticias', tipo: 'noticias' }
    ];

    // FUNCIÓN PARA ACTUALIZAR EL ELEMENTO DE CADA TIPO DE EMPLEADO DISPONIBLE
    function actualizarElemento(elemento) {
        const $elemento = $('#' + elemento.id);
        $elemento.html('Consultando...')
                .removeClass('con-empleados sin-empleados error')
                .addClass('cargando');

        $.ajax({
            // AJAX PARA OBTENER CUANTOS TIPOS DE EMPLEADOS HAY DISPONIBLES DE CADA
            url: URLS[elemento.tipo],
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.recordsTotal !== undefined) {
                    if (response.recordsTotal > 0) {
                        $elemento.text(response.recordsTotal + ' empleado(s) en ' + elemento.tipo)
                                 .removeClass('cargando sin-empleados error')
                                 .addClass('con-empleados');
                    } else {
                        $elemento.html('<i class="bi bi-exclamation-triangle"></i> ¡ATENCIÓN! 0 empleados en ' + elemento.tipo)
                                 .removeClass('cargando con-empleados error')
                                 .addClass('sin-empleados');
                    }
                } else {
                    $elemento.text('Error en datos recibidos')
                             .removeClass('cargando con-empleados sin-empleados')
                             .addClass('error');
                }
            },
            error: function(xhr) {
                $elemento.html('<i class="bi bi-x-circle"></i> Error de conexión')
                         .removeClass('cargando con-empleados sin-empleados')
                         .addClass('error');
            }
        });
    }

    // ACTUALIZACIÓN INICIAL AL CARGAR LA PÁGINA
    elementos.forEach(actualizarElemento);
});