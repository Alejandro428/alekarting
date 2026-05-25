$(document).ready(function () {

    $('#dateCreateFilter').inputmask('99-99-9999');
    
    // CONFIGURAR EL CALENDARIO EN ESPAÑOL
    $.datepicker.setDefaults($.datepicker.regional['es']);

    /* CALENDARIO DE FILTRO DE FECHA NOTICIA */
    $('#dateCreateFilter').datepicker({
        showAnim: "slideDown",
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1
    });

    /* CALENDARIO DE FECHA NOTICIA (MODAL NOTICIA) */
    $('#fecha_publicacion').inputmask('99-99-9999');

    $('#fecha_publicacion').datepicker({
        appendTo: '#modalEmpleado',  
        showAnim: "slideDown",       
        dateFormat: 'dd-mm-yy',    
        showOtherMonths: true,   
        selectOtherMonths: true,    
        numberOfMonths: 1,        
        beforeShow: function(input, inst) {
            setTimeout(function() {
                var inputOffset = $(input).offset(); 
                var inputHeight = $(input).outerHeight();
                inst.dpDiv.css({
                    'z-index': 2000, 
                    'position': 'absolute', 
                    'top': inputOffset.top + inputHeight + 5, 
                    'left': inputOffset.left
                });
            }, 0);
        }
    });
    
    // BORRAR LA FECHA DEL INPUT DE FECHA_PUBLICACION
    $('#borrarFechaPublicacion').on('click', function () {
        $('#fecha_publicacion').val('');
        $('#fecha_publicacion').trigger('change');
    });

    // CAMBIAR EL CURSOR PARA EL BOTÓN DE BORRAR FECHA_PUBLICACION
    $('#borrarFechaPublicacion').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    }).on('mouseleave', function () {
        $(this).css('cursor', 'default');
    });

// VARIABLE GLOBAL PARA ALMACENAR LAS DIMENSIONES DE LA IMAGEN
var imagenDimensions = null;
// MÉTODO PARA VALIDAR MANUALMENTE EL CAMPO IMAGEN
function validarImagen() {
    // RECOJO EL ID DE LA NOTICIA, EL INPUT DE LA IMAGEN, Y EL ARCHIVO QUE CONTIENE LA IMAGEN
    var idN = $('#formNoticia').find('input[name="idnoticia"]').val().trim();
    var imagenInput = $('#imagen');
    var file = imagenInput[0].files[0];

    // CREACIÓN DE NOTICIA: SI NO HAY ID EXISTENTE, SE EXIGE IMAGEN (YA QUE ES UNA CREACIÓN DE NOTICIA, Y NO HAY IMAGEN ANTERIOR)
    if (idN === "") {
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
        if (allowedMimes.indexOf(file.type) === -1) {
            // SI NO ESTA ENTRE ESOS, DA ERROR
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
                text: "El tamaño de la imagen supera el límite permitido (10 MB).",
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

// VARIABLE GLOBAL PARA SABER LA DURACIÓN DEL VIDEO
var videoDuration = null;

// MÉTODO PARA VALIDAR MANUALMENTE EL CAMPO VIDEO
function validarVideo() {
    // RECOJO EL ID DE LA NOTICIA, EL INPUT DEL VIDEO, Y EL ARCHIVO QUE CONTIENE EL VIDEO
    var idN = $('#formNoticia').find('input[name="idnoticia"]').val().trim();
    var videoInput = $('#video');
    var file = videoInput[0].files[0];

    // CREACIÓN DE NOTICIA: SI NO HAY ID EXISTENTE, SE EXIGE VIDEO (YA QUE ES UNA CREACIÓN DE NOTICIA, Y NO HAY VIDEO ANTERIOR)
    if (idN === "") {
        if (!file) {
            videoInput.addClass('is-invalid').removeClass('is-valid');
            return false;
        }
    } else {
        // EDITANDO: SI EDITANDO NO SE SELECCIONA NUEVO VIDEO, SE MANTIENE EL ANTERIOR
        if (!file) {
            return true;
        }
    }

    // SE VALIDA QUE LA DURACIÓN DEL VIDEO SEA LA CORRECTA
    if (!videoDuration || videoDuration > 120) {
        videoInput.addClass('is-invalid').removeClass('is-valid');
        return false;
    }

    videoInput.removeClass('is-invalid').addClass('is-valid');
    return true;
}

// EVENTO CHANGE CUANDO HAY UN CAMBIO EN EL VIDEO
$('#video').on('change', function() {
    var file = this.files[0];
    videoDuration = null; // SE RESETEA LA VARIABLE GLOBAL DE LA DURACIÓN DEL VIDEO
    
    if (file) {
        // TIPOS DE VIDEO PERMITIDO, EN CASO DE NO SER MP4, DA ERROR
        if (file.type !== 'video/mp4' && !file.name.toLowerCase().endsWith('.mp4')) {
            Swal.fire({
                title: "Error",
                text: "Solo se permiten archivos MP4.",
                icon: "error"
            });
            $(this).val('');
            $(this).removeClass('is-valid').addClass('is-invalid');
            return;
        }

        // SI SUPERA LOS 7 MB, DA ERROR
        if (file.size > 7 * 1024 * 1024) {
            Swal.fire({
                title: "Error",
                text: "El video no puede superar los 7MB.",
                icon: "error"
            });
            $(this).val('');
            $(this).removeClass('is-valid').addClass('is-invalid');
            return;
        }

        // Validar duración
        var video = document.createElement('video');
        video.preload = 'metadata';
        
        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            videoDuration = video.duration;
            // SI LA IMAGEN NO CUMPLE CON LA DURACIÓN MÁXIMA, DA ERROR
            if (videoDuration > 120) {
                Swal.fire({
                    title: "Error",
                    text: "El video no puede durar más de 2 minutos (120 segundos).",
                    icon: "error"
                });
                $('#video').val('');
                videoDuration = null;
                $('#video').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#video').removeClass('is-invalid').addClass('is-valid');
            }
        };
        
        video.src = URL.createObjectURL(file);
    }
});
    
    // DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL
    $(document).on('click', '#btnAyudaNoticias', function (event) {
    event.preventDefault();
    $('#modalAyudaNoticias').modal('show');
    });

    /////////////////////////////////////
    //     FORMATEO DE CAMPOS          //
    ///////////////////////////////////

    // VALIDADOR DEL FORMULARIO DE NOTICIAS
    var formValidator = new FormValidator('formNoticia', {
        titulo: {
            pattern: '^(?!\\s*$).{3,90}$',
            required: true
        },
        subtitulo: {
            pattern: '^(?!\\s*$).{3,255}$',
            required: true
        },
        id_categoria: {
            required: true
        },
        fecha_publicacion: {
            pattern: '^\\d{2}-\\d{2}-\\d{4}$', // Se espera formato dd-mm-aaaa (por ejemplo, 31-12-2025)
            required: true
        },  
    });

        // MÉTODO PARA VALIDAR EL FORMATO DE LA FECHA (dd-mm-aaaa)
        function validarFecha(fecha) {
            // EXPRESIÓN REGULAR PARA dd-mm-aaaa
            var regex = /^(\d{2})-(\d{2})-(\d{4})$/;
            if (!regex.test(fecha)) {
                return false;
            }
            var partes = fecha.split("-");
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
    
        // VALIDAR FECHA DE PUBLICACIÓN DE NOTICIA CUANDO CAMBIA EL INPUT DE FECHA
        $('#fecha_publicacion').on('input change', function() {
            var valor = $(this).val();
    
            if (validarFecha(valor)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });

           
        /////////////////////////////////////////
    //     CONTROL DE ACORDEONES          //
    ////////////////////////////////////////

     // DESPLEGABLE de Filtros de Noticias
     $('#collapseNoticias').on('show.bs.collapse', function() {
        $('#accordion-toggle-noticias')
            .removeClass('bg-primary')
            .addClass('bg-info')
            .css('color', 'white');
    });

    $('#collapseNoticias').on('hide.bs.collapse', function() {
        $('#accordion-toggle-noticias')
            .removeClass('bg-info')
            .addClass('bg-primary')
            .css('color', '#e6f0fa');
    });

    $('#accordion-toggle-noticias').hover(
        function() { $(this).css('opacity', '0.9'); },
        function() { $(this).css('opacity', '1'); }
    );

    // DESPLEGABLE de Acciones de Noticias
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
    
    // INICIALIZAR EL SUMMERNOTE DEL CONTENIDO DE LA NOTICIA (MODAL DE NOTICIAS)
    $('#contenido').summernote({
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


    /////////////////////////////////////////
    //     FIN FORMATEO DE CAMPOS          //
    ////////////////////////////////////////

    /////////////////////////////////////
    // INICIO DE LA TABLA DE NOTICIAS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LAS NOTICIAS DEL EMPLEADO
    var datatable_noticiaConfig = {
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
        order: [[4, 'desc']], // Orden inicial por fecha_inicio en orden descendente
        // Definimos solo las columnas que queremos mostrar al usuario
        columns: [
            { name: 'control', data: null, defaultContent: '', className: 'details-control sorting_1 text-center' }, // Columna 0: Mostrar más
            { name: 'nombre_usuario', data: 'nombre_usuario' }, // Columna 1: Autor
            { name: 'titulo', data: 'titulo' }, // Columna 2: Título
            { name: 'nombre_categoria', data: 'nombre_categoria' }, // Columna 3: Categoría
            { name: 'fecha_publicacion', data: 'fecha_publicacion' }, // Columna 4: Fecha de publicación
            { name: 'imagen', data: 'imagen' },  // Columna 5: Imagen
            { name: 'editar', data: null, defaultContent: '' },  // Columna 6: Botón para Editar
            { name: 'eliminar', data: null, defaultContent: '' }   // Columna 7: Botón para Eliminar
        ],
        
        columnDefs: [
            // Columna 0: BOTÓN MÁS 
            { 
                targets: 'control:name', width: '5%', searchable: false, orderable: false, className: "text-center" 
            },
            {
                // Columna 1: Autor (nombre_usuario)
                targets: 'nombre_usuario:name',
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                // Columna 2: Título (recorta a 20 caracteres)
                targets: 'titulo:name',
                width: '35%',
                className: "text-center",
                searchable: true,
                orderable: true,
                render: function(data) {
                    if (!data) return '';
                    const maxLength = 60;
                    return (data.length > maxLength)
                        ? data.substring(0, maxLength) + '...'
                        : data;
                }
            },
            {
                // Columna 3: Categoría (nombre_categoria)
                targets: 'nombre_categoria:name',
                width: '10%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
            {
                 // Columna 4: Fecha de publicación (fecha_publicacion)
                targets: 'fecha_publicacion:name',
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
                // Columna 5: Imagen
                targets: 'imagen:name',
                width: '5%',
                className: "text-center",
                searchable: true,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <img src="${base_url + 'assets/imagenes/noticias/imgs/' + row.imagen}" 
                             style="width: 40px; height: 30px; object-fit: cover;"
                             alt="Noticia"
                             data-toggle="tooltip-primary" data-placement="top">
                    `;
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
                    // Aunque no se muestra la columna del id, el objeto row contiene row.id.
                    return `<button type="button" class="btn btn-info btn-sm editarNoticia" 
                                  data-toggle="tooltip-primary" data-placement="top" 
                                  title="Editar" data-id="${row.id}" data-cat="${row.id_categoria}">
                              <i class="fa-solid fa-edit"></i>
                            </button>`;
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
                    return `<button type="button" class="btn btn-danger btn-sm eliminarNoticia" 
                                  data-toggle="tooltip-primary" data-placement="top" 
                                  title="Eliminar" data-id="${row.id}">
                              <i class="fa-solid fa-trash"></i>
                            </button>`;
                }
            }
        ],
        
        ajax: {
            url: base_url + "EmpleadoNoticias/obtenerNoticiasDeUsuario",
            type: 'GET',
            dataSrc: 'data'
        },
        order: [[4, 'asc']], // ordenar por la columna 4 - fecha noticia
        rowGroup: {
            dataSrc: function (row) {
                return formatoFechaEuropeoSoloFecha(row.fecha_publicacion);
            },
            startRender: function (rows, group) {
                let $row = $('<tr/>').append('<td colspan="9" class="group-header">' + group + ' / ' + rows.count() + ' noticia/s' + '</td>');
                return $row;
            } // de la function startRender
        }, // de la rowGroup
        initComplete: function () {
            const api = this.api();
            // AL CARGAR EL DATATABLE, SE SELECCIONA EL FILTRO QUE ESTE SELECCIONADO EN ESE MOMENTO (POR DEFECTO, TODAS LAS NOTICIAS)
            aplicarFiltroNoticias(api); // Al cargar la tabla
        
            // EN CASO DE CAMBIAR EL FILTRO, SE ACTUALIZA EL CONTENIDO DEL DATATABLE DE NOTICIAS
            $('input[name="filtroNoticias"]').on('change', function () {
                aplicarFiltroNoticias(api); // Al cambiar el filtro
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
    var $table = $('#noticias_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_noticiaConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#noticias_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#noticias_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    /* en el $columnFilterInputs solo cambiar el nombre de la tabla que encontraremos en HTML*/

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

     // MÉTODO PARA MOSTRAR TEXTOS CON CARÁCTERES ESPECIALES CORRECTAMENTE
     function escapeHtmlAttr(text) {
      if (!text) return '';
        return text.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    
    // BOTÓN MÁS
    // MÉTODO PARA MOSTRAR EL CONTENIDO DEL MOSTRAR MÁS
    function format(d) {
        console.log(d);
        
        return `
        <div class="card border-primary" style="max-width: 100%; overflow: visible;">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bi bi-newspaper fs-4 me-2"></i>
                <h5 class="mb-0">Detalles Completos</h5>
            </div>
            
            <div class="card-body" style="overflow: visible;">
                <!-- Imagen -->
                <div class="mb-3 text-center" style="overflow: visible;">
                    <p class="fw-bold mb-2"><i class="bi bi-image me-2"></i>Imagen de la noticia:</p>
                    <img src="${base_url + 'assets/imagenes/noticias/imgs/' + d.imagen}" 
                         class="img-fluid rounded border" 
                         style="max-height: 250px; object-fit: cover; width: auto;" 
                         alt="Imagen de la noticia">
                </div>
                
                <!-- Video (si existe) -->
                ${d.video ? `
                <div class="mb-3 text-center" style="overflow: visible;">
                    <p class="fw-bold mb-2"><i class="bi bi-film me-2"></i>Video de la noticia:</p>
                    <video controls class="img-fluid rounded border" style="max-height: 250px; width: auto;">
                        <source src="${base_url + 'assets/imagenes/noticias/videos/' + d.video}" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                </div>
                ` : ''}
                
                <!-- Título y Subtítulo - Versión expandible -->
                <div class="row mb-3" style="overflow: visible;">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <p class="fw-bold mb-1"><i class="bi bi-type me-2"></i>Título:</p>
                        <div class="p-2 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word; min-height: 50px;">
                            ${escapeHtmlAttr(d.titulo) || '<span class="text-muted">No tiene un título</span>'}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-bold mb-1"><i class="bi bi-subtract me-2"></i>Subtítulo:</p>
                        <div class="p-2 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word; min-height: 50px;">
                            ${escapeHtmlAttr(d.subtitulo) || '<span class="text-muted">No tiene un subtítulo</span>'}
                        </div>
                    </div>
                </div>
                
                <!-- Contenido - Versión expandible -->
                <div class="mb-2" style="overflow: visible;">
                    <p class="fw-bold mb-1"><i class="bi bi-text-paragraph me-2"></i>Contenido:</p>
                    <div class="p-3 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word; min-height: 50px;">
                        ${escapeHtmlAttr(d.contenido) || '<span class="text-muted">No tiene contenido</span>'}
                    </div>
                </div>
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
    // Funcion para cargar un select    //
    ///////////////////////////// ///////
    function cargarCategorias(selectId, idCategoriaSeleccionada = null) {
        // AJAX PARA OBTENER TODAS LAS CATEGORIAS DISPONIBLES DE NOTICIAS
        $.get(base_url + "categorias/getCategorias", function (data) {
            let $select = $(selectId);
            $select.empty(); // Limpiar opciones actuales
    
            // Agregar opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione una categoría...'
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
                data.forEach(function (categoria) {
                    let option = $('<option>', {
                        value: categoria.id,
                        text: categoria.nombre_categoria
                    });
    
                    if (idCategoriaSeleccionada && categoria.id == idCategoriaSeleccionada) {
                        option.prop('selected', true);
                    }
    
                    $select.append(option);
                });
            } else {
                $select.append($('<option>', {
                    value: '',
                    text: 'No hay categorías disponibles'
                }));
            }
    
        }, "json").fail(function (xhr, status, error) {
            console.error("Error al cargar las categorías:", error);
            $(selectId).html("<option value=''>Error al cargar categorías</option>");
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
    //   INICIO ZONA DELETE NOTICIA  //
    ///////////////////////////////////
    // MÉTODO PARA ELIMINAR LA NOTICIA ESCOGIDA
    function eliminarNoticia(id) {
        // NOTIFICACIÓN PARA AVISAR DE SI SE QUIERE ELIMINAR
        swal.fire({
            title: 'Desactivar',
            text: `¿Desea eliminar la noticia con ID ${id}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // SI SE DICE QUE SI, SE HACE UN AJAX QUE SE ENCARGA DE ELIMINAR LA NOTICIA
                $.post(base_url + "EmpleadoNoticias/eliminarNoticias/" + id, function(data) {
                    // NOTIFICACIÓN PARA AVISAR AL EMPLEADO DE QUE SE HA ELIMINADO LA NOTICIA
                    $table.DataTable().ajax.reload();
                    swal.fire(
                        'Desactivado',
                        'La noticia ha sido eliminada',
                        'success'
                    );
                }).fail(function(xhr, status, error) {
                    console.error("Error al eliminar la noticia:", error);
                    swal.fire(
                        'Error',
                        'No se pudo eliminar la noticia',
                        'error'
                    );
                });
            }
        });
    }
    


    // CAPTURAR EL CLICK EN EL BOTÓN DE BORRAR
    $(document).on('click', '.eliminarNoticia', function (event) {
        event.preventDefault();
        let id = $(this).data('id'); // Cambiado de data('id') a data('prod_id')
        eliminarNoticia(id);
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
        $('#mdltitulo').text('Nuevo registro de noticia');
    
        // MOSTRAR EL MODAL
        $('#modalEmpleado').modal('show');
    
        // LIMPIAR EL FORMULARIO
        $("#formNoticia")[0].reset();
        $('#formNoticia').find('input[name="idnoticia"]').val("");
        
         // LIMPIAR EL SUMMERNOTE TAMBIÉN
        $('#contenido').summernote('reset'); 
        $('#contenido').summernote('removeFormat'); 
        $('#contenido').removeClass('is-invalid is-valid'); 

        // LIMPIAR LAS VALIDACIONES
        formValidator.clearValidation(); // LIMPIAR TODA LA VALIDACIÓN CON EL CLEAR VALIDATION
    
        // ELIMINAR VALIDACIONES PREVIAS DE IMAGEN Y VIDEO
        $('#imagen').val('').removeClass('is-valid is-invalid');
        $('#video').val('').removeClass('is-valid is-invalid');
        
        // RESETEAR SUS VARIABLES GLOBALES
        imagenDimensions = null; 
        videoDuration = null;   
    
        // CARGAR EN EL SELECT LAS CATEGORÍAS EN EL MODAL DE NOTICIAS
        cargarCategorias('#id_categoria');
    });

// CAPTURAR EL CLICK EN EL BOTÓN DE SALVAR
$(document).on('click', '#btnsalvar', async function(event) {
    event.preventDefault();

    // RECOGER LOS VALORES DEL FORMULARIO
    var idN = $('#formNoticia').find('input[name="idnoticia"]').val().trim();
    var tituloN = $('#formNoticia').find('input[name="titulo"]').val().trim();
    var subtituloN = $('#formNoticia').find('input[name="subtitulo"]').val().trim();
    var contenidoN = $('#formNoticia').find('#contenido').summernote('code');
    var contenidoText = $('<div>').html(contenidoN).text().trim(); // ✅ Seguro
    var idcategoriaN = $('#formNoticia').find('select[name="id_categoria"]').val().trim();
    var imagenFile = $('#imagen')[0].files[0];
    var videoFile = $('#video')[0].files[0];
    
    // RECOGER Y FORMATEAR LA FECHA DE PUBLICACIÓN
    var fechaN = $('#formNoticia').find('input[name="fecha_publicacion"]').val().trim();
    var fechaOriginal = "";
    if (fechaN !== "") {
        var parts = fechaN.split('-');
        if (parts.length === 3) {
            fechaOriginal = parts[2] + '-' + parts[1] + '-' + parts[0];
        } else {
            fechaOriginal = fechaN;
        }
    }

    // VALIDAR EL RESTO DEL FORMULARIO USANDO FORMVALIDATOR
    var isFormValid = formValidator.validateForm(event);
    // VALIDAR CAMPOS DE IMAGEN Y VIDEO DE FORMA CONCRETA
    var isImagenValid = validarImagen();
    var isVideoValid = validarVideo();

    // SI ALGUNA DE LAS VALIDACIONES FALLA, NO SE PUEDE CONTINUAR
    if (!isFormValid || !isImagenValid || !isVideoValid) {
        if (!isFormValid) {
            toastr.error("Por favor, corrija los errores en el formulario.", "Error de Validación");
        } else { 
            // Solo entra aquí si el formulario está bien, pero hay error en imagen o video
            if (!isImagenValid) {
                toastr.error("Revise el campo de imagen.", "Error de Validación");
            }
            if (!isVideoValid) {
                toastr.error("Revise el campo de video.", "Error de Validación");
            }
        }
        return;
    }

     // HAY QUE VALIDAR TAMBIÉN POR SEPARADO EL CONTENIDO, YA QUE ES UN TEXTO DEL SUMMERNOTE
     if (!contenidoText) {
        toastr.error("La noticia necesita contenido.", "Error de Validación");
        return;
    }
    
    // CREAR OBJETO FORMDATA Y AGREGAR LOS DATOS
    var formData = new FormData();
    formData.append("titulo", tituloN);
    formData.append("subtitulo", subtituloN);
    formData.append("contenido", contenidoN);
    formData.append("id_categoria", idcategoriaN);
    
    if (fechaOriginal !== "") {
        formData.append("fecha_publicacion", fechaOriginal);
    }
    if (imagenFile) {
        formData.append("imagen", imagenFile);
    }
    if (videoFile) {
        formData.append("video", videoFile);
    }
    if (idN !== "") {
        formData.append("id", idN);
    }

    // DETERMINAR LA URL DE ENVÍO SEGÚN SI SE TRATA DE CREACIÓN O EDICIÓN
    var urlEnvio = (idN === "")
        ? base_url + "EmpleadoNoticias/crearNoticias"
        : base_url + "EmpleadoNoticias/editarNoticias/" + idN;
    // SE HACE EL AJAX PARA CREAR/EDITAR LA NOTICIA
    $.ajax({
        url: urlEnvio,
        type: "POST",
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(data) {
            $('#modalEmpleado').modal('hide');
            $table.DataTable().ajax.reload();
            $("#formNoticia")[0].reset();
            // SE RESETEAN LOS INPUTS DE IMAGEN Y VIDEO CON SUS ESTILOS
            $('#imagen').val('').removeClass('is-valid is-invalid');
            $('#video').val('').removeClass('is-valid is-invalid');
            // RESETEAR VARIABLES GLOBALES
            imagenDimensions = null;
            videoDuration = null;
            
            toastr["success"]("La noticia ha sido guardada", "Guardado");
        },
        error: function(xhr, status, error) {
            Swal.fire(
                'Error',
                'No se pudo guardar la noticia',
                'error'
            );
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
    $(document).on('click', '.editarNoticia', function(event) {
        event.preventDefault();
    
        // LIMPIAR VALIDACIONES PREVIAS
        formValidator.clearValidation();
        $('#mdltitulo').text('Edición de noticia');
    
        // ELIMINAR VALIDACIONES PREVIAS DE IMAGEN Y VIDEO
        $('#imagen').val('').removeClass('is-valid is-invalid');
        $('#video').val('').removeClass('is-valid is-invalid');
        
        // RESETEAR VARIABLES GLOBALES
        imagenDimensions = null;
        videoDuration = null;
    
        // OBTENER EL ID DE LA NOTICIA A EDITAR
        let idNoticia = $(this).data('id');
        
        // AJAX PARA OBTENER LA NOTICIA
        $.ajax({
            url: base_url + "EmpleadoNoticias/obtenerNoticia/" + idNoticia,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let noticia = response.data;
    
                    // RELLENAR LOS CAMPOS DEL FORMULARIO
                    $("#idnoticia").val(noticia.id);
                    $("#titulo").val(noticia.titulo);
                    $("#subtitulo").val(noticia.subtitulo);
                    // ESTABLECER EL CONTENIDO EN SUMMERNOTE
                    $('#modalEmpleado .modal-body #contenido').summernote('code', noticia.contenido);
                    
                    // CARGAR EL SELECT DE CATEGORÍAS
                    cargarCategorias('#id_categoria', noticia.id_categoria);
                    
                    // FORMATEAR FECHA DE PUBLICACIÓN
                    if (noticia.fecha_publicacion) {
                        let parts = noticia.fecha_publicacion.split('-');
                        if (parts.length === 3) {
                            let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                            $("#fecha_publicacion").val(formattedDate);
                        } else {
                            $("#fecha_publicacion").val(noticia.fecha_publicacion);
                        }
                    } else {
                        $("#fecha_publicacion").val('');
                    }
    
                    // MOSTRAR EL MODAL PARA EDITAR
                    $('#modalEmpleado').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo obtener la noticia para edición', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar la noticia para edición:", error);
                Swal.fire('Error', 'Error en la comunicación con el servidor', 'error');
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
    // MÉTODO PARA APLICAR EL FILTRO AL DATATABLE DE NOTICIAS
    function aplicarFiltroNoticias(api) {
        var filtro = $('input[name="filtroNoticias"]:checked').val();
        var hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
    
        $.fn.dataTable.ext.search = [];
    
        if (filtro !== "all") {
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var fila = api.row(dataIndex).data();
                var fechaStr = fila.fecha_publicacion;
    
                if (!fechaStr) return false;
    
                var fechaNoticia = new Date(fechaStr + 'T00:00:00');
                if (isNaN(fechaNoticia.getTime())) return false;
    
                switch (filtro) {
                    case "past":
                        return fechaNoticia < hoy;
                    case "current":
                        return fechaNoticia.toDateString() === hoy.toDateString();
                }
            });
        }
    
        api.draw();
    
        // Mostrar mensaje si no hay resultados
        if (api.rows({ filter: 'applied' }).count() === 0) {
            var mensaje = {
                "past": "No hay noticias pasadas",
                "current": "No hay noticias publicadas hoy",
                "all": "Este usuario no tiene noticias creadas"
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