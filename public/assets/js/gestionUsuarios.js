$(document).ready(function () {



    // DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL
    $(document).on('click', '#btnAyudaUsuarios', function (event) {
    event.preventDefault();
    $('#modalAyudaUsuarios').modal('show');
    });


    /////////////////////////////////////
    //     FORMATEO DE CAMPOS          //
    ////////////////////////////////////

    // VALIDADOR DEL FORMULARIO DE USUARIO
    var formValidator = new FormValidator('formUsuario', {
        nombre_usuario: {
            required: true,
             message: "El nombre de usuario debe tener al menos 6 caracteres y solo puede contener letras y números (sin espacios)."
        },
        nombre: {
            required: true,
            message: "El nombre solo puede contener letras y números."
        },
        apellidos: {
            required: true,
            message: "Los apellidos solo pueden contener letras."
        },
        email: {
            required: true,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/, // Formato de email válido
            message: "Ingrese un correo válido (ejemplo@dominio.com)."
        },
        telefono: {
            required: true,
            pattern: /^\d{9}$/, // Exactamente 9 dígitos
            message: "El teléfono debe contener 9 números."
        }
    });

    // VALIDADOR DEL FORMULARIO DE CONTRASEÑA
    var formValidatorContrasena = new FormValidator('formCambioContrasena', {
        id_usuarioC: {
            required: true,
        }
    });

 // MÉTODO PARA VALIDAR TANTO LA CONTRASEÑA COMO LA CONFIRMACIÓN DE LA CONTRASEÑA
    function validarContrasena(contrasena, confirmarContrasena, inputContrasenaSelector, inputConfirmarSelector) {
        // Limpiar estados anteriores
        $(inputContrasenaSelector + ', ' + inputConfirmarSelector).removeClass('is-invalid is-valid');
        $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
        $(inputConfirmarSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
    
        let esValido = true;
    
        // ===== VALIDAR CONTRASEÑA PRINCIPAL =====
        if (!contrasena) {
            $(inputContrasenaSelector).addClass('is-invalid');
            $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("La contraseña es obligatoria").show();
            esValido = false;
        } else if (contrasena.length < 10) {
            $(inputContrasenaSelector).addClass('is-invalid');
            $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Debe tener al menos 10 caracteres").show();
            esValido = false;
        } else if (!/[A-Z]/.test(contrasena)) {
            $(inputContrasenaSelector).addClass('is-invalid');
            $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Debe contener al menos una mayúscula").show();
            esValido = false;
        } else if (!/\d/.test(contrasena)) {
            $(inputContrasenaSelector).addClass('is-invalid');
            $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Debe contener al menos un número").show();
            esValido = false;
        } else if (!/[@$!%*?&]/.test(contrasena)) {
            $(inputContrasenaSelector).addClass('is-invalid');
            $(inputContrasenaSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Debe contener al menos un carácter especial (@$!%*?&)").show();
            esValido = false;
        } else {
            $(inputContrasenaSelector).addClass('is-valid');
        }
    
        // ===== VALIDAR CONFIRMACIÓN =====
        if (!confirmarContrasena) {
            $(inputConfirmarSelector).addClass('is-invalid');
            $(inputConfirmarSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Por favor confirme la contraseña").show();
            esValido = false;
        } else if (contrasena !== confirmarContrasena) {
            $(inputConfirmarSelector).addClass('is-invalid');
            $(inputConfirmarSelector).closest('.col-12, .col-lg-9').find('.invalid-feedback')
                .text("Las contraseñas no coinciden").show();
            esValido = false;
        } else {
            $(inputConfirmarSelector).addClass('is-valid');
        }
    
        return esValido;
    }

// CONTRASEÑA DE FORMULARIO DE EMPLEADOS Y DE CONTRASEÑAS BOTÓN PRA VER Y VALIDACIONES
    
// VALIDACIÓN EN TIEMPO REAL DE LA CONTRASEÑA
$(document).on('input', '#contraseña, #confirmar_contraseña', function() {
    const contrasenaActual = $('#contraseña').val();
    const confirmacionActual = $('#confirmar_contraseña').val();

    if (contrasenaActual || confirmacionActual) {
        validarContrasena(contrasenaActual, confirmacionActual, '#contraseña', '#confirmar_contraseña');
    } else {
         // LIMPIAR LAS CLASES DE LA VALIDACIÓN
        $('#contraseña, #confirmar_contraseña').removeClass('is-invalid is-valid');
        // OCULTAR MENSAJES DE ERROR DE CONTRASEÑA
        $('#contraseña').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
        $('#confirmar_contraseña').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
    }
});

 // MOSTRAR U OCULTAR LA CONTRASEÑA CUANDO SE HAGA CLICK EN EL OJO
 $('#verContraseña').on('click', function () {
    var tipo = $('#contraseña').attr('type') === 'password' ? 'text' : 'password';
    $('#contraseña').attr('type', tipo); // Cambiar el tipo del campo
    $(this).find('i').toggleClass('fa-eye fa-eye-slash'); // Cambiar el ícono
});

 // MOSTRAR U OCULTAR EL CONFIRMAR CONTRASEÑA CUANDO SE HAGA CLICK EN EL OJO
$('#verConfirmarContraseña').on('click', function () {
    var tipo = $('#confirmar_contraseña').attr('type') === 'password' ? 'text' : 'password';
    $('#confirmar_contraseña').attr('type', tipo); // Cambiar el tipo del campo
    $(this).find('i').toggleClass('fa-eye fa-eye-slash'); // Cambiar el ícono
});

// MÉTODO PARA MOSTRAR/OCULTAR LA CONTRASEÑA (NUEVA CONTRASEÑA)
$('#verNuevaContrasena').on('click', function() {
    var passwordField = $('#nueva_contrasena');
    var passwordFieldType = passwordField.attr('type');
    if (passwordFieldType == 'password') {
        passwordField.attr('type', 'text');
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
        passwordField.attr('type', 'password');
        $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
});

// MÉTODO PARA MOSTRAR/OCULTAR LA CONTRASEÑA (CONFIRMAR NUEVA CONTRASEÑA)
$('#verConfirmarContrasena').on('click', function() {
    var passwordField = $('#confirmar_nueva_contrasena');
    var passwordFieldType = passwordField.attr('type');
    if (passwordFieldType == 'password') {
        passwordField.attr('type', 'text');
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
        passwordField.attr('type', 'password');
        $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
});


 // DESPLEGABLE de Filtros de Usuario
 $('#collapseUsuarios').on('show.bs.collapse', function() {
    $('#accordion-toggle-usuarios')
        .removeClass('bg-primary')
        .addClass('bg-info')
        .css('color', 'white');
});

$('#collapseUsuarios').on('hide.bs.collapse', function() {
    $('#accordion-toggle-usuarios')
        .removeClass('bg-info')
        .addClass('bg-primary')
        .css('color', '#e6f0fa');
});

$('#accordion-toggle-usuarios').hover(
    function() { $(this).css('opacity', '0.9'); },
    function() { $(this).css('opacity', '1'); }
);

// DESPLEGABLE de Acciones de Usuario
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

    /////////////////////////////////////
    // INICIO DE LA TABLA DE USUARIOS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR A LOS USUARIOS COMO ADMIN
    var datatable_usuarioConfig = {
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
            { name: 'nombre_usuario', data: 'nombre_usuario', className: 'text-center' }, // Columna 1: Nombre de usuario
            { name: 'estado', data: 'estado', className: 'text-center' }, // Columna 2: Estado
            { name: 'activar', data: null, defaultContent: '', className: 'text-center' }, // Columna 3: Activar/Desactivar
            { name: 'editar', data: null, defaultContent: '', className: 'text-center' } // Columna 4: Botón para Editar
        ],
        
        columnDefs: [
             // Columna 0: BOTÓN MÁS 
            { 
                targets: "control:name", width: '10%', searchable: false, orderable: false, className: "text-center" 
            },
            {
                // Columna 1: Nombre usuario
                targets: "nombre_usuario:name",
                width: '40%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
                // Columna 2: Estado
            {
                targets: "estado:name", width: '20%', orderable: true, searchable: true, className: "text-center",
                render: function (data, type, row) {
                    if (type === "display") {
                        return data == 1 ? '<i class="bi bi-check-circle text-success fa-2x"></i>' : '<i class="bi bi-x-circle text-danger fa-2x"></i>';
                    }
                    return data;
                }
            },
                // Columna 3: Botón para Activar/Desactivar
            {
                targets: "activar:name",
                width: '15%',
                searchable: false,
                orderable: false,
                class: "text-center",
                render: function (data, type, row) {
                    if (row.estado == 1) {
                        // botón para desactivar con data para enviar correo al desactivar
                        return `<button type="button" class="btn btn-danger btn-sm desactivarUsuario"
                            data-bs-toggle="tooltip-primary" data-placement="top" title="Desactivar"
                            data-id="${row.id}"
                            data-nombre="${row.nombre}"
                            data-nombre_usuario="${row.nombre_usuario}"
                            data-email="${row.email}">
                            <i class="fa-solid fa-user-slash"></i>
                        </button>`;
                    } else {
                        // botón para activar con data para enviar correo o mostrar usuario
                        return `<button class="btn btn-success btn-sm activarUsuario"
                            data-bs-toggle="tooltip-primary" data-placement="top" title="Activar"
                            data-id="${row.id}"
                            data-nombre="${row.nombre}"
                            data-nombre_usuario="${row.nombre_usuario}"
                            data-email="${row.email}">
                            <i class="fa-solid fa-user-check"></i>
                        </button>`;
                    }
                }
            },
            {   // Columna 3: Botón para Editar
                    targets: "editar:name", width: '15%', searchable: false, orderable: false, class: "text-center",
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-info btn-sm editarUsuario" data-toggle="tooltip-primary" data-placement="top" title="Editar"  
                                 data-id="${row.id}"> 
                                 <i class="fa-solid fa-edit"></i>
                                 </button>`
                    } // de la function
            } // De la columna 9
        ],
        // AJAX PARA OBTENER A TODOS LOS USUARIOS QUE SEAN CLIENTES
        ajax: {
            url: base_url + "Admin/obtenerUsuariosClientes",
            type: 'GET',
            dataSrc: 'data'
        }
    };
    
    ////////////////////////////
    // FIN DE LA TABLA DE    //
    ///////////////////////////


    /************************************/
    //     ZONA DE DEFINICIONES        //
    /**********************************/
    // Definición inicial de la tabla de empleados
    var $table = $('#usuarios_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_usuarioConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#usuarios_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#usuarios_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    /* en el $columnFilterInputs solo cambiar el nombre de la tabla que encontraremos en HTML*/

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

    /************************************/
    //   FIN ZONA DE DEFINICIONES      //
    /**********************************/
    // MÉTODO PARA MOSTRAR EL CONTENIDO DEL MOSTRAR MÁS
    function format(d) {
        return `
        <div class="card border-primary mb-3">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge fs-3 me-2"></i>
                    <h5 class="card-title mb-0">Detalles del Usuario</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless table-striped table-hover mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" class="ps-4 w-25"><i class="bi bi-person-vcard me-2"></i>ID Usuario</th>
                            <td class="pe-4">${d.id ? `<span class="fw-normal">${d.id}</span>` : '<span class="text-muted fst-italic">No tiene un id de usuario</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-person me-2"></i>Nombre</th>
                            <td class="pe-4">${d.nombre ? `<span class="fw-normal">${d.nombre}</span>` : '<span class="text-muted fst-italic">No tiene un nombre</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-people me-2"></i>Apellidos</th>
                            <td class="pe-4">${d.apellidos ? `<span class="fw-normal">${d.apellidos}</span>` : '<span class="text-muted fst-italic">No tiene apellidos</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-telephone me-2"></i>Teléfono</th>
                            <td class="pe-4">${d.telefono ? `<p class="text-decoration-none link-primary">${d.telefono}</p>` : '<span class="text-muted fst-italic">No tiene un teléfono</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-people me-2"></i>Email</th>
                            <td class="pe-4">${d.email ? `<span class="fw-normal">${d.email}</span>` : '<span class="text-muted fst-italic">No tiene email</span>'}</td>
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

        // MÉTODO PARA CONFIGURAR EL SELECT DEL USUARIO EN EL MODAL DE CAMBIO DE CONTRASEÑA
        function configurarSelect2() {
            $('#id_usuarioC').select2({
                width: '100%',
                dropdownParent: $('#modalCambioContrasena .modal-content'),
                dropdownPosition: 'below',
                dropdownAutoWidth: true,
                placeholder: 'Seleccione un usuario',
                allowClear: true
            });
        }

    ////////////////////////////////////////////////////////
    //   INICIO APARTADO CAMBIAR CONTRASEÑA USUARIO     //
    //////////////////////////////////////////////////////

    ///////////////////////////////////////////////////
    // Funcion para cargar el select de usuarios    //
    ///////////////////////////// ////////////////////
    function cargarUsuarios(selectId, idUsuarioSeleccionado = null) {
        // AJAX PARA OBTENER A TODOS LOS USUARIOS CLIENTES
        $.get(base_url + "Admin/obtenerUsuariosClientes", function(response) {
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

// VALIDACIÓN EN TIEMPO REAL DE LA CONTRASEÑA 
$(document).on('input', '#nueva_contrasena, #confirmar_nueva_contrasena', function() {
    const nuevaContrasena = $('#nueva_contrasena').val().trim();
    const confirmarContrasena = $('#confirmar_nueva_contrasena').val().trim();

    if (nuevaContrasena || confirmarContrasena) {
        validarContrasena(nuevaContrasena, confirmarContrasena, '#nueva_contrasena', '#confirmar_nueva_contrasena');
    } else {
        $('#nueva_contrasena, #confirmar_nueva_contrasena').removeClass('is-invalid is-valid');
        $('#nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
        $('#confirmar_nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
    }
});


// MÉTODO PARA ABRIR EL MODAL DE CAMBIO DE CONTRASEÑA 
function abrirModalCambioContrasena() {
    // LIMPIAR EL FORMULARIO
    $("#formCambioContrasena")[0].reset();
    formValidatorContrasena.clearValidation();

    // OCULTAR LOS CAMPOS DE CONTRASEÑA INICIALMENTE
    $('#nueva_contrasena, #confirmar_nueva_contrasena').closest('.row').hide();
    $('#nueva_contrasena, #confirmar_nueva_contrasena')
        .removeClass('is-invalid is-valid')
        .val('');

    // LIMPIAR Y CARGAR LOS USUARIOS EN EL SELECT
    $('#id_usuarioC').removeClass('is-invalid').val("");

    configurarSelect2();
    cargarUsuarios("#id_usuarioC");
    
    $('#modalCambioContrasena #mdltituloCambioContrasena').text("Cambiar Contraseña de Usuario");

    // MOSTRAR EL MODAL
    $('#modalCambioContrasena').modal('show');
}

// EVENTO CLICK PARA ABRIR EL CAMBIAR CONTRASEÑA
$(document).on('click', '#btnCambiarContrasena', function() {
    abrirModalCambioContrasena(); 
});

// MÉTODO PARA MOSTRAR/OCULTAR LOS CAMPOS DE CONTRASEÑAS
$(document).on('change', '#id_usuarioC', function() {
    var usuarioSeleccionado = $(this).val();

    // SI HAY UN USUARIO SELECCIONADO, MOSTRAR LOS CAMPOS DE CONTRASEÑA
    if (usuarioSeleccionado) {
        $('#nueva_contrasena').closest('.row').show();  // MOSTRAR LA FILA DE NUEVA CONTRASEÑA
        $('#confirmar_nueva_contrasena').closest('.row').show();  // MOSTRAR LA FILA DE CONFIRMAR CONTRASEÑA
    } else {
        // SI NO HAY UN USUARIO SELECCIONADO, OCULTAR LOS CAMPOS Y LIMPIAR LOS VALORES
        $('#nueva_contrasena').closest('.row').hide();  // OCULTAR LA FILA DE NUEVA CONTRASEÑA
        $('#confirmar_nueva_contrasena').closest('.row').hide();  // OCULTAR LA FILA DE CONFIRMAR CONTRASEÑA
    }
     // LIMPIAR LOS VALORES DE LAS CONTRASEÑAS
     $('#nueva_contrasena').val('');
     $('#confirmar_nueva_contrasena').val('');

     // LIMPIAR LAS CLASES DE VALIDACIÓN
     $('#nueva_contrasena, #confirmar_nueva_contrasena').removeClass('is-invalid is-valid');
     $('#nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
     $('#confirmar_nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
});

// MÉTODO PARA GUARDAR LA NUEVA CONTRASEÑA
$(document).on('click', '#btnGuardarContrasena', function() {
    // SI NO SE SELECCIONA NINGÚN USUARIO, NO SE HACE NADA Y SE MUESTRA UN TOAST CON EL ERROR
    if (!$('#id_usuarioC').val()) {
        toastr.error("Por favor, seleccione un usuario antes de continuar.", "Error de Validación");
        return;
    }

    // LIMPIAR VALIDACIONES PREVIAS
    formValidatorContrasena.clearValidation();

    // OBTENER LAS CONTRASEÑAS INGRESADAS
    var nuevaContrasena = $('#nueva_contrasena').val().trim();
    var confirmarContrasena = $('#confirmar_nueva_contrasena').val().trim();

    // VALIDAR QUE LAS CONTRASEÑAS COINCIDAN Y CUMPLAN REQUISITOS
    var contrasenaValida = validarContrasena(nuevaContrasena, confirmarContrasena, '#nueva_contrasena', '#confirmar_nueva_contrasena');

    // SI LA CONTRASEÑA NO ES VÁLIDA, NO SE HACE NADA Y SE MUESTRA UN TOAST CON EL ERROR
    if (!contrasenaValida) {
        toastr.error('Por favor, complete correctamente los campos de la contraseña.', 'Error de Validación');
        return;
    }

    var usuarioSeleccionado = $('#id_usuarioC').val();

    // VERIFICAR QUE LA NUEVA CONTRASEÑA SEA DIFERENTE DE LA ACTUAL
    // AJAX QUE COMPRUEBA SI LA NUEVA CONTRASEÑA ES DISTINTA A LA ANTERIOR
    $.ajax({
        url: base_url + 'Admin/verificarContrasenaActual',
        type: 'POST',
        data: {
            usuario_id: usuarioSeleccionado,
            nueva_contrasena: nuevaContrasena
        },
        success: function(response) {
            if (response.success) {
                // SI ES IGUAL, SE SALE Y SE NOTIFICA AL ADMIN DE QUE NO PUEDE INTRODUCIR LA MISMA CONTRASEÑA QUE YA USA ESE USUARIO 
                if (response.es_igual) {
                    toastr.error('La nueva contraseña no puede ser igual a la actual', 'Error');
                    $('#nueva_contrasena').addClass('is-invalid');
                    $('#error-nueva-contrasena').text('La nueva contraseña no puede ser igual a la actual').show();
                } else {
                    // AJAX PARA OBTENER LOS DATOS DEL USUARIO AL QUE SE VA A CAMBIAR LA CONTRASEÑA
                    $.ajax({
                        url: base_url + 'Usuario/getUsuarioPorId/' + usuarioSeleccionado,
                        type: 'GET',
                        dataType: 'json',
                        success: function(usuarioData) {
                            if (usuarioData && usuarioData.email && usuarioData.nombre) {
                                // CON LOS DATOS DEL USUARIO AHORA, SE EJECUTA EL MÉTODO PARA CAMBIAR LA CONTRASEÑA, Y SE PASAN LOS DATOS DEL USUARIO 
                                cambiarContrasena(usuarioSeleccionado, nuevaContrasena, usuarioData);
                            } else {
                                toastr.error('No se pudo obtener información del usuario para enviar el correo.', 'Error');
                            }
                        },
                        error: function() {
                            toastr.error('Error al obtener información del usuario.', 'Error');
                        }
                    });
                }
            } else {
                toastr.error(response.message, 'Error');
            }
        },
        error: function(xhr) {
            var errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                ? xhr.responseJSON.message 
                : 'Error al verificar la contraseña actual';
            toastr.error(errorMsg, 'Error');
        }
    });
});

// MÉTODO PARA CAMBIAR LA CONTRASEÑA Y LUEGO ENVIAR CORREO DE CONFIRMACIÓN
function cambiarContrasena(usuarioId, nuevaContrasena, usuarioData) {
    // AJAX PARA CAMBIAR LA CONTRASEÑA
    $.ajax({
        url: base_url + 'Admin/cambiarContrasena',
        type: 'POST',
        data: {
            usuario_id: usuarioId,
            nueva_contrasena: nuevaContrasena
        },
        success: function(response) {
            if (response.success) {
                // EN CASO DE SER EXITOSO EL CAMBIO DE CONTRASEÑA, SE PREPARA EL CORREO PARA NOTIFICAR AL USUARIO
                const emailPayload = {
                    nombre: usuarioData.nombre,
                    nombre_usuario: usuarioData.nombre_usuario || '',
                    email: usuarioData.email,
                    asunto: "Cambio de contraseña exitoso",
                    mensaje: `Hola ${usuarioData.nombre},

                    Tu contraseña ha sido cambiada exitosamente en la plataforma.

                    Si no realizaste este cambio, por favor contacta con el administrador inmediatamente.

                    Este es un mensaje automático, no respondas.`
                };

                // SE ENVÍA EL CORREO PARA NOTIFICAR AL USUARIO DEL CAMBIO DE SU CONTRASEÑA
                $.ajax({
                    url: base_url + "Contactos/enviar",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(emailPayload),
                    dataType: "json",
                    success: function() {
                        // Mostrar mensaje de éxito y cerrar modal
                        Swal.fire(
                            'Contraseña actualizada',
                            `La contraseña ha sido actualizada correctamente para ${usuarioData.nombre_usuario} y se ha enviado un correo a ${usuarioData.email}.`,
                            'success'
                        );
                        $('#modalCambioContrasena').modal('hide');
                        $("#formCambioContrasena")[0].reset();
                        formValidatorContrasena.clearValidation();
                    },
                    error: function() {
                        toastr.error('No se pudo enviar el correo de notificación. La contraseña se ha cambiado.', 'Error');
                    }
                });
            } else {
                toastr.error(response.message, 'Error');
            }
        },
        error: function() {
            toastr.error('Error en el servidor', 'Error');
        }
    });
}

    

    ////////////////////////////////////////////
    //   INICIO ZONA FUNCIONES DE APOYO      //
    //////////////////////////////////////////
 
    /////////////////////////////////////
    //   INICIO ZONA DELETE CARRERA  //
    ///////////////////////////////////
    // MÉTODO PARA DESACTIVAR AL USUARIO
    function desactivarUsuario(id, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Desactivar',
        html: `¿Desea desactivar al usuario <strong>${nombre_usuario}</strong>? <br>El usuario ya no podrá iniciar sesión, ¿estás seguro?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX QUE DESACTIVA AL USUARIO
            $.post(base_url + "Admin/desactivarUsuario/" + id, function(data) {
                if(data.success) {
                    // PREPARO EL EMAIL PARA NOTIFICAR AL USUARIO DE QUE SE HA DESHABILITADO SU USUARIO
                    const emailPayload = {
                        email: email,
                        nombre: nombre,
                        asunto: "Cuenta deshabilitada",
                        mensaje: `Hola ${nombre},

                        Tu cuenta de usuario "${nombre_usuario}" ha sido deshabilitada y ya no podrás acceder a la plataforma.

                        Si crees que esto es un error, por favor contacta con el administrador del sistema.

                        Este es un mensaje automático, no respondas.`
                    };

                    // ENVIAR CORREO DE NOTIFICACIÓN PARA QUE EL USUARIO SEPA QUE HA SIDO DESACTIVADO
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire('Desactivado', 'El usuario ha sido deshabilitado y notificado por correo.', 'success');
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire('Desactivado', 'El usuario ha sido deshabilitado pero no se pudo enviar el correo.', 'warning');
                        }
                    });
                } else {
                    swal.fire('Error', 'No se pudo deshabilitar al usuario.', 'error');
                }
            }).fail(function(xhr, status, error) {
                console.error("Error al deshabilitar al usuario:", error);
                swal.fire('Error', 'No se pudo deshabilitar al usuario.', 'error');
            });
        }
    });
}

// CAPTURAR CLICK Y OBTENER LOS DATA PARA ENVIAR AL DESACTIVAR USUARIO
$(document).on('click', '.desactivarUsuario', function(event) {
    event.preventDefault();
    let btn = $(this);
    let id = btn.data('id');
    let nombre = btn.data('nombre');
    let nombre_usuario = btn.data('nombre_usuario');
    let email = btn.data('email');

    desactivarUsuario(id, nombre, nombre_usuario, email);
});

    ////////////////////////////////////
    //   FIN ZONA DELETE USUARIO    //
    //////////////////////////////////

     ///////////////////////////////////////
    //   INICIO ZONA ACTIVAR USUARIO  //
    /////////////////////////////////////
    // MÉTODO PARA ACTIVAR AL USUARIO
    function activarUsuario(id, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Activar',
        html: `¿Desea activar al usuario <strong>${nombre_usuario}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX PARA ACTIVAR AL USUARIO
            $.post(base_url + "Admin/activarUsuario/" + id, function(data) {
                if(data.success) {
                    // EN CASO DE HABER ACTIVADO AL USUARIO, SE PREPARA EL CORREO PARA NOTIFICARSELO
                    const emailPayload = {
                        email: email,
                        nombre: nombre,
                        asunto: "Cuenta activada",
                        mensaje: `Hola ${nombre},

                        Tu cuenta de usuario "${nombre_usuario}" ha sido activada y ya puedes acceder a la plataforma.

                        Si tienes alguna duda, por favor contacta con el administrador.

                        Este es un mensaje automático, no respondas.`
                    };

                    // ENVIAR CONRREO PARA NOTIFICAR AL USUARIO DE QUE SU CUENTA HA SIDO ACTIVADA
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire('Activado', 'El usuario ha sido activado y notificado por correo.', 'success');
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire('Activado', 'El usuario ha sido activado pero no se pudo enviar el correo.', 'warning');
                        }
                    });
                } else {
                    swal.fire('Error', 'No se pudo activar al usuario.', 'error');
                }
            }).fail(function(xhr, status, error) {
                console.error("Error al activar al usuario:", error);
                swal.fire('Error', 'No se pudo activar al usuario.', 'error');
            });
        }
    });
}

// EVENTO CLICK PARA ACTIVAR AL USUARIO, RECOGE DATA Y SE LO PASA AL MÉTODO DE ACTIVAR USUARIO
$(document).on('click', '.activarUsuario', function(event) {
    event.preventDefault();
    let btn = $(this);
    let id = btn.data('id');
    let nombre = btn.data('nombre');
    let nombre_usuario = btn.data('nombre_usuario');
    let email = btn.data('email');

    activarUsuario(id, nombre, nombre_usuario, email);
});

    ////////////////////////////////////
    //   FIN ZONA ACTIVAR VACACIÓN    //
    //////////////////////////////////

     ///////////////////////////////////////
    //      INICIO ZONA NUEVO           //
    //        BOTON DE NUEVO           // 
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE NUEVO
    $(document).on('click', '#btnnuevo', function (event) {
        event.preventDefault();
        
        // Configurar título del modal
        $('#mdltitulo').text('Nuevo registro de usuarios');
    
        // Mostrar campos de contraseña (solo para nuevo usuario)
        $('.campos-contrasena').show();
    
        // Limpieza completa del formulario
        $("#formUsuario")[0].reset();
        $('#formUsuario').find('input[name="id"]').val(""); // Asegurar ID vacío
    
        // Limpiar todas las validaciones visuales
        formValidator.clearValidation();
        
        // Limpiar específicamente los campos de contraseña y sus mensajes
        $('#contraseña, #confirmar_contraseña')
        .removeClass('is-invalid is-valid')
        .val('')
        .closest('.input-group').next('.invalid-feedback').hide();
    
        // Manejo del modal
        $('#modalUsuario').modal('show').on('shown.bs.modal', function () {
            $('#nombre_usuario').focus(); // Enfocar primer campo
        });
    });

// CAPTURAR EL CLICK EN EL BOTÓN DE SALVAR
$(document).on('click', '#btnsalvar', function (event) {
    event.preventDefault();

    // OBTENER EL FORMULARIO Y SUS VALORES
    var form = $('#formUsuario');
    var idE = form.find('input[name="id"]').val().trim();
    var esNuevo = idE === "";  // DETERMINAR SI ES UN NUEVO USUARIO SI TIENE O NO UN ID

    var nombreUsuarioE = form.find('input[name="nombre_usuario"]').val().trim();
    var nombreE = form.find('input[name="nombre"]').val().trim();
    var apellidosE = form.find('input[name="apellidos"]').val().trim();
    var emailE = form.find('input[name="email"]').val().trim();
    var telefonoE = form.find('input[name="telefono"]').val().trim();
    var contrasenaE = form.find('input[name="contraseña"]').val().trim();
    var confirmarContrasenaE = form.find('input[name="confirmar_contraseña"]').val().trim();

    // VALIDAR LAS CONTRASEÑAS
    var esContrasenaValida = true;
    if (esNuevo || contrasenaE !== "") {
        esContrasenaValida = validarContrasena(contrasenaE, confirmarContrasenaE, '#contraseña', '#confirmar_contraseña');
    }

    // VALIDAR EL FORMULARIO
    var esFormularioValido = formValidator.validateForm(event);

    // SI HAY ALGÚN ERROR EN LA VALIDACIÓN, SE MUESTRA UN TOAST Y NO SE CONTINÚA
    if (!esFormularioValido || !esContrasenaValida) {
        toastr.error("Corrija los errores en el formulario", "Error de Validación");
        return;
    }

    // AJAX PARA VALIDAR QUE LOS CAMPOS USUARIO, TELÉFONO Y EMAIL SON ÚNICOS
    $.ajax({
        url: base_url + "Admin/validarCamposUnicos",
        type: "POST",
        data: { id: idE, nombre_usuario: nombreUsuarioE, email: emailE, telefono: telefonoE },
        success: function (validacion) {
            if (!validacion.success) {
                let errores = [];
                if (validacion.error_nombre_usuario) {
                    $('#nombre_usuario').addClass('is-invalid');
                    $('#error-nombre_usuario').text(validacion.error_nombre_usuario).show();
                    errores.push(validacion.error_nombre_usuario);
                }
                if (validacion.error_email) {
                    $('#email').addClass('is-invalid');
                    $('#error-email').text(validacion.error_email).show();
                    errores.push(validacion.error_email);
                }
                if (validacion.error_telefono) {
                    $('#telefono').addClass('is-invalid');
                    $('#error-telefono').text(validacion.error_telefono).show();
                    errores.push(validacion.error_telefono);
                }

                if (errores.length) {
                    toastr.error(errores.join('<br>'), 'Errores');
                }
                return;
            }

            // SI TODO SALE CORRECTO, SE PREPARA EL OBJETO PARA CREAR/EDITAR AL USUARIO
            var formData = new FormData();
            formData.append("id", idE);
            formData.append("nombre_usuario", nombreUsuarioE);
            formData.append("nombre", nombreE);
            formData.append("apellidos", apellidosE);
            formData.append("email", emailE);
            formData.append("telefono", telefonoE);

            if (esNuevo || contrasenaE !== "") {
                formData.append("contraseña", contrasenaE);
                formData.append("confirmar_contraseña", confirmarContrasenaE);
            }

            // SE MONTA LA ULR PARA O CREAR/EDITAR AL USUARIO
            var urlAccion = esNuevo ? base_url + "Admin/crearUsuario" : base_url + "Admin/editarUsuario/" + idE;
            // AJAX PARA CREAR/EDITAR AL USUARIO
            $.ajax({
                url: urlAccion,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        // SI SE CREA EL USUARIO, AHORA SE PREPARA EL CORREO PARA NOTIFICAR DE SU ALTA/MODIFICACIÓN
                        let asunto = esNuevo ? "Bienvenido a la plataforma" : "Actualización de sus datos";
                        let mensajeTexto = `
                        Hola ${nombreE},

                        ${esNuevo ? "Se ha creado tu usuario en la plataforma con los siguientes datos:" : "Se han actualizado tus datos de usuario con la siguiente información:"}

                        - Nombre de usuario: ${nombreUsuarioE}
                        - Nombre: ${nombreE}
                        - Apellidos: ${apellidosE}
                        - Email: ${emailE}
                        - Teléfono: ${telefonoE}
                        ${esNuevo ? `- Contraseña temporal: ${contrasenaE}\n\n⚠️ Por favor cambia tu contraseña lo antes posible desde tu perfil.` : ""}
                        ${(!esNuevo && contrasenaE !== "") ? "- Se ha actualizado tu contraseña.\n" : ""}

                        Si no realizaste esta acción, contacta con el administrador.

                        Este es un mensaje automático. No respondas a este correo.
                        `;

                        // EN CASO DE CREACIÓN, EL ADMIN LE ASIGNA LA CONTRASEÑA, Y SE LA ENSEÑA AL USUARIO QUE HA CREADO, Y LE PIDE QUE CAMBIE LA CONTRASEÑA AL INICIAR SESIÓN
                        const emailPayload = {
                            email: emailE,
                            nombre: nombreE,
                            asunto: asunto,
                            mensaje: mensajeTexto
                        };
                        // AJAX PARA NOTIFICAR AL USUARIO DE SU NUEVA CUENTA/EDICIÓN, Y PASAR SUS NUEVAS CREDENCIALES
                        $.ajax({
                            url: base_url + "Contactos/enviar",
                            method: "POST",
                            contentType: "application/json",
                            data: JSON.stringify(emailPayload),
                            dataType: "json",
                            success: function() {
                                $('#modalUsuario').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                if (esNuevo) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Usuario creado y correo enviado',
                                        html: 'El usuario ha sido creado correctamente.<br>Se ha enviado un correo con su contraseña temporal.',
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Usuario actualizado correctamente y correo enviado',
                                        html: 'El usuario ha sido actualizado correctamente.',
                                    });
                                }
                            },
                            error: function() {
                                $('#modalUsuario').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                toastr.warning("Usuario guardado pero no se pudo enviar el correo de notificación");
                            }
                        });

                    } else {
                        swal.fire('Error', response.errors.join('<br>'), 'error');
                    }
                },
                error: function () {
                    swal.fire('Error', 'Error al guardar', 'error');
                }
            });
        },
        error: function () {
            swal.fire('Error', 'Error al validar datos', 'error');
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
    $(document).on('click', '.editarUsuario', function(event) {
        event.preventDefault();
    
        $('#mdltitulo').text('Edición de usuarios');
        //  Limpiar formulario
        $('#formUsuario')[0].reset();
        formValidator.clearValidation();

         // Limpiar específicamente los campos de contraseña y sus mensajes
         $('#contraseña, #confirmar_contraseña')
         .removeClass('is-invalid is-valid')
         .val('')
         .closest('.input-group').next('.invalid-feedback').hide();
    
        //  Obtener el ID del usuario
        var idUsuario = $(this).data('id');
    
        // AJAX PARA OBTENER LOS DATOS DEL USUARIO SELECCIONADO PARA EDITAR
        $.ajax({
            url: base_url + "Admin/obtenerUsuarioEdicion/" + idUsuario,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var usuario = response.data;
    
                    //  Rellenar el formulario con los datos obtenidos
                    $("#id").val(usuario.id);
                    $("#nombre_usuario").val(usuario.nombre_usuario);
                    $("#nombre").val(usuario.nombre);
                    $("#apellidos").val(usuario.apellidos);
                    $("#email").val(usuario.email);
                    $("#telefono").val(usuario.telefono);
    
                    // 5. Mostrar el modal de edición
                    $('#modalUsuario').modal('show');
    
                } else {
                    Swal.fire('Error', response.message || 'Error al cargar datos del usuario', 'error');
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


    ////////////////////////////////////////////////////////
    //        ZONA FILTROS RADIOBUTTON CABECERA           //
    ///////////////////////////////////////////////////////
    // Escuchar cambios en los radio buttons
    // Si es necesario filtrar por texto en lugar de valores numéricos, hay que asegurarse que los valores de los radio buttons coincidan con los valores de la columna.
    $('input[name="filtroActivo"]').on('change', function () {
        var value = $(this).val(); // Obtener el valor seleccionado

        if (value === "all") {
            // Si se selecciona "Todos", limpiar el filtro
            table_e.column(2).search("").draw(); // Cambiar numero por el índice de la columna a filtrar
        } else {
            // Filtrar la columna por el valor seleccionado
            table_e.column(2).search(value).draw(); // Cambia numero por el índice de la columna a filtrar

        }
    });

    ////////////////////////////////////////////////////////////
    //        FIN ZONA FILTROS RADIOBUTTON CABECERA          //
    //////////////////////////////////////////////////////////

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