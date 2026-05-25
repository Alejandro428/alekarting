// ALMACENA TODOS LOS EVENTOS DISPONIBLES
let datosEventos = [];

// ALMACENA LAS RESERVAS DEL USUARIO ACTUAL
let reservasUsuario = [];

/**
MÉTODO PARA RETORNAR OBJETO DE EVENTO, Y PARA PREVENIR POSIBLES VALORES NULOS 
 */
function procesarEvento(evento) {
  return {
    id: evento.id,
    nombre: evento.nombre || "Sin nombre",
    fecha: evento.fecha || "1970-01-01",
    hora_inicio: evento.hora_inicio || "00:00:00",
    hora_fin: evento.hora_fin || "00:00:00",
    tipo_evento: evento.tipo_evento ? String(evento.tipo_evento).trim() : "Desconocido",
    capacidad: evento.capacidad || 0,
    plazas_reservadas: evento.plazas_reservadas || 0,
    precio: evento.precio || 0,
    imagen: evento.imagen || "",
    franja_horaria_id: evento.franja_horaria_id || null
  };
}

/**
METODO PARA FORMATEAR LA FECHA EN FORMATO ESPAÑOL DD-MM-YYYY
 */
function formatearFecha(fechaStr) {
  let fechaObj = new Date(fechaStr);
  let dia = fechaObj.getDate().toString().padStart(2, "0");
  let mes = (fechaObj.getMonth() + 1).toString().padStart(2, "0");
  let anio = fechaObj.getFullYear();
  return `${dia}/${mes}/${anio}`;
}

/**
MÉTODO USADO EN CREAR TARJETA PARA COMPROBAR SI HAY RESERVA EN ESE EVENTO EN CONCRETO,
SI HAY, SE PINTARÁ EL BOTÓN DE EL EVENTO CON UN MÉTODO
 */
function obtenerReservaDetalleLocal(idEvento) {
  return reservasUsuario.find(reserva => reserva.evento_id == idEvento) || null;
}
/**
MÉTODO PARA MOSTRAR UNA NOTIFICACIÓN NORMAL
 */
function mostrarNotificacion(mensaje) {
  let $notificacionExistente = $("#divNotificacion");
  if ($notificacionExistente.length) { 
    $notificacionExistente.remove(); 
  }
  
  // CONTENEDOR PRINCIPAL 
  let $divNotificacion = $("<div>", { 
    id: "divNotificacion", 
    class: "notificacion fadeIn",
    css: {
      maxHeight: "80vh",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      width: "auto",
      minWidth: "300px",
      maxWidth: "95vw"
    }
  });
  
  // CONTENIDO CON SCROLL
  let $contenido = $("<div>", {
    css: {
      overflowY: "auto",
      overflowX: "hidden",
      padding: "15px",
      flex: "1",
      wordWrap: "break-word"
    }
  }).html(mensaje);
  
  $divNotificacion.append($contenido);
  
  // CONTENEDOR DEL BOTÓN 
  let $botonContainer = $("<div>", {
    css: {
      padding: "10px 15px",
      textAlign: "center",
      flexShrink: "0"
    }
  });
  
  // BOTÓN CERRAR 
  let $botonCerrar = $("<button>").text("Cerrar").on("click", function() {
    $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
    setTimeout(() => { $divNotificacion.remove(); }, 500);
  });
  
  $botonContainer.append($botonCerrar);
  $divNotificacion.append($botonContainer);
  $("body").append($divNotificacion);
  
  // AUTO-CIERRE 
  setTimeout(() => {
    if ($("body").has($divNotificacion).length) {
      $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
      setTimeout(() => { $divNotificacion.remove(); }, 500);
    }
  }, 3000);
}

/**
MÉTODO PARA CONFIRMAR LA RESERVA DE EL EVENTO
 */
function mostrarConfirmacion(mensaje, datosReserva, onConfirm, onCancel) {
  let $confirmacionExistente = $("#divConfirmacion");
  if ($confirmacionExistente.length) {
    $confirmacionExistente.remove();
  }

  // CONTENEDOR PRINCIPAL
  let $divConfirmacion = $("<div>", {
    id: "divConfirmacion",
    class: "notificacion fadeIn",
    css: {
      maxHeight: "80vh",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      width: "auto",
      minWidth: "300px",
      maxWidth: "95vw",
      padding: "0"
    }
  });

  // CONTENIDO CON SCROLL
  let $contenido = $("<div>", {
    css: {
      overflowY: "auto",
      overflowX: "hidden",
      padding: "15px",
      flex: "1",
      wordWrap: "break-word",
      minWidth: "0"
    }
  });

  // MENSAJE 
  let mensajeCompleto = mensaje + "<br><br>" +
    "<strong>Evento:</strong> " + (datosReserva.nombre || "Sin nombre") + "<br>" +
    "<strong>Tipo:</strong> " + (datosReserva.tipo || "Desconocido") + "<br>" +
    "<strong>Fecha:</strong> " + (datosReserva.fecha || "No definida") + "<br>" +
    "<strong>Horario:</strong> " + (datosReserva.horario || "00:00 - 00:00") + "<br>" +
    "<strong>Participantes:</strong> " + (datosReserva.participantes || 0) + "<br>" +
    "<strong>Precio por persona:</strong> " + (datosReserva.precio || 0) + " €<br>" +
    "<strong>Total a pagar:</strong> " + (datosReserva.total || 0);

  $contenido.html(mensajeCompleto);

  // CHECKBOX
  let $checkboxLabel = $('<label>', {
    css: {
      display: 'inline-block',
      marginTop: '10px',
      marginBottom: '10px',
      fontSize: '18px',
      cursor: 'pointer',
      userSelect: 'none',
      verticalAlign: 'middle'
    }
  }).html(`
    <input type="checkbox" id="aceptarTerminos">
    Acepto los <a href="${urlCondiciones}" target="_blank" style="color: #007bff; text-decoration: underline;">términos y condiciones</a>
  `);

  $contenido.append("<br><br>").append($checkboxLabel);
  $divConfirmacion.append($contenido);

  // CONTENEDOR DE BOTONES 
  let $botonesContainer = $("<div>", {
    css: {
      display: "flex",
      justifyContent: "center",
      gap: "20px", 
      padding: "15px",
      flexShrink: "0",
      width: "100%",
      background: "none", 
      borderTop: "none"   
    }
  });

  // BOTÓN CONFIRMAR
  let $btnConfirmar = $("<button>").text("Confirmar").on("click", () => {
    if (!$("#aceptarTerminos").is(":checked")) {
      toastr.error("Debe aceptar los términos y condiciones para continuar", "Error de Validación");
      return;
    }
    $divConfirmacion.removeClass("fadeIn").addClass("fadeOut");
    setTimeout(() => {
      onConfirm();
      $divConfirmacion.remove();
    }, 500);
  });

  // BOTÓN CANCELAR
  let $btnCancelar = $("<button>").text("Cancelar").on("click", () => {
    if (onCancel) onCancel();
    $divConfirmacion.removeClass("fadeIn").addClass("fadeOut");
    setTimeout(() => { $divConfirmacion.remove(); }, 500);
  });

  $botonesContainer.append($btnConfirmar).append($btnCancelar);
  $divConfirmacion.append($botonesContainer);
  $("body").append($divConfirmacion);
}

/**
MÉTODO PARA ESCOGER LA CANTIDAD DE PERSONAS QUE VAN A IR AL EVENTO, SI SE LE DA
AL ONCONFIRM, SE PASA POR EL CALLBACK LA VARIABLE CANTIDAD, UTILIZADA EN EL MÉTODO procesarConfirmacionReserva
 */
function mostrarInputReserva(mensaje, maxCantidad, onConfirm, onCancel) {
  let $divInputNotificacion = $("#divInputNotificacion");
  if ($divInputNotificacion.length) { 
    $divInputNotificacion.remove(); 
  }
  
  // CONTENEDOR PRINCIPAL 
  $divInputNotificacion = $("<div>", { 
    id: "divInputNotificacion", 
    class: "notificacion fadeIn",
    css: {
      maxHeight: "80vh",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      width: "auto",
      minWidth: "300px",
      maxWidth: "95vw",
      padding: "0"
    }
  });
  
  // CONTENEDOR DE CONTENIDO 
  let $contenido = $("<div>", {
    css: {
      overflowY: "auto",
      overflowX: "hidden",
      padding: "15px",
      flex: "1",
      wordWrap: "break-word",
      minWidth: "0"
    }
  });
  
  let $mensajeEl = $("<div>").html(mensaje);
  $contenido.append($mensajeEl);
  
  // INPUT CON TAMAÑO ORIGINAL 
  let $inputCantidad = $("<input>", { 
    type: "number", 
    min: "1", 
    value: "1", 
    max: maxCantidad,
    css: {
      margin: "10px 0"
    }
  });
  
  $contenido.append($inputCantidad);
  $divInputNotificacion.append($contenido);
  
  // CONTENEDOR DE BOTONES 
  let $botonesContainer = $("<div>", {
    css: {
      display: "flex",
      justifyContent: "center",
      gap: "20px",
      padding: "15px",
      flexShrink: "0",
      width: "100%"
    }
  });
  
  // BOTÓN CONFIRMAR 
  let $btnConfirmar = $("<button>").text("Confirmar").on("click", function() {
    let cantidad = parseInt($inputCantidad.val());
    if (isNaN(cantidad) || cantidad < 1) {
      mostrarNotificacion("Cantidad inválida");
      return;
    }
    if (cantidad > maxCantidad) {
      mostrarNotificacion("La cantidad ingresada supera la capacidad disponible (" + maxCantidad + "). Reserva anulada.");
      $divInputNotificacion.remove();
      return;
    }
    $divInputNotificacion.removeClass("fadeIn").addClass("fadeOut");
    setTimeout(() => { onConfirm(cantidad); $divInputNotificacion.remove(); }, 500);
  });
  
  // BOTÓN CANCELAR 
  let $btnCancelar = $("<button>").text("Cancelar").on("click", function() {
    if (onCancel) onCancel();
    $divInputNotificacion.removeClass("fadeIn").addClass("fadeOut");
    setTimeout(() => { $divInputNotificacion.remove(); }, 500);
  });
  
  $botonesContainer.append($btnConfirmar).append($btnCancelar);
  $divInputNotificacion.append($botonesContainer);
  $("body").append($divInputNotificacion);
}

/**
MÉTODO PARA CREAR CADA TARJETA DE EVENTO, Y ASIGNAR DEPENDIENDO DE LA SITUACIÓN QUE
TENGA EL EVENTO UNA U OTRA COSA (BOTÓN PARA EVENTO RESERVADO, EVENTO COMPLETO O PARA RESERVAR)
 */
function crearTarjetaEvento(evento) {
  let datosEvento = procesarEvento(evento);
  let $divTarjeta = $("<div>", { class: "tarjeta-evento" });
  
  // CONFIGURAR IMAGEN DEL EVENTO
  let $imgEvento = $("<img>", {
    class: "evento-img",
    src: datosEvento.imagen 
      ? (base_url + "assets/imagenes/eventos/" + datosEvento.imagen)
      : (base_url + "assets/imagenes/eventos/ejemplo1Noticia.jpg"),
    alt: datosEvento.nombre
  });
  $divTarjeta.append($imgEvento);
  
  // CONFIGURO LA INFORMACIÓN QUE SE VA A VER EN CADA TARJETA
  let $divOverlay = $("<div>", { class: "overlay-evento" });
  $divOverlay.append($("<h2>").text(datosEvento.nombre));
  $divOverlay.append($("<p>").text("Tipo: " + datosEvento.tipo_evento));
  $divOverlay.append($("<p>").text("Horario: " + datosEvento.hora_inicio.substring(0, 5) + " - " + datosEvento.hora_fin.substring(0, 5)));
  $divOverlay.append($("<p>").text("Fecha: " + formatearFecha(datosEvento.fecha)));
  $divOverlay.append($("<p>").text("Capacidad total: " + datosEvento.capacidad));
  $divOverlay.append($("<p>").text("Plazas reservadas: " + datosEvento.plazas_reservadas));
  $divOverlay.append($("<p>").text("Precio por persona: " + datosEvento.precio + " €"));
  
  $divTarjeta.append($divOverlay);
  
  // CONFIGURO EL BOTÓN DE ACCIÓN SEGÚN LA DISPONIBILIDAD QUE TENGA EL EVENTO
  let disponibilidad = datosEvento.capacidad - datosEvento.plazas_reservadas;
  let reservaExistente = (datosSesion && datosSesion.sesion_iniciada)
    ? obtenerReservaDetalleLocal(datosEvento.id)
    : null;
  
  let $btnAccion = $("<button>");
  
  if (reservaExistente) {
    configurarBotonReservado($btnAccion, datosEvento, reservaExistente);
  } else if (disponibilidad <= 0) {
    configurarBotonCompleto($btnAccion);
  } else {
    configurarBotonReservar($btnAccion, datosEvento);
  }
  
  $divTarjeta.append($btnAccion);
  return $divTarjeta;
}

/**
MÉTODO PARA MOSTRAR LA RESERVA DEL USUARIO DE EL EVENTO AL QUE SE LE HACE CLICK
 */
function configurarBotonReservado($boton, datosEvento, reservaExistente) {
  $boton.text("Ya reservado")
    .addClass("boton-ya-reservado")
    .css("cursor", "pointer")
    .on("click", function() {
      let mensajeDetalle = "Ya tienes una reserva para este evento:<br>" +
        "<strong>Evento:</strong> " + datosEvento.nombre + "<br>" +
        "<strong>Tipo:</strong> " + datosEvento.tipo_evento + "<br>" +
        "<strong>Fecha:</strong> " + formatearFecha(datosEvento.fecha) + "<br>" +
        "<strong>Horario:</strong> " + datosEvento.hora_inicio.substring(0,5) + " - " + datosEvento.hora_fin.substring(0,5) + "<br>" +
        "<strong>Participantes:</strong> " + reservaExistente.cantidad + "<br>" +
        "<strong>Total:</strong> " + reservaExistente.total + " €";
      mostrarNotificacion(mensajeDetalle);
    });
}

/**
MÉTODO PARA MOSTRAR QUE EL EVENTO YA ESTA COMPLETO
 */
function configurarBotonCompleto($boton) {
  $boton.text("Completo")
    .addClass("boton-completo")
    .on("click", function() {
      mostrarNotificacion("El evento ya está completo.");
    });
}

/**
MÉTODO PARA HACER LA RESERVA DEL EVENTO
 */
function configurarBotonReservar($boton, datosEvento) {
  $boton.text("Reservar")
    .addClass("boton-evento")
    .on("click", function() {
      if (!(datosSesion && datosSesion.sesion_iniciada)) {
        mostrarNotificacion("Para reservar, primero debes iniciar sesión.");
      } else {
        continuarReservaEvento(datosEvento);
      }
    });
}

/**
MÉTODO PARA PINTAR LOS EVENTOS DISPONIBLES
 */
function pintarEventos(data) {
  var $contenedor = $("#eventos-container");

  if (!$contenedor.length) {
    console.error("Contenedor de eventos no encontrado");
    return;
  }

  $contenedor.fadeOut(200, function() {
    if (!Array.isArray(data) || data.length === 0) {
      mostrarMensajeSinEventos($contenedor);
    } else {
      vaciarYAgregarEventos($contenedor, data);
    }
    $contenedor.fadeIn(200);
  });
}

/**
MÉTODO PARA MOSTRAR EL MENSAJE DE QUE NO HAY EVENTOS
 */
function mostrarMensajeSinEventos($contenedor) {
  $contenedor.html("<p class='no-eventos'>No existen eventos disponibles.</p>");
}

/**
MÉTODO PARA PINTAR TODOS LOS EVENTOS
 */
function vaciarYAgregarEventos($contenedor, eventos) {
  $contenedor.empty();
  $.each(eventos, function(i, evento) {
    $contenedor.append(crearTarjetaEvento(evento));
  });
}

/**
MÉTODO PARA OBTENER LOS EVENTOS DISPONIBLES POR AJAX, Y DESPUÉS PINTARLOS
 */
function pintarTodosLosEventos() {
  return new Promise((resolve, reject) => {
    console.log("Cargando eventos desde servidor...");
    
    $.ajax({
      url: base_url + "eventos/proximos",
      method: "GET",
      dataType: "json",
      success: function(data) {
        datosEventos = data;
        pintarEventos(datosEventos);
        resolve(); // Indicamos que la operación terminó bien
      },
      error: function(error) {
        console.error("Error al obtener eventos:", error);
        $("#eventos-container").html("<p>Error al cargar eventos.</p>");
        reject(error); // Indicamos que hubo un error
      }
    });
  });
}

/**
 MÉTODO POSTERIOR AL CLICK EN EL BOTÓN DE RESERVAR, COMPRUEBA QUE SE CUMPLEN LAS CONDICIONES
 MÍNIMAS ANTES DE DEJAR CONTINUAR AL USUARIO.
 POR ÚLTIMO, CON OTRO MÉTODO GUARDO UN OBJETO CON DETALLES, Y USO MOSTRAR INPUT RESERVA,
 COMO TERCER PARÁMETRO, SE PASA LA VARIABLE CANTIDAD, QUE VIENE COMO CALLBACK DE EL MÉTODO 
 MOSTRAR INPUT RESERVA AL ACCIONAR EL ONCONFIRM Y SE LE PASA A PROCESARCONFIRMACIONRESERVA, Y 
 COMO CUARTO PARÁMETRO SE LE PASA UN CANCELAR SIMPLE AL ACCIONAR EL ONCANCEL
 */
function continuarReservaEvento(evento) {
  if (!validarEventoParaReserva(evento)) return;
  
  const datosReserva = prepararDatosReserva(evento);
  mostrarInputReserva(datosReserva.mensaje, datosReserva.plazasDisponibles, 
    (cantidad) => procesarConfirmacionReserva(evento, cantidad, datosReserva),
    () => mostrarNotificacion("Reserva cancelada.")
  );
}

/**
MÉTODO PARA HACER VALIDACIONES BÁSICAS PARA COMPROBAR SI EL USUARIO PUEDE RESERVAR
ESE EVENTO SELECCIONADO
 */
function validarEventoParaReserva(evento) {
  if (!evento) {
    mostrarNotificacion("Por favor, seleccione un evento para la reserva.");
    return false;
  }

  if (!evento.hora_inicio || !evento.hora_fin) {
    mostrarNotificacion("El evento no tiene horarios definidos.");
    return false;
  }

  const fechaMaxima = new Date();
  fechaMaxima.setFullYear(fechaMaxima.getFullYear() + 2);
  const fechaEvento = new Date(evento.fecha);

  if (fechaEvento > fechaMaxima) {
    mostrarNotificacion("No se pueden hacer reservas para más de 2 años en el futuro.");
    return false;
  }

  const plazasDisponibles = parseInt(evento.capacidad) - parseInt(evento.plazas_reservadas);
  if (plazasDisponibles <= 0) {
    mostrarNotificacion("Este evento ya está completo.");
    return false;
  }

  return true;
}

/**
PREPARO EL OBJETO, QUE USARÉ POSTERIORMENTE PARA USOS COMO MOSTRAR EL MENSAJE PARA 
AGREGAR LA CANTIDAD DE PERSONAS PARA EL EVENTO
 */
function prepararDatosReserva(evento) {
  const fechaFormateada = formatearFecha(evento.fecha);
  const horario = `${evento.hora_inicio.substring(0, 5)} - ${evento.hora_fin.substring(0, 5)}`;
  const plazasDisponibles = parseInt(evento.capacidad) - parseInt(evento.plazas_reservadas);
  const precioUnitario = parseFloat(evento.precio);

  return {
    fechaFormateada,
    horario,
    plazasDisponibles,
    precioUnitario,
    mensaje: `Ingrese la cantidad de personas:<br>
             <strong>${evento.nombre}</strong><br>
             Tipo: ${evento.tipo_evento}<br>
             Fecha: ${fechaFormateada}<br>
             Horario: ${horario}<br>
             Precio por persona: <strong>${precioUnitario.toFixed(2)} €</strong><br>
             Plazas disponibles: <strong>${plazasDisponibles}</strong>`
  };
}

/**
MÉTODO PARA CONFIRMAR YA DEFINITIVAMENTE LA RESERVA POR PARTE DEL USUARIO, EN CASO AFIRMATIVO,
PREPARO OBJETO PARA GUARDARLO YA EN SESIÓN, Y POSTERIORMENTE REDIRIGIR A LA PASARELA DE PAGO
 */
function procesarConfirmacionReserva(evento, cantidad, datosReserva) {
  const totalGastado = (datosReserva.precioUnitario * cantidad).toFixed(2);
  
  const datosConfirmacion = {
    nombre: evento.nombre,
    tipo: evento.tipo_evento,
    fecha: datosReserva.fechaFormateada,
    horario: datosReserva.horario,
    participantes: cantidad,
    precio: `${datosReserva.precioUnitario.toFixed(2)}`,
    total: `${totalGastado} €`
  };

  mostrarConfirmacion("¿Desea proceder con la reserva?", datosConfirmacion, () => {
    procesarReserva(evento, cantidad, totalGastado, datosReserva);
  });
}

/**
MÉTODO PARA FORMAR OBJETO DEFINITIVO QUE GUARDO EN SESIÓN PREVIO A LA PASARELA DE PAGO
 */
function procesarReserva(evento, cantidad, totalGastado, datosReserva) {
  const reservaData = {
    tipo: 'evento',
    evento_id: evento.id,
    tipo_evento: evento.tipo_evento,
    evento_nombre: evento.nombre,
    descripcion_evento: evento.descripcion || null,
    fecha: evento.fecha,
    franja_horaria_id: evento.franja_horaria_id,
    horario_texto: datosReserva.horario,
    num_participantes: cantidad,
    cantidad: totalGastado,
    total: totalGastado,
    precio_unitario: datosReserva.precioUnitario.toFixed(2),
    usuario_id: datosSesion.id,
    nombre_usuario: datosSesion.nombre,
    metodo_pago: null,
    source: 'web_v3',
    fecha_formateada: datosReserva.fechaFormateada
  };
  
  enviarReservaAlBackend(reservaData);
}

/**
MÉTODO QUE LLAMA POR AJAX AL MÉTODO DEL CONTROLADOR DE PAGO
PARA GUARDAR LA RESERVA EN SESIÓN Y REDIRIGIR AL USUARIO A LA
PASARELA DE PAGO
 */
function enviarReservaAlBackend(reservaData) {
  const formData = new FormData();
  Object.keys(reservaData).forEach(key => {
    formData.append(key, reservaData[key]);
  });

  $.ajax({
    url: base_url + "pago/guardar_reserva",
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    success: function(response) {
      if (response.success) {
        redirigirAPasarela();
      } else {
        mostrarNotificacion(response.error || "Error al procesar la reserva");
      }
    },
    error: function(xhr, status, error) {
      mostrarNotificacion("Error al procesar la reserva: " + error);
    }
  });
}

/**
MÉTODO PARA LA REDIRECCIÓN A LA PASARELA
 */
function redirigirAPasarela() {
  $.ajax({
    url: base_url + "pago/pasarela",
    method: 'GET',
    success: function(response) {
      if (response.success) {
        window.location.href = response.redirect;
      } else {
        mostrarNotificacion(response.error || "Error al acceder a la pasarela");
      }
    },
    error: function(xhr, status, error) {
      mostrarNotificacion("Error al redirigir a pasarela: " + error);
    }
  });
}

/**
MÉTODO PARA INICIALIZACIÓN DEL AUTOCOMPLETE, EL REQUEST Y EL RESPONSE SON PARAMETROS QUE GENERA JQUERY UI
REQUEST TIENE EL TEXTO QUE QUIERO BUSCAR Y EL RESPONSE SON LOS RESULTADOS QUE ME VA A DEVOLVER EL FILTRAR EVENTOS
PARA AUTOCOMPLETE, YA QUE ES UNA FUNCIÓN CALLBACK
EL SELECT SE ENCARGA DE QUE CUANDO SELECCIONO UNA DE LAS OPCIONES DEL DESPLEGABLE DEL BUSCAR, ES QUE USO EL APLICAR FILTRAR
EL FOCUS BLOQUEA LA ACTUALIZACIÓN AUTOMÁTICA DEL INPUT

 */
function inicializarAutocompleteEventos() {
  $("#filtro-nombre-evento").autocomplete({
    source: (request, response) => filtrarEventosParaAutocomplete(request, response),
    minLength: 1,
    select: (event, ui) => setTimeout(aplicarFiltros, 100),
    focus: (event, ui) => event.preventDefault()
  });
}

/**
EN ESTE MÉTODO APLICO TODOS LOS FILTROS QUE TENGO DISPONIBLES EN CONJUNTO, ESTÁN SEPARADOS
POR MÉTODOS MÁS PEQUEÑOS PARA MAYOR USABILIDAD.
COMO HE DICHO ANTES, DEVUELVO EL RESPONSE CON EL LABEL Y EL VALUE, QUE SON LOS EVENTOS
QUE ADMITEN EL FILTRO, QUE SON LAS OPCIONES QUE SALEN EN EL BUSCAR.
 */
function filtrarEventosParaAutocomplete(request, response) {
  const termino = request.term.toLowerCase();
  const tipoSeleccionado = $("#filtro-tipo-evento").val();
  const soloNoCompletos = $("#filtro-no-completos").is(":checked");
  
  // DATOS EVENTOS ES EL ARRAY GLOBAL QUE TIENE LOS EVENTOS
  const resultados = datosEventos.filter(evento => 
    filtrarPorTipo(evento, tipoSeleccionado) &&
    filtrarPorNombre(evento, termino) &&
    filtrarPorDisponibilidad(evento, soloNoCompletos)
  );

  ordenarEventos(resultados, $("#filtro-fecha-evento").val());
  
  response($.map(resultados, evento => ({
    label: `${evento.nombre} (${evento.tipo_evento})`,
    value: evento.nombre
  })));
}

// MÉTODO PARA APLICAR LOS FILTROS, ORDENARLOS, Y POR ÚLTIMO, PINTARLOS.
function aplicarFiltros() {
  const filtros = obtenerValoresFiltros();
  
  if (!validarElementosFiltros()) {
    console.error("Elementos de filtro no encontrados");
    return;
  }
  // PASO EL ARRAY GLOBAL Y LOS INPUTS/CHECKS DE FILTROS
  let eventosFiltrados = filtrarEventos(datosEventos, filtros);
  // ORDENO ESE ARRAY SEGÚN ORDEN INDICADO
  eventosFiltrados = ordenarEventos(eventosFiltrados, filtros.ordenFecha);
  
  console.log("Eventos filtrados:", eventosFiltrados);
  // PINTO EL EVENTO
  pintarEventos(eventosFiltrados);
}

/**
MÉTODO PARA OBTENER DIRECTAMENTE VALORES DE LOS FILTROS
 */
function obtenerValoresFiltros() {
  return {
    tipoSeleccionado: $("#filtro-tipo-evento").val(),
    nombreFiltro: $("#filtro-nombre-evento").val().toLowerCase().trim(),
    soloNoCompletos: $("#filtro-no-completos").is(":checked"),
    ordenFecha: $("#filtro-fecha-evento").val()
  };
}

/**
COMPROBAR QUE EXISTEN FILTROS
 */
function validarElementosFiltros() {
  return $("#filtro-tipo-evento").length && 
         $("#filtro-nombre-evento").length && 
         $("#filtro-no-completos").length &&
         $("#filtro-fecha-evento").length; 
}

/**
MÉTODO PARA IR FILTRANDO EVENTO POR EVENTO SEGÚN LOS FILTROS QUE TENGO,
AL FINAL, RETORNO EL ARRAY FILTRADO CON EL RESULTADO.
 */
function filtrarEventos(eventos, {tipoSeleccionado, nombreFiltro, soloNoCompletos}) {
  return eventos.filter(evento => {
    const cumpleTipo = tipoSeleccionado === "todos" || 
                      String(evento.tipo_evento_id) === String(tipoSeleccionado);
    const cumpleNombre = nombreFiltro === "" || 
                        evento.nombre.toLowerCase().includes(nombreFiltro);
    const tienePlazas = !soloNoCompletos || 
                       (parseInt(evento.capacidad) - parseInt(evento.plazas_reservadas) > 0);
    
    return cumpleTipo && cumpleNombre && tienePlazas;
  });
}

/**
MÉTODO PARA ORDENAR LOS EVENTOS EN RECIENTE O ANTIGUO
 */
function ordenarEventos(eventos, ordenFecha) {
  if (ordenFecha === "recientes") {
    return [...eventos].sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
  } 
  else if (ordenFecha === "antiguas") {
    return [...eventos].sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
  }
  return eventos;
}

/**
MÉTODO PARA FILTRAR POR TIPO
 */
function filtrarPorTipo(evento, tipoSeleccionado) {
  return tipoSeleccionado === "todos" || 
         String(evento.tipo_evento_id) === String(tipoSeleccionado);
}
/**
MÉTODO PARA FILTRAR POR NOMBRE
 */
function filtrarPorNombre(evento, termino) {
  return evento.nombre.toLowerCase().includes(termino);
}
/**
MÉTODO PARA FILTRAR POR DISPONIBILIDAD
 */
function filtrarPorDisponibilidad(evento, soloNoCompletos) {
  return !soloNoCompletos || 
         (parseInt(evento.capacidad) - parseInt(evento.plazas_reservadas) > 0);
}

/**
MÉTODO QUE MEDIANTE LLAMADA AJAX, RECOGE TODOS LOS TIPOS DE EVENTOS, Y LOS METE
COMO OPTIONS AL SELECT DE TIPO EVENTOS, PARA PODER FILTRAR
 */
function rellenarSelectTipoEventos() {
  $.ajax({
    url: base_url + "tipoEventos/getTipoEventos",
    method: "GET",
    dataType: "json",
    success: function(data) {
      const $selectTipo = $("#filtro-tipo-evento");
      if (!$selectTipo.length) return;
      
      $selectTipo.html('<option value="todos">Todos</option>');
      data.forEach(tipo => {
        $selectTipo.append($("<option>").val(tipo.slug || tipo.id).text(tipo.nombre));
      });
    },
    error: function(error) {
      console.error("Error al cargar tipos de evento:", error);
    }
  });
}

/**
MÉTODO PARA TENER UBICADO TODOS LOS POSIBLES EVENTOS DE LOS FILTROS
 */
function configurarEventosFiltros() {
  $("#filtro-tipo-evento, #filtro-fecha-evento").on("change", aplicarFiltros);
  $("#filtro-nombre-evento").on("input", aplicarFiltros);
  $("#filtro-no-completos").on("change", aplicarFiltros);

  $("#btn-limpiar-filtros").on("click", function() {
    $("#filtro-tipo-evento").val("todos");
    $("#filtro-fecha-evento").val("recientes");
    $("#filtro-nombre-evento").val("");
    $("#filtro-no-completos").prop("checked", false);
    aplicarFiltros();
  });
}

/**
MÉTODO PARA CARGAR LAS RESERVAS QUE TENGA EL USUARIO EN EL CASO
DE QUE HAYA INICIADO SESIÓN
 */
function cargarReservasUsuario() {
  return new Promise((resolve, reject) => {
    if (!(datosSesion && datosSesion.sesion_iniciada)) {
      pintarTodosLosEventos().then(resolve).catch(reject);
      return;
    }

    $.ajax({
      url: base_url + "Usuario/obtenerReservasEventosActuales",
      method: "GET",
      dataType: "json",
      success: function(data) {
        reservasUsuario = data;
        console.log("Reservas del usuario:", reservasUsuario);
        pintarTodosLosEventos().then(resolve).catch(reject);
      },
      error: function(error) {
        console.error("Error al obtener reservas:", error);
        pintarTodosLosEventos().then(resolve).catch(reject);
      }
    });
  });
}

/**
MÉTODO PARA INICIALIZAR TODOS LOS MÉTODOS QUE QUIERO QUE FUNCIONEN 
AL INICIO EN EL DOCUMENT.READY
 */
async function inicializarApartadoEvento() {
  console.log("Inicializando eventos...");

  try {
    // PRIMERO SE ESPERA A QUE SE CARGUEN LAS RESERVAS Y EVENTOS
    await cargarReservasUsuario();
    
    // CONFIGURACIÓN COMPLETA DE LOS FILTROS Y DE LA INTERFAZ DEL USUARIO
    rellenarSelectTipoEventos();
    inicializarAutocompleteEventos();
    configurarEventosFiltros();
    resetearValoresFiltros();

    $("#filtro-tipo-evento").val("todos");
    $("#filtro-fecha-evento").val("recientes");
    
    console.log("Módulo de eventos inicializado correctamente");
  } catch (error) {
    console.error("Error al inicializar módulo de eventos:", error);
    mostrarNotificacion("Error al cargar los eventos. Por favor, recarga la página.");
  }
}

/**
MÉTODO PARA RESETEO DE FILTROS
 */
function resetearValoresFiltros() {
  $("#filtro-tipo-evento").val("todos");
  $("#filtro-nombre-evento").val("");   
  $("#filtro-no-completos").prop("checked", false);
  $("#filtro-fecha-evento").val("recientes"); 
}

// DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL DE EVENTOS DE AYUDA
$(document).on('click', '#btnAyudaEventos', function (event) {
  event.preventDefault();
  $('#modalAyudaEventos').modal('show');
});

// AL CARGARSE LA PÁGINA
$(document).ready(async function() {
  // MÉTODO QUE INICIALIZA TODO EL APARTADO DE EVENTOS
  await inicializarApartadoEvento();

  // SI DESDE EL APARTADO DE horarioEvento SE PASA UNA VARIABLE 
  // EVENTO CON EL NOMBRE DEL EVENTO, EL FILTRO DEL BUSCADOR
  // LO PONE, Y DESPUÉS ELIMINA LA VARIABLE URL

  // SE PROCESAN LOS PARÁMETROS DE URL DESPUÉS DE LA INICIALIZACIÓN
  const params = new URLSearchParams(window.location.search);
  const nombreEvento = params.get("evento");

  // SI HAY UN NOMBRE DE EVENTO, SE PONE EN EL INPUT DE BÚSQUEDA ESE
  // CONTENIDO, Y SE HACE LA BÚSQUEDA DE ESE EVENTO
  if (nombreEvento) {
    const $inputFiltro = $("#filtro-nombre-evento");
    $inputFiltro.val(nombreEvento);
    aplicarFiltros();

    // DESPUÉS DE QUE SE HAGA LA BÚSQUEDA, BORRO LAS VARIABLES DE URL
    // Y QUE SI SE RECARGA LA PÁGINA, TAMPOCO SALGAN DE NUEVO
    const urlSinEvento = new URL(window.location);
    urlSinEvento.searchParams.delete("evento");
    window.history.replaceState({}, document.title, urlSinEvento);
  }
});
