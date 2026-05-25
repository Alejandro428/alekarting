$(document).ready(function () {

    $('#dateCreateFilter').inputmask('99-99-9999');

    // CONFIGURA EL CALENDARIO EN ESPAÑOL
    $.datepicker.setDefaults($.datepicker.regional['es']);

    /* FILTRO DE FECHA PARA EL PAGO */
    $('#dateCreateFilter').datepicker({
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1
    });

  
          /////////////////////////////////////////
    //     CONTROL DE ACORDEONES          //
    ////////////////////////////////////////

     // DESPLEGABLE de Filtros de Pagos
     $('#collapsePagos').on('show.bs.collapse', function() {
        $('#accordion-toggle-pagos')
            .removeClass('bg-primary')
            .addClass('bg-info')
            .css('color', 'white');
    });

    $('#collapsePagos').on('hide.bs.collapse', function() {
        $('#accordion-toggle-pagos')
            .removeClass('bg-info')
            .addClass('bg-primary')
            .css('color', '#e6f0fa');
    });

    $('#accordion-toggle-pagos').hover(
        function() { $(this).css('opacity', '0.9'); },
        function() { $(this).css('opacity', '1'); }
    );

    // DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL
    $(document).on('click', '#btnAyudaPagos', function (event) {
    event.preventDefault();
    $('#modalAyudaGestionPagos').modal('show');
    });

    /////////////////////////////////////
    // INICIO DE LA TABLA DE CARRERAS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LOS PAGOS QUE EXISTAN COMO ADMIN
    var datatable_pagoConfig = {
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
            { name: 'dia', data: 'dia', className: 'text-center' }, // Columna 1: dia
            { name: 'total_carreras', data: 'total_carreras', className: 'text-center' }, // Columna 2: Total carreras
            { name: 'cantidad_carreras', data: 'cantidad_carreras', className: 'text-center' },       // Columna 3: Total carreras
            { name: 'total_eventos', data: 'total_eventos', className: 'text-center' },   // Columna 4: Cantidad de carreras
            { name: 'cantidad_eventos', data: 'cantidad_eventos', className: 'text-center' },   // Columna 5: Cantidad de eventos
            { name: 'total_dia', data: 'total_dia',  className: 'text-center' },   // Columna 6: Total del día
        ],
        
        columnDefs: [
               // Columna 0: BOTÓN MÁS 
            { 
                targets: "control:name", width: '5%', searchable: false, orderable: false, className: "text-center" 
            },
            {
                // Columna 1: Día
                targets: "dia:name",
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
                // Columna 2: Total carreras
                targets: "total_carreras:name",
                width: '15%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 3: Cantidad carreras
                targets: "cantidad_carreras",
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 4: Total eventos
                targets: "total_eventos:name",
                width: '15%',
                searchable: true,
                orderable: true,
                className: "text-center",
            },
            {
                // Columna 5: Total eventos
                targets: "cantidad_eventos:name",
                width: '15%',
                searchable: true,
                orderable: true,
                className: "text-center",
            },
            {
                // Columna 6: Total eventos
                targets: "total_dia:name",
                width: '15%',
                searchable: true,
                orderable: true,
                className: "text-center",
            },
        ],
        // AJAX PARA OBTENER TODOS LOS DÍAS QUE TENGAN PAGOS
        ajax: {
            url: base_url + "RegistroPagos/obtenerTodosLosDiasConPagos",
            type: 'GET',
            dataSrc: 'data'
        },
        order: [[1, 'asc']], // ORDENAR POR DÍAS
        initComplete: function() {
            const api = this.api();
    
            // APLICAR FILTRO PARA SOLO MOSTRAR LOS DATOS QUE CUMPLAN EL FILTRO DEL RADIO SELECCIONADO
            aplicarFiltroPagos(api);
    
            // APLICAR FILTRO PARA SOLO MOSTRAR LOS DATOS QUE CUMPLAN EL FILTRO DEL RADIO SELECCIONADO
            $('input[name="filterDates"]').on('change', function() {
                aplicarFiltroPagos(api);  
            });
        }
    };
    
    ////////////////////////////
    // FIN DE LA TABLA DE    //
    ///////////////////////////

    /************************************/
    //     ZONA DE DEFINICIONES        //
    /**********************************/
    // Definición inicial de la tabla de empleados
    var $table = $('#pagos_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_pagoConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#pagos_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#pagos_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    /* en el $columnFilterInputs solo cambiar el nombre de la tabla que encontraremos en HTML*/

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

    // MÉTODO PARA MOSTRAR EL CONTENIDO DEL MOSTRAR MÁS
    function format(d) {
        return `
        <div class="card border-primary mb-3">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bar-chart-line fs-3 me-2"></i>
                    <h5 class="card-title mb-0">Resumen de Pagos del Día</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless table-striped table-hover mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" class="ps-4 w-25"><i class="bi bi-calendar-date me-2"></i>Fecha</th>
                            <td class="pe-4">${d.dia ? `<span class="fw-normal">${formatoFechaEuropeo(d.dia)}</span>` : '<span class="text-muted fst-italic">No registrada</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-speedometer2 me-2"></i>Total Carreras</th>
                            <td class="pe-4">${d.total_carreras ? `<span class="fw-normal">${d.total_carreras} €</span>` : '<span class="text-muted fst-italic">Sin carreras</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-123 me-2"></i>Cantidad Carreras</th>
                            <td class="pe-4">${d.cantidad_carreras ? `<span class="fw-normal">${d.cantidad_carreras}</span>` : '<span class="text-muted fst-italic">0</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-ticket-perforated me-2"></i>Total Eventos</th>
                            <td class="pe-4">${d.total_eventos ? `<span class="fw-normal">${d.total_eventos} €</span>` : '<span class="text-muted fst-italic">Sin eventos</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-123 me-2"></i>Cantidad Res Eventos</th>
                            <td class="pe-4">${d.cantidad_eventos ? `<span class="fw-normal">${d.cantidad_eventos}</span>` : '<span class="text-muted fst-italic">0</span>'}</td>
                        </tr>
                        <tr class="table-success">
                            <th scope="row" class="ps-4"><i class="bi bi-cash-stack me-2"></i>Total del Día</th>
                            <td class="pe-4 fw-bold">${d.total_dia ? `<span class="fw-bold">${d.total_dia} €</span>` : '<span class="text-muted fst-italic">0 €</span>'}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-top-0 text-end">
                <small class="text-muted">Consultado: ${new Date().toLocaleString()}</small>
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

    ////////////////////////////////////////////////////////
    //        ZONA FILTROS RADIOBUTTON CABECERA           //
    ///////////////////////////////////////////////////////
    // Escuchar cambios en los radio buttons
    // Si es necesario filtrar por texto en lugar de valores numéricos, hay que asegurarse que los valores de los radio buttons coincidan con los valores de la columna.
    
    // MÉTODO PARA APLICAR EL FILTRO AL DATATABLE DE PAGOS
    function aplicarFiltroPagos(api) {
        var filtro = $('input[name="filterDates"]:checked').val();
        var hoy = new Date();
        hoy.setHours(0, 0, 0, 0); // Normalizamos a inicio del día
    
        // Limpiar filtros previos
        $.fn.dataTable.ext.search = [];
    
        if (filtro !== "all") {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var fila = api.row(dataIndex).data();
                var fechaPago = new Date(fila.dia);
                fechaPago.setHours(0, 0, 0, 0); 
    
                switch(filtro) {
                    case "past":
                        return fechaPago < hoy; // Pagos pasados
                    case "current":
                        return fechaPago.getTime() === hoy.getTime(); // Pagos de hoy
                    case "future":
                        return fechaPago > hoy; // Pagos futuros
                }
            });
        }
    
        api.draw();
    
        // Mensaje si no hay resultados
        if (api.rows({ filter: 'applied' }).count() === 0) {
            var mensajes = {
                "past": "No hay pagos anteriores al día de hoy",
                "current": "No hay pagos registrados hoy", 
                "future": "No hay pagos programados para días futuros",
                "all": "No hay pagos registrados"
            };
            
            $(api.table().body()).html(`
                <tr><td colspan="${api.columns().count()}" class="text-center py-4 text-muted">
                    <i class="bi bi-info-circle me-2"></i>${mensajes[filtro]}
                </td></tr>
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
        table_e.column(1).search(value).draw();
    });


    // BORRAR LA FECHA
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

    // Filtro de cada columna en el pie de la tabla de empleados (tfoot)
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