$(document).ready(function () {

  // CONFIGURACIÓN DEL CALENDARIO DE FILTROS PARA BUSCAR FECHA EN CONCRETO, ESTE
  // FILTRO ESTA DENTRO DEL DESPLEGABLE DE FILTRO DE CARRERAS

    $('#dateCreateFilter').inputmask('99-99-9999');
  // SE CONFIGURA EN ESPAÑOL
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $('#dateCreateFilter').datepicker({
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1
    });

    /* DATEPICKER DE MODAL FECHA CARRERA */
$('#fecha_carrera').inputmask('99-99-9999');
$('#fecha_carrera').datepicker({
    appendTo: '#modalCarreras .modal-body',
    showAnim: "slideDown",
    dateFormat: 'dd-mm-yy',
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    beforeShow: function(input, inst) {
        setTimeout(function() {
            inst.dpDiv.css({
                'position': 'absolute',
                'top': $(input).offset().top + $(input).outerHeight(),
                'left': $(input).offset().left
            });
        }, 0);
    }
});

// MÉTODO PARA CONFIGURAR CADA VEZ QUE SEA NECESARIO EL SELECT DE 
// USUARIOS QUE HAY EN EL MODAL DE CARRERAS
function configurarSelect2UsuarioCarreras(selector = '#id_usuario') {
    $(selector).select2({
        width: '100%',
        dropdownParent: $('#modalCarreras .modal-content'),
        dropdownPosition: 'below',
        dropdownAutoWidth: true,
        placeholder: 'Seleccione un usuario',
        allowClear: true,
        language: {
            noResults: function () {
                return "No hay usuarios disponibles";
            }
        }
    });
}

// MÉTODO PARA CONFIGURAR CADA VEZ QUE SEA NECESARIO EL SELECT DE 
// EMPLEADOS QUE HAY EN EL MODAL DE CARRERAS
function configurarSelect2EmpleadoCarreras(selector = '#id_empleado') {
    $(selector).select2({
        width: '100%',
        dropdownParent: $('#modalCarreras .modal-content'),
        dropdownPosition: 'below',
        dropdownAutoWidth: true,
        placeholder: 'Seleccione un empleado',
        allowClear: true,
        language: {
            noResults: function () {
                return "No hay empleados disponibles";
            }
        }
    });
}

// BORRAR LA FECHA DE LA CARRERA EN EL MODAL DE CARRERA
$('#borrarFechaCarrera').on('click', function() {
    // LIMPIO EL CAMPO DEL FORMULARIO, TANTO EL HORARIO COMO LOS HORARIOS DE ESE DÍA
    $('#fecha_carrera').val('');
    $('#id_horario').empty().append('<option value="">Seleccione primero una fecha</option>');
    
    // LIMPIO LAS VALIDACIONES, YA QUE ESTABAN LOS CAMPOS EN ROJO Y CON MENSAJE DE ERROR
    formValidator.clearValidation('fecha_carrera');
    formValidator.clearValidation('id_horario');
    
    // DISPARO EL EVENTO DE CHANGE DE LA FECHA DE CARRERA
    $('#fecha_carrera').trigger('change');
});
    // CAMBIO EL CURSOR PARA EL BOTÓN DE BORRAR FECHA DE CARRERA (EN MODAL DE CARRERAS)
    $('#borrarFechaCarrera').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    }).on('mouseleave', function () {
        $(this).css('cursor', 'default');
    });

   /* DATEPICKER DE MODAL FECHA PAGO (EN MODAL DE CARRERAS) */
$('#fecha_pago').inputmask('99-99-9999');
$('#fecha_pago').datepicker({
    appendTo: '#modalCarreras .modal-body', 
    showAnim: "slideDown",
    dateFormat: 'dd-mm-yy',
    showOtherMonths: true,
    selectOtherMonths: true,
    numberOfMonths: 1,
    beforeShow: function(input, inst) {
        setTimeout(function() {
            inst.dpDiv.css({
                'position': 'absolute',
                'top': $(input).offset().top + $(input).outerHeight(),
                'left': $(input).offset().left
            });
        }, 0);
    }
});

// DETECTAR EL CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL DE LA AYUDA
$(document).on('click', '#btnAyudaCarreras', function (event) {
  event.preventDefault();
  $('#modalAyudaGestionCarreras').modal('show');
});

// VALIDADOR PARA LOS CAMPOS DEL FORMULARIO DE CARRERAS

    var formValidator = new FormValidator('formCarrera', {
        id_usuario: {
            required: true
        },
        id_empleado: {
            required: true
        },
        metodo_pago: {
            pattern: '^(card|paypal|presencial)$',
            required: true
        },
        id_pista: {
            required: true
        },
        num_participantes: {
            // Solo números del 1 al 20
            pattern: '^([1-9]|1[0-9]|20)$',
            required: true
        },
        precio: {
            pattern: '^\\d+(\\.\\d{1,2})?$',
            required: true
        },
        fecha_carrera: {
            pattern: '^\\d{2}-\\d{2}-\\d{4}$',
            required: true
        },
        id_horario: {
            required: true
        },
        fecha_pago: {
            pattern: '^\\d{2}-\\d{2}-\\d{4}$',
            required: true
        }
    });

    var formPista = new FormValidator('formPista', {
        nombre_pista: {
            required: true
        },
        precio_pista: {
            required: true
        }
    });


    /////////////////////////////////////////
    //     FIN FORMATEO DE CAMPOS          //
    ////////////////////////////////////////

          /////////////////////////////////////////
    //     CONTROL DE ACORDEONES          //
    ////////////////////////////////////////

     // DESPLEGABLE DE FILTRO DE CARRERAS
     $('#collapseCarreras').on('show.bs.collapse', function() {
        $('#accordion-toggle-carreras')
            .removeClass('bg-primary')
            .addClass('bg-info')
            .css('color', 'white');
    });

    $('#collapseCarreras').on('hide.bs.collapse', function() {
        $('#accordion-toggle-carreras')
            .removeClass('bg-info')
            .addClass('bg-primary')
            .css('color', '#e6f0fa');
    });

    $('#accordion-toggle-carreras').hover(
        function() { $(this).css('opacity', '0.9'); },
        function() { $(this).css('opacity', '1'); }
    );

    // DESPLEGABLE DE ACCIONES DE CARRERAS
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

    /////////////////////////////////////
    // INICIO DE LA TABLA DE CARRERAS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LAS CARRERAS DEL ADMIN
    var datatable_carreraConfig = {
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
        // Definimos las columnas que se mostrarán al usuario
        columns: [
            { name: 'control', data: null, defaultContent: '', className: 'details-control sorting_1 text-center' }, // Columna 0: Mostrar más
            { name: 'usuario_cliente', data: 'usuario_cliente', className: 'text-center' }, // Columna 1: Usuario cliente
            { name: 'usuario_empleado', data: 'usuario_empleado', className: 'text-center' }, // Columna 2: Usuario empleado
            { name: 'nombre_pista', data: 'nombre_pista', className: 'text-center' },       // Columna 3: Nombre de pista
            { name: 'fecha_carrera', data: 'fecha_carrera', className: 'text-center' },   // Columna 4: Fecha de carrera
            { name: 'pagado', data: 'pagado', className: 'text-center' },   // Columna 5: Botón para Pagar
            { name: 'editar', data: null, defaultContent: '', className: 'text-center' },   // Columna 6: Botón para Editar
            { name: 'eliminar', data: null, defaultContent: '', className: 'text-center' }   // Columna 7: Botón para Eliminar
        ],
        
        columnDefs: [
               // Columna 0: BOTÓN MÁS 
            { 
                targets: "control:name", width: '5%', searchable: false, orderable: false, className: "text-center" 
            },
            {
                // Columna 1: Usuario Cliente
                targets: "usuario_cliente:name",
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 2: Usuario empleado
                targets: "usuario_empleado:name",
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 3: Nombre de pista
                targets: "nombre_pista",
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 4: de Fecha (solo visualización)
                targets: "fecha_carrera:name",
                width: '15%',
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
                // Columna 5: Columna de Confirmación de Pago
                targets: 'pagado:name',
                width: '10%',
                searchable: true,
                orderable: true,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || row.id === undefined) return '';

                    // Parseo seguro de estados
                    const estaPagado = parseInt(row.pagado) === 1;
                    const fechaCarrera = new Date(`${row.fecha_carrera}T${row.hora_inicio || '23:59:59'}`);
                    const esCarreraPasada = fechaCarrera < new Date();

                    // Para filtrado y ordenamiento, retornar valores numéricos consistentes
                    if (type === 'filter' || type === 'sort') {
                        return estaPagado ? '1' : '0'; // 1=pagado, 0=no pagado
                    }

                    // Para visualización (type === 'display' o undefined)
                    if (esCarreraPasada) {
                        return `<span class="badge bg-secondary">Carrera finalizada</span>`;
                    }

                    if (estaPagado) {
                        return `
                            <button type="button" 
                                    class="btn btn-success btn-sm pago-confirmado"
                                    data-id="${row.id}" 
                                    data-usuario_cliente="${escapeHtmlAttr(row.usuario_cliente)}"
                                    data-nombre_cliente="${escapeHtmlAttr(row.nombre_cliente)}"
                                    data-email_cliente="${escapeHtmlAttr(row.email_cliente)}"
                                    data-nombre_pista="${escapeHtmlAttr(row.nombre_pista)}"
                                    data-fecha_carrera="${row.fecha_carrera}"
                                    data-hora_inicio="${row.hora_inicio}"
                                    data-hora_fin="${row.hora_fin}"
                                    data-num_participantes="${row.num_participantes}"
                                    data-total="${row.precio}"
                                    data-metodo_pago="${row.metodo_pago}"
                                    data-fecha_pago="${escapeHtmlAttr(row.fecha_pago)}"
                                    data-toggle="tooltip" 
                                    title="Pago confirmado el ${formatoFechaEuropeo(row.fecha_pago) || 'fecha no disponible'}">
                                <i class="fas fa-lock"></i> Pagado
                            </button>
                        `;
                    }

                    // Botón Pagar (editable), incluye todos los datos necesarios para notificación
                    return `
                        <button type="button" 
                                class="btn btn-warning btn-sm confirmar-pago"
                                data-id="${row.id}"
                                data-usuario_cliente="${escapeHtmlAttr(row.usuario_cliente)}"
                                data-nombre_cliente="${escapeHtmlAttr(row.nombre_cliente)}"
                                data-email_cliente="${escapeHtmlAttr(row.email_cliente)}"
                                data-usuario_empleado="${escapeHtmlAttr(row.usuario_empleado)}"
                                data-nombre_empleado="${escapeHtmlAttr(row.nombre_empleado)}"
                                data-nombre_pista="${escapeHtmlAttr(row.nombre_pista)}"
                                data-fecha_carrera="${row.fecha_carrera}"
                                data-hora_inicio="${row.hora_inicio}"
                                data-hora_fin="${row.hora_fin}"
                                data-num_participantes="${row.num_participantes}"
                                data-total="${row.precio}"
                                data-metodo_pago="${row.metodo_pago}"
                                data-toggle="tooltip"
                                title="Click para confirmar pago (irreversible)">
                            <i class="fas fa-money-bill-wave"></i> Pagar
                        </button>
                    `;
                }
            },
            {
                // Columna 6: Botón para Editar
                targets: "editar:name",
                width: '5%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    if (!row || !row.id) {
                        console.error('Datos de fila incompletos:', row);
                        return '';
                    }
            
                    // 1. Verificar si la carrera ya pasó (fecha + hora)
                    var fechaHoraCarrera = new Date(row.fecha_carrera + 'T' + row.hora_inicio);
                    var ahora = new Date();
                    var esPasada = fechaHoraCarrera < ahora;
            
                    // 2. Crear botón condicional
                    if (esPasada) {
                        // Botón DESHABILITADO (carrera pasada)
                        return `<button type="button" class="btn btn-info btn-sm" disabled
                                  data-toggle="tooltip" data-placement="top" 
                                  title="No editable (carrera pasada)">
                              <i class="fa-solid fa-edit"></i>
                            </button>`;
                    } else {
                        // Botón HABILITADO (carrera futura o actual)
                        return `<button type="button" class="btn btn-info btn-sm editarCarrera"
                                  data-toggle="tooltip" data-placement="top" 
                                  title="Editar"
                                  data-id="${row.id}"
                                  data-pista_id="${row.id_pistas || ''}"
                                  data-id_horario="${row.franja_horaria_id || ''}">
                              <i class="fa-solid fa-edit"></i>
                            </button>`;
                    }
                }
            },
            {
                // Columna 7: Botón para Eliminar
                targets: "eliminar:name",
                width: '5%',
                searchable: false,
                orderable: false,
                className: "text-center",
                render: function(data, type, row) {
                    // 1. Verificar si la carrera ya pasó
                    var fechaHoraCarrera = new Date(row.fecha_carrera + 'T' + row.hora_inicio);
                    var ahora = new Date();
                    var esPasada = fechaHoraCarrera < ahora;
                    
                    // 2. Verificar si está pagada
                    var estaPagada = row.pagado == 1;

                    // 3. Crear botón condicional
                    if (esPasada || estaPagada) {
                        // Botón DESHABILITADO
                        var motivo = esPasada ? "carrera pasada" : "carrera pagada";
                        return `<button type="button" class="btn btn-danger btn-sm" disabled
                                data-toggle="tooltip" data-placement="top"
                                title="No eliminable (${motivo})">
                            <i class="fa-solid fa-trash"></i>
                            </button>`;
                    } else {
                        // Botón HABILITADO
                        return `<button type="button" class="btn btn-danger btn-sm eliminarCarrera"
                                data-toggle="tooltip" data-placement="top"
                                title="Eliminar"
                                data-id="${row.id}"
                                data-usuario-cliente="${escapeHtmlAttr(row.usuario_cliente)}"
                                data-nombre-cliente="${escapeHtmlAttr(row.nombre_cliente)}"
                                data-email-cliente="${escapeHtmlAttr(row.email_cliente)}"
                                data-usuario-empleado="${escapeHtmlAttr(row.usuario_empleado)}"
                                data-nombre-empleado="${escapeHtmlAttr(row.nombre_empleado)}"
                                data-nombre-pista="${escapeHtmlAttr(row.nombre_pista)}"
                                data-fecha-carrera="${row.fecha_carrera}"
                                data-hora-inicio="${row.hora_inicio}"
                                data-hora-fin="${row.hora_fin}"
                                data-num-participantes="${row.num_participantes}"
                                data-total="${row.precio}"
                                data-metodo-pago="${row.metodo_pago}">
                            <i class="fa-solid fa-trash"></i>
                            </button>`;
                    }
                }
            }

        ],
        
        ajax: {
            url: base_url + "EmpleadoCarreras/obtenerTodasLasCarreras",
            type: 'GET',
            dataSrc: 'data'
        },
        order: [[4, 'asc']], // ordenar por la columna 4 - fecha carrera 
        rowGroup: {
            dataSrc: function (row) {
                return formatoFechaEuropeoSoloFecha(row.fecha_carrera);
            },
            startRender: function (rows, group) {
                let $row = $('<tr/>').append('<td colspan="8" class="group-header">' + group + ' / ' + rows.count() + ' carrera/s' + '</td>');
                return $row;
            } // de la function startRender
        }, // de la rowGroup
        initComplete: function() {
            const api = this.api();
    
            // APLICAR FILTRO PARA QUE PONGA DE PRIMERAS AL INICIAR LA PÁGINA SOLO LAS CARRERAS FUTURAS
            aplicarFiltroCarreras(api);
    
            // CADA VEZ QUE CAMBIE EL FILTRO, VOLVER A FILTRAR POR EL RADIO SELECCIONADO
            $('input[name="filterDates"]').on('change', function() {
                aplicarFiltroCarreras(api);  // Aplicar filtro cuando se cambia
            });
        }
    };
    
    ////////////////////////////
    // FIN DE LA TABLA DE    //
    ///////////////////////////


    /************************************/
    //     ZONA DE DEFINICIONES        //
    /**********************************/
    // Definición inicial de la tabla de carreras
    var $table = $('#carreras_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_carreraConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#carreras_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#carreras_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

    // MÉTODO PARA MOSTRAR DATOS CORRECTAMENTE AUNQUE TENGAN CARÁCTERES ESPECIALES
      function escapeHtmlAttr(text) {
        if (!text) return '';
        return text.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // MÉTODO PARA MOSTRAR TODO EL CONTENIDO DEL MOSTRAR MÁS
    function format(d) {
    // Mapeo de métodos de pago
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    
    // OBTENGO EL NOMBRE FORMATEADO CORRECTAMENTE DEL MÉTODO DE PAGO
    const metodoPagoFormateado = d.metodo_pago 
        ? (metodosPago[d.metodo_pago.toLowerCase()] || d.metodo_pago)
        : null;

    return `
    <div class="card border-primary mb-3">
        <div class="card-header bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-check fs-3 me-2"></i>
                <h5 class="card-title mb-0">Detalles de la Reserva</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-borderless table-striped table-hover mb-0">
                <tbody>
                    <tr>
                        <th scope="row" class="ps-4 w-25"><i class="bi bi-credit-card me-2"></i>Método de pago</th>
                        <td class="pe-4">${metodoPagoFormateado 
                            ? `<span class="fw-normal">${metodoPagoFormateado}</span>` 
                            : '<span class="text-muted fst-italic">No tiene un método de pago</span>'}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-people me-2"></i>Número de participantes</th>
                        <td class="pe-4">${d.num_participantes ? `<span class="fw-normal">${d.num_participantes}</span>` : '<span class="text-muted fst-italic">No tiene un número de participantes</span>'}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-cash-coin me-2"></i>Precio</th>
                        <td class="pe-4">${d.precio ? `<span class="fw-normal">${d.precio} €</span>` : '<span class="text-muted fst-italic">No tiene un precio</span>'}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-clock me-2"></i>Horario</th>
                        <td class="pe-4">${(d.hora_inicio && d.hora_fin) ? `<span class="fw-normal">${d.hora_inicio.substring(0,5)} - ${d.hora_fin.substring(0,5)}</span>` : '<span class="text-muted fst-italic">No tiene un horario</span>'}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-calendar-event me-2"></i>Fecha de pago</th>
                        <td class="pe-4">${d.fecha_pago ? `<span class="fw-normal">${formatoFechaEuropeo(d.fecha_pago)}</span>` : '<span class="text-muted fst-italic">No tiene fecha de pago</span>'}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-cash-coin me-2"></i>¿Está pagado?</th>
                        <td class="pe-4">
                            ${d.pagado == 1 
                                ? `<span class="fw-normal">${"Carrera Pagada"}</span>` 
                                : '<span class="text-muted fst-italic">Carrera PENDIENTE DE PAGO</span>'}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="ps-4"><i class="bi bi-calendar-event me-2"></i>Payment Intent Id</th>
                        <td class="pe-4">${d.payment_intent_id ? `<span class="fw-normal">${d.payment_intent_id}</span>` : '<span class="text-muted fst-italic">No tiene Payment Intent Id</span>'}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent border-top-0 text-end">
            <small class="text-muted">Actualizado: ${new Date().toLocaleDateString()}</small>
        </div>
    </div>`;
}
      
        // MÉTODO QUE MUESTRA TODO EL CONTENIDO DE MOSTRAR MÁS
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

// FUNCIÓN PARA CALCULAR EL PRECIO TOTAL (precio pista * participantes)
function calcularPrecio() {
    const pista = $('#id_pista option:selected');
    const participantes = parseInt($('#num_participantes').val()) || 1;
    const precioBase = parseFloat(pista.data('precio')) || 0;
    
    if (pista.val() && precioBase > 0) {
        const precioTotal = (precioBase * participantes).toFixed(2);
        $('#precio').val(precioTotal);
    } else {
        $('#precio').val('0.00');
    }
}

        //////////////////////////////////////
    // Funcion para cargar el select de pistas    //
    ///////////////////////////// ///////
function cargarPistas(selectId, idPistaSeleccionada = null) {
    // HAGO UN AJAX PARA OBTENER TODAS LAS PISTAS QUE HAY DISPONIBLES 
    $.get(base_url + "pistas/getPistas", function (data) {
        let $select = $(selectId);
        $select.empty(); // Limpiar opciones actuales

        // Agregar opción por defecto
        $select.append($('<option>', {
            value: '',
            text: 'Seleccione una pista...',
            'data-precio': '0'
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
            data.forEach(function (pista) {
                let option = $('<option>', {
                    value: pista.id,
                    text: pista.nombre,
                    'data-precio': pista.precio || '0' 
                });

                if (idPistaSeleccionada && pista.id == idPistaSeleccionada) {
                    option.prop('selected', true);
                }

                $select.append(option);
            });
        } else {
            $select.append($('<option>', {
                value: '',
                text: 'No hay pistas disponibles',
                'data-precio': '0'
            }));
        }

    }, "json").fail(function (xhr, status, error) {
        console.error("Error al cargar las pistas:", error);
        $(selectId).html("<option value='' data-precio='0'>Error al cargar pistas</option>");
    });
}

    //////////////////////////////////////
    // Funcion para cargar el select de usuarios    //
    ///////////////////////////// ///////
    function cargarUsuarios(selectId, idUsuarioSeleccionado = null) {
        // HAGO UN AJAX PARA OBTENER TODOS LOS USUARIOS ACTIVOS
        $.get(base_url + "Empleado/getUsuariosActivos", function(response) {
            console.log("Datos de usuarios recibidos:", response);
            
            let $select = $(selectId);
            $select.empty(); // Limpiar opciones actuales
    
            // Agregar opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione un usuario...'
            }));
    
            // Manejar tanto response.data como respuesta directa
            const data = response.success ? response.data : response;
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(usuario) {
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
    // Funcion para cargar el select de empleados    //
    ///////////////////////////// ///////
    function cargarEmpleados(selectId, idEmpleadoSeleccionado = null) {
        // HAGO UN AJAX PARA OBTENER TODOS LOS EMPLEADOS DE CARRERAS ACTIVOS
        $.get(base_url + "Admin/getEmpleadosCarreras", function(response) {
            console.log("Datos de empleados recibidos:", response);
            
            let $select = $(selectId);
            $select.empty(); // Limpiar opciones actuales
    
            // Agregar opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione un empleado...'
            }));
    
            // Manejar tanto response.data como respuesta directa
            const data = response.success ? response.data : response;
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(empleado) {
                    // Usar EXCLUSIVAMENTE nombre_usuario
                    if (empleado.nombre_usuario) {
                        let option = $('<option>', {
                            value: empleado.id,
                            text: empleado.nombre_usuario
                        });
    
                        if (idEmpleadoSeleccionado && empleado.id == idEmpleadoSeleccionado) {
                            option.prop('selected', true);
                        }
    
                        $select.append(option);
                    }
                });
            } else {
                $select.append($('<option>', {
                    value: '',
                    text: 'No hay empleados disponibles'
                }));
            }
            
        }, "json").fail(function(xhr, status, error) {
            console.error("Error al cargar empleados:", status, error);
            $(selectId).html("<option value=''>Error al cargar</option>");
        });
    }
    


          //////////////////////////////////////
    // Funcion para cargar el select de horarios //
    ///////////////////////////// ///////
    function cargarHorarios(selectId, fecha, idHorarioSeleccionado = null) {
        const $select = $(selectId);
        
        // Mostrar estado de carga
        $select.empty();
        
        const esFechaOriginal = (fecha === fechaOriginalCarrera);
        
        // HAGO UN AJAX PARA OBTENER TODOS LOS HORARIOS DISPONIBLES DE LA FECHA
        // SELECCIONADA
        $.ajax({
            url: base_url + "EmpleadoCarreras/obtenerHorariosDisponibles",
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
                
                // Verificar si hay horarios disponibles
                if (!response.horarios || response.horarios.length === 0) {
                    $select.append('<option value="">No hay horarios disponibles</option>');
                    return;
                }
                
                // Agregar opción por defecto solo si hay horarios
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
    //              FIN                 //
    // Funcion para cargar un select    //
    ///////////////////////////// ///////

    // FUNCIÓN PARA CONFIRMAR PAGO (NO PAGADO)
function confirmarPagoCarrera(idCarrera, datosCarrera) {
     const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    const metodoPagoTraducido = metodosPago[datosCarrera.metodoPago] || 'Desconocido';

    // MUESTRO MENSAJE POR SI EL ADMIN QUIERO PONER COMO PAGADA ESA RESERVA DE CARRERA
    Swal.fire({
        title: 'Confirmar Pago',
        html: `
            ¿Marcar reserva <b>${idCarrera}</b> como PAGADA?<br><br>
            <ul style="text-align:left;">
                <li><b>Cliente:</b> ${escapeHtmlAttr(datosCarrera.nombreCliente)}</li>
                <li><b>Pista:</b> ${escapeHtmlAttr(datosCarrera.nombrePista)}</li>
                <li><b>Fecha:</b> ${formatoFechaEuropeo(datosCarrera.fechaCarrera)}</li>
                <li><b>Horario:</b> ${datosCarrera.horaInicio} - ${datosCarrera.horaFin}</li>
                <li><b>Participantes:</b> ${datosCarrera.numParticipantes}</li>
                <li><b>Total:</b> ${parseFloat(datosCarrera.total).toFixed(2)} €</li>
                <li><b>Método de pago:</b> ${metodoPagoTraducido}</li>
            </ul>
            <p style="margin-top: 1em;"><i>Pago para el usuario <b>${escapeHtmlAttr(datosCarrera.nombreUsuario)}</b></i></p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        // SI SE CONFIRMA, SE HACE UN AJAX QUE SE ENCARGA DE CONFIRMAR EL PAGO
        if (result.isConfirmed) {
            $.post(base_url + "EmpleadoCarreras/confirmarPago/" + idCarrera)
                .done(function() {
                    // DESPUÉS DEL PRIMER AJAX, MONTO UN OBJETO PARA ENVIAR EL CORREO Y SE LO PASO AL MÉTODO DE ENVIAR CORREO DIRECTAMENTE
                    enviarCorreoPago({
                        email: datosCarrera.emailCliente,
                        nombre: datosCarrera.nombreCliente,
                        asunto: `Pago confirmado para carrera en pista ${datosCarrera.nombrePista}`,
                        mensaje: `
                        Hola ${escapeHtmlAttr(datosCarrera.nombreCliente)},

                        Confirmamos que hemos recibido el pago de tu reserva para la carrera en la pista ${datosCarrera.nombrePista}.

                        Detalles:
                        Fecha: ${formatoFechaEuropeo(datosCarrera.fechaCarrera)}
                        Horario: ${datosCarrera.horaInicio} - ${datosCarrera.horaFin}
                        Participantes: ${datosCarrera.numParticipantes}
                        Total pagado: ${parseFloat(datosCarrera.total).toFixed(2)} €
                        Método de pago: ${metodoPagoTraducido}

                        ¡Gracias por tu reserva!

                        Saludos,
                        El equipo.
                        `
                    })
                    .done(function() {
                        // CUANDO SE ENVÍA EL CORREO, SE MUESTRA ESE SWAL
                        $table.DataTable().ajax.reload();
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

// FUNCIÓN QUE SE ENCARGA DE SIMPLEMENTE ENVIAR EL CORREO CUANDO SE PAGA LA RESERVA DE LA CARRERA
function enviarCorreoPago(datosCorreo) {
    return $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(datosCorreo),
        dataType: "json"
    });
}


// FUNCIÓN PARA PAGOS YA CONFIRMADOS
function verPagoConfirmadoCarrera(idCarrera, datosCarrera) {
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    const metodoPagoTraducido = metodosPago[datosCarrera.metodoPago] || 'Desconocido';
    const fechaPagoFormateada = datosCarrera.fechaPago ? formatoFechaEuropeo(datosCarrera.fechaPago) : 'fecha no disponible';

    // SI ESA RESERVA YA ESTA PAGADA, SIMPLEMENTE SE ENSEÑA UN MENSAJE CON LOS DETALLES DE LA RESERVA PAGADA
    Swal.fire({
        title: `Pago Registrado para ${escapeHtmlAttr(datosCarrera.usuarioCliente)}`,
        html: `
        Reserva <b>${idCarrera}</b> para la pista <b>${escapeHtmlAttr(datosCarrera.nombrePista)}</b> ya está pagada.<br>
        <ul style="text-align:left; margin-top: 1em;">
            <li><b>Fecha:</b> ${formatoFechaEuropeo(datosCarrera.fechaCarrera)}</li>
            <li><b>Horario:</b> ${datosCarrera.horaInicio} - ${datosCarrera.horaFin}</li>
            <li><b>Participantes:</b> ${datosCarrera.numParticipantes}</li>
            <li><b>Total:</b> ${parseFloat(datosCarrera.total).toFixed(2)} €</li>
            <li><b>Método de pago:</b> ${metodoPagoTraducido}</li>
        </ul>
        <small class="text-muted">Pago confirmado el ${fechaPagoFormateada}</small><br>
        <small class="text-muted">Estado irreversible</small>
    `,
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}


// FUNCIÓN PARA CUANDO SE HACE CLICK EN EL BOTÓN DE CONFIRMAR PAGO
// RECOJO LOS DATOS DEL DATA, LO MONTO EN UN OBJETO Y LO MANDO AL
// CONFIRMAR PAGO CARRERA
$(document).on('click', '.confirmar-pago', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const datosCarrera = {
    nombreCliente: $btn.data('nombre_cliente'),
    emailCliente: $btn.data('email_cliente'),
    nombrePista: $btn.data('nombre_pista'),
    fechaCarrera: $btn.data('fecha_carrera'),
    horaInicio: $btn.data('hora_inicio'),
    horaFin: $btn.data('hora_fin'),
    numParticipantes: $btn.data('num_participantes'),
    total: $btn.data('total'),
    metodoPago: $btn.data('metodo_pago'),
    nombreUsuario: $btn.data('usuario_cliente')
};
    confirmarPagoCarrera($btn.data('id'), datosCarrera);
});

// FUNCIÓN PARA CUANDO SE HACE CLICK EN EL BOTÓN DE VER PAGO CONFIRMADO
// RECOGER LOS DATOS DEL DATA, MONTARLO EN UN OBJETO Y MANDARLO AL
// PAGO CONFIRMADO
$(document).on('click', '.pago-confirmado', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const datosCarrera = {
        nombreCliente: $btn.data('nombre_cliente'),
        usuarioCliente: $btn.data('usuario_cliente'),
        nombrePista: $btn.data('nombre_pista'),
        fechaCarrera: $btn.data('fecha_carrera'),
        horaInicio: $btn.data('hora_inicio'),
        horaFin: $btn.data('hora_fin'),
        numParticipantes: $btn.data('num_participantes'),
        total: $btn.data('total'),
        metodoPago: $btn.data('metodo_pago'),
        fechaPago: $btn.data('fecha_pago')
    };
    verPagoConfirmadoCarrera($btn.data('id'), datosCarrera);
});


    ////////////////////////////////////////////
    //   INICIO ZONA FUNCIONES DE APOYO      //
    //////////////////////////////////////////
 
    // USAR EL MÉTODO DE CALCULAR PRECIO CUANDO
    // OCURRAN ESTOS SUCESOS
    $('#id_pista').change(calcularPrecio);
    $('#num_participantes').on('change input', calcularPrecio);

    /////////////////////////////////////
    //   INICIO ZONA DELETE CARRERA  //
    ///////////////////////////////////
function eliminarCarrera(datosCarrera) {
    // SI LA CARRERA YA ESTA PAGADA NO SE PUEDE ELIMINAR
    if (datosCarrera.pagado == 1 || datosCarrera.estaPagada) {
        Swal.fire({
            title: 'Operación no permitida',
            html: `
                <p>No se puede eliminar una carrera que ya ha sido pagada.</p>
                <p><strong>Cliente:</strong> ${datosCarrera.nombreCliente}</p>
                <p><strong>Fecha y hora:</strong> ${datosCarrera.fechaCarrera} ${datosCarrera.horaInicio} - ${datosCarrera.horaFin}</p>
                <p><strong>Pista:</strong> ${datosCarrera.nombrePista}</p>
                <p><strong>Total pagado:</strong> ${parseFloat(datosCarrera.total).toFixed(2)} €</p>
            `,
            icon: 'warning',
            customClass: { htmlContainer: 'text-left' }
        });
        return;
    }

    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    const metodoPagoTraducido = metodosPago[datosCarrera.metodoPago] || 'Desconocido';

    // SI LA CARRERA NO ESTÁ PAGADA, SE RECOGEN LOS DETALLES DE LA RESERVA EN UN TEXTO, Y SE 
    // PREGUNTA SI SE QUIERE ELIMINAR
    const detallesHTML = `
        <p><strong>Usuario cliente:</strong> ${datosCarrera.usuarioCliente}</p>
        <p><strong>Empleado:</strong> ${datosCarrera.usuarioEmpleado}</p>
        <p><strong>Pista:</strong> ${datosCarrera.nombrePista}</p>
        <p><strong>Fecha:</strong> ${formatoFechaEuropeo(datosCarrera.fechaCarrera)}</p>
        <p><strong>Horario:</strong> ${datosCarrera.horaInicio?.substring(0,5)} - ${datosCarrera.horaFin?.substring(0,5)}</p>
        <p><strong>Número de participantes:</strong> ${datosCarrera.numParticipantes}</p>
        <p><strong>Total:</strong> ${parseFloat(datosCarrera.total).toFixed(2)} €</p>
        <p><strong>Método de pago:</strong> ${metodoPagoTraducido}</p>
        <hr>
        <span class="text-success">Al eliminar, el horario quedará disponible nuevamente.</span>
    `;

    Swal.fire({
        title: '¿Eliminar Carrera?',
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
                html: 'Liberando horario...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            // EN CASO AFIRMATIVO, SE BORRA ESA RESERVA DE CARRERA
            $.ajax({
                url: base_url + "EmpleadoCarreras/eliminarReservaCarrera/" + datosCarrera.id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        // SI TODO SALE BIEN, DESPUÉS PASO EL OBJETO DE LOS DETALLES DE LA CARRERA
                        // AL MÉTODO DE CANCELARRESERVACARRERA, Y ASÍ ENVIAR EL CORREO CON EL
                        // AVISO DE LA CANCELACIÓN DE LA RESERVA
                        cancelarReservaCarrera(datosCarrera);
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo eliminar la carrera', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    console.error("Error al eliminar la carrera:", xhr.responseText);
                    Swal.fire('Error', 'Error al conectar con el servidor', 'error');
                }
            });
        }
    });
}

// MÉTODO PARA ENVIAR CORREO CON LOS DATOS DE LA RESERVA CANCELADA
function cancelarReservaCarrera(datosCarrera) {
    const metodosPago = {
        'card': 'Tarjeta',
        'paypal': 'PayPal',
        'presencial': 'Presencial'
    };
    const metodoPagoTraducido = metodosPago[datosCarrera.metodoPago] || 'Desconocido';

    const mensajeCorreo = `
        Tipo de reserva: Carrera
        Pista: ${datosCarrera.nombrePista}
        Fecha: ${formatoFechaEuropeo(datosCarrera.fechaCarrera)}
        Horario: ${datosCarrera.horaInicio?.substring(0,5)} - ${datosCarrera.horaFin?.substring(0,5)}
        Número de participantes: ${datosCarrera.numParticipantes}
        Total: ${parseFloat(datosCarrera.total).toFixed(2)} €
        Método de pago: ${metodoPagoTraducido}

        Lamentamos informarte que tu reserva ha sido cancelada. 
        Si no has solicitado esta cancelación, por favor contacta con nuestro equipo.
    `.trim();

    const emailPayload = {
        email: datosCarrera.emailCliente,
        nombre: datosCarrera.nombreCliente,
        asunto: "Cancelación de tu reserva de carrera",
        mensaje: mensajeCorreo
    };

    // SI NO ESTÁN LOS DATOS NECESARIOS PARA ENVIAR EL EMAIL, NO SE ENVÍA EL CORREO
    if (!emailPayload.email || !emailPayload.nombre || !emailPayload.mensaje) {
        console.error("Faltan campos obligatorios en el payload del correo:", emailPayload);
        $table.DataTable().ajax.reload();
        Swal.fire('Error', 'No se pudo enviar la notificación por correo. Por favor contacta al usuario manualmente.', 'error');
        return;
    }

    // SE ENVÍA EL CORREO, Y SE NOTIFICA AL USUARIO DE QUE LA CARRERA HA SIDO CANCELADA Y ADEMÁS QUE SE LE HA NOTIFICADO.
    $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(emailPayload),
        dataType: "json",
        success: function() {
            $table.DataTable().ajax.reload();
            Swal.fire('Cancelado', 'La carrera ha sido cancelada y se ha notificado al usuario.', 'success');
        },
        error: function(xhr) {
            console.error("Error al enviar correo:", xhr.responseText);
            $table.DataTable().ajax.reload();
            Swal.fire('Error', 'La carrera se canceló pero no se pudo enviar la notificación. Por favor, contacta al usuario manualmente.', 'error');
        }
    });
}




    // CAPTURO EL CLICK EN EL BOTÓN DE BORRAR
$(document).on('click', '.eliminarCarrera', function(event) {
    event.preventDefault();

    const $boton = $(this);
    // RECOJO LOS DATOS DE LA CARRERA RESERVADA Y LOS PASO AL MÉTODO ELIMINAR CARRERA
    let datosCarrera = {
        id: $boton.data('id'),
        usuarioCliente: $boton.data('usuario-cliente'),
        nombreCliente: $boton.data('nombre-cliente'),
        emailCliente: $boton.data('email-cliente'),
        usuarioEmpleado: $boton.data('usuario-empleado'),
        nombreEmpleado: $boton.data('nombre-empleado'),
        nombrePista: $boton.data('nombre-pista'),
        fechaCarrera: $boton.data('fecha-carrera'),
        horaInicio: $boton.data('hora-inicio'),
        horaFin: $boton.data('hora-fin'),
        numParticipantes: $boton.data('num-participantes'),
        total: $boton.data('total'),
        metodoPago: $boton.data('metodo-pago'),
        pagado: $boton.data('pagado') // agrega esta línea si tienes el dato pagado en el botón
    };

    eliminarCarrera(datosCarrera);
});


    ////////////////////////////////////
    //   FIN ZONA DELETE NOTICIA    //
    //////////////////////////////////

    ///////////////////////////////////////
    //      INICIO ZONA NUEVO           //
    //        BOTON DE NUEVO           // 
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE NUEVO

// ABRIR MODAL PARA NUEVO REGISTRO
$(document).on('click', '#btnnuevo', function (event) {
    event.preventDefault();
    $('#mdltitulo').text('Nuevo registro de carrera');

    // MUESTRO EL MODAL DE CARRERAS
    $('#modalCarreras').modal('show');

    // RESETEO TODO EL FORMULARIO Y SUS VARIABLES
    $("#formCarrera")[0].reset();
    $('#formCarrera').find('input[name="idcarrera"]').val("");

    // LIMPIO LAS VALIDACIONES ANTERIORES
    formValidator.clearValidation();

    // LOS CAMPOS QUE PUEDEN HABER DESHABILITADOS, LOS PONGO COMO HABILITADOS 
    const camposDeshabilitados = [
        '#num_participantes',
        '#metodo_pago',
        '#id_pista',
        '#id_usuario',
        '#fecha_pago'
    ];

    // HABILITO Y QUITO ESTILOS DE POSIBLES ERRORES ANTERIORES
    $(camposDeshabilitados.join(',')).each(function() {
        $(this)
            .removeClass('campo-deshabilitado')
            .prop('readonly', false)
            .prop('disabled', false)
            .css('background-color', '')
            .off('click');
    });

    // EL CALENDARIO DE LA FECHA PAGO SE VUELVE A MONTAR
    $('#fecha_pago').datepicker('destroy'); 
    
       /* DATEPICKER DE MODAL FECHA PAGO */
        $('#fecha_pago').inputmask('99-99-9999');
        $('#fecha_pago').datepicker({
            appendTo: '#modalCarreras .modal-body', 
            showAnim: "slideDown",
            dateFormat: 'dd-mm-yy',
            showOtherMonths: true,
            selectOtherMonths: true,
            numberOfMonths: 1,
            beforeShow: function(input, inst) {
                setTimeout(function() {
                    inst.dpDiv.css({
                        'position': 'absolute',
                        'top': $(input).offset().top + $(input).outerHeight(),
                        'left': $(input).offset().left
                    });
                }, 0);
            }
        });
    $('#fecha_pago').prop('readonly', true);

    $("#id_pista, #id_usuario, #metodo_pago").trigger('change.select2');

    // CARGO LOS SELECTS DEL MODAL
    cargarPistas("#id_pista");
    cargarUsuarios("#id_usuario");
    cargarEmpleados("#id_empleado");

    configurarSelect2UsuarioCarreras();
    configurarSelect2EmpleadoCarreras();

    // INICIALIZO LA FECHA ACTUAL
    const fechaActual = new Date();
    const dia = String(fechaActual.getDate()).padStart(2, '0');
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
    const anio = fechaActual.getFullYear();

    const fechaVisual = `${dia}-${mes}-${anio}`;
    const fechaBackend = `${anio}-${mes}-${dia}`;

    $('#fecha_carrera').val(fechaVisual);

    // CARGO LOS HORARIOS DEL DÍA ACTUAL POR DEFECTO
    cargarHorarios("#id_horario", fechaBackend);

    // RESETEO LAS VARIABLES GLOBALES
    fechaOriginalEvento = null;
    horarioOriginalEvento = null;
});

// GUARDAR/EDITAR CARRERA
$(document).on('click', '#btnsalvar', async function(event) {
    event.preventDefault();

    // RECOJO LOS VALORES DEL FORMULARIO
    const form = $('#formCarrera');
    const idC = form.find('input[name="idcarrera"]').val().trim();
    const metodoPagoC = form.find('select[name="metodo_pago"]').val().trim();
    const idPistaC = form.find('select[name="id_pista"]').val().trim();
    const pistaOption = form.find('select[name="id_pista"] option:selected');
    const pistaNombre = pistaOption.text();
    const pistaPrecio = pistaOption.data('precio') || '0';
    const numParticipantesC = form.find('input[name="num_participantes"]').val().trim();
    const precioC = form.find('input[name="precio"]').val().trim();
    const idHorarioC = form.find('select[name="id_horario"]').val().trim();
    const horarioTexto = form.find('select[name="id_horario"] option:selected').text();
    const fechaPagoC = form.find('input[name="fecha_pago"]').val().trim();
    const fechaCarreraC = form.find('input[name="fecha_carrera"]').val().trim();
    const idUsuarioC = form.find('select[name="id_usuario"]').val().trim();
    const idEmpleadoC = form.find('select[name="id_empleado"]').val().trim();

    // VALIDO ESOS VALORES
    const isFormValid = formValidator.validateForm(event);
    if (!isFormValid) {
        toastr.error("Error de Validación', 'Por favor, corrija los errores en el formulario.", "Error");
        return;
    }

    // TRANSFORMO LA FECHA A FORMATO yyyy-mm-dd
    function transformarFecha(fechaStr) {
        if (fechaStr !== "") {
            const parts = fechaStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
        }
        return fechaStr;
    }

    const fechaCarreraTransformada = transformarFecha(fechaCarreraC);
    const fechaPagoTransformada = transformarFecha(fechaPagoC);

    const fechaCarrera = new Date(fechaCarreraTransformada);
    const fechaActual = new Date();
    const fechaMaxima = new Date();
    fechaMaxima.setFullYear(fechaActual.getFullYear() + 2);

    // SI LA FECHA DE AHORA ES MAYOR A DENTRO DE DOS AÑOS, NO SE PUEDE HACER ESA RESERVA
    if (fechaCarrera > fechaMaxima) {
        Swal.fire('Error de Fecha', 'La reserva no puede ser para más de 2 años en el futuro.', 'error');
        return;
    }

    // SI LA FECHA DEL PAGO ES POSTERIOR A LA CARRERA, TAMBIÉN DA ERROR
    if (fechaPagoTransformada && new Date(fechaPagoTransformada) > fechaCarrera) {
        Swal.fire('Error de Fechas', 'La fecha de pago no puede ser posterior a la fecha de la carrera.', 'error');
        return;
    }

    // OBTENER LA INFORMACIÓN DEL USUARIO DE LA RESERVA
    let usuarioEmail = "", usuarioNombre = "";
    try {
        const usuarioResponse = await $.get(base_url + 'Usuario/getUsuarioPorId/' + idUsuarioC);
        if (usuarioResponse && usuarioResponse.email && usuarioResponse.nombre) {
            usuarioEmail = usuarioResponse.email;
            usuarioNombre = usuarioResponse.nombre;
        } else {
            Swal.fire('Advertencia', 'No se pudo obtener información del usuario para enviar el correo.', 'warning');
        }
    } catch (usuarioError) {
        console.error("Error al obtener el usuario:", usuarioError);
    }

    // SE PREPARA EL OBJETO DE LA RESERVA QUE SE VA A ENVIAR
    const formData = new FormData();
    formData.append("metodo_pago", metodoPagoC);
    formData.append("id_pistas", idPistaC);
    formData.append("num_participantes", numParticipantesC);
    formData.append("cantidad", precioC);
    formData.append("franja_horaria_id", idHorarioC);
    formData.append("fecha_carrera", fechaCarreraTransformada);
    formData.append("fecha_pago", fechaPagoTransformada);
    formData.append("id_usuario", idUsuarioC);
    formData.append("empleado_id", idEmpleadoC);

    // SEGÚN SI EXISTE EL ID O NO, SE CREA EL REGISTRO O SE EDITA
    const esNueva = idC === "";
    const urlEnvio = esNueva 
        ? base_url + "Admin/crearReservaCarrera" 
        : base_url + "Admin/editarReservaCarrera/" + idC;

    try {
        // SE ENVÍA EL AJAX PARA CREAR/EDITAR CARRERA
        const response = await $.ajax({
            url: urlEnvio,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false
        });

        $('#modalCarreras').modal('hide');
        $table.DataTable().ajax.reload();
        $("#formCarrera")[0].reset();

        // SI EXISTE EL CORREO, FORMO EL CORREO, Y SE ENVÍA PARA CONFIRMAR AL USUARIO
        // LA CREACIÓN/EDICIÓN DE SU RESERVA
        if (usuarioEmail) {
            const metodoPagoTexto = metodoPagoC === 'card' ? 'Tarjeta' :
                      metodoPagoC === 'paypal' ? 'PayPal' :
                      metodoPagoC === 'presencial' ? 'Pago presencial' :
                      metodoPagoC;

            let asunto = "", mensaje = "";

            // SI LA CARRERA ES NUEVA, FORMO UNA ESTRUCTURA DE CORREO
            if (esNueva) {
                asunto = "Confirmación de tu reserva de carrera";
                mensaje = `Gracias por realizar tu reserva.

                Detalles de tu reserva:
                Tipo de reserva: Carrera
                Pista: ${pistaNombre} - ${parseFloat(pistaPrecio).toFixed(2)}€
                Fecha: ${fechaCarreraC}
                Horario: ${horarioTexto}
                Número de participantes: ${numParticipantesC}
                Total: ${parseFloat(precioC).toFixed(2)} €
                Método de pago: ${metodoPagoTexto}`;
                // SI ES UNA EDICIÓN, FORMO UNA ESTRUCTURA DISTINTA
            } else {
                asunto = "Tu reserva de carrera ha sido modificada";
                mensaje = `Hola ${usuarioNombre},

                Te informamos que tu reserva de carrera ha sido actualizada con los siguientes detalles:

                Nueva fecha: ${fechaCarreraC}
                Nuevo horario: ${horarioTexto}
                Pista: ${pistaNombre}
                Número de participantes: ${numParticipantesC}
                Nuevo total: ${parseFloat(precioC).toFixed(2)} €
                Método de pago: ${metodoPagoTexto}

                Si no solicitaste este cambio o tienes alguna duda, por favor contáctanos.`;
            }

            // FORMO EL OBJETO QUE SE VA A NECESITAR PARA ENVIAR EL CORREO
            const emailPayload = {
                email: usuarioEmail,
                nombre: usuarioNombre,
                asunto: asunto,
                mensaje: mensaje
            };

            // SE ENVÍA EL CORREO PARA INFORMAR AL USUARIO
            $.ajax({
                url: base_url + "Contactos/enviar",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(emailPayload),
                dataType: "json",
                success: function() {
                    if (esNueva) {
                        Swal.fire('Confirmado', 'La carrera ha sido creada y se ha notificado al usuario.', 'success');
                    } else {
                        Swal.fire('Actualizado', 'La carrera ha sido modificada y se ha notificado al usuario.', 'success');
                    }
                },
                error: function(xhr) {
                    if (esNueva) {
                        Swal.fire('Confirmado', 'La carrera ha sido creada, pero no se pudo enviar el correo.', 'warning');
                    } else {
                        Swal.fire('Actualizado', 'La carrera ha sido modificada, pero no se pudo enviar el correo.', 'warning');
                    }
                }
            });
        } else {
            if (esNueva) {
                Swal.fire('Confirmado', 'La carrera ha sido creada, pero no se pudo enviar el correo.', 'warning');
            } else {
                Swal.fire('Actualizado', 'La carrera ha sido modificada, pero no se pudo enviar el correo.', 'warning');
            }
        }
    } catch (xhr) {
        console.error("Error en la petición:", xhr.responseText);
        const errorMsg = xhr.responseJSON?.message || "Error al procesar la reserva";
        Swal.fire('Error', errorMsg, 'error');
    }
});




    ///////////////////////////////////////
    //      FIN ZONA NUEVO           //
    /////////////////////////////////////

    // VARIABLES GLOBALES PARA CONTROLAR FECHA Y HORARIO DE FECHA_CARRERA
    var fechaOriginalCarrera = null;
    var horarioOriginalCarrera = null;

    // CARGAR HORARIOS AL CAMBIAR LA FECHA DE CARRERA
    $(document).on('change', '#fecha_carrera', function() {
        const fechaInput = $(this).val();
        if (!fechaInput) return;

        formValidator.clearValidation('fecha_carrera');
        formValidator.clearValidation('id_horario');
        
        const partes = fechaInput.split('-');
        if (partes.length !== 3) return;
        
        const fechaBackend = `${partes[2]}-${partes[1]}-${partes[0]}`;
        const esMismaFecha = (fechaBackend === fechaOriginalCarrera);
        
        cargarHorarios('#id_horario', fechaBackend, esMismaFecha ? horarioOriginalCarrera : null);
    });

    ///////////////////////////////////////
    //      INICIO ZONA EDITAR           //
    //        BOTON DE EDITAR           //
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE EDITAR

    // MÉTODO QUE SE EJECUTA AL DARLE AL BOTÓN DE EDITAR CARRERA
    $(document).on('click', '.editarCarrera', function(event) {
        event.preventDefault();
        
        $('#mdltitulo').text('Edición de carrera');
    
        // FORMATEO EL FORMULARIO
        $('#formCarrera')[0].reset();
        formValidator.clearValidation();
        $('#id_horario').empty();
        
        // OBTENGO EL ID DE LA CARRERA
        var idCarrera = $(this).data('id');
        var idPistaRespaldo = $(this).data('pista_id');
        var idHorarioRespaldo = $(this).data('id_horario');
        
        // HAGO UN AJAX PARA OBTENER LOS DATOS DE LA RESERVA DE LA CARRERA
        $.ajax({
            url: base_url + "EmpleadoCarreras/obtenerReservaCarreraEdicion/" + idCarrera,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var carrera = response.data;
                    
                    // LOS VALORES DE LA FECHA Y EL HORARIO DE LA CARRERA ME LO GUARDO PARA LAS
                    // VARIABLES GLOBALES
                    fechaOriginalCarrera = carrera.fecha_carrera;
                    horarioOriginalCarrera = carrera.franja_horaria_id || idHorarioRespaldo;
                    
                    // RELLENO TODOS LOS CAMPOS DEL FORMULARIO, CON LOS CAMPOS QUE TENÍA LA RESERVA
                    // DE CARRERA QUE TENÍA HECHA. ADEMÁS, DESHABILITO LOS DATOS QUE NO VOY A QUERER
                    // QUE SE PUEDAN EDITAR
                    // CARGO EL ID DE LA CARRERA
                    $("#idcarrera").val(carrera.id);
                    
                    // CARGO PRECIO
                    $("#precio").val(carrera.precio);
                    
                    // CARGO EL NÚMERO DE PARTICIPANTES, Y LO DESHABILITO
                    $("#num_participantes").val(carrera.num_participantes)
                        .prop('readonly', true)
                        .addClass('campo-deshabilitado');
                    
                    // CARGO EL MÉTODO DE PAGO, Y LO DESHABILITO
                    if (carrera.metodo_pago) {
                        $("#metodo_pago").val(carrera.metodo_pago)
                            .prop('disabled', true)
                            .addClass('campo-deshabilitado')
                            .trigger('change');
                    }
                    
                    // CARGO LAS PISTAS, Y LAS DESHABILITO
                    cargarPistas('#id_pista', carrera.id_pistas || idPistaRespaldo);
                    $('#id_pista').prop('disabled', true)
                        .addClass('campo-deshabilitado');
                    
                    // CARGO AL USUARIO DE LA CARRERA, Y DESHABILITO EL SELECT
                    cargarUsuarios("#id_usuario", carrera.id_usuario);
                    $('#id_usuario').prop('disabled', true)
                        .addClass('campo-deshabilitado');
                    
                    // SE CONFIGURAN LOS SELECT 2 DE USUARIO Y EMPLEADO
                    configurarSelect2UsuarioCarreras();
                    configurarSelect2EmpleadoCarreras();
                    
                    // CARGO A LOS EMPLEADOS
                    cargarEmpleados('#id_empleado', carrera.empleado_id);
                    
                    // PONGO LA FECHA DE CARRERA CON UN FORMATO CORRECTO
                    var partesFecha = carrera.fecha_carrera.split('-');
                    if (partesFecha.length === 3) {
                        $("#fecha_carrera").val(`${partesFecha[2]}-${partesFecha[1]}-${partesFecha[0]}`);
                    }
                    
                    // FECHA DE PAGO, LA DESHABILITO
                    if (carrera.fecha_pago) {
                        var partesPago = carrera.fecha_pago.split('-');
                        if (partesPago.length === 3) {
                            $("#fecha_pago").val(`${partesPago[2]}-${partesPago[1]}-${partesPago[0]}`)
                                .prop('readonly', true)
                                .addClass('campo-deshabilitado')
                                .off('click')
                                .datepicker('destroy');
                        }
                    } else {
                        $("#fecha_pago").prop('readonly', true)
                            .addClass('campo-deshabilitado')
                            .off('click')
                            .datepicker('destroy');
                    }
                    
                    // CARGO LOS HORARIOS, CON LA FECHA Y HORARIO QUE TIENE LA RESERVA DE LA CARRERA
                    cargarHorarios('#id_horario', carrera.fecha_carrera, horarioOriginalCarrera);
                    
                    $('#formCarrera').on('submit', function() {
                        $('#id_pista, #id_usuario, #metodo_pago').prop('disabled', false);
                        return true;
                    });
                    
                    $("#id_pista, #id_usuario, #metodo_pago").trigger('change.select2');
                    
                    $('#modalCarreras').modal('show');
                    
                } else {
                    Swal.fire('Error', response.message || 'Error al cargar datos', 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Error de conexión con el servidor', 'error');
            }
        });
    });
    
    
    ///////////////////////////////////////
    //        FIN ZONA EDITAR           //
    /////////////////////////////////////

// VARIABLE GLOBAL PARA COMPROBAR EL MODO DE LA PISTA
var modoActualPista = "";

// MÉTODO PARA RESETEAR EL MODAL DE LA PISTA
function resetearModalPista() {
    $('#formPista')[0].reset();
    $('#formPista').find('input[name="id_pista"]').val("");
    $('#nombre_pista').val('').removeClass('is-valid is-invalid');
    $('#precio_pista').val('').removeClass('is-valid is-invalid');
    $('#divNombrePista').show();
    $('#divPrecioPista').show();
    $('#divSelectPista').hide();
    $('#btnSalvarPista').show().text(' Guardar');
    $('#btnConfirmarEliminarPista').hide();
    $('[data-bs-dismiss="modal"]').html('<i class="fas fa-times"></i> Cerrar');
}

///////////////////////////////////////
//    INICIO ZONA CREAR PISTA      //
/////////////////////////////////////
$(document).on('click', '#btnCrearPista', function(event) {
    event.preventDefault();
    modoActualPista = 'crear';
    resetearModalPista();
    $('#mdltituloPista').text('Nuevo registro de pista');
    $('#modalPista').modal('show');
});

///////////////////////////////////////
//    CHANGE PARA SELECT PISTA      //
/////////////////////////////////////
$(document).on('change', '#select_pista', function() {
    const opcion = $(this).find('option:selected');
    const id = opcion.val();
    const nombre = opcion.text();
    const precio = opcion.data('precio');
    
    // Solo asignamos el ID si hay una selección válida
    if (id && id !== '') {
        $('#formPista').find('input[name="id_pista"]').val(id);
    } else {
        $('#formPista').find('input[name="id_pista"]').val("");// Limpiamos el ID si no hay selección válida
    }
    
    if (modoActualPista === 'editar') {
        if (id) {
            $('#divNombrePista').show();
            $('#divPrecioPista').show();
            $('#nombre_pista').val(nombre);
            $('#precio_pista').val(precio);
        } else {
            $('#divNombrePista').hide();
            $('#divPrecioPista').hide();
            $('#nombre_pista').val('');
            $('#precio_pista').val('');
        }
    } else if (modoActualPista === 'eliminar') {
        $('#divNombrePista').hide();
        $('#divPrecioPista').hide();
    }
});

///////////////////////////////////////
//    INICIO ZONA EDITAR PISTA      //
/////////////////////////////////////
$(document).on('click', '#btnEditarPista', function(evento) {
    evento.preventDefault();
    modoActualPista = 'editar';
    
    resetearModalPista();
    $('#mdltituloPista').text('Editar pista');
    $('#modalPista').modal('show');
    
    // Configuración específica para edición
    $('#divSelectPista').show();
    $('#divNombrePista').hide();
    $('#divPrecioPista').hide();
    $('#formPista').find('input[name="id_pista"]').val(""); // Aseguramos que empiece vacío
    
    cargarPistas('#select_pista');
});

///////////////////////////////////////
//    INICIO ZONA CREAR/EDITAR PISTA //
/////////////////////////////////////
$(document).on('click', '#btnSalvarPista', async function(evento) {
    evento.preventDefault();
    
    const pistaId = $('#formPista').find('input[name="id_pista"]').val();
    const pistaNombre = ($('#nombre_pista').val() || '').trim();
    const pistaPrecio = ($('#precio_pista').val() || '').trim();
    
    // Validación adicional para modo editar
    if (modoActualPista === 'editar' && !pistaId) {
        toastr.error("Debe seleccionar una pista para editar", "Error");
        return;
    }
    
     // Validación del formulario
     const isFormValid = formPista.validateForm(evento);
     if (!isFormValid) {
         toastr.error("Por favor, corrija los errores en el formulario.", "Error de Validación");
         return;
     }
    
    try {
        // COMPRUEBO SI LA PISTA YA EXISTE, EN CASO AFIRMATIVO, NO SE DEJA CREAR/EDITAR ESTA
        const verificacion = await $.ajax({
            url: base_url + "Pistas/verificarPista",
            type: "POST",
            data: {
                nombre_pista: pistaNombre,
                id: pistaId || null
            },
            dataType: "json"
        });
        
        if(verificacion.existe) {
            toastr.error(verificacion.mensaje || "La pista ya existe", "Error");
            $('#nombre_pista').addClass('is-invalid');
            return;
        }
        
        // AJAX PARA CREAR/EDITAR LA PISTA
        const respuesta = await $.ajax({
            url: pistaId 
                ? base_url + "Pistas/editarPista" 
                : base_url + "Pistas/crearPista",
            type: "POST",
            data: {
                nombre_pista: pistaNombre,
                precio_pista: pistaPrecio,
                id: pistaId || null
            },
            dataType: "json"
        });
        
        if(respuesta.success) {
            $('#modalPista').modal('hide');
            resetearModalPista();
            toastr.success(
                pistaId 
                    ? "¡Pista actualizada correctamente!" 
                    : "¡Pista creada con éxito!", 
                "Éxito"
            );
            // Recargar tabla de pistas si es necesario
        } else {
            toastr.error(respuesta.message || respuesta.mensaje || "Error al procesar", "Error");
        }
        
    } catch(error) {
        if(error.status === 409) {
            toastr.error("Conflicto: " + (error.responseJSON?.mensaje || "La pista ya existe"), "Error");
            $('#nombre_pista').addClass('is-invalid');
        } else {
            console.error("Error completo:", error);
            toastr.error("Error de conexión: " + (error.responseJSON?.mensaje || ""), "Error");
        }
    }
});

///////////////////////////////////////
//    INICIO ZONA ELIMINAR PISTA    //
/////////////////////////////////////
$(document).on('click', '#btnEliminarPista', function(evento) {
    evento.preventDefault();
    modoActualPista = 'eliminar';
    
    resetearModalPista();
    $('#mdltituloPista').text('Eliminar pista');
    $('#modalPista').modal('show');
    
    // Configuración específica para eliminación
    $('#nombre_pista').val('');
    $('#precio_pista').val('');
    $('#select_pista').val('');
    $('#formPista').find('input[name="id_pista"]').val(""); // Aseguramos que empiece vacío
    $('#divSelectPista').show();
    $('#divNombrePista').hide();
    $('#divPrecioPista').hide();
    $('#btnSalvarPista').hide();
    $('#btnConfirmarEliminarPista').show();
    $('[data-bs-dismiss="modal"]').html('<i class="fas fa-times"></i> Cancelar');
    
    cargarPistas('#select_pista');
});

///////////////////////////////////////
//    INICIO ZONA CONFIRMAR ELIMINAR PISTA //
/////////////////////////////////////
$(document).on('click', '#btnConfirmarEliminarPista', async function() {
    const id = $('#formPista').find('input[name="id_pista"]').val();
    
    if (!id) {
        toastr.error("No se ha seleccionado ninguna pista", "Error");
        return;
    }
    // AJAX PARA COMPROBAR QUE NINGUNA CARRERA ESTA USANDO ESA PISTA
    // SI EXISTEN, NO SE PUEDE BORRAR LA PISTA
    try {
        const verificacion = await $.ajax({
            url: base_url + "Pistas/verificarUsoPista",
            type: "POST",
            data: { id: id },
            dataType: "json"
        });
        
        if (verificacion.enUso) {
            toastr.error(
                `No se puede eliminar: ${verificacion.mensaje} (Carreras afectadas: ${verificacion.totalCarreras})`, 
                "Error",
                {timeOut: 5000}
            );
            return;
        }
        
        // Confirmación con toastr
        const toastConfirmacion = toastr.info(
            '<div class="text-center mb-2">¿Está seguro de eliminar esta pista?</div>' +
            '<div class="d-flex justify-content-center">' +
            '  <button class="btn btn-danger btn-sm mx-1" id="confirmarEliminarPistaBtn">Sí, eliminar</button>' +
            '  <button class="btn btn-outline-secondary btn-sm mx-1" id="cancelarEliminarPistaBtn">Cancelar</button>' +
            '</div>',
            "Confirmar eliminación",
            {
                timeOut: 0,
                extendedTimeOut: 0,
                tapToDismiss: false,
                onShown: function() {
                    $('#confirmarEliminarPistaBtn').click(function() {
                        $('.toast-info').hide().remove();
                        eliminarPista(id);
                    });
                    
                    $('#cancelarEliminarPistaBtn').click(function() {
                        $('.toast-info').hide().remove();
                    });
                }
            }
        );
        
    } catch(error) {
        toastr.error("Error al verificar el estado de la pista", "Error");
    }
});

// Función para eliminar pista
async function eliminarPista(id) {
    try {
        // AJAX PARA BORRAR LA PISTA
        const respuesta = await $.ajax({
            url: base_url + "Pistas/eliminarPista",
            type: "POST",
            data: { id: id },
            dataType: "json"
        });
        
        $('#modalPista').modal('hide');
        resetearModalPista();
        toastr.success(
            respuesta.exito 
                ? "Pista eliminada correctamente" 
                : respuesta.mensaje || "Operación fallida",
            respuesta.exito ? "Éxito" : "Error"
        );
        // Recargar datos si es necesario
        
    } catch(error) {
        toastr.error("Error de conexión con el servidor", "Error");
    }
}

    ////////////////////////////////////////////////////////
    //        ZONA FILTROS RADIOBUTTON CABECERA           //
    ///////////////////////////////////////////////////////
    // Escuchar cambios en los radio buttons
    // Si es necesario filtrar por texto en lugar de valores numéricos, hay que asegurarse que los valores de los radio buttons coincidan con los valores de la columna.
    // Función para aplicar el filtro a la tabla de carreras
    function aplicarFiltroCarreras(api) {
        var filtro = $('input[name="filterDates"]:checked').val();
        var ahora = new Date(); // Fecha y hora exactas actuales

        // Limpiar filtros previos
        $.fn.dataTable.ext.search = [];

        // Si el filtro no es "all", se aplica el filtro seleccionado
        if (filtro !== "all") {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var fila = api.row(dataIndex).data();
                var fechaHoraCarrera = new Date(fila.fecha_carrera + 'T' + fila.hora_inicio);
                var esHoy = fechaHoraCarrera.toDateString() === ahora.toDateString();

                switch(filtro) {
                    case "past":
                        return fechaHoraCarrera < ahora; // Carreras pasadas
                    case "current":
                        return esHoy && fechaHoraCarrera > ahora; // Carreras de hoy con horario futuro
                    case "future":
                        return fechaHoraCarrera > ahora; // Carreras futuras
                }
            });
        }

        api.draw(); // Redibujar la tabla con el filtro aplicado

        // Mensaje si no hay resultados
        if (api.rows({ filter: 'applied' }).count() === 0) {
            var mensaje = {
                "past": "No hay carreras pasadas",
                "current": "No hay carreras futuras hoy", 
                "future": "No hay carreras futuras",
                "all": "No hay carreras registradas"
            }[filtro];

            $(api.table().body()).html(`
                <tr><td colspan="${api.columns().count()}" class="text-center">${mensaje}</td></tr>
            `);
        }
    }

    ////////////////////////////////////////////////////////////
    //        FIN ZONA FILTROS RADIOBUTTON CABECERA          //
    //////////////////////////////////////////////////////////
    ////////////////////////////////////////////////
    //        ZONA FILTRO DE LA FECHA            //
    ///////////////////////////////////////////////
    $('#dateCreateFilter').on('change', function () {
        var value = $(this).val(); // Obtener el valor seleccionado
        //console.log(value);
        table_e.column(4).search(value).draw();
    });


    // borrar la fecha
    $('#borrarFechaFiltro').on('click', function () {
        $('#dateCreateFilter').val('');
        $('#dateCreateFilter').trigger('change');
    });

    // cambiar el cursor
    $('#borrarFechaFiltro').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    }).on('mouseleave', function () {
        $(this).css('cursor', 'default');
    });
    ////////////////////////////////////////////////
    //     FIN ZONA FILTRO DE LA FECHA           //
    ///////////////////////////////////////////////

    ////////////////////////////////////////////
    //  INICIO ZONA FILTROS PIES y SEARCH     //
    //    NO ES NECESARIO TOCAR              //
    //     FUNCIONES NO TOCAR               // 
    ///////////////////////////////////////////¡

    // Filtro de cada columna en el pie de la tabla de admin (tfoot)
    // ejemplo - $('#employees-table tfoot input').on('keyup', function () {
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
}); // de document.ready