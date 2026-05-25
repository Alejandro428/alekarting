$(document).ready(function () {

    /////////////////////////////////////
    //     FORMATEO DE CAMPOS          //
    ////////////////////////////////////

    //  VALIDADOR PARA EL FORMULARIO DE EMPLEADO QUE VOY A USAR MÁS ADELANTE
    var formValidator = new FormValidator('formEmpleado', {
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

    //  VALIDADOR PARA EL FORMULARIO DE LA CONTRASEÑA QUE VOY A USAR MÁS ADELANTE
    var formValidatorContrasena = new FormValidator('formCambioContrasena', {
        id_usuarioC: {
            required: true,
        }
    });

    // FUNCIÓN PARA VALIDAR TANTO LA CONTRASEÑA COMO LA VALIDACIÓN DE LA CONTRASEÑA
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
    
// VALIDACIÓN EN TIEMPO REAL DE CONTRASEÑA
$(document).on('input', '#contraseña, #confirmar_contraseña', function() {
    const contrasenaActual = $('#contraseña').val();
    const confirmacionActual = $('#confirmar_contraseña').val();

    if (contrasenaActual || confirmacionActual) {
        validarContrasena(contrasenaActual, confirmacionActual, '#contraseña', '#confirmar_contraseña');
    } else {
         // Limpiar las clases de validación
         $('#contraseña, #confirmar_contraseña').removeClass('is-invalid is-valid');
         // Ocultar SOLO los mensajes de error de contraseña
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

// CONTRASEÑA DE FORMULARIO DE CAMBIAR CONTRASEÑA

$('#verConfirmarContraseña').on('click', function () {
    var tipo = $('#confirmar_contraseña').attr('type') === 'password' ? 'text' : 'password';
    $('#confirmar_contraseña').attr('type', tipo); // Cambiar el tipo del campo
    $(this).find('i').toggleClass('fa-eye fa-eye-slash'); // Cambiar el ícono
});

// FUNCIÓN PARA MOSTRAR/OCULTAR LA CONTRASEÑA
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

// FUNCIÓN PARA MOSTRAR/OCULTAR LA CONTRASEÑA (CONFIRMAR)
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

/**
 * MÉTODO PARA VALIDAR QUE AL MENOS UN ROL ESTE SELECCIONADO
 */
function validarRoles(seleccionados) {
    var errorElement = $('#error-roles'); // EL DIV CON EL MENSAJE DE ERROR DE DEBAJO DE LOS ROLES

    // SI NO SE SELECCIONA NINGÚN ROL, SE MUESTRA EL ERROR
    if (seleccionados === 0) {
        errorElement.text("Debe seleccionar al menos un rol").show(); // Muestra el mensaje de error
        return false; // Retorna false indicando que la validación ha fallado
    } else {
        errorElement.hide(); // Si se seleccionó al menos un rol, oculta el mensaje de error
        return true; // Retorna true indicando que la validación pasó correctamente
    }
}

// Detectar cambios en los checkboxes para limpiar errores en tiempo real
$(document).on('change', 'input[name="roles[]"]', function() {
    // Válida los roles cada vez que se cambie el estado de los checkboxes
    validarRoles($('input[name="roles[]"]:checked').length);
});

 // DESPLEGABLE DE Filtros de Carreras
 $('#collapseEmpleados').on('show.bs.collapse', function() {
    $('#accordion-toggle-empleados')
        .removeClass('bg-primary')
        .addClass('bg-info')
        .css('color', 'white');
});

$('#collapseEmpleados').on('hide.bs.collapse', function() {
    $('#accordion-toggle-empleados')
        .removeClass('bg-info')
        .addClass('bg-primary')
        .css('color', '#e6f0fa');
});

$('#accordion-toggle-empleados').hover(
    function() { $(this).css('opacity', '0.9'); },
    function() { $(this).css('opacity', '1'); }
);

// DESPLEGABLE DE acordeón de Acciones de Carrera
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

// Detectar click en el botón de ayuda y abrir el modal
$(document).on('click', '#btnAyudaEmpleados', function (event) {
  event.preventDefault();
  $('#modalAyudaGestionEmpleados').modal('show');
});

    /////////////////////////////////////
    // INICIO DE LA TABLA DE CARRERAS //
    //         DATATABLES             //
    ///////////////////////////////////
    // CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR A LOS EMPLEADOS
    var datatable_empleadoConfig = {
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
        // Definimos las columnas que se mostrarán al admin
        columns: [
            { name: 'control', data: null, defaultContent: '', className: 'details-control sorting_1 text-center' }, // Columna 0: Mostrar más
            { name: 'nombre_usuario', data: 'nombre_usuario', className: "text-center"  }, // Columna 1: Nombre de usuario
            { name: 'estado', data: 'estado', className: "text-center"  }, // Columna 2: Estado
            { name: 'activar', data: null, defaultContent: '', className: "text-center"  }, // Columna 3: Activar/Desactivar empleado
            { name: 'emp_noticia', data: 'emp_noticia', className: "text-center"  }, // Columna 4: Emp Noticia
            { name: 'activarN', data: null, defaultContent: '', className: "text-center"  }, // Columna 5: Activar/Desactivar rol noticia
            { name: 'emp_evento', data: 'emp_evento' }, // Columna 6: Emp Evento
            { name: 'activarE', data: null, defaultContent: '', className: "text-center"  }, // Columna 7: Activar/Desactivar rol evento
            { name: 'emp_carreras', data: 'emp_carreras' }, // Columna 8: Emp Carrera
            { name: 'activarC', data: null, defaultContent: '', className: "text-center"  }, // Columna 9: Activar/Desactivar rol carrera
            { name: 'editarEmp', data: null, defaultContent: '', className: "text-center"  } // Columna 10: Botón para Editar Empleado
        ],
        
        columnDefs: [
               // Columna 0: BOTÓN MÁS 
            { 
                targets: "control:name", width: '10%', searchable: false, orderable: false, className: "text-center" 
            },   
            {
                // Columna 1: Nombre usuario
                targets: "nombre_usuario:name",
                width: '20%',
                searchable: true,
                orderable: true,
                className: "text-center"
            },
                // Columna 2: Estado
            {
                targets: "estado:name", width: '5%', orderable: true, searchable: true, className: "text-center",
                render: function (data, type, row) {
                    if (type === "display") {
                        return data == 1 ? '<i class="bi bi-check-circle text-success fa-2x"></i>' : '<i class="bi bi-x-circle text-danger fa-2x"></i>';
                    }
                    return data;
                }
            },
                 // Columna 3: Desactivar empleado
            {
                targets: "activar:name",
                width: '5%',
                searchable: false,
                orderable: false,
                class: "text-center",
                render: function (data, type, row) {
                    if (row.estado == 1) {
                        // Botón desactivar con ícono de usuario inactivo
                        return `<button type="button" class="btn btn-danger btn-sm desactivarEmpleado" data-bs-toggle="tooltip-primary" data-placement="top" title="Desactivar"
                            data-id="${row.id}" 
                            data-id_empleado="${row.id_empleado}"
                            data-nombre="${row.nombre}"
                            data-nombre_usuario="${row.nombre_usuario}"
                            data-email="${row.email}">
                            <i class="fa-solid fa-user-slash"></i>
                        </button>`;
                    } else {
                        // Botón activar con ícono de usuario activo
                        return `<button class="btn btn-success btn-sm activarEmpleado" data-bs-toggle="tooltip-primary" data-placement="top" title="Activar"
                            data-id="${row.id}" 
                            data-id_empleado="${row.id_empleado}"
                            data-nombre="${row.nombre}"
                            data-nombre_usuario="${row.nombre_usuario}"
                            data-email="${row.email}">
                            <i class="fa-solid fa-user-check"></i>
                        </button>`;
                    }
                }
            },
                // Columna 4: Empleado noticia
            {
                    targets: "emp_noticia:name", width: '5%', orderable: true, searchable: true, className: "text-center",
                    render: function (data, type, row) {
                        if (type === "display") {
                            return data == 1 ? '<i class="bi bi-check-circle text-success fa-2x"></i>' : '<i class="bi bi-x-circle text-danger fa-2x"></i>';
                        }
                        return data;
                    }
            },
                // Columna 5: Desactivar empleado rol noticia
            {
                targets: "activarN:name",
                width: '5%',
                searchable: false,
                orderable: false,
                class: "text-center",
                render: function (data, type, row) {
                    if (row.emp_noticia == 1) {
                        // Quitar rol de noticias
                        return `<button type="button" class="btn btn-warning btn-sm desactivarEmpleadoNoticia" 
                                    data-bs-toggle="tooltip-primary" 
                                    data-placement="top" 
                                    title="Quitar rol de noticias" 
                                    data-id_empleado="${row.id_empleado}" 
                                    data-nombre="${row.nombre}" 
                                    data-nombre_usuario="${row.nombre_usuario}" 
                                    data-email="${row.email}">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </button>`;
                    } else {
                        // Asignar rol de noticias
                        return `<button class="btn btn-success btn-sm activarEmpleadoNoticia" 
                                    data-bs-toggle="tooltip-primary" 
                                    data-placement="top" 
                                    title="Asignar rol de noticias" 
                                    data-id_empleado="${row.id_empleado}" 
                                    data-nombre="${row.nombre}" 
                                    data-nombre_usuario="${row.nombre_usuario}" 
                                    data-email="${row.email}">
                                    <i class="fa-solid fa-user-tag"></i>
                                </button>`;
                    }
                }
            },
                // Columna 6: Empleado evento
            {
                    targets: "emp_evento:name", width: '5%', orderable: true, searchable: true, className: "text-center",
                    render: function (data, type, row) {
                        if (type === "display") {
                            return data == 1 ? '<i class="bi bi-check-circle text-success fa-2x"></i>' : '<i class="bi bi-x-circle text-danger fa-2x"></i>';
                        }
                        return data;
                    }
            },
                // Columna 7: Desactivar empleado rol evento
            {
                targets: "activarE:name", 
                width: '5%', 
                searchable: false, 
                orderable: false, 
                class: "text-center",
                render: function (data, type, row) {
                    if (row.emp_evento == 1) {
                        // Quitar rol de eventos
                        return `
                            <button type="button" 
                                class="btn btn-warning btn-sm desactivarEmpleadoEvento" 
                                data-bs-toggle="tooltip" 
                                title="Quitar rol de eventos"
                                data-id_empleado="${row.id_empleado}"
                                data-nombre="${row.nombre}"
                                data-nombre_usuario="${row.nombre_usuario}"
                                data-email="${row.email}">
                                <i class="fa-solid fa-user-xmark"></i>
                            </button>`;
                    } else {
                        // Asignar rol de eventos
                        return `
                            <button type="button"
                                class="btn btn-success btn-sm activarEmpleadoEvento" 
                                data-bs-toggle="tooltip" 
                                title="Asignar rol de eventos"
                                data-id_empleado="${row.id_empleado}"
                                data-nombre="${row.nombre}"
                                data-nombre_usuario="${row.nombre_usuario}"
                                data-email="${row.email}">
                                <i class="fa-solid fa-user-tag"></i>
                            </button>`;
                    }
                }
            },
                // Columna 8: Empleado carrera
            {
                    targets: "emp_carreras:name", width: '5%', orderable: true, searchable: true, className: "text-center",
                    render: function (data, type, row) {
                        if (type === "display") {
                            return data == 1 ? '<i class="bi bi-check-circle text-success fa-2x"></i>' : '<i class="bi bi-x-circle text-danger fa-2x"></i>';
                        }
                        return data;
                    }
            },
                // Columna 9: Desactivar empleado rol carrera
            {
                targets: "activarC:name",
                width: '5%',
                searchable: false,
                orderable: false,
                class: "text-center",
                render: function (data, type, row) {
                    if (row.emp_carreras == 1) {
                        // Quitar rol de carreras
                        return `
                            <button type="button" 
                                    class="btn btn-warning btn-sm desactivarEmpleadoCarrera" 
                                    data-bs-toggle="tooltip-primary" 
                                    data-placement="top" 
                                    title="Quitar rol de carreras" 
                                    data-id_empleado="${row.id_empleado}" 
                                    data-nombre="${row.nombre}"
                                    data-nombre_usuario="${row.nombre_usuario}"
                                    data-email="${row.email}">
                                <i class="fa-solid fa-user-xmark"></i>
                            </button>`;
                    } else {
                        // Asignar rol de carreras
                        return `
                            <button type="button" 
                                    class="btn btn-success btn-sm activarEmpleadoCarrera" 
                                    data-bs-toggle="tooltip-primary" 
                                    data-placement="top" 
                                    title="Asignar rol de carreras" 
                                    data-id_empleado="${row.id_empleado}" 
                                    data-nombre="${row.nombre}"
                                    data-nombre_usuario="${row.nombre_usuario}"
                                    data-email="${row.email}">
                                <i class="fa-solid fa-user-tag"></i>
                            </button>`;
                    }
                }
            },
            {   // BOTON PARA EDITAR EMPLEADO
                    targets: "editarEmp:name", width: '5%', searchable: false, orderable: false, class: "text-center",
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-info btn-sm editarEmpleado" data-toggle="tooltip-primary" data-placement="top" title="Editar"  
                                 data-id="${row.id}" data-id_empleado="${row.id_empleado}"> 
                                 <i class="fa-solid fa-edit"></i>
                                 </button>`
                    } // de la function
            } // De la columna 9
        ],
        // AJAX PARA RECOGER A TODOS LOS EMPLEADOS DE LA WEB DE ALEKARTING
        ajax: {
            url: base_url + "Admin/obtenerEmpleados",
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
    var $table = $('#empleados_data');  /*<--- Es el nombre que le hemos dado a la tabla en HTML */
    var $tableConfig = datatable_empleadoConfig; /*<--- Es el nombre que le hemos dado a la declaración de la definicion de la tabla */
    //var $columSearch = 3; /* <-- Es la columna en la cual al hacer click el valor se colocará en la zona de search y se buscará */
    var $tableBody = $('#empleados_data tbody'); /*<--- Es el nombre que le hemos dado al cuerpo de la tabla en HTML */
    /* en el tableBody solo cambiar el nombre de la tabla que encontraremos en HTML*/
    var $columnFilterInputs = $('#empleados_data tfoot input'); /*<--- Es el nombre que le hemos dado a los inputs de los pies de la tabla en HTML */
    /* en el $columnFilterInputs solo cambiar el nombre de la tabla que encontraremos en HTML*/

    //ejemplo -- var table_e = $('#employees-table').DataTable(datatable_employeeConfig);
    var table_e = $table.DataTable($tableConfig);

    /************************************/
    //   FIN ZONA DE DEFINICIONES      //
    /**********************************/
  
    ////////////////////////////////////////////
    //   INICIO ZONA FUNCIONES DE APOYO      //
    //////////////////////////////////////////

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
                            <td class="pe-4">${d.id ? `<span class="fw-normal">${d.id}</span>` : '<span class="text-muted fst-italic">No registrado</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-briefcase me-2"></i>ID Empleado</th>
                            <td class="pe-4">${d.id_empleado ? `<span class="fw-normal">${d.id_empleado}</span>` : '<span class="text-muted fst-italic">No aplica</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-person me-2"></i>Nombre</th>
                            <td class="pe-4">${d.nombre ? `<span class="fw-normal">${d.nombre}</span>` : '<span class="text-muted fst-italic">No proporcionado</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-people me-2"></i>Apellidos</th>
                            <td class="pe-4">${d.apellidos ? `<span class="fw-normal">${d.apellidos}</span>` : '<span class="text-muted fst-italic">No proporcionados</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-telephone me-2"></i>Teléfono</th>
                            <td class="pe-4">${d.telefono ? `<p class="text-decoration-none link-primary">${d.telefono}</p>` : '<span class="text-muted fst-italic">No disponible</span>'}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="ps-4"><i class="bi bi-people me-2"></i>Email</th>
                            <td class="pe-4">${d.email ? `<span class="fw-normal">${d.email}</span>` : '<span class="text-muted fst-italic">Email no proporcionado</span>'}</td>
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
    
    // MÉTODO PARA CONFIGURAR EL SELECT DEL USUARIO DE CAMBIO DE CONTRASEÑA
    function configurarSelect2(esAdmin = false) {
        $('#id_usuarioC').select2({
            width: '100%',
            dropdownParent: $('#modalCambioContrasena .modal-content'),
            dropdownPosition: 'below',
            dropdownAutoWidth: true,
            placeholder: esAdmin ? 'Seleccione un administrador' : 'Seleccione un empleado',
            allowClear: true
        });
    }
    ////////////////////////////////////////////
    //   INICIO APARTADO CAMBIAR CONTRASEÑA EMPLEADO     //
    //////////////////////////////////////////

        //////////////////////////////////////
    // Funcion para cargar el select de empleados    //
    ///////////////////////////// ///////
    function cargarEmpleados(selectId, idEmpleadoSeleccionado = null) {
        // AJAX PARA OBTENER A TODOS LOS EMPLEADOS Y PONERLOS EN EL SELECT
        $.get(base_url + "Admin/obtenerEmpleados", function(response) {
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
    // Funcion para cargar el select de admins    //
    ///////////////////////////// ///////
    function cargarAdmins(selectId, idAdminSeleccionado = null) {
        // AJAX PARA OBTENER A TODOS LOS ADMINS Y PONERLOS EN EL SELECT
        $.get(base_url + "Admin/obtenerAdmins", function(response) {
            console.log("Datos de admins recibidos:", response);
            
            let $select = $(selectId);
            $select.empty(); // Limpiar opciones actuales
    
            // Agregar opción por defecto
            $select.append($('<option>', {
                value: '',
                text: 'Seleccione un admin...'
            }));
    
            // Manejar tanto response.data como respuesta directa
            const data = response.success ? response.data : response;
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(admin) {
                    // Usar EXCLUSIVAMENTE nombre_usuario
                    if (admin.nombre_usuario) {
                        let option = $('<option>', {
                            value: admin.id,
                            text: admin.nombre_usuario
                        });
    
                        if (idAdminSeleccionado && admin.id == idAdminSeleccionado) {
                            option.prop('selected', true);
                        }
    
                        $select.append(option);
                    }
                });
            } else {
                $select.append($('<option>', {
                    value: '',
                    text: 'No hay admins disponibles'
                }));
            }
            
        }, "json").fail(function(xhr, status, error) {
            console.error("Error al cargar admins:", status, error);
            $(selectId).html("<option value=''>Error al cargar</option>");
        });
    }

// VALIDACIÓN EN TIEMPO REAL PARA LAS CONTRASEÑAS
$(document).on('input', '#nueva_contrasena, #confirmar_nueva_contrasena', function() {
    const nuevaContrasena = $('#nueva_contrasena').val().trim();
    const confirmarContrasena = $('#confirmar_nueva_contrasena').val().trim();

    if (nuevaContrasena || confirmarContrasena) {
        validarContrasena(nuevaContrasena, confirmarContrasena, '#nueva_contrasena', '#confirmar_nueva_contrasena');
    } else {
         // Limpiar las clases de validación
         $('#nueva_contrasena, #confirmar_nueva_contrasena').removeClass('is-invalid is-valid');
         // Ocultar SOLO los mensajes de error de contraseña
         $('#nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
         $('#confirmar_nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
    }
});

// MÉTODO PARA ABRIR EL MODAL DE LA CONTRASEÑA, SI ES ADMIN, SE CARGAN COSAS DISTINTAS EN EL CAMBIO DE CONTRASEÑA
function abrirModalCambioContrasena(esAdmin = false) {
    // Limpiar el formulario
    $("#formCambioContrasena")[0].reset();
    formValidatorContrasena.clearValidation();

    // Ocultar campos de contraseña inicialmente
    $('#nueva_contrasena, #confirmar_nueva_contrasena').closest('.row').hide();
    $('#nueva_contrasena, #confirmar_nueva_contrasena')
        .removeClass('is-invalid is-valid')
        .val('');

    // Limpiar y cargar usuarios según el tipo
    $('#id_usuarioC').removeClass('is-invalid').val("");

    configurarSelect2(esAdmin);
    
    if (esAdmin) {
        cargarAdmins("#id_usuarioC");
        $('#modalCambioContrasena #mdltituloCambioContrasena').text("Cambiar Contraseña de Administrador");
    } else {
        cargarEmpleados("#id_usuarioC");
        $('#modalCambioContrasena #mdltituloCambioContrasena').text("Cambiar Contraseña de Empleado");
    }

    // Mostrar el modal
    $('#modalCambioContrasena').modal('show');
}

// ABRIR EL MODAL DE CAMBIO DE CONTRSEÑA COMO EMPLEADO
$(document).on('click', '#btnCambiarContrasena', function() {
    abrirModalCambioContrasena(false); // Para empleados
});

// ABRIR EL MODAL DE CAMBIO DE CONTRSEÑA COMO ADMIN
$(document).on('click', '#btnCambiarContrasenaAdmin', function() {
    abrirModalCambioContrasena(true); // Para admins
});

// MÉTODO PARA MOSTRAR/OCULTAR LOS CAMPOS DE CONTRASEÑAS
$(document).on('change', '#id_usuarioC', function() {
    // Obtener el valor del select (usuario seleccionado)
    var usuarioSeleccionado = $(this).val();

    // Si hay un usuario seleccionado, mostrar los campos de contraseña
    if (usuarioSeleccionado) {
        $('#nueva_contrasena').closest('.row').show();  // Mostrar la fila de nueva contraseña
        $('#confirmar_nueva_contrasena').closest('.row').show();  // Mostrar la fila de confirmar contraseña
    } else {
        // Si no hay un usuario seleccionado, ocultar los campos y limpiar los valores
        $('#nueva_contrasena').closest('.row').hide();  // Ocultar la fila de nueva contraseña
        $('#confirmar_nueva_contrasena').closest('.row').hide();  // Ocultar la fila de confirmar contraseña
    }
     // Limpiar los valores de las contraseñas
     $('#nueva_contrasena').val('');
     $('#confirmar_nueva_contrasena').val('');

    // Limpiar las clases de validación
    $('#nueva_contrasena, #confirmar_nueva_contrasena').removeClass('is-invalid is-valid');
    // Ocultar SOLO los mensajes de error de contraseña
    $('#nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
    $('#confirmar_nueva_contrasena').closest('.col-12, .col-lg-9').find('.invalid-feedback').hide();
});

// MÉTODO PARA GUARDAR LA CONTRASEÑA
$(document).on('click', '#btnGuardarContrasena', function() {
    // Validar que se haya seleccionado un usuario
    if (!$('#id_usuarioC').val()) {
        toastr.error("Por favor, seleccione un usuario antes de continuar.", "Error de Validación");
        return;
    }

    // Limpiar validaciones previas
    formValidatorContrasena.clearValidation();

    // Obtener las contraseñas ingresadas
    var nuevaContrasena = $('#nueva_contrasena').val().trim();
    var confirmarContrasena = $('#confirmar_nueva_contrasena').val().trim();

    // Validar que las contraseñas coincidan y cumplan requisitos
    var contrasenaValida = validarContrasena(nuevaContrasena, confirmarContrasena, '#nueva_contrasena', '#confirmar_nueva_contrasena');

    if (!contrasenaValida) {
        toastr.error('Por favor, complete correctamente los campos de la contraseña.', 'Error de Validación');
        return;
    }

    var usuarioSeleccionado = $('#id_usuarioC').val();

    // AJAX PARA COMPROBAR QUE LA CONTRASEÑA QUE QUIERE PONER EL ADMIN SEA DISTINTA A LA QUE TENÍA PUESTA
    // EL USUARIO AL QUE QUIERE CAMBIAR LA CONTRASEÑA
    $.ajax({
        url: base_url + 'Admin/verificarContrasenaActual',
        type: 'POST',
        data: {
            usuario_id: usuarioSeleccionado,
            nueva_contrasena: nuevaContrasena
        },
        success: function(response) {
            if (response.success) {
                if (response.es_igual) {
                    toastr.error('La nueva contraseña no puede ser igual a la actual', 'Error');
                    $('#nueva_contrasena').addClass('is-invalid');
                    $('#error-nueva-contrasena').text('La nueva contraseña no puede ser igual a la actual').show();
                } else {
                    // Obtener datos del usuario para enviar correo de notificación
                    $.ajax({
                        url: base_url + 'Usuario/getUsuarioPorId/' + usuarioSeleccionado,
                        type: 'GET',
                        dataType: 'json',
                        success: function(usuarioData) {
                            if (usuarioData && usuarioData.email && usuarioData.nombre) {
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

// MÉTODO QUE LLAMA A UN AJAX PARA HACER EL CAMBIO DE LA CONTRASEÑA
function cambiarContrasena(usuarioId, nuevaContrasena, usuarioData) {
    $.ajax({
        url: base_url + 'Admin/cambiarContrasena',
        type: 'POST',
        data: {
            usuario_id: usuarioId,
            nueva_contrasena: nuevaContrasena
        },
        success: function(response) {
            if (response.success) {
                // SI LA CONTRASEÑA SE CAMBIA, SE PREPARA EL CORREO 
                // PARA CONFIRMAR EL CAMBIO
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

                // SE ENVÍA EL CORREO PARA NOTIFICAR DEL CAMBIO DE CONTRASEÑA
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
    //   INICIO APARTADO CAMBIAR CONTRASEÑA ADMIN     //
    //////////////////////////////////////////


    /////////////////////////////////////
    //   INICIO ZONA DESACTIVAR EMPLEADO  //
    ///////////////////////////////////
    // MÉTODO PARA DESACTIVAR AL EMPLEADO
function desactivarEmpleado(id_empleado, id_usuario, nombre, nombre_usuario, email) {
    // AJAX PARA COMPROBAR SI EL EMPLEADO QUE QUIERO DESHABILITAR TIENE ROLES ACTIVOS 
    // ACTUALMENTE
    $.ajax({
        url: base_url + "Admin/verificarRolesEmpleado/" + id_empleado,
        type: 'GET',
        dataType: 'json',
        success: function(respuesta) {
            // SI TIENE ROLES, ME GUARDO LOS QUE TIENE, Y SE LOS MUESTRO
            // PARA PODER DESHABILITAR A UN EMPLEADO, HAY QUE QUITARLE SUS ROLES
            if (respuesta.tiene_roles && respuesta.roles.length > 0) {
                let listaRoles = respuesta.roles.map(rol => `• ${rol}`).join('<br>');

                Swal.fire({
                    title: 'No se puede desactivar',
                    html: `El empleado/a <strong>${nombre_usuario}</strong> tiene los siguientes roles asignados:<br><br>
                          ${listaRoles}<br><br>
                          <strong>Debe quitarle todos los roles primero.</strong>`,
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // SI NO TIENE ROLES, PREGUNTA SI SE QUIERE DESHABILITAR A ESE EMPLEADO
            Swal.fire({
                title: `¿Desactivar a ${nombre_usuario}?`,
                text: 'El empleado/a no tiene roles asignados. ¿Desea desactivarlo?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // EN CASO AFIRMATIVO, SE HACE UN AJAX QUE DESHABILITA AL EMPLEADO
                    $.post(base_url + "Admin/desactivarEmpleado/" + id_usuario)
                        .done(function() {
                            const emailPayload = {
                                email: email,
                                nombre: nombre,
                                asunto: "Cuenta deshabilitada",
                                mensaje: `Hola ${nombre},

                                Tu cuenta de empleado "${nombre_usuario}" ha sido deshabilitada y ya no podrás acceder a la plataforma.

                                Si crees que esto es un error, por favor contacta con el administrador del sistema.

                                Este es un mensaje automático, no respondas.`
                            };
                            // SE MONTA UN CORREO PARA NOTIFICAR AL EMPLEADO
                            // DE QUE SU CUENTA HA SIDO INHABILITADA
                            $.ajax({
                                url: base_url + "Contactos/enviar",
                                method: "POST",
                                contentType: "application/json",
                                data: JSON.stringify(emailPayload),
                                dataType: "json",
                                success: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        '¡Desactivado!',
                                        `El empleado/a <strong>${nombre_usuario}</strong> ha sido deshabilitado/a y notificado/a por correo.`,
                                        'success'
                                    );
                                },
                                error: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        'Desactivado',
                                        `El empleado/a <strong>${nombre_usuario}</strong> ha sido deshabilitado/a pero no se pudo enviar el correo.`,
                                        'warning'
                                    );
                                }
                            });
                        })
                        .fail(function() {
                            Swal.fire(
                                'Error',
                                'No se pudo desactivar al empleado',
                                'error'
                            );
                        });
                }
            });
        },
        error: function() {
            Swal.fire(
                'Error',
                'No se pudieron verificar los roles del empleado',
                'error'
            );
        }
    });
}

    
    // CAPTURAR EL CLICK EN EL BOTÓN DE BORRAR
    $(document).on('click', '.desactivarEmpleado', function (event) {
    event.preventDefault();
    let id_usuario = $(this).data('id');
    let id_empleado = $(this).data('id_empleado');
    let nombre = $(this).data('nombre');
    let nombre_usuario = $(this).data('nombre_usuario');
    let email = $(this).data('email');

    desactivarEmpleado(id_empleado, id_usuario, nombre, nombre_usuario, email);
});


    ////////////////////////////////////
    //   FIN ZONA DESACTIVAR EMPLEADO    //
    //////////////////////////////////

     ///////////////////////////////////////
    //   INICIO ZONA ACTIVAR EMPLEADO  //
    /////////////////////////////////////
    // MÉTODO PARA ACTIVAR AL EMPLEADO
function activarEmpleado(id_empleado, id_usuario, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Activar',
        html: `¿Desea activar al empleado/a <strong>${nombre_usuario}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // SI SE ACEPTA ACTIVAR AL EMPLEADO, SE HACE UN AJAX QUE LO ACTIVA
            $.post(base_url + "Admin/activarEmpleado/" + id_usuario, function(data) {
                if (data.success) {
                    const emailPayload = {
                        email: email,
                        nombre: nombre,
                        asunto: "Cuenta activada",
                        mensaje: `Hola ${nombre},

                        Tu cuenta de empleado "${nombre_usuario}" ha sido activada exitosamente.

                        Ya puedes acceder a la plataforma con tus credenciales.

                        Este es un mensaje automático, no respondas.`
                    };
                    // AL HACERSE EL AJAX, SE MANDA UN CORREO TAMBIÉN AL EMPLEADO PARA NOTIFICARLE
                    // DE LA ACTIVACIÓN DE SU CUENTA
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function () {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El empleado/a <strong>${nombre_usuario}</strong> ha sido activado/a y notificado/a por correo.`,
                                'success'
                            );
                        },
                        error: function () {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El empleado/a <strong>${nombre_usuario}</strong> ha sido activado/a pero no se pudo enviar el correo.`,
                                'warning'
                            );
                        }
                    });
                } else {
                    swal.fire('Error', 'No se pudo activar al empleado.', 'error');
                }
            }).fail(function(xhr, status, error) {
                console.error("Error al activar al empleado:", error);
                swal.fire('Error', 'No se pudo activar al empleado.', 'error');
            });
        }
    });
}

    // CAPTURAR EL CLICK EN EL BOTÓN DE ACTIVAR
    $(document).on('click', '.activarEmpleado', function (event) {
    event.preventDefault();
    let id = $(this).data('id');
    let id_empleado = $(this).data('id_empleado');
    let nombre = $(this).data('nombre');
    let nombre_usuario = $(this).data('nombre_usuario');
    let email = $(this).data('email');

    activarEmpleado(id_empleado, id, nombre, nombre_usuario, email);
});


    ////////////////////////////////////
    //   FIN ZONA ACTIVAR EMPLEADO    //
    //////////////////////////////////

     /////////////////////////////////////
    //   INICIO ZONA DESACTIVAR ROL NOTICIA  //
    ///////////////////////////////////
    // MÉTODO PARA DESHABILITAR AL EMPLEADO DE SU ROL DE NOTICIAS
    function desactivarEmpleadoNoticia(id_empleado, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Desactivar',
        html: `¿Desea desactivar el rol de noticias del empleado/a <strong>${nombre_usuario}</strong>?<br>
               Esto impedirá que pueda gestionar publicaciones.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Llamada AJAX para desactivar el rol de noticias
            $.post(base_url + "Admin/desactivarEmpleadoNoticia/" + id_empleado, function(data) {
                if (data.success) {
                    // Preparar y enviar correo
                    const emailPayload = {
                        email: email,
                        nombre: nombre,
                        asunto: "Rol de noticias deshabilitado",
                        mensaje: `Hola ${nombre},

                        El rol de noticias asignado a tu cuenta de usuario "${nombre_usuario}" ha sido deshabilitado.
                        Ya no podrás realizar publicaciones ni gestiones relacionadas con noticias.

                        Si consideras que esto fue un error, por favor contacta con el administrador del sistema.

                        Este es un mensaje automático, no respondas.`
                    };

                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Desactivado',
                                `El rol de noticias ha sido deshabilitado y <strong>${nombre_usuario}</strong> ha sido notificado/a por correo.`,
                                'success'
                            );
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Desactivado',
                                `El rol de noticias ha sido deshabilitado pero no se pudo enviar el correo a <strong>${nombre_usuario}</strong>.`,
                                'warning'
                            );
                        }
                    });
                } else {
                    swal.fire('Error', 'No se pudo deshabilitar el rol de noticias.', 'error');
                }
            }).fail(function(xhr, status, error) {
                console.error("Error al deshabilitar el rol de noticias:", error);
                swal.fire('Error', 'No se pudo deshabilitar el rol de noticias.', 'error');
            });
        }
    });
}

    
    // CAPTURAR EL CLICK EN EL BOTÓN DE BORRAR
    $(document).on('click', '.desactivarEmpleadoNoticia', function (event) {
    event.preventDefault();
    let id_empleado = $(this).data('id_empleado');
    let nombre = $(this).data('nombre');
    let nombre_usuario = $(this).data('nombre_usuario');
    let email = $(this).data('email');

    desactivarEmpleadoNoticia(id_empleado, nombre, nombre_usuario, email);
});

    ////////////////////////////////////
    //   FIN ZONA DESACTIVAR ROL NOTICIA    //
    //////////////////////////////////

     ///////////////////////////////////////
    //   INICIO ZONA ACTIVAR ROL NOTICIA  //
    /////////////////////////////////////
    // MÉTODO PARA ACTIVAR EL ROL DE NOTICIA DEL EMPLEADO ESCOGIDO
function activarEmpleadoNoticia(id_empleado, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Activar',
        html: `¿Desea activar el rol de noticias para el empleado/a <strong>${nombre_usuario}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // SI SE CONFIRMA, SE HACE EL AJAX PARA ACTIVAR EL ROL DEL EMPLEADO
            $.post(base_url + "Admin/activarEmpleadoNoticia/" + id_empleado, function(data) {
                if(data.success) {
                    const emailPayload = {
                        email: email,
                        nombre: nombre,
                        asunto: "Rol de noticias activado",
                        mensaje: `Hola ${nombre},

                        El rol de noticias asignado a tu cuenta de usuario "${nombre_usuario}" ha sido activado.
                        Ahora puedes gestionar publicaciones y noticias en la plataforma.

                        Si crees que esto es un error, por favor contacta con el administrador del sistema.

                        Este es un mensaje automático, no respondas.`
                    };
                    // SE ENVÍA UN CORREO NOTIFICANDO AL USUARIO
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El rol de noticias ha sido activado y <strong>${nombre_usuario}</strong> ha sido notificado/a por correo.`,
                                'success'
                            );
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El rol de noticias ha sido activado pero no se pudo enviar el correo a <strong>${nombre_usuario}</strong>.`,
                                'warning'
                            );
                        }
                    });
                } else {
                    swal.fire('Error', 'No se pudo activar el rol de noticias.', 'error');
                }
            }).fail(function(xhr, status, error) {
                console.error("Error al activar el rol de noticias:", error);
                swal.fire('Error', 'No se pudo activar el rol de noticias.', 'error');
            });
        }
    });
}

// Capturar click y obtener todos los datos necesarios
$(document).on('click', '.activarEmpleadoNoticia', function (event) {
    event.preventDefault();
    let id_empleado = $(this).data('id_empleado');
    let nombre = $(this).data('nombre');
    let nombre_usuario = $(this).data('nombre_usuario');
    let email = $(this).data('email');

    activarEmpleadoNoticia(id_empleado, nombre, nombre_usuario, email);
});

    ////////////////////////////////////
    //   FIN ZONA ACTIVAR ROL NOTICIA    //
    //////////////////////////////////

        /////////////////////////////////////
    //   INICIO ZONA DESACTIVAR ROL EVENTO  //
    ///////////////////////////////////
    // MÉTODO PARA DESHABILITAR EL ROL DE EVENTOS DEL EMPLEADO
function desactivarEmpleadoEvento(id_empleado, nombre, nombre_usuario, email) {
    // SE HACE UN AJAX PARA COMPROBAR SI EL EMPLEADO TIENE EVENTOS PENDIENTES
    // (QUE EN UN FUTURO TIENE QUE DIRIGIRLOS)
    $.ajax({
        url: base_url + "Admin/verificarEventosPendientes/" + id_empleado,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.tieneEventosPendientes) {
                Swal.fire({
                    title: 'No se puede desactivar',
                    html: `El/la empleado/a <strong>${nombre_usuario}</strong> tiene ${response.total} evento(s) pendiente(s) asignados.<br><br>Debe reasignarlos antes de desactivar el rol.`,
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            // SI NO TIENE, SE LE PREGUNTA SI QUIERE DESHABILITAR EL ROL DEL EMPLEADO
            Swal.fire({
                title: `Confirmar desactivación de ${nombre_usuario}`,
                html: `¿Está seguro de desactivar el rol de eventos para el/la empleado/a <strong>${nombre_usuario}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                // EN CASO AFIRMATIVO, SE HACE UN AJAX QUE DESACTIVA EL ROL DEL EMPLEADO
                if (result.isConfirmed) {
                    $.post(base_url + "Admin/desactivarEmpleadoEvento/" + id_empleado)
                        .done(function() {
                            // Preparar payload para el correo
                            const emailPayload = {
                                nombre: nombre,
                                nombre_usuario: nombre_usuario,
                                email: email,
                                asunto: "Rol de eventos deshabilitado",
                                mensaje: `Hola ${nombre},

                                Tu rol de gestión de eventos ha sido deshabilitado. Ya no podrás gestionar eventos en la plataforma.

                                Si crees que esto es un error, por favor contacta con el administrador del sistema.

                                Este es un mensaje automático, no respondas.`
                            };

                            // SE ENVÍA UN CORREO PARA NOTIFICAR AL USUARIO 
                            $.ajax({
                                url: base_url + "Contactos/enviar",
                                method: "POST",
                                contentType: "application/json",
                                data: JSON.stringify(emailPayload),
                                dataType: "json",
                                success: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        'Desactivado',
                                        `El rol de eventos ha sido deshabilitado y el/la empleado/a <strong>${nombre_usuario}</strong> ha sido notificado/a por correo.`,
                                        'success'
                                    );
                                },
                                error: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        'Desactivado',
                                        `El rol de eventos ha sido deshabilitado pero no se pudo enviar el correo al/la empleado/a <strong>${nombre_usuario}</strong>.`,
                                        'warning'
                                    );
                                }
                            });
                        })
                        .fail(function() {
                            Swal.fire(
                                'Error',
                                `No se pudo deshabilitar el rol para el/la empleado/a <strong>${nombre_usuario}</strong>.`,
                                'error'
                            );
                        });
                }
            });
        },
        error: function() {
            Swal.fire(
                'Error',
                `No se pudieron verificar los eventos para el/la empleado/a <strong>${nombre_usuario}</strong>.`,
                'error'
            );
        }
    });
}
    
    // Capturar click y pasar los datos necesarios para el correo
    $(document).on('click', '.desactivarEmpleadoEvento', function(e) {
        e.preventDefault();
        let id_empleado = $(this).data('id_empleado');
        let nombre = $(this).data('nombre');
        let nombre_usuario = $(this).data('nombre_usuario');
        let email = $(this).data('email');
        desactivarEmpleadoEvento(id_empleado, nombre, nombre_usuario, email);
    });
    ////////////////////////////////////
    //   FIN ZONA DESACTIVAR ROL EVENTO    //
    //////////////////////////////////

     ///////////////////////////////////////
    //   INICIO ZONA ACTIVAR ROL EVENTO  //
    /////////////////////////////////////
    // MÉTODO PARA ACTIVAR EL ROL DE EVENTO DEL EMPLEADO ESCOGIDO
 function activarEmpleadoEvento(id_empleado, nombre, nombre_usuario, email) {
    swal.fire({
        title: 'Activar',
        html: `¿Desea activar el rol de eventos para el/la empleado/a <strong>${nombre_usuario}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        // SI SE CONFIRMA, SE ACTIVA SU ROL DE EVENTO
        if (result.isConfirmed) {
            $.post(base_url + "Admin/activarEmpleadoEvento/" + id_empleado)
                .done(function() {
                    // Preparar payload para el correo
                    const emailPayload = {
                        nombre: nombre,
                        nombre_usuario: nombre_usuario,
                        email: email,
                        asunto: "Rol de eventos activado",
                        mensaje: `Hola ${nombre},

                        Tu rol de gestión de eventos ha sido activado. Ya puedes gestionar eventos en la plataforma.

                        Si crees que esto es un error, por favor contacta con el administrador del sistema.

                        Este es un mensaje automático, no respondas.`
                    };

                    // SE ENVÍA UN CORREO PARA NOTIFICAR AL USUARIO
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El rol de eventos ha sido activado y el/la empleado/a <strong>${nombre_usuario}</strong> ha sido notificado/a por correo.`,
                                'success'
                            );
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            swal.fire(
                                'Activado',
                                `El rol de eventos ha sido activado pero no se pudo enviar el correo al/la empleado/a <strong>${nombre_usuario}</strong>.`,
                                'warning'
                            );
                        }
                    });
                })
                .fail(function(xhr, status, error) {
                    console.error("Error al activar el rol de eventos:", error);
                    swal.fire(
                        'Error',
                        `No se pudo activar el rol de eventos para el/la empleado/a <strong>${nombre_usuario}</strong>.`,
                        'error'
                    );
                });
        }
    });
}
    // Capturar el click y pasar los datos necesarios
    $(document).on('click', '.activarEmpleadoEvento', function(event) {
        event.preventDefault();
        let id_empleado = $(this).data('id_empleado');
        let nombre = $(this).data('nombre');
        let nombre_usuario = $(this).data('nombre_usuario');
        let email = $(this).data('email');
        activarEmpleadoEvento(id_empleado, nombre, nombre_usuario, email);
    });
    ////////////////////////////////////
    //   FIN ZONA ACTIVAR ROL EVENTO    //
    //////////////////////////////////

            /////////////////////////////////////
    //   INICIO ZONA DESACTIVAR ROL CARRERA  //
    ///////////////////////////////////
    // MÉTODO PARA DESACTIVAR EL ROL DE LA CARRERA
    function desactivarEmpleadoCarrera(id_empleado, nombre, nombre_usuario, email) {
    // SE COMPRUEBA QUE EL EMPLEADO NO TENGA CARRERAS PENDIENTES EN UN FUTURO
    $.ajax({
        url: base_url + "Admin/verificarCarrerasPendientes/" + id_empleado,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // SI TIENE, NO SE PUEDE DESHABILITAR
            if (response.success && response.tieneCarrerasPendientes) {
                Swal.fire({
                    title: 'No se puede desactivar',
                    html: `El empleado <strong>${nombre_usuario}</strong> tiene ${response.total} carrera(s) pendiente(s) asignada(s).<br><br>Debe reasignarlas antes de desactivar el rol.`,
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // SI NO TIENE, SE PREGUNTA AL ADMIN SI SE QUIERE DESHABILITAR AL EMPLEADO DEL ROL DE CARRERAS
            Swal.fire({
                title: 'Confirmar desactivación',
                text: `¿Está seguro de desactivar este rol de carreras para ${nombre_usuario}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // EN CASO AFIRMATIVO, SE HACE UN AJAX PARA DESACTIVAR EL ROL DEL EMPLEADO
                    $.post(base_url + "Admin/desactivarEmpleadoCarrera/" + id_empleado)
                        .done(function() {
                            // Preparar payload para el correo
                            const emailPayload = {
                                nombre: nombre,
                                nombre_usuario: nombre_usuario,
                                email: email,
                                asunto: "Rol de carreras deshabilitado",
                                mensaje: `Hola ${nombre},

                                Tu rol de gestión de carreras ha sido deshabilitado. Ya no podrás gestionar carreras en la plataforma.

                                Si crees que esto es un error, por favor contacta con el administrador del sistema.

                                Este es un mensaje automático, no respondas.`
                            };

                            // SE ENVÍA UN CORREO PARA NOTIFICAR AL USUARIO
                            $.ajax({
                                url: base_url + "Contactos/enviar",
                                method: "POST",
                                contentType: "application/json",
                                data: JSON.stringify(emailPayload),
                                dataType: "json",
                                success: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        'Desactivado',
                                        `El rol de carreras ha sido deshabilitado y ${nombre_usuario} ha sido notificado/a por correo.`,
                                        'success'
                                    );
                                },
                                error: function() {
                                    $table.DataTable().ajax.reload();
                                    Swal.fire(
                                        'Desactivado',
                                        `El rol de carreras ha sido deshabilitado pero no se pudo enviar el correo a ${nombre_usuario}.`,
                                        'warning'
                                    );
                                }
                            });
                        })
                        .fail(function() {
                            Swal.fire(
                                'Error',
                                'No se pudo deshabilitar el rol',
                                'error'
                            );
                        });
                }
            });
        },
        error: function() {
            Swal.fire(
                'Error',
                'No se pudieron verificar las carreras',
                'error'
            );
        }
    });
}
    
    // Evento click PARA DESACTIVAR AL EMPLEADO DE CARRERAS
    $(document).on('click', '.desactivarEmpleadoCarrera', function(e) {
        e.preventDefault();
        let id_empleado = $(this).data('id_empleado');
        let nombre = $(this).data('nombre');
        let nombre_usuario = $(this).data('nombre_usuario');
        let email = $(this).data('email');
        desactivarEmpleadoCarrera(id_empleado, nombre, nombre_usuario, email);
});
    ////////////////////////////////////
    //   FIN ZONA DESACTIVAR ROL CARRERA    //
    //////////////////////////////////

     ///////////////////////////////////////
    //   INICIO ZONA ACTIVAR ROL CARRERA  //
    /////////////////////////////////////
    // MÉTODO PARA ACTIVAR EL ROL DEL EMPLEADO DE CARRERA
 function activarEmpleadoCarrera(id_empleado, nombre, nombre_usuario, email) {
    Swal.fire({
        title: 'Activar',
        text: `¿Desea activar el rol de carreras para ${nombre_usuario}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        // SI SE CONFIRMA, SE HACE UN AJAX PARA PONER ESE ROL COMO ACTIVO
        if (result.isConfirmed) {
            $.post(base_url + "Admin/activarEmpleadoCarrera/" + id_empleado)
                .done(function() {
                    // Preparar payload para el correo
                    const emailPayload = {
                        nombre: nombre,
                        nombre_usuario: nombre_usuario,
                        email: email,
                        asunto: "Rol de carreras activado",
                        mensaje: `Hola ${nombre},

                        Tu rol de gestión de carreras ha sido activado. Ahora puedes gestionar carreras en la plataforma.

                        Si tienes alguna duda, contacta con el administrador del sistema.

                        Este es un mensaje automático, no respondas.`
                    };

                    // SE ENVÍA UN CORREO PARA NOTIFICAR AL EMPLEADO
                    $.ajax({
                        url: base_url + "Contactos/enviar",
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(emailPayload),
                        dataType: "json",
                        success: function() {
                            $table.DataTable().ajax.reload();
                            Swal.fire(
                                'Activado',
                                `El rol de carreras ha sido activado y ${nombre_usuario} ha sido notificado/a por correo.`,
                                'success'
                            );
                        },
                        error: function() {
                            $table.DataTable().ajax.reload();
                            Swal.fire(
                                'Activado',
                                `El rol de carreras ha sido activado pero no se pudo enviar el correo a ${nombre_usuario}.`,
                                'warning'
                            );
                        }
                    });
                })
                .fail(function() {
                    Swal.fire(
                        'Error',
                        'No se pudo activar el rol de carreras',
                        'error'
                    );
                });
        }
    });
}

// Evento click, obteniendo también los datos necesarios para el correo
$(document).on('click', '.activarEmpleadoCarrera', function(e) {
    e.preventDefault();
    let id_empleado = $(this).data('id_empleado');
    let nombre = $(this).data('nombre');
    let nombre_usuario = $(this).data('nombre_usuario');
    let email = $(this).data('email');
    activarEmpleadoCarrera(id_empleado, nombre, nombre_usuario, email);
});

    ////////////////////////////////////
    //   FIN ZONA ACTIVAR ROL CARRERA    //
    //////////////////////////////////

    ///////////////////////////////////////
    //      INICIO ZONA NUEVO           //
    //        BOTON DE NUEVO           // 
    /////////////////////////////////////
    // CAPTURAR EL CLICK EN EL BOTÓN DE NUEVO
    $(document).on('click', '#btnnuevo', function (event) {
        event.preventDefault();
        
        // Configurar título del modal
        $('#mdltitulo').text('Nuevo registro de empleados');
    
        // Mostrar campos de contraseña y roles (solo para nuevo usuario)
        $('.campos-contrasena').show();
        $('.campos-roles').show();

        $('#btnsalvarAdmin').hide();
        $('#btnsalvar').show();
    
        // Limpieza completa del formulario
        $("#formEmpleado")[0].reset();
        $('#formEmpleado').find('input[name="id"]').val(""); // Asegurar ID vacío
    
        // Limpiar todas las validaciones visuales
        formValidator.clearValidation();

        // Limpiar específicamente los campos de contraseña y sus mensajes
        $('#contraseña, #confirmar_contraseña')
        .removeClass('is-invalid is-valid')
        .val('')
        .closest('.input-group').next('.invalid-feedback').hide();
    
        // Limpiar los campos de los roles (checkboxes)
        $('input[name="roles[]"]').prop('checked', false); // Desmarcar todos los checkboxes
        $('.campos-roles input').removeClass('is-invalid is-valid'); // Remover clases de validación
        $('#error-roles').text("");
    
        // Manejo del modal
        $('#modalEmpleado').modal('show').on('shown.bs.modal', function () {
            $('#nombre_usuario').focus(); // Enfocar primer campo
        });
    });
    
// CAPTURAR EL CLICK EN EL BOTÓN DE SALVAR
$(document).on('click', '#btnsalvar', function (event) {
    event.preventDefault();

    var form = $('#formEmpleado');
    var idE = form.find('input[name="id"]').val().trim();
    var esNuevo = idE === "";

    var nombreUsuarioE = form.find('input[name="nombre_usuario"]').val().trim();
    var nombreE = form.find('input[name="nombre"]').val().trim();
    var apellidosE = form.find('input[name="apellidos"]').val().trim();
    var emailE = form.find('input[name="email"]').val().trim();
    var telefonoE = form.find('input[name="telefono"]').val().trim();
    var contrasenaE = form.find('input[name="contraseña"]').val().trim();
    var confirmarContrasenaE = form.find('input[name="confirmar_contraseña"]').val().trim();

    var esContrasenaValida = true;
    var esRolesValido = true;

    if (esNuevo || contrasenaE !== "") {
        esContrasenaValida = validarContrasena(contrasenaE, confirmarContrasenaE, '#contraseña', '#confirmar_contraseña');
    }

    if (esNuevo) {
        var checkboxesSeleccionados = form.find('input[name="roles[]"]:checked').length;
        esRolesValido = validarRoles(checkboxesSeleccionados);
    }

    var esFormularioValido = formValidator.validateForm(event);

    if (!esContrasenaValida || !esRolesValido || !esFormularioValido) {
        toastr.error("Corrija los errores en el formulario", "Error de Validación");
        return;
    }

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

            if (esNuevo) {
                form.find('input[name="roles[]"]:checked').each(function () {
                    formData.append("roles[]", $(this).val());
                });
            }

            // SI NO HAY ID, SE ESTÁ CREANDO UN EMPLEADO, Y SI YA EXISTE, SE ESTÁ EDITANDO
            var urlAccion = esNuevo ? base_url + "Admin/crearEmpleado" : base_url + "Admin/editarUsuario/" + idE;

            $.ajax({
                url: urlAccion,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        // Extraer roles legibles
                        let rolesLegibles = [];
                        form.find('input[name="roles[]"]:checked').each(function () {
                            const idCheck = $(this).attr('id');
                            const label = form.find(`label[for="${idCheck}"]`).text().trim();
                            if (label) rolesLegibles.push(label);
                        });

                        // Construir mensaje
                        let asunto = esNuevo ? "Alta de empleado en la plataforma" : "Actualización de su cuenta";
                        let mensajeTexto = `
                        Hola ${nombreE},

                        ${esNuevo ? "Se ha creado su cuenta en la plataforma con los siguientes datos:" : "Se han actualizado sus datos de empleado con la siguiente información:"}

                        - Nombre de usuario: ${nombreUsuarioE}
                        - Nombre: ${nombreE}
                        - Apellidos: ${apellidosE}
                        - Email: ${emailE}
                        - Teléfono: ${telefonoE}
                        ${rolesLegibles.length ? "- Roles asignados: " + rolesLegibles.join(', ') : ""}
                        ${esNuevo ? `- Contraseña temporal: ${contrasenaE}\n\n⚠️ Por favor, cambie su contraseña desde su perfil al iniciar sesión.` : ""}
                        ${(!esNuevo && contrasenaE !== "") ? "- Se ha actualizado su contraseña.\n" : ""}

                        Si usted no realizó esta acción, contacte con el administrador del sistema.

                        Este es un mensaje automático. Por favor, no responda.
                        `;

                        const emailPayload = {
                            email: emailE,
                            nombre: nombreE,
                            asunto: asunto,
                            mensaje: mensajeTexto
                        };

                        // ENVÍO UN EMAIL PARA NOTIFICAR AL EMPLEADO DE SU CREACIÓN/EDICIÓN DE SU CUENTA
                        $.ajax({
                            url: base_url + "Contactos/enviar",
                            method: "POST",
                            contentType: "application/json",
                            data: JSON.stringify(emailPayload),
                            dataType: "json",
                            success: function () {
                                $('#modalEmpleado').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                Swal.fire({
                                    icon: 'success',
                                    title: esNuevo ? 'Empleado creado y correo enviado' : 'Empleado actualizado y correo enviado',
                                    html: esNuevo 
                                        ? 'El empleado ha sido creado correctamente.<br>Se ha enviado un correo con sus credenciales.'
                                        : 'El empleado ha sido actualizado correctamente.<br>Se ha enviado un correo de notificación.',
                                });
                            },
                            error: function () {
                                $('#modalEmpleado').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                toastr.warning("Empleado guardado pero no se pudo enviar el correo de notificación");
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
    $(document).on('click', '.editarEmpleado', function(event) {
        event.preventDefault();

         // Configurar título del modal
         $('#mdltitulo').text('Edición de empleado');
    
        // Limpiar formulario
        $('#formEmpleado')[0].reset();
        formValidator.clearValidation();
    
        // Ocultar campos de contraseña y roles al editar (no se deben mostrar ni permitir editar)
        $('.campos-contrasena').show();  // Ocultar campos de contraseña
        $('.campos-roles').hide();       // Ocultar campos de roles (radio buttons)

        $('#btnsalvarAdmin').hide();
        $('#btnsalvar').show();

      // Limpiar específicamente los campos de contraseña y sus mensajes
      $('#contraseña, #confirmar_contraseña')
      .removeClass('is-invalid is-valid')
      .val('')
      .closest('.input-group').next('.invalid-feedback').hide();
    
        // Obtener el ID del empleado
        var idEmpleado = $(this).data('id');
    
        // AJAX PARA OBTENER LOIS DATOS DEL EMPLEADO PARA EDITARLO
        $.ajax({
            url: base_url + "Admin/obtenerUsuarioEdicion/" + idEmpleado,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var empleado = response.data;
    
                    // Rellenar el formulario con los datos obtenidos
                    $("#id").val(empleado.id);
                    $("#nombre_usuario").val(empleado.nombre_usuario);
                    $("#nombre").val(empleado.nombre);
                    $("#apellidos").val(empleado.apellidos);
                    $("#email").val(empleado.email);
                    $("#telefono").val(empleado.telefono);
    
                    // Mostrar el modal de edición
                    $('#modalEmpleado').modal('show');
                } else {
                    Swal.fire('Error', response.message || 'Error al cargar datos del empleado', 'error');
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


    ///////////////////////////////////////
    //        INICIO ZONA ADMIN          //
    /////////////////////////////////////
    // MÉTODO PARA ABIRR EL MODAL DE NUEVO ADMIN
    $(document).on('click', '#btnNuevoAdmin', function(event) {
        event.preventDefault();
        
        // Configurar título del modal específico para admin
        $('#mdltitulo').text('Nuevo Administrador');
        
        // Mostrar campos de contraseña pero OCULTAR roles
        $('.campos-contrasena').show();
        $('.campos-roles').hide();  // Ocultamos la sección de roles
        
        // Ocultar botón normal y mostrar botón admin
        $('#btnsalvar').hide();
        $('#btnsalvarAdmin').show();
        
        // Limpieza completa del formulario
        $("#formEmpleado")[0].reset();
        $('#formEmpleado').find('input[name="id"]').val("");
        
        // Limpiar validaciones
        formValidator.clearValidation();
        
        // Limpiar campos de contraseña sin afectar los demás
        $('#contraseña, #confirmar_contraseña')
        .removeClass('is-invalid is-valid')  // Limpiar validaciones
        .val('');  // Limpiar valores

        // Limpiar mensajes de error solo para los campos de contraseña
        $('#contraseña, #confirmar_contraseña')
        .closest('.input-group')  // Encontrar el grupo del input
        .next('.invalid-feedback')  // Seleccionar el mensaje de error
        .hide();  // Ocultar el mensaje de error
            
        // Manejo del modal
          $('#modalEmpleado').modal('show').on('shown.bs.modal', function () {
            $('#nombre_usuario').focus(); // Enfocar primer campo
        });
    });

    // CAPTURAR EL CLICK EN EL BOTÓN DE GUARDAR ADMIN
$(document).on('click', '#btnsalvarAdmin', function(event) {
    event.preventDefault();

    var form = $('#formEmpleado');

    // Obtener valores
    var contrasenaE = form.find('input[name="contraseña"]').val().trim();
    var confirmarContrasenaE = form.find('input[name="confirmar_contraseña"]').val().trim();

    // 1. Validar contraseña
    var esContrasenaValida = validarContrasena(contrasenaE, confirmarContrasenaE, '#contraseña', '#confirmar_contraseña');

    // 2. Validar formulario
    var esFormularioValido = formValidator.validateForm(event);

    // 3. Verificar errores iniciales
    if (!esContrasenaValida || !esFormularioValido) {
        toastr.error("Corrija los errores en el formulario", "Error de Validación");
        return;
    }

    // Obtener el resto de valores
    var nombreUsuarioE = form.find('input[name="nombre_usuario"]').val().trim();
    var nombreE = form.find('input[name="nombre"]').val().trim();
    var apellidosE = form.find('input[name="apellidos"]').val().trim();
    var emailE = form.find('input[name="email"]').val().trim();
    var telefonoE = form.find('input[name="telefono"]').val().trim();

    // SE COMPRUEBA QUE EL CORREO, NÚMERO Y NOMBRE DE USUARIO SEAN ÚNICOS
    $.ajax({
        url: base_url + "Admin/validarCamposUnicos",
        type: "POST",
        data: { 
            id: '',
            nombre_usuario: nombreUsuarioE, 
            email: emailE, 
            telefono: telefonoE 
        },
        success: function(validacion) {
            // Limpiar errores previos
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();

            if (!validacion.success) {
                // Mostrar errores específicos
                var errores = [];

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

                // Mostrar todos los errores juntos
                toastr.error(errores.join('<br>'), 'Error de Validación');
                return;
            }

            // 5. Preparar datos para admin
            var formData = new FormData();
            formData.append("nombre_usuario", nombreUsuarioE);
            formData.append("nombre", nombreE);
            formData.append("apellidos", apellidosE);
            formData.append("email", emailE);
            formData.append("telefono", telefonoE);
            formData.append("contraseña", contrasenaE);
            formData.append("confirmar_contraseña", confirmarContrasenaE);
            formData.append("es_admin", "1");

            // SE CREA EL ADMIN
            $.ajax({
                url: base_url + "Admin/crearAdministrador",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Construir mensaje para email
                        let asunto = "Alta de administrador en la plataforma";
                        let mensajeTexto = `
                        Hola ${nombreE},

                        Se ha creado su cuenta de administrador en la plataforma con los siguientes datos:

                        - Nombre de usuario: ${nombreUsuarioE}
                        - Nombre: ${nombreE}
                        - Apellidos: ${apellidosE}
                        - Email: ${emailE}
                        - Teléfono: ${telefonoE}
                        - Contraseña temporal: ${contrasenaE}

                        ⚠️ Por favor, cambie su contraseña desde su perfil al iniciar sesión.

                        Si usted no realizó esta acción, contacte con el administrador del sistema.

                        Este es un mensaje automático. Por favor, no responda.
                        `;

                        const emailPayload = {
                            email: emailE,
                            nombre: nombreE,
                            asunto: asunto,
                            mensaje: mensajeTexto
                        };

                        // SE ENVÍA EL CORREO PARA NOTIFICAR AL ADMIN
                        $.ajax({
                            url: base_url + "Contactos/enviar",
                            method: "POST",
                            contentType: "application/json",
                            data: JSON.stringify(emailPayload),
                            dataType: "json",
                            success: function() {
                                $('#modalEmpleado').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Administrador creado y correo enviado',
                                    html: 'El administrador ha sido creado correctamente.<br>Se ha enviado un correo con sus credenciales.',
                                });
                            },
                            error: function() {
                                $('#modalEmpleado').modal('hide');
                                $table.DataTable().ajax.reload();
                                form[0].reset();

                                toastr.warning("Administrador creado pero no se pudo enviar el correo de notificación");
                            }
                        });

                    } else {
                        // Mostrar errores del servidor
                        var errores = response.errors || [response.message] || ['Error desconocido'];
                        toastr.error(errores.join('<br>'), 'Error al crear administrador');
                    }
                },
                error: function() {
                    toastr.error("Error de conexión con el servidor", "Error");
                }
            });
        },
        error: function() {
            toastr.error("Error al validar los datos", "Error de conexión");
        }
    });
});


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