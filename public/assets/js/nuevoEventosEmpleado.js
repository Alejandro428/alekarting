$(document).ready(function () {

    $('#dateCreateFilter').inputmask('99-99-9999');
    // CONFIGURAR EL CALENDARIO EN ESPAÑOL DEL FILTRO DEL DESPLEGABLE
    // DE FECHA


    /* CALENDARIO DE FILTRO DE FECHA EVENTO */
    $.datepicker.setDefaults($.datepicker.regional['es']);

    $('#dateCreateFilter').datepicker({
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1
    });

    /* CALENDARIO DE MODAL FECHA EVENTO */
    $('#fecha').inputmask('99-99-9999');
    $('#fecha').datepicker({
        appendTo: '#modalEmpleado .modal-body', 
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        beforeShow: function(input, inst) {
            // Corrección de posición
            setTimeout(function() {
                inst.dpDiv.css({
                    'position': 'absolute',
                    'top': $(input).offset().top + $(input).outerHeight(),
                    'left': $(input).offset().left
                });
            }, 0);
        }
    });

    // CALENDARIO DE MODAL RESERVA EVENTO FECHA DE PAGO
    $('#fecha_pago').inputmask('99-99-9999');
    $('#fecha_pago').datepicker({
        appendTo: '#modalReservaEventos .modal-body', 
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        beforeShow: function(input, inst) {
            // Corrección de posición
            setTimeout(function() {
                inst.dpDiv.css({
                    'position': 'absolute',
                    'top': $(input).offset().top + $(input).outerHeight(),
                    'left': $(input).offset().left
                });
            }, 0);
        }
    });

// BORRAR FECHA Y HORARIO EN EL MODAL DE EVENTO
$('#borrarFechaEvento').on('click', function() {
    
    $('#fecha').val('');
    $('#franja_horaria_id').empty().append('<option value="">Seleccione primero una fecha</option>');
    
    $('#imagen').val('').removeClass('is-invalid is-valid');
    
    formValidator.clearValidation('fecha');
    formValidator.clearValidation('franja_horaria_id');
    
    $('#fecha').trigger('change');
});

    // DARLE CURSOR AL BORRAR FECHA EVENTO
    $('#borrarFechaEvento').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    }).on('mouseleave', function () {
        $(this).css('cursor', 'default');
    });


// VARIABLE GLOBAL PARA ALMACENAR LAS DIMENSIONES DE LA IMAGEN
var imagenDimensions = null;
// MÉTODO PARA VALIDAR MANUALMENTE EL CAMPO IMAGEN
function validarImagen() {
    // RECOJO EL ID DEL EVENTO, EL INPUT DE LA IMAGEN Y EL ARCHIVO QUE HAY ACTUALMENTE
    var idE = $('#formEvento').find('input[name="idevento"]').val().trim();
    var imagenInput = $('#imagen');
    var file = imagenInput[0].files[0];

    // CREACIÓN DE EVENTO: SI NO HAY ID EXISTENTE, SE EXIGE IMAGEN (YA QUE ES UNA CREACIÓN DE EVENTO, Y NO HAY IMAGEN ANTERIOR)
    if (idE === "") {
        if (!file) {
            imagenInput.addClass('is-invalid').removeClass('is-valid');
            return false;
        }
    } else {
        // EDITANDO: SI EDITANDO NO SE SELECCIONA NUEVA IMAGEN, SE MANTIENE LA ANTERIOR
        if (!file) {
            return true;
        }
    }
    // SI NO HAY DIMENSIONES, ES QUE NO HAY UN ARCHIVO SELECCIONADO Y DA ERROR
    if (!imagenDimensions) {
        imagenInput.addClass('is-invalid').removeClass('is-valid');
        return false;
    }
    // SI LA IMAGEN NO CUMPLE EL TAMAÑO MÍNIMO, NO VALE
    if (imagenDimensions.width < 800 || imagenDimensions.height < 600) {
        imagenInput.addClass('is-invalid').removeClass('is-valid');
        return false;
    }
    // SI NO OCURRE NADA DE LO ANTERIOR, LA IMAGEN ES CORRECTA
    imagenInput.removeClass('is-invalid').addClass('is-valid');
    return true;
}

// EVENTO CHANGE CUANDO HAY UN CAMBIO EN LA IMAGEN 
$('#imagen').on('change', function () {
    var file = this.files[0];
    imagenDimensions = null; // SE RESETEA LA VARIABLE GLOBAL DE LAS DIMENSIONES DE LA IMAGEN
    if (file) {
        // TIPOS DE IMAGEN PERMITIDOS
        var allowedMimes = ["image/jpg", "image/jpeg", "image/png", "image/webp"];
        // SI NO ESTA ENTRE ESOS, DA ERROR
        if (allowedMimes.indexOf(file.type) === -1) {
            Swal.fire({
                title: "Error",
                text: "El archivo seleccionado no es un tipo de imagen permitido.",
                icon: "error"
            });
            $(this).val('');
            $(this).removeClass('is-valid').addClass('is-invalid');
            return;
        }
        // SI SUPERA LOS 2 MB, DA ERROR
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                title: "Error",
                text: "El tamaño de la imagen supera el límite permitido (2 MB).",
                icon: "error"
            });
            $(this).val('');
            $(this).removeClass('is-valid').addClass('is-invalid');
            return;
        }
        // SI PASA LO ANTERIOR, SE CREA UN OBJETO DE LA IMAGEN Y SE RECOGEN SUS DIMENSIONES
        var img = new Image();
        img.onload = function () {
            imagenDimensions = {
                width: img.naturalWidth,
                height: img.naturalHeight
            };
            // SI LA IMAGEN NO CUMPLE CON DIMENSIONES MÍNIMAS, DA ERROR
            if (imagenDimensions.width < 800 || imagenDimensions.height < 600) {
                Swal.fire({
                    title: "Error",
                    text: "La imagen debe tener al menos 800 píxeles de ancho y 600 píxeles de alto.",
                    icon: "error"
                });
                $('#imagen').val('');
                imagenDimensions = null;
                $('#imagen').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#imagen').removeClass('is-invalid').addClass('is-valid');
            }
            URL.revokeObjectURL(img.src);
        };
        img.src = URL.createObjectURL(file);
    }
});
    

    /////////////////////////////////////
    //     FORMATEO DE CAMPOS          //
    ///////////////////////////////////

    // VALIDADOR PARA EL FORMULARIO DEL EVENTO
    var formValidator = new FormValidator('formEvento', {
        nombre: {
            pattern: '^(?!\\s*$).{1,70}$',
            required: true
        },
        tipo_evento_id: {
            required: true
        },
        precio: {
            required: true
        },
        fecha: {
            pattern: '^\\d{2}-\\d{2}-\\d{4}$', // Se espera formato dd-mm-aaaa (por ejemplo, 31-12-2025)
            required: true
        },
        franja_horaria_id: {
            required: true
        },
        capacidad: {
            required: true
        }
    });

    // VALIDADOR PARA EL FORMULARIO DE RESERVA DE EVENTOS
    var formValidatorReserva = new FormValidator('formReservaEvento', {
        ideventoR: {
            required: true
        },
        idusuarioR: {
            required: true
        },
        cantidad: {
            pattern: '^[1-9]\\d*$',
            required: true,
        },
        metodo_pago: {
            required: true
        },
        fecha_pago: {
            pattern: '^\\d{2}-\\d{2}-\\d{4}$', // Se espera formato dd-mm-aaaa (por ejemplo, 31-12-2025)
            required: true
        },
        total: {
            required: true
        }
    });

    // DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL
    $(document).on('click', '#btnAyudaEventos', function (event) {
    event.preventDefault();
    $('#modalAyudaGestionEventos').modal('show');
    });


      // FUNCIÓN PARA VALIDAR EL FORMATO DE LA FECHA (dd-mm-aaaa)
      function validarFecha(fecha_pago) {
        // EXPRESIÓN REGULAR PARA dd-mm-aaaa
        var regex = /^(\d{2})-(\d{2})-(\d{4})$/;
        if (!regex.test(fecha_pago)) {
            return false;
        }
        var partes = fecha_pago.split("-");
        var dia = parseInt(partes[0], 10);
        var mes = parseInt(partes[1], 10) - 1; 
        var anio = parseInt(partes[2], 10);

        var fechaObjeto = new Date(anio, mes, dia);
        
        return (
            fechaObjeto.getFullYear() === anio &&
            fechaObjeto.getMonth() === mes &&
            fechaObjeto.getDate() === dia
        );
    }

        // VALIDAR CUANDO CAMBIA EL INPUT DE FECHA
    $('#fecha_pago').on('input change', function() {
        var valor = $(this).val();

        if (validarFecha(valor)) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // MÉTODO PARA CONFIGURAR EL SELECT DEL EVENTO DEL MODAL DE RESERVA DE EVENTOS
    function configurarSelect2Evento(selector = '#ideventoR') {
        $(selector).select2({
            width: '100%',
            dropdownParent: $('#modalReservaEventos .modal-content'),
            dropdownPosition: 'below',
            dropdownAutoWidth: true,
            placeholder: 'Seleccione un evento',
            allowClear: true,
            language: {
                noResults: function () {
                    return 'No hay eventos disponibles';
                }
            }
        }).val(null).trigger('change')
        .on('select2:unselect', function () {
            $('#precio_evento').val('0.00');
            $('#cantidad').val(0).prop('disabled', true);
            $('#total').val('0.00');
            formValidatorReserva.clearValidation();
        });
    }
    
    // MÉTODO PARA CONFIGURAR EL SELECT DEL USUARIO DEL MODAL DE RESERVA DE EVENTOS
    function configurarSelect2Usuario(selector = '#idusuarioR') {
        $(selector).select2({
            width: '100%',
            dropdownParent: $('#modalReservaEventos .modal-content'),
            dropdownPosition: 'below',
            dropdownAutoWidth: true,
            placeholder: 'Seleccione un usuario',
            allowClear: true,
            language: {
                noResults: function () {
                    return 'No hay usuarios disponibles';
                }
            }
        }).val(null).trigger('change');
    }

    
        /////////////////////////////////////////
    //     CONTROL DE ACORDEONES          //
    ////////////////////////////////////////

    // VALIDADOR PARA EL FORMULARIO DEL EVENTO
     $('#collapseEventos').on('show.bs.collapse', function() {
        $('#accordion-toggle-eventos')
            .removeClass('bg-primary')
            .addClass('bg-info')
            .css('color', 'white');
    });

    $('#collapseEventos').on('hide.bs.collapse', function() {
        $('#accordion-toggle-eventos')
            .removeClass('bg-info')
            .addClass('bg-primary')
            .css('color', '#e6f0fa');
    });

    $('#accordion-toggle-eventos').hover(
        function() { $(this).css('opacity', '0.9'); },
        function() { $(this).css('opacity', '1'); }
    );

    // VALIDADOR PARA EL FORMULARIO DE RESERVA DE EVENTOS
    $('#collapseAcciones').on('show.bs.collapse', function() {
        $('#accordion-toggle-acciones')
            .removeClass('bg-primary')
            .addClass('bg-info')
            .css('color', 'white');
    });

    $('#collapseAcciones').on('hide.bs.collapse', function() {
        $('#accordion-toggle-acciones')
            .removeClass('bg-info')
            .addClass('bg-primary')
            .css('color', '#e6f0fa');
    });

    $('#accordion-toggle-acciones').hover(
        function() { $(this).css('opacity', '0.9'); },
        function() { $(this).css('opacity', '1'); }
    );
    

    /////////////////////////////////////////
    //     FIN FORMATEO DE CAMPOS          //
    ////////////////////////////////////////

    // INICIALIZAR EL SUMMERNOTE DE LA DESCRIPCIÓN DEL EVENTO (MODAL DE EVENTOS)
     $('#descripcion').summernote({
        height: 200,
        lang: 'es-ES',
        disableDragAndDrop: true,
        disableResizeEditor: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']]
        ]
    });

    /////////////////////////////////////
    // INICIO DE LA TABLA DE EVENTOS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LOS EVENTOS DEL EMPLEADO
    var datatable_eventosConfig = {
        processing: true,
        layout: {
            bottomEnd: { 
                paging: {
                    firstLast: true,
                    numbers: false,
                    previousNext: true
                }
            },
            top2Start: 'pageLength',
        },
        language: {
            paginate: {
                first: '<i class="bi bi-chevron-double-left"></i>',
                last: '<i class="bi bi-chevron-double-right"></i>',
                previous: '<i class="bi bi-chevron-compact-left"></i>',
                next: '<i class="bi bi-chevron-compact-right"></i>'
            }
        },
        columns: [
            { name: 'control', data: null, defaultContent: '', className: 'details-control sorting_1 text-center' }, // Columna 0: Mostrar más
            { name: 'nombre', data: 'nombre' }, // Columna 1: Nombre evento
            { name: 'imagen', data: 'imagen' }, // Columna 2: Imagen
            { name: 'fecha', data: 'fecha' }, // Columna 3: Fecha de evento
            { name: 'empleado_usuario', data: 'empleado_usuario' }, // Columna 4: Usuario empleado
            { name: 'reservar', data: null, defaultContent: '' },  // Columna 5: Botón para Ver Reservas de evento
            { name: 'editar', data: null, defaultContent: '' },  // Columna 6: Botón para Editar
            { name: 'eliminar', data: null, defaultContent: '' }   // Columna 7: Botón para Eliminar
        ],
        
        columnDefs: [
              // Columna 0: BOTÓN MÁS 
            { 
                targets: 'control:name', width: '5%', searchable: false, orderable: false, className: "text-center" 
            },
            {
                // Columna 1: Nombre (recorta a 20 caracteres)
                targets: 'nombre:name',
                width: '50%',
                className: "text-center",
                searchable: true,
                orderable: true,
                render: function(data) {
                    if (!data) return '';
                    const maxLength = 20;
                    return (data.length > maxLength)
                        ? data.substring(0, maxLength) + '...'
                        : data;
                }
            },
            {
                // Columna 2: Imagen
                targets: 'imagen:name',
                width: '5%',
                className: "text-center",
                searchable: true,
                orderable: false,
                render: function(data, type, row) {
                    if (!data) return ''; // Validación añadida
                    return `
                        <img src="${base_url + 'assets/imagenes/eventos/' + data}" 
                             style="width: 40px; height: 30px; object-fit: cover;"
                             alt="Imagen evento"
                             data-toggle="tooltip-primary" data-placement="top">
                    `;
                }
            },
            {
                // Columna 3: Fecha de evento (evento)
                targets: 'fecha:name',
                width: '10%',
                searchable: true,
                orderable: true,
                className: "text-center",
                render: function(data, type, row) {
                    if (type === "display" || type === "filter") {
                        return formatoFechaEuropeo(data); // Muestra "DD-MM-YYYY"
                    }
                    return data; // Ordenamiento/filtro usa "YYYY-MM-DD" (original)
                }
            },
            {
                // Columna 4: Empleado usuario (empleado_usuario)
                targets: 'empleado_usuario:name',
                width: '10%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },    
            {
                // Columna 5 - Reservar
                targets: 'reservar:name',
                width: '10%',
                render: function(data, type, row) {
                    return `
                    <button type="button" class="btn btn-primary btn-sm ver-reservas"
                        title="Ver listado completo de reservas"
                        data-id="${row?.id || ''}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top">
                        <i class="far fa-calendar-check"></i>
                        <span class="d-none d-md-inline ms-2">Reservas</span>
                    </button>`;
                }
            },
            {
                // Columna 6: Botón para Editar
                targets: 'editar:name',
                width: '5%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || !row.id) {
                        console.error('Datos de fila incompletos:', row);
                        return '';
                    }
            
                    // 1. Verificar si el evento ya pasó (fecha + hora_inicio)
                    var fechaHoraEvento = new Date(row.fecha + 'T' + (row.hora_inicio || '00:00:00'));
                    var ahora = new Date();
                    var esPasado = fechaHoraEvento < ahora;
            
                    // 2. Crear botón condicional
                    if (esPasado) {
                        // Botón DESHABILITADO (evento pasado)
                        return `<button type="button" class="btn btn-info btn-sm" disabled
                                  data-toggle="tooltip-primary" data-placement="top" 
                                  title="No editable (evento ya ocurrió)">
                              <i class="fa-solid fa-edit"></i>
                            </button>`;
                    } else {
                        // Botón HABILITADO (evento futuro)
                        return `<button type="button" class="btn btn-info btn-sm editarEvento"
                                  data-toggle="tooltip-primary" data-placement="top" 
                                  title="Editar"
                                  data-id="${row.id || ''}" 
                                  data-tipo_ev_id="${row.tipo_evento_id || ''}" 
                                  data-franja_horaria_id="${row.franja_horaria_id || ''}">
                              <i class="fa-solid fa-edit"></i>
                            </button>`;
                    }
                }
            },
             {
                // Columna 7: Botón para Eliminar
                targets: 'eliminar:name',
                width: '5%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || !row.id) {
                        console.error('Datos de fila incompletos:', row);
                        return '';
                    }
                
                    // 1. Verificar si el evento ya pasó (fecha + hora_inicio)
                    var fechaHoraEvento = new Date(row.fecha + 'T' + (row.hora_inicio || '00:00:00'));
                    var ahora = new Date();
                    var esPasado = fechaHoraEvento < ahora;
                
                    // 2. Crear botón condicional
                     if (esPasado) {
                        // Botón DESHABILITADO (evento pasado)
                        return `<button type="button" class="btn btn-danger btn-sm" disabled
                                data-toggle="tooltip-primary" data-placement="top"
                                title="No eliminable (evento ya ocurrió)"
                                data-nombre="${escapeHtmlAttr(row.nombre) || ''}"
                                data-empleado="${escapeHtmlAttr(row.empleado_usuario) || ''}">
                            <i class="fa-solid fa-trash"></i>
                            </button>`;
                    } else {
                        // Botón HABILITADO (evento futuro)
                        return `<button type="button" class="btn btn-danger btn-sm eliminarEvento"
                                data-toggle="tooltip-primary" data-placement="top"
                                title="Eliminar"
                                data-id="${row.id || ''}"
                                data-nombre="${escapeHtmlAttr(row.nombre) || ''}"
                                data-empleado="${escapeHtmlAttr(row.empleado_usuario) || ''}">
                            <i class="fa-solid fa-trash"></i>
                            </button>`;
                    }
                }
            }
        ],
        
        ajax: {
            // AJAX PARA OBTENER TODOS LOS EVENTOS DEL EMPLEADO DE EVENTOS
            url: base_url + "EmpleadoEventos/obtenerEventosEmpleado",
            type: 'GET',
            dataSrc: function(json) {
                return json.data || json;
            },
            error: function(xhr, error, thrown) {
                console.error('Error al cargar eventos:', error);
                return []; // Devuelve array vacío en caso de error
            }
        },
        order: [[3, 'asc']], // ordenar por la columna 3 - FECHA EVENTO
        rowGroup: {
            dataSrc: function (row) {
                return formatoFechaEuropeoSoloFecha(row.fecha);
            },
            startRender: function (rows, group) {
                let $row = $('<tr/>').append('<td colspan="9" class="group-header">' + group + ' / ' + rows.count() + ' evento/s' + '</td>');
                return $row;
            } // de la function startRender
        }, // de la rowGroup
        initComplete: function() {
            const api = this.api();
    
            // APLICAR EL FILTRO PARA QUE NADA MÁS INICIAR LA TABLA, MUESTRE SOLO REGISTROS FUTUROS DE EVENTOS
            aplicarFiltro(api);
        }
    };
    
    ////////////////////////////
    // FIN DE LA TABLA DE    //
    ///////////////////////////


    /************************************/
    //     ZONA DE DEFINICIONES        //
    /**********************************/
    // Definición inicial de la tabla de empleados
    var $table = $('#eventos_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_eventosConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#eventos_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#eventos_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    /* en el $columnFilterInputs solo cambiar el nombre de la tabla que encontraremos en HTML*/

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

      // CADA VEZ QUE CAMBIA EL FILTRO DE FILTRAR POR FECHA (PASADA, FUTURA, ETC) SE LLAMA A APLICAR FILTRO
    $('input[name="filterDates"]').on('change', function() {
        aplicarFiltro(table_e);  // Aplicar filtro cuando se cambia
    });

    // MÉTODO PARA MOSTRAR EL CONTENIDO DEL MOSTRAR MÁS
    function format(d) {
        return `
        <div class="card border-primary mb-3" style="overflow: visible;">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-event fs-3 me-2"></i>
                    <h5 class="card-title mb-0">Detalles del Evento</h5>
                </div>
            </div>
            <div class="card-body p-0" style="overflow: visible;">
                <table class="table table-borderless table-striped table-hover mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" class="ps-4 w-25 align-top"><i class="bi bi-tag me-2"></i>ID Evento</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.id || '<span class="text-muted fst-italic">No tiene un id de evento</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-image me-2"></i>Imagen</th>
                            <td class="pe-4">
                                <img src="${base_url + 'assets/imagenes/eventos/' + d.imagen}" 
                                     class="img-fluid rounded border" 
                                     style="max-width: 100%; height: auto; max-height: 250px; object-fit: cover;" 
                                     alt="Imagen del evento"
                                     data-bs-toggle="tooltip" data-bs-placement="top" title="Imagen del evento">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-card-heading me-2"></i>Nombre</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${escapeHtmlAttr(d.nombre) || '<span class="text-muted fst-italic">No tiene un nombre de evento</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-text-paragraph me-2"></i>Descripción</th>
                            <td class="pe-4">
                                <div class="bg-light p-2 rounded" style="white-space: pre-wrap; word-wrap: break-word;">
                                    ${escapeHtmlAttr(d.descripcion) || '<span class="text-muted fst-italic">No tiene una descripción</span>'}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-grid me-2"></i>Tipo</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${escapeHtmlAttr(d.nombre_tipo) || '<span class="text-muted fst-italic">No tiene un tipo</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-clock me-2"></i>Horario</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.hora_inicio && d.hora_fin ? `${d.hora_inicio.substring(0,5)} - ${d.hora_fin.substring(0,5)}` : '<span class="text-muted fst-italic">No tiene un horario</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-cash-coin me-2"></i>Precio</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.precio ? `${d.precio}€` : '<span class="text-muted fst-italic">No tiene un precio</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-people me-2"></i>Capacidad</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.capacidad || '<span class="text-muted fst-italic">No tiene una capacidad</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-people me-2"></i>Total recaudado</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.total_recaudado || '<span class="text-muted fst-italic">No tiene un total recaudado</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-people me-2"></i>Reservas pagadas</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.reservas_pagadas || '<span class="text-muted fst-italic">No tiene reservas pagadas</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4 align-top"><i class="bi bi-people me-2"></i>Total reservas</th>
                            <td class="pe-4" style="white-space: pre-wrap; word-wrap: break-word;">${d.total_reservas || '<span class="text-muted fst-italic">No tiene un total de reservas</span>'}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-top-0 text-end">
                <small class="text-muted">Actualizado: ${new Date().toLocaleDateString()}</small>
            </div>
        </div>`;
    }

    // MÉTODO QUE MUESTRA TODO DEL MOSTRAR MÁS
    $tableBody.on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table_e.row(tr);

        if (row.child.isShown()) {
            // Esta fila ya está abierta, la cerramos
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Abrir esta fila
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    /************************************/
    //   FIN ZONA DE DEFINICIONES      //
    /**********************************/

    //////////////////////////////////////
// Función para cargar tipos de evento en select //
//////////////////////////////////////
function cargarTiposEventoEnSelect(selectId, idTipoSeleccionado = null) {
    // AJAX PARA OBTENER A TODOS LOS EMPLEADOS DE EVENTOS DISPONIBLES
    $.get(base_url + "tipoEventos/getTipoEventos", function(data) {
        let $select = $(selectId);
        $select.empty(); // Limpiar opciones actuales

        // Agregar opción por defecto según el tipo de select
        const textoPorDefecto = selectId === "filtro-tipo" 
            ? "-- Todos --" 
            : "-- Selecciona un tipo de evento --";
        
        $select.append($('<option>', {
            value: '',
            text: textoPorDefecto
        }));

        // Parsear si viene como string
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                return;
            }
        }

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(function(tipo) {
                let option = $('<option>', {
                    value: tipo.id,
                    text: tipo.nombre
                });

                if (idTipoSeleccionado && tipo.id == idTipoSeleccionado) {
                    option.prop('selected', true);
                }

                $select.append(option);
            });
        } else {
            $select.append($('<option>', {
                value: '',
                text: 'No hay tipos de evento disponibles'
            }));
        }
    }, "json").fail(function(xhr, status, error) {
        console.error("Error al cargar tipos de evento:", error);
        $(selectId).html(
            $('<option>', {
                value: '',
                text: 'Error al cargar tipos de evento'
            })
        );
    });
}

// VARIABLES GLOBAL ES DE FECHA Y HORARIO QUE HAY ESCOGIDOS, PARA TRABAJAR CON EL MODAL
// MÁS ADELANTE
var fechaOriginalEvento = null;
var horarioOriginalEvento = null;

// CARGAR HORARIOS AL CAMBIAR LA FECHA DE EVENTO
$(document).on('change', '#fecha', function() {
    const fechaInput = $(this).val();
    if (!fechaInput) return;
    
    // LIMPIAR POSIBLES VALIDACIONES QUE PUEDAN TENER FECHA Y HORARIOS ANTERIORES
    formValidator.clearValidation('fecha');
    formValidator.clearValidation('franja_horaria_id');
    
    const partes = fechaInput.split('-');
    if (partes.length !== 3) return;
    
    const fechaBackend = `${partes[2]}-${partes[1]}-${partes[0]}`;
    const esMismaFecha = (fechaBackend === fechaOriginalEvento);
    
    // DESPUÉS DE ESCOGER LA FECHA, SE CARGAN LOS HORARIOS DISPONIBLES DE ESE DÍA
    cargarHorariosDisponibles(fechaBackend, esMismaFecha ? horarioOriginalEvento : null);
});

// MÉTODO PARA CARGAR LOS HORARIOS DISPONIBLES
function cargarHorariosDisponibles(fecha, idHorarioSeleccionado = null) {
    const $select = $("#franja_horaria_id");
    
    // Mostrar estado de carga
    $select.empty();
    
    const esFechaOriginal = (fecha === fechaOriginalEvento);
    
    // AJAX PARA RECOGER HORARIOS DISPONIBLES PARA EVENTOS DE ESA FECHA
    return $.ajax({
        url: base_url + "EmpleadoEventos/obtenerHorariosDisponibles",
        type: "GET",
        data: { 
            fecha: fecha,
            horario_actual: esFechaOriginal ? idHorarioSeleccionado : null
        },
        dataType: "json",
        success: function(response) {
            if (!response || !response.success) {
                $select.html('<option value="">Error al cargar horarios</option>');
                return;
            }

            // Limpiar el select
            $select.empty();
            
            // VERIFICAR SI HAY HORARIOS DISPONIBLES
            if (!response.horarios || response.horarios.length === 0) {
                $select.append('<option value="">No hay horarios disponibles</option>');
                return;
            }
            
            // AGREGAR OPCIÓN DE SELECCIONAR HORARIOS SOLO SI HAY
            $select.append('<option value="">Seleccione horario</option>');
            
            // Construir opciones de horarios
            let horarioExiste = false;
            response.horarios.forEach(horario => {
                const selected = (esFechaOriginal && horario.id == idHorarioSeleccionado) ? 'selected' : '';
                if (selected) horarioExiste = true;
                $select.append(`<option value="${horario.id}" ${selected}>${horario.hora_inicio} - ${horario.hora_fin}</option>`);
            });
            
            // Forzar selección si es necesario (solo si es la fecha original)
            if (esFechaOriginal && horarioExiste && idHorarioSeleccionado) {
                $select.val(idHorarioSeleccionado);
            }
        },
        error: function() {
            $select.html('<option value="">Error al cargar horarios</option>');
        }
    });
}


//////////////////////////////////////
    // Funcion para cargar el select de usuarios    //
    ///////////////////////////// ///////
    function cargarUsuarios(selectId, idUsuarioSeleccionado = null) {
        // AJAX PARA OBTENER A TODOS LOS USUARIOS ACTIVOS
        $.get(base_url + "Empleado/getUsuariosActivos", function(response) {
            console.log("Datos de usuarios recibidos:", response);
            
            let $select = $(selectId);
            $select.empty(); // Limpiar opciones actuales
    
            // Agregar opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione un usuario...'
            }));
    
            const data = response.success ? response.data : response;
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(usuario) {
                    // Usar EXCLUSIVAMENTE nombre_usuario
                    if (usuario.nombre_usuario) {
                        let option = $('<option>', {
                            value: usuario.id,
                            text: usuario.nombre_usuario
                        });
    
                        if (idUsuarioSeleccionado && usuario.id == idUsuarioSeleccionado) {
                            option.prop('selected', true);
                        }
    
                        $select.append(option);
                    }
                });
            } else {
                $select.append($('<option>', {
                    value: '',
                    text: 'No hay usuarios disponibles'
                }));
            }
            
        }, "json").fail(function(xhr, status, error) {
            console.error("Error al cargar usuarios:", status, error);
            $(selectId).html("<option value=''>Error al cargar</option>");
        });
    }

    //////////////////////////////////////
    // Funcion para cargar el select de usuarios    //
    ///////////////////////////// ///////
    // AJAX PARA OBTENER TODOS LOS EVENTOS PRÓXIMOS
    function cargarEventos(selectId, idEventoSeleccionado = null) {
        $.get(base_url + "eventos/proximosEmpleado", function(response) {
            console.log("Eventos recibidos:", response);
    
            let $select = $(selectId);
            $select.empty();
    
            // Agregar una opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione un evento...'
            }));
    
            const data = response.success ? response.data : response;
    
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(evento) {
                    if (evento.nombre) {
                        // Añadir los datos adicionales a cada opción
                        let option = $('<option>', {
                            value: evento.id,
                            text: evento.nombre,
                            'data-precio': evento.precio,               // Precio del evento
                            'data-capacidad': evento.capacidad,         // Capacidad del evento
                            'data-plazas_reservadas': evento.plazas_reservadas // Plazas reservadas
                        });
    
                        // Marcar como seleccionado el evento, si es necesario
                        if (idEventoSeleccionado && evento.id == idEventoSeleccionado) {
                            option.prop('selected', true);
                        }
    
                        // Agregar la opción al select
                        $select.append(option);
                    }
                });
            } else {
                $select.append($('<option>', {
                    value: '',
                    text: 'No hay eventos disponibles'
                }));
            }
    
        }, "json").fail(function(xhr, status, error) {
            console.error("Error al cargar eventos:", status, error);
            $(selectId).html("<option value=''>Error al cargar</option>");
        });
    }
    


    
    //////////////////////////////////////
    //              FIN                 //
    // Funcion para cargar un select    //
    ///////////////////////////// ///////


    ////////////////////////////////////////////
    //   INICIO ZONA FUNCIONES DE APOYO      //
    //////////////////////////////////////////
 
    /////////////////////////////////////
    //   INICIO ZONA DELETE EVENTOS  //
    ///////////////////////////////////
        // MÉTODO PARA ELIMINAR EL EVENTO
       function eliminarEvento(id, nombreEvento, empleadoResponsable) {
    // PRIMERO SE HACE UN AJAX PARA COMPROBAR SI EXISTEN RESERVAS HECHAS PARA ESE EVENTO, EN ESE CASO, EL EVENTO NO SE PUEDE ELIMINAR
    $.ajax({
        url: base_url + "EmpleadoEventos/verificarReservasEvento/" + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.totalReservas > 0) {
                // SE MUESTRA LA ADVERTENCIA DE QUE HAY RESERVAS
                Swal.fire({
                    title: 'No se puede eliminar',
                    html: `El evento <strong>${escapeHtmlAttr(nombreEvento)}</strong> asignado a <strong>${escapeHtmlAttr(empleadoResponsable)}</strong> tiene ${response.totalReservas} reserva(s).<br><br>Debe cancelar las reservas primero.`,
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });

                return;
            }

            // SI NO HAY RESERVAS, SE PREGUNTA POR LA ELIMINACIÓN DEL EVENTO
            Swal.fire({
                title: 'Eliminar Evento',
                html: `¿Desea eliminar el evento <strong>${escapeHtmlAttr(nombreEvento)}</strong> asignado a <strong>${escapeHtmlAttr(empleadoResponsable)}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                // SI SE CONFIRMA, SE ELIMINA EL EVENTO
                if (result.isConfirmed) {
                    $.post(base_url + "EmpleadoEventos/eliminarEvento/" + id)
                        .done(function(response) {
                            if (response.success) {
                                // SE RECARGA LA TABLA DEL EVENTO
                                recargarSoloTablaEvento();
                                Swal.fire(
                                    'Eliminado',
                                    response.message,
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );
                            }
                        })
                        .fail(function(xhr, status, error) {
                            console.error("Error al eliminar el evento:", error);
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el evento',
                                'error'
                            );
                        });
                }
            });
        },
        error: function() {
            Swal.fire(
                'Error',
                'No se pudieron verificar las reservas',
                'error'
            );
        }
    });
}

    
    // CAPTURAR EL CLICK EN EL BOTÓN DE ELIMINAR
$(document).on('click', '.eliminarEvento', function(event) {
    event.preventDefault();
    
    // DECLARAR Y OBTENER TODOS LOS DATOS NECESARIOS PARA ELIMINAR EL EVENTO
    const $botonEliminar = $(this);
    const idEvento = $botonEliminar.data('id');
    const nombreEvento = $botonEliminar.data('nombre'); // Obligatorio
    const empleadoResponsable = $botonEliminar.data('empleado'); // Obligatorio
    
    // PASAR LOS DATOS AL MÉTODO ELIMINAR EVENTO
    eliminarEvento(idEvento, nombreEvento, empleadoResponsable);
});
    ////////////////////////////////////
    //   FIN ZONA DELETE NOTICIA    //
    //////////////////////////////////

    ///////////////////////////////////////
    //      INICIO ZONA NUEVO           //
    //        BOTON DE NUEVO           // 
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE NUEVO
    $(document).on('click', '#btnnuevo', function(event) {
        event.preventDefault();
        $('#mdltitulo').text('Nuevo registro de evento');
    
        // MOSTRAR EL MODAL DEL EVENTO
        $('#modalEmpleado').modal('show');
    
        // LIMPIAR EL FORMULARIO
        $("#formEvento")[0].reset();
    
        // RESETEAR ID
        $('#formEvento').find('input[name="idevento"]').val("");
        
        // LIMPIAR EL SUMMERNOTE DE DESCRIPCIÓN
        $('#descripcion').summernote('reset'); // VACIAR EL CONTENIDO
        $('#descripcion').summernote('removeFormat'); // Opcional: ELIMINAR FORMATO RESIDUAL
        $('#descripcion').removeClass('is-invalid is-valid'); // LIMPIAR CLASES DE VALIDACIÓN

        // LIMPIAR LAS VALIDACIONES
        formValidator.clearValidation();
        
        // ELIMINAR CUALQUIER CLASE DE VALIDACIÓN DEL INPUT DE IMAGEN
        $('#imagen').val('').removeClass('is-valid is-invalid');
    
        // HABILITAR CAMPOS DE CAPACIDAD Y PRECIO (AL CREAR)
        $("#capacidad").prop('readonly', false).css('background-color', ''); // Fondo normal
        $("#precio").prop('readonly', false).css('background-color', '');   // Fondo normal

        // CARGAR TIPOS DE EVENTO EN EL SELECT CORRESPONDIENTE
        cargarTiposEventoEnSelect('#tipo_evento_id');
    
        // CONFIGURAR FECHA ACTUAL EN FORMATO CORRECTO
        const fechaActual = new Date();
        const dia = String(fechaActual.getDate()).padStart(2, '0');
        const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
        const anio = fechaActual.getFullYear();
    
        // FORMATO VISUAL PARA USUARIO (dd-mm-yyyy)
        const fechaVisual = `${dia}-${mes}-${anio}`;
        
        // FORMATO PARA BACKEND (yyyy-mm-dd)
        const fechaBackend = `${anio}-${mes}-${dia}`;
    
        // ESTABLECER FECHA EN EL INPUT (formato visual)
        $('#fecha').val(fechaVisual);
        
        // CARGAR HORARIOS DISPONIBLES PARA LA FECHA ACTUAL
        cargarHorariosDisponibles(fechaBackend);
    
        // RESETEAR VARIABLES GLOBALES DE FECHA/HORARIO ORIGINAL
        fechaOriginalEvento = null;
        horarioOriginalEvento = null;
    });
    
// CAPTURAR EL CLICK EN EL BOTÓN DE SALVAR
$(document).on('click', '#btnsalvar', async function (event) {
    event.preventDefault();
    
    // RECOGER LOS VALORES DEL FORMULARIO
    var idEvento = $('#formEvento').find('input[name="idevento"]').val().trim();
    var nombre = $('#formEvento').find('input[name="nombre"]').val().trim();
    var descripcion = $('#formEvento').find('#descripcion').summernote('code');
    var descripcionText = $('<div>').html(descripcion).text().trim(); // ✅ Seguro
    var tipo_evento_id = $('#formEvento').find('select[name="tipo_evento_id"]').val().trim();
    var precio = $('#formEvento').find('input[name="precio"]').val().trim();
    var franja_horaria_id = $('#formEvento').find('select[name="franja_horaria_id"]').val().trim();
    var capacidad = $('#formEvento').find('input[name="capacidad"]').val().trim();
    var imagenFile = $('#imagen')[0].files[0];
    
    // FORMATEAR LA FECHA DEL EVENTO (dd-mm-yyyy a yyyy-mm-dd)
    var fechaInput = $('#formEvento').find('input[name="fecha"]').val().trim();
    var fechaFormateada = "";
    if (fechaInput !== "") {
        var parts = fechaInput.split('-');
        if (parts.length === 3) {
            fechaFormateada = parts[2] + '-' + parts[1] + '-' + parts[0];
        } else {
            fechaFormateada = fechaInput;
        }
    }

    // VALIDAR EL FORMULARIO
    var isFormValid = formValidator.validateForm(event);
    var isImagenValid = validarImagen();
    
    if (!isFormValid || !isImagenValid) {
        if (!isFormValid) {
            toastr.error("Por favor, corrija los errores en el formulario.", "Error de Validación");
        } else if(!isImagenValid){
            toastr.error("Revise el campo de imagen.", "Error de Validación");
        }
        return;
    }

    // VALIDACIÓN: LA FECHA DEL EVENTO NO PUEDE SER SUPERIOR A 2 AÑOS DESDE HOY
    if (fechaFormateada) {
        const fechaEvento = new Date(fechaFormateada);
        const fechaActual = new Date();
        const fechaMaxima = new Date();
        fechaMaxima.setFullYear(fechaActual.getFullYear() + 2);

        if (fechaEvento > fechaMaxima) {
            toastr.error("La fecha del evento no puede ser para más de 2 años en el futuro.", "Error de Fecha");
            return;
        }
    }

    // VALIDAR EL CONTENIDO DEL EVENTO PRIMERO
    if (!descripcionText) {
        toastr.error("El evento necesita una descripción.", "Error de Validación");
        return;
    }

    // CREAR EL OBJETO DE FORMDATA Y AGREGAR LOS DATOS
    var formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("descripcion", descripcion);
    formData.append("tipo_evento_id", tipo_evento_id);
    formData.append("precio", precio);
    formData.append("franja_horaria_id", franja_horaria_id);
    formData.append("capacidad", capacidad);
    formData.append("fecha", fechaFormateada);
    
    // SI HAY NUEVA IMAGEN, TAMBIÉN SE AÑADE
    if (imagenFile) {
        formData.append("imagen", imagenFile);
    }
    
    // SI EXISTE EL ID DEL EVENTO, SE AÑADE, PARA ASÍ EDITAR
    if (idEvento !== "") {
        formData.append("id", idEvento);
    }

    // DETERMINO LA URL DEL ENVÍO DEPENDIENDO DE SI ES CREACIÓN O EDICIÓN
    var urlEnvio = (idEvento === "")
        ? base_url + "EmpleadoEventos/crearEvento"
        : base_url + "EmpleadoEventos/editarEvento/" + idEvento;

    // ENVIAR DATOS AL SERVIDOR
    $.ajax({
        url: urlEnvio,
        type: "POST",
        data: formData,
        processData: false, 
        contentType: false, 
        success: function (response) {
            $('#modalEmpleado').modal('hide');
            recargarSoloTablaEvento(); // RECARGO LA TABLA DE EVENTOS
            $("#formEvento")[0].reset();
            $('#imagen').val('').removeClass('is-valid is-invalid');
            
            if (response.success) {
                toastr.success(response.message || "El evento ha sido guardado", "Éxito");
            } else {
                toastr.error(response.message || "Error al guardar el evento", "Error");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al guardar el evento:", error);
            toastr.error("No se pudo guardar el evento. Por favor, inténtelo de nuevo.", "Error");
        }
    });
});

    ///////////////////////////////////////
    //      FIN ZONA NUEVO           //
    /////////////////////////////////////


    ///////////////////////////////////////
    //      INICIO ZONA EDITAR           //
    //        BOTON DE EDITAR           //
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE EDITAR
    $(document).on('click', '.editarEvento', function (event) {
        event.preventDefault();
        $('#mdltitulo').text('Edición de evento');
    
        // LIMPIAR LAS VALIDACIONES PREVIAS
        formValidator.clearValidation();
        $('#imagen').val('').removeClass('is-valid is-invalid');
    
        // OBTENER EL ID DEL EVENTO A EDITAR
        var idEvento = $(this).data('id');
        
        // AJAX PARA OBTENER EL EVENTO SELECCIONADO PARA EDITAR
        $.ajax({
            url: base_url + "EmpleadoEventos/obtenerEvento/" + idEvento,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log('Respuesta completa:', response);
                
                if (response.success) {
                    var evento = response.evento;
                    console.log('Datos del evento:', evento);
                    
                    if (!evento) {
                        swal.fire('Error', 'Datos del evento no recibidos', 'error');
                        return;
                    }
    
                    // RELLENAR TODOS LOS CAMPOS DEL FORMULARIO CON LA INFORMACIÓN DEL EVENTO
                    $("#idevento").val(evento.id);
                    $("#nombre").val(evento.nombre);
                    // Establecer el contenido en Summernote
                    $('#modalEmpleado .modal-body #descripcion').summernote('code', evento.descripcion);
                    
                    // DESHABILITAR CAPACIDAD Y PRECIO
                    $("#capacidad").val(evento.capacidad)
                        .prop('readonly', true)
                        .css('background-color', '#f5f5f5');
                    
                    $("#precio").val(evento.precio)
                        .prop('readonly', true)
                        .css('background-color', '#f5f5f5');
                    
                    cargarTiposEventoEnSelect('#tipo_evento_id', evento.tipo_evento_id);
                    
                    // GUARDAR LA FECHA Y HORARIO ORIGINAL DEL EVENTO
                    fechaOriginalEvento = evento.fecha;
                    horarioOriginalEvento = evento.franja_horaria_id;
                    
                    // CONVERTIR LA FECHA DE "yyyy-mm-dd" a "dd-mm-yyyy"
                    if (evento.fecha) {
                        var parts = evento.fecha.split('-'); // [yyyy, mm, dd]
                        if (parts.length === 3) {
                            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                            $("#fecha").val(formattedDate);
                            
                            // CARGAR LOS HORARIOS DISPOONIBLES PARA ESA FECHA
                            cargarHorariosDisponibles(evento.fecha, evento.franja_horaria_id)
                                .then(() => {
                                    $('#modalEmpleado').modal('show');
                                });
                        } else {
                            $("#fecha").val(evento.fecha);
                            cargarHorariosDisponibles(evento.fecha, evento.franja_horaria_id)
                                .then(() => {
                                    $('#modalEmpleado').modal('show');
                                });
                        }
                    } else {
                        $("#fecha").val('');
                        $('#modalEmpleado').modal('show');
                    }
                } else {
                    swal.fire('Error', response.message || 'No se pudo obtener el evento para edición', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar el evento para edición:", error, xhr.responseText);
                swal.fire('Error', 'Error en la comunicación con el servidor', 'error');
            }
        });
    });
    
    
    ///////////////////////////////////////
    //        FIN ZONA EDITAR           //
    /////////////////////////////////////


     ////////////////////////////////////////////////////////
    //        ZONA FILTROS RADIOBUTTON CABECERA           //
    ///////////////////////////////////////////////////////
    // Escuchar cambios en los radio buttons
    // Si es necesario filtrar por texto en lugar de valores numéricos, hay que asegurarse que los valores de los radio buttons coincidan con los valores de la columna.
    // Función para aplicar el filtro a la tabla
    function aplicarFiltro(api) {
    var filtro = $('input[name="filterDates"]:checked').val();
    var ahora = new Date(); // Fecha y hora exactas actuales

    // 2. Limpiamos filtros previos (solo para nuestra tabla)
    $.fn.dataTable.ext.search = [];

    if (filtro !== "all") {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            // Verificamos que el filtro solo se aplique a nuestra tabla de eventos
            if (settings.nTable.id !== 'eventos_data') {
                return true; // Ignoramos completamente otras tablas
            }

            var fila = api.row(dataIndex).data();
            
            // Protección contra datos incompletos
            if (!fila || !fila.fecha || !fila.hora_inicio) return false;
            
            var fechaHoraEvento = new Date(fila.fecha + 'T' + fila.hora_inicio);
            var esHoy = fechaHoraEvento.toDateString() === ahora.toDateString();

            switch(filtro) {
                case "past":
                    return fechaHoraEvento < ahora;
                case "current":
                    return esHoy && fechaHoraEvento > ahora;
                case "future":
                    return fechaHoraEvento > ahora;
                default:
                    return true;
            }
        });
    }

    // 3. Aplicamos el filtro y redibujamos SOLO nuestra tabla
    api.draw();

    // Mensaje si no hay resultados
    if (api.rows({ filter: 'applied' }).count() === 0) {
        var mensaje = {
            "past": "No hay eventos pasados",
            "current": "No hay eventos futuros hoy", 
            "future": "No hay eventos futuros",
            "all": "No hay eventos registrados"
        }[filtro];

        $(api.table().body()).html(
            `<tr><td colspan="${api.columns().count()}" class="text-center">${mensaje}</td></tr>`
        );
    }
}
    ////////////////////////////////////////////////////////////
    //        FIN ZONA FILTROS RADIOBUTTON CABECERA          //
    //////////////////////////////////////////////////////////
    ////////////////////////////////////////////////
    //        ZONA FILTRO DE LA FECHA            //
    ///////////////////////////////////////////////
    // EVENTO PARA EL FILTRO DE LA FECHA CUANDO CAMBIA
    $('#dateCreateFilter').on('change', function () {
        var value = $(this).val(); // Obtener el valor seleccionado
        //console.log(value);
        table_e.column(3).search(value).draw();
    });

    // EVENTO DE CLICK PARA EL BORRAR FECHA FILTRO DEL DESPLEGABLE
    $('#borrarFechaFiltro').on('click', function () {
        $('#dateCreateFilter').val('');
        $('#dateCreateFilter').trigger('change');
    });

    // CAMBIAR EL CURSOR
    $('#borrarFechaFiltro').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    }).on('mouseleave', function () {
        $(this).css('cursor', 'default');
    });
    ////////////////////////////////////////////////
    //     FIN ZONA FILTRO DE LA FECHA           //
    ///////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    //        FIN ZONA FILTROS RADIOBUTTON CABECERA          //
    //////////////////////////////////////////////////////////

    ////////////////////////////////////////////////
    //     FIN ZONA FILTRO DE LA FECHA           //
    ///////////////////////////////////////////////


    /*********************************************************** */
    /********************************************************** */
    /* A PARTIR DE AQUI NO TOCAR  SE ACTUALIZA AUTOMATICAMENTE */
    /******************************************************** */
    /******************************************************* */

    $columnFilterInputs.on('keyup', function () {
        var columnIndex = $(this).closest('th').index(); // Obtener el índice de la columna del encabezado correspondiente
        var searchValue = $(this).val(); // Obtener el valor del campo de búsqueda

        // Aplicar el filtro a la columna correspondiente
        table_e.column(columnIndex).search(searchValue).draw();

        // Actualizar el mensaje de filtro
        updateFilterMessage();
    });

    // Función para actualizar el mensaje de filtro activo
    function updateFilterMessage() {
        var activeFilters = false;

        // Revisamos si hay algún filtro activo en cualquier columna
        $columnFilterInputs.each(function () {
            if ($(this).val() !== "") {
                activeFilters = true;
                return false; // Si encontramos un filtro activo, salimos del loop
            }
        });

        // Revisamos si hay un filtro activo en la búsqueda global
        if (table_e.search() !== "") {
            activeFilters = true;
        }

        // Muestra u oculta el mensaje "Hay un filtro activo"
        if (activeFilters) {
            $('#filter-alert').show();
        } else {
            $('#filter-alert').hide();
        }
    }

    // Esto es solo valido para la busqueda superior //
    table_e.on('search.dt', function () {
        updateFilterMessage(); // Actualizar mensaje de filtro
    });
    ////////////////////////////////////////////////////////

    // Botón para limpiar los filtros y ocultar el mensaje ////////////////////////////////////////////
    $('#clear-filter').on('click', function () {
        //console.log('Limpiando filtros...');
        table_e.destroy();  // Destruir la tabla para limpiar los filtros

        // Limpiar los campos de búsqueda del pie de la tabla
        // ejemplo - $('#employees-table tfoot   input').each(function () {
        $columnFilterInputs.each(function () {
            //console.log('Campo:', $(this).attr('placeholder'), 'Valor antes:', $(this).val());
            $(this).val('');  // Limpiar cada campo input del pie y disparar el evento input
            //console.log('Valor después:', $(this).val());
        });

        table_e = $table.DataTable($tableConfig);

        // Ocultar el mensaje de "Hay un filtro activo"
        $('#filter-alert').hide();
    });
    ////////////////////////////////////////////
    //  FIN ZONA FILTROS PIES y SEARCH     //
    ///////////////////////////////////////////

    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LAS RESERVAS DE EVENTOS DE LOS EVENTOS DEL EMPLEADO
    var datatable_reservaeventosConfig = {
        processing: true,
        layout: {
            bottomEnd: { 
                paging: {
                    firstLast: true,
                    numbers: false,
                    previousNext: true
                }
            },
            top2Start: 'pageLength',
        },
        language: {
            paginate: {
                first: '<i class="bi bi-chevron-double-left"></i>',
                last: '<i class="bi bi-chevron-double-right"></i>',
                previous: '<i class="bi bi-chevron-compact-left"></i>',
                next: '<i class="bi bi-chevron-compact-right"></i>'
            },
            emptyTable: "No se ha seleccionado ningún evento" // Mensaje inicial simple
        },
        // Definimos solo las columnas que queremos mostrar al usuario
        columns: [
            { name: 'detalles', data: null, defaultContent: '', className: 'details-control sorting_1 text-center' }, // Columna 0: DETALLES
            { name: 'nombreUsuario', data: 'nombreUsuario' }, // Columna 1: Nombre usuario
            { name: 'pagado', data: 'pagado', defaultContent: '' },  // Columna 3: Botón para Pagar
            { name: 'eliminar', data: null, defaultContent: '' }   // Columna 4: Botón para Eliminar
        ],
        
        columnDefs: [
            // Columna 0: DETALLES
            {
                targets: 'detalles:name',
                width: '5%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm ver-detalleReserva"
                                data-id="${row.id}"
                                data-evento="${escapeHtmlAttr(row.nombreEvento) || ''}"
                                data-tipo="${escapeHtmlAttr(row.tipoEvento) || ''}"
                                data-metodo_pago="${row.metodo_pago || ''}"
                                data-cantidad="${row.cantidad || ''}"
                                data-fecha_pago="${row.fecha_pago || ''}"
                                data-total="${row.total || ''}"
                                data-pagado="${row.pagado || 0}"
                                data-payment_intent_id="${row.payment_intent_id || ''}">
                            <i class="fa fa-eye"></i> Detalles
                        </button>
                    `;
                }
            },
            {
                // Columna 1: Nombre Usuario (recorta a 20 caracteres)
                targets: 'nombreUsuario:name',
                width: '65%',
                className: "text-center",
                searchable: true,
                orderable: true,
                render: function(data) {
                    if (!data) return '';
                    const maxLength = 20;
                    return (data.length > maxLength)
                        ? data.substring(0, maxLength) + '...'
                        : data;
                }
            },
            {
                // Columna de Confirmación de Pago
                targets: 'pagado:name',
                width: '15%',
                searchable: true,
                orderable: true,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || row.id === undefined) return '';
                        
                    // Parseo seguro de estados
                    const estaPagado = parseInt(row.pagado) === 1;
                    const fechaEvento = new Date(`${row.fecha}T${row.hora_inicio || '23:59:59'}`);
                    const esEventoPasado = fechaEvento < new Date();
                        
                    // Para filtrado y ordenamiento, retornar valores numéricos consistentes
                    if (type === 'filter' || type === 'sort') {
                        return estaPagado ? '1' : '0'; // 1=pagado, 0=no pagado
                    }
                        
                    // Para visualización (type === 'display' o undefined)
                    if (esEventoPasado) {
                        return `<span class="badge bg-secondary">Evento finalizado</span>`;
                    }
                        
                    if (estaPagado) {
                    return `
                        <button type="button" 
                                class="btn btn-success btn-sm pago-confirmado"
                                data-id="${row.id}"
                                data-usuario_id="${row.usuario_id}"
                                data-email="${escapeHtmlAttr(row.email)}"
                                data-nombre_usuario="${escapeHtmlAttr(row.nombreUsuario)}"
                                data-nombre_evento="${escapeHtmlAttr(row.nombreEvento)}"
                                data-fecha_evento="${row.fecha}"
                                data-hora_inicio="${row.hora_inicio}"
                                data-hora_fin="${row.hora_fin}"
                                data-participantes="${row.cantidad}"
                                data-total="${row.total}"
                                data-metodo_pago="${row.metodo_pago}"
                                data-toggle="tooltip" 
                                title="Pago confirmado el ${formatoFechaEuropeo(row.fecha_pago) || 'fecha no disponible'}">
                            <i class="fas fa-lock"></i> Pagado
                        </button>
                    `;
                } 

                return `
                    <button type="button" 
                            class="btn btn-warning btn-sm confirmar-pago"
                            data-id="${row.id}"
                            data-usuario_id="${row.usuario_id}"
                            data-email="${escapeHtmlAttr(row.email)}"
                            data-nombre_usuario="${escapeHtmlAttr(row.nombreUsuario)}"
                            data-nombre_evento="${escapeHtmlAttr(row.nombreEvento)}"
                            data-fecha_evento="${row.fecha}"
                            data-hora_inicio="${row.hora_inicio}"
                            data-hora_fin="${row.hora_fin}"
                            data-participantes="${row.cantidad}"
                            data-total="${row.total}"
                            data-metodo_pago="${row.metodo_pago}"
                            data-toggle="tooltip"
                            title="Click para confirmar pago (irreversible)">
                        <i class="fas fa-money-bill-wave"></i> Pagar
                    </button>
                `;
                }
            },
            {
                // Columna 3: Botón para Eliminar
                targets: 'eliminar:name',
                width: '15%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || !row.id) {
                        console.error('Datos de fila incompletos:', row);
                        return '';
                    }
                
                    // 1. Verificar si el evento ya pasó (fecha + hora_inicio)
                    var fechaHoraEvento = new Date(row.fecha + 'T' + (row.hora_inicio || '00:00:00'));
                    var ahora = new Date();
                    var esPasado = fechaHoraEvento < ahora;
                    
                    // 2. Verificar si está pagado
                    var estaPagado = row.pagado == 1;
            
                    // 3. Crear botón condicional
                    if (esPasado || estaPagado) {
                        // Botón DESHABILITADO
                        var motivo = esPasado ? "evento ya ocurrió" : "evento ya pagado";
                        return `<button type="button" class="btn btn-danger btn-sm" disabled
                                  data-toggle="tooltip-primary" data-placement="top"
                                  title="No eliminable (${motivo})">
                              <i class="fa-solid fa-trash"></i>
                            </button>`;
                    } else {
                          // Botón HABILITADO (evento futuro y no pagado)
                        return `<button type="button" class="btn btn-danger btn-sm eliminarReservaEvento"
                                data-id="${row.id}"
                                data-pagado="${row.pagado}"
                                data-email="${escapeHtmlAttr(row.email)}"
                                data-nombre-usuario="${escapeHtmlAttr(row.nombreUsuario)}"
                                data-nombre-evento="${escapeHtmlAttr(row.nombreEvento)}"
                                data-fecha-evento="${row.fecha}"
                                data-hora-inicio="${row.hora_inicio}"
                                data-hora-fin="${row.hora_fin}"
                                data-participantes="${row.cantidad}"
                                data-total="${row.total}"
                                data-metodo-pago="${row.metodo_pago}">
                                <i class="fa-solid fa-trash"></i>
                            </button>`;
                    }
                }
            }
        ],
        
                // En tu datatable_reservaeventosConfig:
        ajax: {
            url: "",
            type: 'GET',
            dataSrc: function(json) {
                // Maneja ambos formatos de respuesta
                return json.data || json;
            }
        }
    };
    
    
    
    ////////////////////////////
    // FIN DE LA TABLA DE    //
    ///////////////////////////

    // MÉTODO PARA PONER CORRECTAMENTE DATOS CON TECLAS ESPECIALES
    function escapeHtmlAttr(text) {
        if (!text) return '';
        return text.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    /************************************/
    //     ZONA DE DEFINICIONES        //
    /**********************************/
    var $tableR = $('#reservas_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfigR = datatable_reservaeventosConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    var $tableBodyR = $('#reservas_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    var $columnFilterInputsR = $('#reservas_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    var table_r = $tableR.DataTable($tableConfigR);

        // FUNCIÓN PARA RECARGAR LAS TABLAS
        function recargarTablas() {
        table_r.ajax.reload();
        // AL RECARGAR LA TABLA DE EVENTOS, SE LE UTILIZA EL FILTRO PARA MOSTRAR EL RADIO SELECCIONADO
        table_e.ajax.reload(function () {
            $('input[name="filterDates"]:checked').trigger('change');
        }, false); // false = mantener la página actual
    }

    function recargarSoloTablaEvento() {
        // AL RECARGAR LA TABLA DE EVENTOS, SE LE UTILIZA EL FILTRO PARA MOSTRAR EL RADIO SELECCIONADO
        table_e.ajax.reload(function () {
            $('input[name="filterDates"]:checked').trigger('change');
        }, false); // false = mantener la página actual
    }

    // EVENTO QUE SE ACCIONA AL HACER CLICK EN EL BOTÓN DE DETALLES, ESTE MUESTRA
    // UN MODAL EN LA RESERVA DEL EVENTO SELECCIONADA
    $(document).on('click', '.ver-detalleReserva', function() {
    const $btn = $(this);
    const fechaPago = $btn.data('fecha_pago') ? formatoFechaEuropeo($btn.data('fecha_pago')) : 'N/D';
    const total = $btn.data('total') ? `${$btn.data('total')}€` : 'N/D';

    // Diccionario para traducir método de pago
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };

    const metodoOriginal = $btn.data('metodo_pago');
    const metodo = metodoOriginal ? (metodosPago[metodoOriginal.toLowerCase()] || 'Otro') : 'N/D';

    // Configurar el modal
    $('#modalEventoLabel').text($btn.data('evento') || 'Detalles de la Reserva');

    // Llenar los datos
    $('#detalle-id').text($btn.data('id') || 'N/D');
    $('#detalle-evento').text($btn.data('evento') || 'N/D');
    $('#detalle-tipo').text($btn.data('tipo') || 'N/D');
    $('#detalle-metodo').text(metodo);
    $('#detalle-cantidad').text($btn.data('cantidad') || 'N/D');
    $('#detalle-fecha').text(fechaPago);
    $('#detalle-total').text(total);

    // Campo pagado
    $('#detalle-pagado').text(
        $btn.data('pagado') !== undefined 
            ? ($btn.data('pagado') ? 'PAGADO' : 'PENDIENTE DE PAGO') 
            : 'N/D'
    );

    // Campo payment_intent_id
    $('#detalle-payment-intent').text(
        $btn.data('payment_intent_id') 
            ? $btn.data('payment_intent_id') 
            : 'No disponible'
    );

    // Mostrar el modal
    new bootstrap.Modal(document.getElementById('modalMostrarEvento')).show();
});

 // MÉTODO PARA MANEJAR EL EVENTO CLICK PARA CARGAR LAS RESERVAS DEL EVENTO
function cargarReservasParaEvento(eventoId) {
    const url = base_url + "EmpleadoEventos/obtenerReservasEventoEscogido/" + eventoId;
    console.log("🔄 Cargando reservas para evento ID:", eventoId);

    table_r.search('').draw(); // Limpia búsqueda
    // HAGO UN AJAX CON ESA URL CUANDO EL EMPLEADO SELECCIONA UNO DE LOS BOTONES DE RESERVAS QUE EXISTEN
    // EN EL EVENTO SELECCIONADO
    table_r.ajax.url(url).load(function(json) {
        
        const dataArray = json.data || json;
        if (!Array.isArray(dataArray)) {
            table_r.settings()[0].oLanguage.sEmptyTable = "Error en los datos";
            table_r.clear().draw();
            return;
        }

        if (dataArray.length === 0) {
            table_r.settings()[0].oLanguage.sEmptyTable = "No hay reservas para este evento";
        }

        // No hace falta usar .clear().rows.add() si usás .ajax.url().load()
    });
}

// EVENTO AL HACER CLICK EN VER RESERVAS, QUE CARGA LAS RESERVAS DEL EVENTO SELECCIONADO 
$(document).on('click', '.ver-reservas', function() {
    const eventoId = $(this).data('id');
    cargarReservasParaEvento(eventoId);
});

// EVENTRO AL HACER CLICK EN EL BOTÓN NUEVA RESERVA
$(document).on('click', '#btnnuevoReserva', function(event) {
    event.preventDefault();

    // MOSTRAR EL MODAL
    $('#modalReservaEventos').modal('show');

    // LIMPIAR EL FORMULARIO
    $("#formReservaEvento")[0].reset();

    $('#formReservaEvento').find('input[name="idreserva"]').val("");
    
    // ESTABLECER VALORES INICIALES EXPLÍCITOS - NUEVO
    $('#precio_evento').val('0.00').prop('readonly', true);
    $('#total').val('0.00').prop('readonly', true);
    $('#cantidad').val(0)
                 .attr('min', 0)
                 .attr('max', 0)
                 .prop('disabled', true); // Asegurar que empiece deshabilitado
    
    // LIMPIAR VALIDACIONES
    formValidatorReserva.clearValidation();

    // CONFIGURAR FECHA ACTUAL EN FORMATO CORRECTO
    const fechaActual = new Date();
    const dia = String(fechaActual.getDate()).padStart(2, '0');
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
    const anio = fechaActual.getFullYear();
    const fechaVisual = `${dia}-${mes}-${anio}`;
    $('#fecha_pago').val(fechaVisual);

    configurarSelect2Evento();
    configurarSelect2Usuario();

    // Cargar datos
    cargarEventos("#ideventoR");
    cargarUsuarios("#idusuarioR");
});

 // EVENTO AL HACER CLICK EN EL BORRAR FECHA PAGO
$(document).on('click', '#borrarFechaPago', function() {
    $('#fecha_pago').val('').trigger('change');
});

// EVENTO PARA CAMBIAR EL CURSOR DE BORRAR FECHA PAGO
$('#borrarFechaPago').on('mouseenter', function () {
    $(this).css('cursor', 'pointer');
}).on('mouseleave', function () {
    $(this).css('cursor', 'default');
});

// MÉTODO PARA CALCULAR EL TOTAL BASADO EN LA CANTIDAD Y PRECIO DEL EVENTO
function calcularTotal() {
    const cantidad = parseInt($('#cantidad').val()) || 0;
    const precio = parseFloat($('#precio_evento').val()) || 0;
    
    // Verificar si hay un evento seleccionado
    if (!$('#ideventoR').val()) {
        $('#total').val('0.00');
        return;
    }
    
    const total = (cantidad * precio).toFixed(2);
    $('#total').val(total);
}

// RECALCULAR TOTAL CUANDO SE SELECCIONA UN EVENTO EN EL MODAL DE RESERVA DE EVENTOS
$(document).on('change', '#ideventoR', function () {
    // LIMPIAR VALIDACIONES PREVIAS
    formValidatorReserva.clearValidation();
    
    const selectedOption = $(this).find('option:selected');
    
    // SI NO HAY OPCIÓN SELECCIONADA, LIMPIAR CAMPOS Y SALIR
    if (selectedOption.length === 0 || !selectedOption.val()) {
        $('#precio_evento').val('0.00');
        $('#cantidad').val(0)
                      .attr('min', 0)
                      .attr('max', 0)
                      .prop('disabled', true);
        $('#total').val('0.00');
        return;
    }

    // PRECIO, CAPACIDAD Y PLAZAS RESERVADAS DEL EVENTO
    const precio = parseFloat(selectedOption.data('precio')) || 0;
    const capacidad = parseInt(selectedOption.data('capacidad')) || 0;
    const plazasReservadas = parseInt(selectedOption.data('plazas_reservadas')) || 0;

    const maximoPermitido = capacidad - plazasReservadas;

    $('#precio_evento').val(precio.toFixed(2));

    if (maximoPermitido <= 0) {
        $('#cantidad').val(0)
                      .attr('min', 0)
                      .attr('max', 0)
                      .attr('title', 'No hay entradas disponibles')
                      .prop('disabled', true);
        Swal.fire({
            icon: 'error',
            title: 'No hay entradas disponibles',
            text: 'Este evento ya ha alcanzado su capacidad máxima.'
        });
    } else {
        $('#cantidad').val(0)
                      .attr('min', 1)
                      .attr('max', maximoPermitido)
                      .attr('title', `Máximo permitido: ${maximoPermitido}`)
                      .prop('disabled', false);
    }

    calcularTotal();
});

// RECALCULAR EL TOTAL CUANDO SE CAMBIA LA CANTIDAD
$(document).on('input', '#cantidad', function() {
    const input = $(this);
    let cantidad = parseInt(input.val()) || 0;
    const max = parseInt(input.attr('max')) || 0;
    const min = parseInt(input.attr('min')) || 1;

    if (isNaN(cantidad)) {
        input.val(min);
        cantidad = min;
    } else if (cantidad < min) {
        input.val(min);
        cantidad = min;
    } else if (cantidad > max) {
        input.val(max);
        cantidad = max;
        Swal.fire({
            icon: 'warning',
            title: 'Límite alcanzado',
            text: `No puedes reservar más de ${max} entradas`,
            timer: 2000,
            showConfirmButton: false
        });
    }

    calcularTotal();
});

// CAPTURAR EL CLICK EN EL BOTÓN DE CREAR RESERVA
$(document).on('click', '#btnsalvarReservaEvento', async function (event) {
    event.preventDefault();

    // RECOGER LOS DATOS DEL FORMULARIO
    const form = $('#formReservaEvento');
    const eventoId = form.find('select[name="ideventoR"]').val().trim();
    const cantidad = form.find('input[name="cantidad"]').val().trim();
    const metodoPago = form.find('select[name="metodo_pago"]').val().trim();
    const total = form.find('input[name="total"]').val().trim();
    const idUsuario = form.find('select[name="idusuarioR"]').val().trim();
    const fechaInput = form.find('input[name="fecha_pago"]').val().trim();

    //  FORMATEO DE FECHA 
    let fechaFormateada = "";
    if (fechaInput !== "") {
        const parts = fechaInput.split('-');
        if (parts.length === 3) {
            fechaFormateada = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
    }

    // VALIDAR EL FORMULARIO
    const isFormValid = formValidatorReserva.validateForm(event);
    if (!isFormValid) {
        toastr.error("Por favor, corrija los errores en el formulario.", "Error de Validación");
        return;
    }

    let eventoInfo = null;

    // OBTENER LA FECHA DEL EVENTO
    try {
        const eventoResponse = await $.get(base_url + 'EmpleadoEventos/obtenerFechaEvento/' + eventoId);

        if (eventoResponse.success && eventoResponse.evento && eventoResponse.evento.fecha) {
            eventoInfo = eventoResponse.evento;

            const fechaParts = eventoInfo.fecha.split('-');
            const fechaEvento = new Date(`${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`);
            const fechaPago = new Date(fechaFormateada);

            if (fechaPago > fechaEvento) {
                toastr.error("La fecha de pago no puede ser posterior a la fecha del evento.", "Error de Fechas");
                return;
            }
        } else {
            toastr.error("No se pudo obtener la información del evento.", "Error");
            return;
        }
    } catch (error) {
        console.error("Error al obtener fecha del evento:", error);
        toastr.error("Error al verificar las fechas.", "Error");
        return;
    }

    // COMPROBAR SI EL USUARIO AL QUE SE LE QUIERE HACER UNA RESERVA, YA TIENE UNA RESERVA
    // HECHA EN ESE EVENTO
    try {
        const checkResponse = await $.post(base_url + 'EmpleadoEventos/comprobarReservaExistente', {
            evento_id: eventoId,
            usuario_id: idUsuario
        });

        // EN CASO AFIRMATIVO, NO SE DEJA AL USUARIO HACER UNA NUEVA RESERVA EN ESE EVENTO, YA QUE YA 
        // TIENE UNA EXISTENTE
        if (checkResponse.existe) {
            toastr.warning("Este usuario ya tiene una reserva para este evento.", "Duplicado");
            return;
        }
    } catch (checkError) {
        console.error("Error al comprobar duplicado:", checkError);
        toastr.error("No se pudo verificar la reserva existente.", "Error");
        return;
    }

    // OBTENER INFORMACIÓN DEL USUARIO DE LA RESERVA
    let usuarioEmail = "", usuarioNombre = "";
    try {
        // AJAX PARA OBTENER TODOS LOS DATOS DEL USUARIO, PARA ENVIARLE UN CORREO CON LOS DETALLES DE SU RESERVA DE EVENTO
        const usuarioResponse = await $.get(base_url + 'Usuario/getUsuarioPorId/' + idUsuario);
        if (usuarioResponse && usuarioResponse.email && usuarioResponse.nombre) {
            usuarioEmail = usuarioResponse.email;
            usuarioNombre = usuarioResponse.nombre;
        } else {
            toastr.warning("No se pudo obtener información del usuario para enviar el correo.", "Advertencia");
        }
    } catch (usuarioError) {
        console.error("Error al obtener el usuario:", usuarioError);
    }

    // CREAR LA RESERVA
    try {
        const formData = new FormData();
        formData.append("evento_id", eventoId);
        formData.append("usuario_id", idUsuario);
        formData.append("cantidad", cantidad);
        formData.append("metodo_pago", metodoPago);
        formData.append("fecha_pago", fechaFormateada);
        formData.append("total", total);

        // AJAX PARA CREAR LA RESERVA
        const response = await $.ajax({
            url: base_url + "EmpleadoEventos/crearReserva",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false
        });

        $('#modalReservaEventos').modal('hide');
        recargarTablas();
        $("#formReservaEvento")[0].reset();

        if (response.success) {
            // PREPARAR EL OBJETO DEL CORREO
            let metodoPagoTexto = metodoPago === 'card' ? 'Tarjeta' :
                metodoPago === 'paypal' ? 'PayPal' :
                metodoPago === 'presencial' ? 'Pago presencial' :
                metodoPago;

            const emailPayload = {
                email: usuarioEmail,
                nombre: usuarioNombre,
                asunto: "Confirmación de tu reserva",
                mensaje: `
                Gracias por realizar tu reserva.
                                    
                Detalles de tu reserva:
                Tipo de reserva: Evento
                Evento: ${eventoInfo.nombre}
                Fecha: ${eventoInfo.fecha}
                Horario: ${eventoInfo.hora_inicio} - ${eventoInfo.hora_fin}
                Número de participantes: ${cantidad}
                Total: ${parseFloat(total).toFixed(2)} €
                Método de pago: ${metodoPagoTexto}`
            };
            // AJAX PARA ENVIAR CORREO CON DETALLES DE LA RESERVA DEL EVENTO
            $.ajax({
                url: base_url + "Contactos/enviar",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(emailPayload),
                dataType: "json",
                success: function(json) {
                    console.log("Correo enviado:", json);
                    Swal.fire({
                        icon: 'success',
                        title: 'Reserva creada correctamente',
                        text: 'La reserva se ha registrado y se ha enviado un correo de confirmación.',
                        confirmButtonText: 'Aceptar'
                    });
                },
                error: function(xhr) {
                    console.error("Error al enviar el correo:", xhr);
                    toastr.success(response.message || "Reserva creada correctamente", "Éxito");
                }
            });

        } else {
            toastr.error(response.message || "Error al crear la reserva", "Error");
        }

    } catch (error) {
        console.error("Error al crear la reserva:", error);
        toastr.error("No se pudo crear la reserva. Por favor, inténtelo de nuevo.", "Error");
    }
});


// FUNCIÓN PARA CONFIRMAR PAGO (NO PAGADO)
function confirmarPago(idReserva, datosPago) {
    // Traducción del método de pago
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    const metodoPagoTraducido = metodosPago[datosPago.metodoPago] || 'Desconocido';
    // NOTIFICACIÓN PARA CONFIRMAR EL PAGO
    Swal.fire({
        title: 'Confirmar Pago',
        html: `
            ¿Marcar reserva <b>${idReserva}</b> como PAGADA?<br><br>
            <ul style="text-align:left;">
                <li><b>Usuario:</b> ${escapeHtmlAttr(datosPago.usuarioNombre)}</li>
                <li><b>Evento:</b> ${escapeHtmlAttr(datosPago.nombreEvento)}</li>
                <li><b>Fecha:</b> ${formatoFechaEuropeo(datosPago.fechaEvento)}</li>
                <li><b>Horario:</b> ${datosPago.horaInicio} - ${datosPago.horaFin}</li>
                <li><b>Participantes:</b> ${datosPago.participantes}</li>
                <li><b>Total:</b> ${parseFloat(datosPago.total).toFixed(2)} €</li>
                <li><b>Método de pago:</b> ${metodoPagoTraducido}</li>
            </ul>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // EN CASO DE CONFIRMARLO, SE HACE UN AJAX
            $.post(base_url + "EmpleadoEventos/confirmarPago/" + idReserva)
                .done(function() {
                    // UNA VEZ QUE SE CONFIRMA EL PAGO, SE PREPARA OTRO AJAX PARA ENVIAR EL CORREO CON LOS DETALLES DE QUE SE HA PAGADO LA RESERVA
                    enviarCorreoPago({
                        email: datosPago.usuarioEmail,
                        nombre: datosPago.usuarioNombre,
                        asunto: `Pago confirmado para evento ${escapeHtmlAttr(datosPago.nombreEvento)}`,
                       mensaje: `
                        Hola ${escapeHtmlAttr(datosPago.usuarioNombre)},

                        Confirmamos que hemos recibido el pago de tu reserva para el evento ${datosPago.nombreEvento}.

                        Detalles:
                        Fecha: ${formatoFechaEuropeo(datosPago.fechaEvento)}
                        Horario: ${datosPago.horaInicio} - ${datosPago.horaFin}
                        Participantes: ${datosPago.participantes}
                        Total pagado: ${parseFloat(datosPago.total).toFixed(2)} €
                        Método de pago: ${metodoPagoTraducido}

                        ¡Gracias por tu reserva!

                        Saludos,
                        El equipo.
                        `,
                    })
                    .done(function() {
                        // RECARGO LAS TABLAS Y INFORMO AL EMPLEADO
                        recargarTablas();
                        Swal.fire('¡Pagado!', 'El pago fue registrado y el correo enviado', 'success');
                    })
                    .fail(function() {
                        Swal.fire('Error', 'Pago registrado, pero fallo al enviar el correo', 'warning');
                    });
                })
                .fail(function() {
                    Swal.fire('Error', 'No se pudo confirmar el pago', 'error');
                });
        }
    });
}

// MÉTODO PARA ENVIAR EL CORREO
function enviarCorreoPago(datosCorreo) {
    return $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(datosCorreo),
        dataType: "json"
    });
}

// EVENTO CLICK PARA ENVIAR LOS DATOS COMPLETOS AL CONFIRMARPAGO
$(document).on('click', '.confirmar-pago', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const datosPago = {
        usuarioEmail: $btn.data('email'),
        usuarioNombre: $btn.data('nombre_usuario'),
        nombreEvento: $btn.data('nombre_evento'),
        fechaEvento: $btn.data('fecha_evento'),
        horaInicio: $btn.data('hora_inicio'),
        horaFin: $btn.data('hora_fin'),
        participantes: $btn.data('participantes'),
        total: $btn.data('total'),
        metodoPago: $btn.data('metodo_pago'),
    };
    confirmarPago($btn.data('id'), datosPago);
});

// MÉTODO PARA MOSTRAR LA NOTIFICACIÓN CON LOS DETALLES DEL PAGO REALIZADO ANTERIORMENTE, CON SUS DETALLES
$(document).on('click', '.pago-confirmado', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    const usuario = $(this).data('nombre_usuario');
    const evento = $(this).data('nombre_evento');
    const fechaPago = $(this).attr('title'); // ya viene en tooltip
    
    Swal.fire({
        title: `Pago Registrado para ${escapeHtmlAttr(usuario)}`,
        html: `Reserva <b>${id}</b> del evento <b>${escapeHtmlAttr(evento)}</b> ya está pagada.<br>
              <small class="text-muted">${fechaPago}</small>`,
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
});

   /////////////////////////////////////
    //   INICIO ZONA DELETE EVENTOS  //
    ///////////////////////////////////
    // MÉTODO PARA ELIMINAR LA RESERVA DEL EVENTO
function eliminarReservaEvento(id, estaPagado, datosCorreo) {
    if (estaPagado) {
        Swal.fire(
            'Operación no permitida',
            'No se puede eliminar un evento que ya ha sido pagado',
            'warning'
        );
        return;
    }

    // Traducción del método de pago
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };

    const metodoPagoTraducido = metodosPago[datosCorreo.metodoPago] || 'Desconocido';

    // Mensaje HTML con los detalles del evento + usuario
    const detallesHTML = `
        <p><strong>Usuario:</strong> ${datosCorreo.usuarioNombre}</p>
        <p><strong>Evento:</strong> ${datosCorreo.nombreEvento}</p>
        <p><strong>Fecha:</strong> ${formatoFechaEuropeo(datosCorreo.fechaEvento)}</p>
        <p><strong>Horario:</strong> ${datosCorreo.horaInicio?.substring(0, 5)} - ${datosCorreo.horaFin?.substring(0, 5)}</p>
        <p><strong>Participantes:</strong> ${datosCorreo.participantes}</p>
        <p><strong>Total:</strong> ${parseFloat(datosCorreo.total).toFixed(2)} €</p>
        <p><strong>Método de pago:</strong> ${metodoPagoTraducido}</p>
        <hr>
        <span class="text-success">Al eliminar, las entradas reservadas quedarán disponibles nuevamente.</span>
    `;

    Swal.fire({
        title: '¿Eliminar Reserva?',
        html: detallesHTML,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
        customClass: { htmlContainer: 'text-left' }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando',
                html: 'Liberando entradas...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                // AJAX PARA ELIMINAR LA RESERVA
                url: base_url + "EmpleadoEventos/eliminarReserva/" + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        cancelarReservaEvento(id, datosCorreo);
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo eliminar la reserva', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error al conectar con el servidor', 'error');
                }
            });
        }
    });
}

//MÉTODO PARA CANCELAR LA RESERVA DEL EVENTO
function cancelarReservaEvento(reservaId, datosCorreo) {
    console.log("Cancelando reserva de evento, ID:", reservaId);

    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };

    const metodoPagoTraducido = metodosPago[datosCorreo.metodoPago] || 'Desconocido';

    const mensajeCorreo = `
    Tipo de reserva: Evento
    Evento: ${datosCorreo.nombreEvento}
    Fecha: ${formatoFechaEuropeo(datosCorreo.fechaEvento)}
    Horario: ${datosCorreo.horaInicio?.substring(0, 5)} - ${datosCorreo.horaFin?.substring(0, 5)}
    Número de participantes: ${datosCorreo.participantes}
    Total: ${parseFloat(datosCorreo.total).toFixed(2)} €
    Método de pago: ${metodoPagoTraducido}

    Lamentamos informarte que tu reserva ha sido cancelada. 
    Si no has solicitado esta cancelación, por favor contacta con nuestro equipo.
    `.trim();

    const emailPayload = {
        email: datosCorreo.usuarioEmail,
        nombre: datosCorreo.usuarioNombre,
        asunto: "Cancelación de tu reserva de evento",
        mensaje: mensajeCorreo
    };

    console.log("Payload enviado al backend:", emailPayload);

    if (!emailPayload.email || !emailPayload.nombre || !emailPayload.mensaje) {
        console.error("Faltan campos obligatorios en el payload del correo:", emailPayload);
        return;
    }
    // AJAX PARA ENVIAR EL CORREO QUE NOTIFICA DE LA CANCELACIÓN DE LA RESERVA DEL EVENTO
    $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(emailPayload),
        dataType: "json",
        success: function() {
            recargarTablas();
            Swal.fire('Cancelado', 'La reserva ha sido cancelada y se ha notificado al usuario.', 'success');
        },
        error: function(xhr) {
            console.error("Error al enviar correo:", xhr.responseText);
            recargarTablas();
            Swal.fire('Error', 'La reserva se canceló pero no se pudo enviar la notificación. Por favor, contacta al usuario manualmente.', 'error');
        }
    });
}
    
    // MÉTODO PARA RECOGER LA INFORMACIÓN DE LA RESERVA DEL EVENTO Y LLAMAR AL ELIMINAR RESERVA EVENTO
    $(document).on('click', '.eliminarReservaEvento', function(event) {
    event.preventDefault();
    let $btn = $(this);

let datosCorreo = {
    usuarioEmail: $btn.data('email'),
    usuarioNombre: $btn.data('nombreUsuario').trim(),
    nombreEvento: $btn.data('nombreEvento').trim(),
    fechaEvento: $btn.data('fechaEvento'),
    horaInicio: $btn.data('horaInicio'),
    horaFin: $btn.data('horaFin'),
    participantes: $btn.data('participantes'),
    total: $btn.data('total'),
    metodoPago: $btn.data('metodoPago')
};


    console.log(datosCorreo);

    let idReserva = $btn.data('id');
    let estaPagado = $btn.data('pagado') == 1;

    eliminarReservaEvento(idReserva, estaPagado, datosCorreo);
});



   // Evento para filtrar por columnas (inputs en el footer)
$columnFilterInputsR.on('keyup', function () {
    var columnIndex = $(this).closest('th').index(); // Índice de la columna
    var searchValue = $(this).val(); // Valor del input
    table_r.column(columnIndex).search(searchValue).draw(); // Aplicar filtro
    updateFilterMessage2(); // Actualizar mensaje de filtro
});

// Función para actualizar el mensaje de filtro activo
function updateFilterMessage2() {
    var activeFilters = false;
    
    // Verificar filtros por columna
    $columnFilterInputsR.each(function () {
        if ($(this).val() !== "") {
            activeFilters = true;
            return false; // Salir del bucle si hay filtro
        }
    });
    
    // Verificar búsqueda global
    if (table_r.search() !== "") {
        activeFilters = true;
    }
    
    // Mostrar/ocultar alerta
    $('#filter-alert').toggle(activeFilters);
}

// Evento para búsqueda global (input superior)
table_r.on('search.dt', function () {
    updateFilterMessage2();
});

// Botón para limpiar todos los filtros
$('#clear-filter').on('click', function () {
    // Destruir y recrear la tabla
    table_r.destroy();
    
    // Limpiar inputs de filtro
    $columnFilterInputsR.each(function () {
        $(this).val('');
    });
    
    // Reconstruir DataTable
    table_r = $tableR.DataTable($tableConfigR);
    
    // Ocultar alerta
    $('#filter-alert').hide();
});


}); // de document.ready