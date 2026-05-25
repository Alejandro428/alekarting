
// CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LOS EVENTOS ACTUALES DEL USUARIO
var datatable_eventosActualesConfig = {
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
      emptyTable: "No tienes reservas actuales de eventos",
  },
  columns: [
      { name: 'detalles', data: null },
      { name: 'pagado', data: 'pagado' },
      { name: 'evento', data: 'nombre_evento' },
      { name: 'tipo', data: 'tipo_evento' },
      { name: 'fecha', data: 'fecha' },
      { name: 'horario', data: null },
      { name: 'cancelar', data: null }
  ],
  columnDefs: [
      {
        targets: 'detalles:name',
        width: '5%',
        searchable: false,
        orderable: false,
        className: "text-center",
        render: function(data, type, row) {
            return `
                <button class="btn btn-primary btn-sm ver-detalleEv"
                        data-id="${row.id}"
                        data-nombre="${row.nombre_evento || ''}"
                        data-tipo="${row.tipo_evento || ''}"
                        data-fecha="${row.fecha}"
                        data-hora_inicio="${row.hora_inicio}"
                        data-hora_fin="${row.hora_fin}"
                        data-participantes="${row.cantidad || ''}"
                        data-total="${row.total_pagado || ''}"
                        data-metodo_pago="${row.metodo_pago || ''}"
                        data-pagado="${row.pagado || ''}"
                        data-payment_intent_id="${row.payment_intent_id || ''}"
                        data-fecha_pago="${row.fecha_pago || ''}">
                    <i class="fa fa-eye"></i>
                </button>
            `;
        }
      },
       {
        targets: 'pagado:name',
        width: '5%',
        className: "text-center",
        render: function(data, type, row) {
            if (row.pagado === "1") {
                return '<span class="badge bg-success">Pagado</span>';
            } else if (row.pagado === "0") {
                return '<span class="badge bg-warning text-dark">Pendiente</span>';
            }
          }
      },
      {
          targets: 'evento:name',
          width: '45%',
          className: "text-center",
          render: function(data) {
              if (!data) return 'N/D';
              const maxLength = 50;
              return data.length > maxLength ? data.substring(0, maxLength) + '...' : data;
          }
      },
      {
          targets: 'tipo:name',
          width: '25%',
          className: "text-center",
          render: function(data) {
              return data || 'N/D';
          }
      },
      {
          targets: 'fecha:name',
          width: '10%',
          className: "text-center",
          render: function(data, type) {
              if (type === "display" || type === "filter") {
                  return formatearFecha(data) || 'N/D';
              }
              return data; // Para ordenamiento usa el valor original
          }
      },
      {
          targets: 'horario:name',
          width: '10%',
          className: "text-center",
          render: function(data, type, row) {
              return row.hora_inicio 
                  ? `${row.hora_inicio.substring(0,5)} - ${row.hora_fin.substring(0,5)}` 
                  : 'N/D';
          }
      },
      {
        targets: 'cancelar:name',
        width: '5%',
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function(data, type, row) {
          return `
            <button class="btn btn-danger btn-sm btn-cancel"
                    data-id="${row.id}"
                    data-usuario_id="${row.usuario_id}"
                    data-metodo_pago="${row.metodo_pago}"
                    data-nombre="${row.nombre_evento || ''}"
                    data-fecha="${row.fecha}"
                    data-hora_inicio="${row.hora_inicio}"
                    data-hora_fin="${row.hora_fin}"
                    data-participantes="${row.cantidad || ''}"
                    data-total="${row.total_pagado || ''}">
              Cancelar
            </button>
          `;
        }
      }
      
  ],
  ajax: {
      url: base_url + "Usuario/obtenerReservasEventosActuales",
      dataSrc: ''
  },
  order: [[4, 'asc']], 
  rowGroup: {
      dataSrc: function (row) {
          return formatoFechaEuropeoSoloFecha(row.fecha);
      },
      startRender: function (rows, group) {
          let $row = $('<tr/>').append('<td colspan="7" class="group-header">' + group + ' / ' + rows.count() + ' evento/s' + '</td>');
          return $row;
      } // de la function startRender
  }, // de la rowGroup
};

// CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR EL HISTORIAL DE EVENTOS DEL USUARIO
var datatable_historialEventosConfig = {
  ...datatable_eventosActualesConfig,
  language: {
      ...datatable_eventosActualesConfig.language,
      emptyTable: "No tienes historial de eventos"
  },
  order: [[4, 'desc']], // Ordenar por fecha descendente
  columns: datatable_eventosActualesConfig.columns.slice(0, -1), // Quitamos la columna de acción
  columnDefs: datatable_eventosActualesConfig.columnDefs.slice(0, -1), // Quitamos la definición de acción
  ajax: {
      url: base_url + "Usuario/obtenerHistorialReservasEventos",
      dataSrc: ''
  }
};

// CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR LAS CARRERAS ACTUALES DEL USUARIO
var datatable_carrerasActualesConfig = {
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
      emptyTable: "No tienes reservas actuales de carreras"
  },
  columns: [
      { name: 'detalles', data: null },
      { name: 'pagado', data: 'pagado' },
      { name: 'pista', data: 'nombre_pista' },
      { name: 'fecha', data: 'fecha' },
      { name: 'horario', data: null },
      { name: 'cancelar', data: null }
  ],
  columnDefs: [
        {
          targets: 'detalles:name',
          width: '5%',
          searchable: false,
          orderable: false,
          className: "text-center",
          render: function(data, type, row) {
              return `
                  <button class="btn btn-primary btn-sm ver-detalleCarrera"
                          data-id="${row.id}"
                          data-pista="${row.nombre_pista || ''}"
                          data-fecha="${row.fecha}"
                          data-hora_inicio="${row.hora_inicio}"
                          data-hora_fin="${row.hora_fin}"
                          data-participantes="${row.num_participantes || ''}"
                          data-total="${row.total_pagado || ''}"
                          data-metodo_pago="${row.metodo_pago || ''}"
                          data-pagado="${row.pagado || ''}"
                          data-payment_intent_id="${row.payment_intent_id || ''}"
                          data-fecha_pago="${row.fecha_pago || ''}">
                      <i class="fa fa-eye"></i>
                  </button>
              `;
          }
      },
      {
        targets: 'pagado:name',
        width: '10%',
        className: "text-center",
        render: function(data, type, row) {
            if (row.pagado === "1") {
                return '<span class="badge bg-success">Pagado</span>';
            } else if (row.pagado === "0") {
                return '<span class="badge bg-warning text-dark">Pendiente</span>';
            }
          }
      },
      {
          targets: 'pista:name',
          width: '50%',
          className: "text-center",
          render: function(data) {
              if (!data) return 'N/D';
              const maxLength = 40;
              return data.length > maxLength ? data.substring(0, maxLength) + '...' : data;
          }
      },
      {
          targets: 'fecha:name',
          width: '10%',
          className: "text-center",
          render: function(data, type) {
              if (type === "display" || type === "filter") {
                  return formatearFecha(data) || 'N/D';
              }
              return data;
          }
      },
      {
          targets: 'horario:name',
          width: '20%',
          className: "text-center",
          render: function(data, type, row) {
              return row.hora_inicio 
                  ? `${row.hora_inicio.substring(0,5)} - ${row.hora_fin.substring(0,5)}` 
                  : 'N/D';
          }
      },
    {
        targets: 'cancelar:name',
        width: '5%',
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function(data, type, row) {
          console.log(row);
          
          return `
            <button class="btn btn-danger btn-sm btn-cancelar-carrera"
                    data-id="${row.id}"
                    data-id_usuario="${row.id_usuario}"
                    data-metodo_pago="${row.metodo_pago}"
                    data-pista="${row.nombre_pista || ''}"
                    data-fecha="${row.fecha}"
                    data-hora_inicio="${row.hora_inicio}"
                    data-hora_fin="${row.hora_fin}"
                    data-participantes="${row.num_participantes || ''}"
                    data-total="${row.total_pagado || ''}">
              Cancelar
            </button>
          `;
      }
    }    
  ],
  ajax: {
      url: base_url + "Usuario/obtenerReservasCarrerasActuales",
      dataSrc: ''
  }, 
  order: [[3, 'asc']], 
        rowGroup: {
            dataSrc: function (row) {
                return formatoFechaEuropeoSoloFecha(row.fecha);
            },
            startRender: function (rows, group) {
                let $row = $('<tr/>').append('<td colspan="6" class="group-header">' + group + ' / ' + rows.count() + ' carrera/s' + '</td>');
                console.log("Fila creada:", $row[0].outerHTML); // Imprime el HTML de la fila);
                return $row;
            } // de la function startRender
        }, // de la rowGroup
};

// CONFIGURACIÓN DEL DATATABLE PARA GESTIONAR EL HISTORIAL DE CARRERAS DEL USUARIO
var datatable_historialCarrerasConfig = {
  ...datatable_carrerasActualesConfig,
  language: {
      ...datatable_carrerasActualesConfig.language,
      emptyTable: "No tienes historial de carreras"
  },
  order: [[3, 'desc']], // Ordenar por fecha descendente
  columns: datatable_carrerasActualesConfig.columns.slice(0, -1), // Quitamos la columna de acción
  columnDefs: datatable_carrerasActualesConfig.columnDefs.slice(0, -1), // Quitamos la definición de acción
  ajax: {
      url: base_url + "Usuario/obtenerHistorialReservasCarreras",
      dataSrc: ''
  }
};

// MÉTODO PARA FORMATEO DE FECHA REUTILIZABLE
function formatearFecha(fechaISO) {
  if (!fechaISO) return "N/D"; 
  const fecha = new Date(fechaISO);
  const dia = String(fecha.getDate()).padStart(2, "0");
  const mes = String(fecha.getMonth() + 1).padStart(2, "0"); 
  const anio = fecha.getFullYear();
  return `${dia}-${mes}-${anio}`;
}

$(document).ready(function() {
  // PESTAÑAS CON BOOTSTRAP
  $(".nav-link").each(function() {
    $(this).on("click", function() {
      var idContenido = $(this).attr("data-pestana");
      // MÉTODO PARA CAMBIAR DE PESTAÑA Y PARA CARGAR OTRA TABLA DISTINTA
      cambiarPestana(this, idContenido);
    });
  });

  // ESTABLECER LA PESTAÑA INICIAL ACTIVA
  var $pestanaInicial = $(".nav-link[data-pestana='reservas_eventos_actuales']");
  if ($pestanaInicial.length) {
    cambiarPestana($pestanaInicial[0], "reservas_eventos_actuales");
  }


  // VALIDADOR DEL FORMULARIO DE CREDENCIALES
var formValidator = new FormValidator('formCredenciales', {
  nombre_usuario: {
  required: true,
  pattern: /^[a-zA-Z0-9]{6,}$/,  // Letras y números, mínimo 6 caracteres
  message: "El nombre de usuario debe tener al menos 6 caracteres y solo puede contener letras y números (sin espacios)."
},
nombre: {
  required: true,
  pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,  // Letras con tildes y espacios
  message: "El nombre solo puede contener letras y espacios."
},
apellidos: {
  required: true,
  pattern: /^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/,  // Igual que nombre
  message: "Los apellidos solo pueden contener letras y espacios."
},
email: {
  required: true,
  pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
  message: "Ingrese un correo válido (ejemplo@dominio.com)."
},
telefono: {
  required: true,
  pattern: /^\d{9}$/,  // 9 dígitos exactos
  message: "El teléfono debe contener 9 números."
}

});

// MÉTODO PARA VALIDAR CONTRASEÑASFunción para validar contraseñas
function validarContrasena(contrasena, confirmarContrasena, inputContrasenaSelector, inputConfirmarSelector) {
  // Limpiar estados anteriores
  $(inputContrasenaSelector + ', ' + inputConfirmarSelector).removeClass('is-invalid is-valid');
  $(inputContrasenaSelector).closest('.form-group').find('.invalid-feedback').hide();
  $(inputConfirmarSelector).closest('.form-group').find('.invalid-feedback').hide();

  let esValido = true;

  // ===== VALIDAR CONTRASEÑA PRINCIPAL =====
  if (!contrasena) {
      mostrarError(inputContrasenaSelector, "La contraseña es obligatoria");
      esValido = false;
  } else if (contrasena.length < 10) {
      mostrarError(inputContrasenaSelector, "Debe tener al menos 10 caracteres");
      esValido = false;
  } else if (!/[A-Z]/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos una mayúscula");
      esValido = false;
  } else if (!/\d/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos un número");
      esValido = false;
  } else if (!/[@$!%*?&]/.test(contrasena)) {
      mostrarError(inputContrasenaSelector, "Debe contener al menos un carácter especial (@$!%*?&)");
      esValido = false;
  } else {
      $(inputContrasenaSelector).addClass('is-valid');
  }

  // ===== VALIDAR CONFIRMACIÓN =====
  if (!confirmarContrasena) {
      mostrarError(inputConfirmarSelector, "Por favor confirme la contraseña");
      esValido = false;
  } else if (contrasena !== confirmarContrasena) {
      mostrarError(inputConfirmarSelector, "Las contraseñas no coinciden");
      esValido = false;
  } else {
      $(inputConfirmarSelector).addClass('is-valid');
  }

  return esValido;
}

// MÉTODO AUXILIAR PARA MOSTRAR ERRORES
function mostrarError(selector, mensaje) {
  $(selector).addClass('is-invalid');
  $(selector).closest('.form-group').find('.invalid-feedback')
      .text(mensaje).show();
}

// VALIDACIÓN EN TIEMPO REAL DE CONTRASEÑA
$(document).on('input', '#contraseña, #confirmar_contraseña', function() {
  const contrasenaActual = $('#contraseña').val();
  const confirmacionActual = $('#confirmar_contraseña').val();

  if (contrasenaActual || confirmacionActual) {
      validarContrasena(contrasenaActual, confirmacionActual, '#contraseña', '#confirmar_contraseña');
  } else {
      $('#contraseña, #confirmar_contraseña').removeClass('is-invalid is-valid');
      $('#contraseña').closest('.form-group').find('.invalid-feedback').hide();
      $('#confirmar_contraseña').closest('.form-group').find('.invalid-feedback').hide();
  }
});


// MÉTODO PARA MOSTRAR/OCULTAR CONTRASEÑA
$('#verContraseña').on('click', function() {
  var passwordField = $('#contraseña');
  var passwordFieldType = passwordField.attr('type');
  if (passwordFieldType == 'password') {
      passwordField.attr('type', 'text');
      $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
      passwordField.attr('type', 'password');
      $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
});

// MÉTODO PARA MOSTRAR/OCULTAR CONFIRMAR CONTRASEÑA
$('#verConfirmarContraseña').on('click', function() {
  var passwordField = $('#confirmar_contraseña');
  var passwordFieldType = passwordField.attr('type');
  if (passwordFieldType == 'password') {
      passwordField.attr('type', 'text');
      $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
      passwordField.attr('type', 'password');
      $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
});

// 5. EVENTO SUBMIT PARA EL ENVÍO DEL FORMULARIO
$(document).on('submit', '#formCredenciales', function(event) {
  event.preventDefault();

  // OBTENER TODOS LOS CAMPOS INDIVIDUALMENTE
  var id = $('input[name="id"]').val().trim();
  var nombre_usuario = $('input[name="nombre_usuario"]').val().trim();
  var nombre = $('input[name="nombre"]').val().trim();
  var apellidos = $('input[name="apellidos"]').val().trim();
  var email = $('input[name="email"]').val().trim();
  var telefono = $('input[name="telefono"]').val().trim();
  var contrasena = $('input[name="contraseña"]').val().trim();
  var confirmar_contrasena = $('input[name="confirmar_contraseña"]').val().trim();

  // VALIDAR CONTRASEÑAS (SOLO SI SE HAN INTRODUCIDO)
  var esContrasenaValida = true;
  if (contrasena || confirmar_contrasena) {
      esContrasenaValida = validarContrasena(contrasena, confirmar_contrasena, '#contraseña', '#confirmar_contraseña');
  }

  // VALIDAR FORMULARIO
  var esFormularioValido = formValidator.validateForm(event);

  // SI HAY ERRORES, SE SALE Y SE MUESTRA UN TOAST CON EL ERROR
  if (!esContrasenaValida || !esFormularioValido) {
      toastr.error("Corrija los errores en el formulario", "Error de Validación");
      return;
  }

  // SE HACE UN AJAX PARA VALIDAR QUE LOS CAMPOS DE EMAIL, USUARIO Y TELÉFONO NO SE REPITEN
  $.ajax({
      url: base_url + "Admin/validarCamposUnicos",
      type: "POST",
      data: { 
          id: id, 
          nombre_usuario: nombre_usuario, 
          email: email, 
          telefono: telefono 
      },
      success: function(validacion) {
          if (!validacion.success) {
              let errores = [];
              if (validacion.error_nombre_usuario) {
                  $('#nombre_usuario').addClass('is-invalid');
                  $('#nombre_usuario').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                      .text(validacion.error_nombre_usuario).show();
                  errores.push(validacion.error_nombre_usuario);
              }
              if (validacion.error_email) {
                  $('#email').addClass('is-invalid');
                  $('#email').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                      .text(validacion.error_email).show();
                  errores.push(validacion.error_email);
              }
              if (validacion.error_telefono) {
                  $('#telefono').addClass('is-invalid');
                  $('#telefono').closest('.col-sm-9, .col-7').find('.invalid-feedback')
                      .text(validacion.error_telefono).show();
                  errores.push(validacion.error_telefono);
              }

              if (errores.length) {
                  toastr.error(errores.join('<br>'), 'Errores');
              }
              return;
          }

          // SI LA VALIDACIÓN ES EXITOSA, PREPARAR LOS DATOS PARA ENVIAR
          var formData = {
              id: id,
              nombre_usuario: nombre_usuario,
              nombre: nombre,
              apellidos: apellidos,
              email: email,
              telefono: telefono
          };

          // SOLO AGREGAR CONTRASEÑA SI SE HA PRODUCIDO
          if (contrasena) {
              formData.contraseña = contrasena;
              formData.confirmar_contraseña = confirmar_contrasena;
          }

          // ENVIAR LOS DATOS PARA ACTUALIZAR LAS CREDENCIALES CON UN AJAX
          $.ajax({
              url: base_url + "Usuario/actualizarCredenciales",
              method: "POST",
              contentType: "application/json",
              data: JSON.stringify(formData),
              dataType: "json",
              success: function(json) {
                  if (json.success) {

                      // Preparar mensaje para el correo
                      let mensajeTexto = `
                      Hola, ${nombre}:

                      Se han actualizado tus credenciales de acceso con los siguientes datos:

                      - Nombre de usuario: ${nombre_usuario}
                      - Nombre: ${nombre}
                      - Apellidos: ${apellidos}
                      - Email: ${email}
                      - Teléfono: ${telefono}
                      `;

                      if (contrasena) {
                          mensajeTexto += `

                      - La contraseña ha sido cambiada (por seguridad no se muestra aquí).
                      `;
                      }

                      mensajeTexto += `

                      Si tú no solicitaste este cambio, contacta con el administrador de inmediato.

                      Este es un mensaje automático. No respondas a este correo.
                      `;

                      const emailPayload = {
                          email: email,
                          nombre: nombre,
                          asunto: "Actualización de credenciales",
                          mensaje: mensajeTexto
                      };

                      // AJAX PARA ENVIAR EL CORREO Y NOTIFICAR AL USUARIO DE SU CAMBIO DE CREDENCIALES
                      $.ajax({
                          url: base_url + "Contactos/enviar",
                          method: "POST",
                          contentType: "application/json",
                          data: JSON.stringify(emailPayload),
                          dataType: "json",
                          success: function() {
                              Swal.fire({
                                  title: '¡Éxito!',
                                  text: json.message + "\n\nSe ha enviado un correo de notificación.",
                                  icon: 'success',
                                  confirmButtonText: 'Aceptar',
                                  timer: 3000,
                                  timerProgressBar: true,
                                  willClose: () => {
                                      window.location.reload();
                                  }
                              });
                          },
                          error: function() {
                              Swal.fire({
                                  title: '¡Éxito!',
                                  text: json.message + "\n\nNo se pudo enviar el correo de notificación.",
                                  icon: 'warning',
                                  confirmButtonText: 'Aceptar',
                                  timer: 3000,
                                  timerProgressBar: true,
                                  willClose: () => {
                                      window.location.reload();
                                  }
                              });
                          }
                      });

                  } else {
                      Swal.fire({
                          title: 'Error',
                          html: "Error: " + (json.message || "Ocurrió un error al actualizar") + 
                               (json.errors ? "<br><br>" + json.errors.join("<br>") : ""),
                          icon: 'error',
                          confirmButtonText: 'Entendido'
                      });
                  }
              },
              error: function(xhr) {
                  console.error("Error al actualizar las credenciales:", xhr);
                  Swal.fire({
                      title: 'Error',
                      text: 'Error al conectar con el servidor',
                      icon: 'error',
                      confirmButtonText: 'Entendido'
                  });
              }
          });
      },
      error: function() {
          Swal.fire('Error', 'Error al validar datos', 'error');
      }
  });
});

  // MÉTODO PARA INICIALIZAR LA TABLA ESCOGIDA
  function inicializarTabla(tablaId) {
    let config;

    switch(tablaId) {
        case 'tablaReservasEventosActuales':
            config = datatable_eventosActualesConfig;
            break;
        case 'tablaHistorialEventos':
            config = datatable_historialEventosConfig;
            break;
        case 'tablaReservasCarrerasActuales':
            config = datatable_carrerasActualesConfig;
            break;
        case 'tablaHistorialCarreras':
            config = datatable_historialCarrerasConfig;
            break;
    }

    const $tabla = $(`#${tablaId}`);

    if ($tabla.length > 0) {
        if ($.fn.DataTable.isDataTable($tabla)) {
            $tabla.DataTable().destroy();
        }

        $tabla.DataTable(config);
    } else {
        console.warn(`La tabla con id ${tablaId} no existe en el DOM aún.`);
    }
}
  
  // MÉTODO PARA CAMBIAR PESTAÑAS Y CARGAR RESERVAS
  function cambiarPestana(elemento, idContenido) {
    $(".nav-link").removeClass("active");
    $(".contenido-pestana").removeClass("show active");
    $(elemento).addClass("active");
    $("#" + idContenido).addClass("show active");

    const tablas = {
        'reservas_eventos_actuales': 'tablaReservasEventosActuales',
        'reservas_carreras_actuales': 'tablaReservasCarrerasActuales',
        'historial_eventos': 'tablaHistorialEventos',
        'historial_carreras': 'tablaHistorialCarreras'
    };

    if (tablas[idContenido]) {
        inicializarTabla(tablas[idContenido]);
    }
}

////////////////////////////////////////////////////////////
//   INICIO ZONA LANZAMIENTO MODAL DETALLE EVENTOS       //
//////////////////////////////////////////////////////////
// EVENTO AL HACER CLICK EN EL VER DETALLE DE EVENTOS
$(document).on('click', '.ver-detalleEv', function () {
  const $btn = $(this);

  // Formatear fechas y horarios
  const fechaEvento = formatearFecha($btn.data('fecha'));
  const fechaPago = formatearFecha($btn.data('fecha_pago'));
  const horario = `${$btn.data('hora_inicio')?.substring(0, 5) || ''} - ${$btn.data('hora_fin')?.substring(0, 5) || ''}`;

  // Determinar estado de pago con badge
  const estadoPago = $btn.data('pagado') 
    ? '<span class="badge bg-success">Pagado</span>'
    : '<span class="badge bg-warning text-dark">Pendiente</span>';

  // Traducir método de pago
  const metodoPagoRaw = $btn.data('metodo_pago');
  let metodoPago = 'N/D';
  if (metodoPagoRaw === 'paypal') metodoPago = 'PayPal';
  else if (metodoPagoRaw === 'card') metodoPago = 'Tarjeta';
  else if (metodoPagoRaw === 'presencial') metodoPago = 'Presencial';

  // Actualizar el título del modal
  $('#modalEventoLabel').html(`
    <i class="fas fa-info-circle me-2"></i>
    ${$btn.data('nombre') || 'Detalles del Evento'}
  `);

  // Insertar los datos en el modal
  $('#nombreEvento').html($btn.data('nombre') || 'N/D');
  $('#tipoEvento').html($btn.data('tipo') || 'N/D');
  $('#fechaEvento').html(fechaEvento || 'N/D');
  $('#horarioEvento').html(horario || 'N/D');
  $('#participantesEvento').html($btn.data('participantes') || 'N/D');
  $('#totalEvento').html($btn.data('total') ? `${$btn.data('total')}€` : 'N/D');
  $('#metodoPagoEvento').html(metodoPago);
  $('#fechaPagoEvento').html(fechaPago || 'N/D');
  $('#estadoPagoEvento').html(estadoPago);
  $('#paymentIntentEvento').html($btn.data('payment_intent_id') || 'N/D');

  // Mostrar el modal con animación
  $('#modalMostrarEvento').modal('show');
});


/////////////////////////////////////////////////////////////
//   INICIO ZONA LANZAMIENTO MODAL DETALLE CARRERAS       //
///////////////////////////////////////////////////////////
// EVENTO AL HACER CLICK EN EL VER DETALLE DE CARRERAS
$(document).on('click', '.ver-detalleCarrera', function() {
  const $btn = $(this);
  const fechaCarrera = formatearFecha($btn.data('fecha'));
  const fechaPago = formatearFecha($btn.data('fecha_pago'));
  const horario = `${$btn.data('hora_inicio')?.substring(0,5) || ''} - ${$btn.data('hora_fin')?.substring(0,5) || ''}`;

  // Determinar estado de pago con badge
  const estadoPago = $btn.data('pagado') 
    ? '<span class="badge bg-success">Pagado</span>'
    : '<span class="badge bg-warning text-dark">Pendiente</span>';

  // Traducir método de pago
  const metodoPagoRaw = $btn.data('metodo_pago');
  let metodoPago = 'N/D';
  if (metodoPagoRaw === 'paypal') metodoPago = 'PayPal';
  else if (metodoPagoRaw === 'card') metodoPago = 'Tarjeta';
  else if (metodoPagoRaw === 'presencial') metodoPago = 'Presencial';

  // Actualizar el título del modal con icono
  $('#modalCarreraLabel').html(`
    <i class="fas fa-flag-checkered me-2"></i>Detalles de Carrera - ${$btn.data('pista') || ''}
  `);

  // Insertar los datos en el modal
  $('#pistaCarrera').text($btn.data('pista') || 'N/D');
  $('#fechaCarrera').text(fechaCarrera || 'N/D');
  $('#horarioCarrera').text(horario || 'N/D');
  $('#participantesCarrera').text($btn.data('participantes') || 'N/D');
  $('#totalCarrera').text($btn.data('total') ? `${$btn.data('total')}€` : 'N/D');
  $('#metodoPagoCarrera').text(metodoPago);
  $('#fechaPagoCarrera').text(fechaPago || 'N/D');
  $('#estadoPagoCarrera').html(estadoPago);
  $('#paymentIntentCarrera').text($btn.data('payment_intent_id') || 'N/D');

  // Mostrar el modal con animación
  $('#modalMostrarCarrera').modal('show');
});


// CANCELAR EVENTO ACTUAL
$('#tablaReservasEventosActuales').on('click', '.btn-cancel', function () {
  const $btn = $(this);

  const id = $btn.data('id');
  const nombre = $btn.data('nombre');
  const fecha = $btn.data('fecha');
  const hora_inicio = $btn.data('hora_inicio');
  const hora_fin = $btn.data('hora_fin');
  const participantes = $btn.data('participantes');
  const total = $btn.data('total');
  const usuario_id = $btn.data('usuario_id');
  
  // Formatear método de pago
  const metodoPagoRaw = $btn.data('metodo_pago');
  let metodoPago = 'N/D';
  if (metodoPagoRaw === 'paypal') metodoPago = 'PayPal';
  else if (metodoPagoRaw === 'card') metodoPago = 'Tarjeta';
  else if (metodoPagoRaw === 'presencial') metodoPago = 'Presencial';

  const fechaReserva = new Date(`${fecha}T${hora_inicio}`);
  const hoy = new Date();
  const diferenciaDias = (fechaReserva - hoy) / (1000 * 60 * 60 * 24);

  // SI QUEDAN MENOS O 7 DÍAS, NO SE PUEDE CANCELAR EL EVENTO
  if (diferenciaDias <= 7) {
    swal.fire({
      icon: 'warning',
      title: 'No se puede cancelar',
      html: `
        Quedan 7 días o menos para el evento.<br><br>
        <strong>Evento:</strong> ${nombre}<br>
        <strong>Participantes:</strong> ${participantes}<br>
        <strong>Total:</strong> ${total}€<br>
        <strong>Fecha:</strong> ${formatearFecha(fecha)}<br>
        <strong>Hora:</strong> ${hora_inicio.substring(0,5)} - ${hora_fin.substring(0,5)}<br>
        <strong>Método de pago:</strong> ${metodoPago}
      `
    });
    return;
  }

  swal.fire({
    title: '¿Cancelar reserva?',
    html: `
      <strong>Evento:</strong> ${nombre}<br>
      <strong>Participantes:</strong> ${participantes}<br>
      <strong>Total:</strong> ${total}€<br>
      <strong>Fecha:</strong> ${formatearFecha(fecha)}<br>
      <strong>Hora:</strong> ${hora_inicio?.substring(0, 5)} - ${hora_fin?.substring(0, 5)}<br>
      <strong>Método de pago:</strong> ${metodoPago}
    `,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      // AJAX PARA OBTENER INFORMACIÓN DEL USUARIO, PARA POSTERIORMENTE MANDAR EL CORREO
      $.get(base_url + 'Usuario/getUsuarioPorId/' + usuario_id)
        .then(function(usuarioResponse) {
          if (!usuarioResponse?.email || !usuarioResponse?.nombre) {
            throw new Error("Datos de usuario incompletos");
          }
          
          // SE PROCEDE A CANCELAR EL EVENTO ESCOGIDO 
          cancelarReservaEvento(id, {
            nombreEvento: nombre,
            fechaEvento: fecha,
            horaInicio: hora_inicio,
            horaFin: hora_fin,
            participantes: participantes,
            total: total,
            metodoPago: metodoPago, // Usamos el método formateado
            usuarioEmail: usuarioResponse.email,
            usuarioNombre: usuarioResponse.nombre
          });
        })
        .catch(function(error) {
          console.error("Error al obtener usuario:", error);
          swal.fire({
            icon: 'error',
            title: 'No se puede cancelar',
            text: 'No se pudieron obtener los datos del usuario para enviar la notificación.',
          });
        });
    }
  });
});

// MÉTODO PARA CANCELAR LA RESERVA DEL EVENTO
function cancelarReservaEvento(reservaId, datosCorreo) {
  console.log("Cancelando reserva de evento, ID:", reservaId);
  // AJAX QUE CANCELA EL EVENTO ESCOGIDO
  $.ajax({
    url: base_url + "Usuario/cancelarReservaEvento",
    method: "DELETE",
    contentType: "application/json",
    data: JSON.stringify({ id: reservaId }),
    dataType: "json",
    success: function(json) {
      console.log("Respuesta cancelarReservaEvento:", json);
      
      // Preparar el mensaje del correo con la estructura solicitada
      const mensajeCorreo = `
      Tipo de reserva: Evento
      Evento: ${datosCorreo.nombreEvento}
      Fecha: ${formatearFecha(datosCorreo.fechaEvento)}
      Horario: ${datosCorreo.horaInicio?.substring(0, 5)} - ${datosCorreo.horaFin?.substring(0, 5)}
      Número de participantes: ${datosCorreo.participantes}
      Total: ${parseFloat(datosCorreo.total).toFixed(2)} €
      Método de pago: ${datosCorreo.metodoPago}
      
      Lamentamos informarte que tu reserva ha sido cancelada. 
      Si no has solicitado esta cancelación, por favor contacta con nuestro equipo.`;

      // Enviar correo 
      const emailPayload = {
        email: datosCorreo.usuarioEmail,
        nombre: datosCorreo.usuarioNombre,
        asunto: "Cancelación de tu reserva de evento",
        mensaje: mensajeCorreo
      };
      // AJAX QUE ENVÍA EL CORREO PARA AVISAR AL USUARIO DE QUE SE HA CANCELADO SU RESERVA DEL EVENTO
      $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(emailPayload),
        dataType: "json",
        success: function() {
          // Recargar tabla y mostrar mensaje
          $('#tablaReservasEventosActuales').DataTable().ajax.reload();
          swal.fire(
            'Cancelado',
            'La reserva ha sido cancelada y se ha notificado al usuario.',
            'success'
          );
        },
        error: function(xhr) {
          console.error("Error al enviar correo:", xhr);
          // Si falla el correo, consideramos que la cancelación falló
          $('#tablaReservasEventosActuales').DataTable().ajax.reload();
          swal.fire(
            'Error',
            'La reserva se canceló pero no se pudo enviar la notificación. Por favor, contacta al usuario manualmente.',
            'error'
          );
        }
      });
    },
    error: function(xhr) {
      console.error("Error al cancelar reserva:", xhr);
      swal.fire(
        'Error',
        'No se pudo cancelar la reserva.',
        'error'
      );
    }
  });
}

// MÉTODO PARA CANCELAR LA RESERVA DE LA CARRERA
$('#tablaReservasCarrerasActuales').on('click', '.btn-cancelar-carrera', function () {
  const $btn = $(this);

  const id = $btn.data('id');
  const pista = $btn.data('pista');
  const fecha = $btn.data('fecha');
  const hora_inicio = $btn.data('hora_inicio');
  const hora_fin = $btn.data('hora_fin');
  const participantes = $btn.data('participantes');
  const total = $btn.data('total');
  const id_usuario = $btn.data('id_usuario');
  
  // Formatear método de pago
  const metodoPagoRaw = $btn.data('metodo_pago');
  let metodoPago = 'N/D';
  if (metodoPagoRaw === 'paypal') metodoPago = 'PayPal';
  else if (metodoPagoRaw === 'card') metodoPago = 'Tarjeta';
  else if (metodoPagoRaw === 'presencial') metodoPago = 'Presencial';

  const fechaReserva = new Date(`${fecha}T${hora_inicio}`);
  const hoy = new Date();
  const diferenciaDias = (fechaReserva - hoy) / (1000 * 60 * 60 * 24);

  if (diferenciaDias <= 7) {
  swal.fire({
    icon: 'warning',
    title: 'No se puede cancelar',
    html: `
      Quedan 7 días o menos para la carrera.<br><br>
      <strong>Pista:</strong> ${pista}<br>
      <strong>Participantes:</strong> ${participantes}<br>
      <strong>Total:</strong> ${total}€<br>
      <strong>Fecha:</strong> ${formatearFecha(fecha)}<br>
      <strong>Hora:</strong> ${hora_inicio.substring(0,5)} - ${hora_fin.substring(0,5)}<br>
      <strong>Método de pago:</strong> ${metodoPago}
    `
  });
  return;
}


  swal.fire({
    title: '¿Cancelar reserva de carrera?',
    html: `
      <strong>Pista:</strong> ${pista}<br>
      <strong>Participantes:</strong> ${participantes}<br>
      <strong>Total:</strong> ${total}€<br>
      <strong>Fecha:</strong> ${formatearFecha(fecha)}<br>
      <strong>Hora:</strong> ${hora_inicio?.substring(0, 5)} - ${hora_fin?.substring(0, 5)}<br>
      <strong>Método de pago:</strong> ${metodoPago}
    `,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      // AJAX PARA OBTENER INFORMACIÓN DEL USUARIO, PARA POSTERIORMENTE MANDAR EL CORREO
      $.get(base_url + 'Usuario/getUsuarioPorId/' + id_usuario)
        .then(function(usuarioResponse) {
          if (!usuarioResponse?.email || !usuarioResponse?.nombre) {
            throw new Error("Datos de usuario incompletos");
          }
          
          // SE PROCEDE A CANCELAR LA CARRERA ESCOGIDA
          cancelarReservaCarrera(id, {
            pista: pista,
            fechaCarrera: fecha,
            horaInicio: hora_inicio,
            horaFin: hora_fin,
            participantes: participantes,
            total: total,
            metodoPago: metodoPago, // Usamos el método formateado
            usuarioEmail: usuarioResponse.email,
            usuarioNombre: usuarioResponse.nombre
          });
        })
        .catch(function(error) {
          console.error("Error al obtener usuario:", error);
          swal.fire({
            icon: 'error',
            title: 'No se puede cancelar',
            text: 'No se pudieron obtener los datos del usuario para enviar la notificación.',
          });
        });
    }
  });
});

// MÉTODO PARA CANCELAR LA RESERVA DE LA CARRERA
function cancelarReservaCarrera(reservaId, datosCorreo) {
  console.log("Cancelando reserva de carrera, ID:", reservaId);
  
  // AJAX QUE CANCELA LA RESERVA DE LA CARRERA ESCOGIDA
  $.ajax({
    url: base_url + "Usuario/cancelarReservaCarrera",
    method: "DELETE",
    contentType: "application/json",
    data: JSON.stringify({ id: reservaId }),
    dataType: "json",
    success: function(json) {
      console.log("Respuesta cancelarReservaCarrera:", json);
      
      // Preparar el mensaje del correo con estructura específica para carreras
      const mensajeCorreo = `
      Tipo de reserva: Carrera
      Pista: ${datosCorreo.pista}
      Fecha: ${formatearFecha(datosCorreo.fechaCarrera)}
      Horario: ${datosCorreo.horaInicio?.substring(0, 5)} - ${datosCorreo.horaFin?.substring(0, 5)}
      Número de participantes: ${datosCorreo.participantes}
      Total: ${parseFloat(datosCorreo.total).toFixed(2)} €
      Método de pago: ${datosCorreo.metodoPago}
      
      Lamentamos informarte que tu reserva ha sido cancelada. 
      Si no has solicitado esta cancelación, por favor contacta con nuestro equipo.`;

      // Enviar correo (obligatorio)
      const emailPayload = {
        email: datosCorreo.usuarioEmail,
        nombre: datosCorreo.usuarioNombre,
        asunto: "Cancelación de tu reserva de carrera",
        mensaje: mensajeCorreo
      };

      // AJAX PARA NOTIFICAR AL USUARIO DE QUE SU RESERVA DE CARRERA HA SIDO CANCELADA
      $.ajax({
        url: base_url + "Contactos/enviar",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(emailPayload),
        dataType: "json",
        success: function() {
          // Recargar tabla y mostrar mensaje
          $('#tablaReservasCarrerasActuales').DataTable().ajax.reload();
          swal.fire(
            'Cancelado',
            'La reserva de carrera ha sido cancelada y se ha notificado al usuario.',
            'success'
          );
        },
        error: function(xhr) {
          console.error("Error al enviar correo:", xhr);
          // Si falla el correo, consideramos que la cancelación falló
          $('#tablaReservasCarrerasActuales').DataTable().ajax.reload();
          swal.fire(
            'Error',
            'La reserva se canceló pero no se pudo enviar la notificación. Por favor, contacta al usuario manualmente.',
            'error'
          );
        }
      });
    },
    error: function(xhr) {
      console.error("Error al cancelar reserva:", xhr);
      swal.fire(
        'Error',
        'No se pudo cancelar la reserva de carrera.',
        'error'
      );
    }
  });
}

});
