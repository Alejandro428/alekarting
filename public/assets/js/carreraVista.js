// VARIABLES GLOBALES
var celdaDiaSeleccionado = null; // CELDA DEL DÍA SELECCIONADO
var divHorarioSeleccionado = null; // CELDA DEL HORARIO SELECCIONADO
var fechaSeleccionadaGlobal = null; // FECHA SELECCIONADA
var reservasCarrerasUsuario = []; // ARRAY CON LAS RESERVAS DEL USUARIO

var fechaHoy = new Date(); 
var fechaControl = new Date();
var totalFranjas = 0;

// Variable global para controlar si se está seleccionando desde URL
let seleccionandoDesdeURL = false;


//MÉTODO PARA MOSTRAR SOLO HORA Y MINUTOS DE EL HORARIO
function formatearHora(hora) {
    return hora.split(":").slice(0, 2).join(":");
}

// MÉTODO PARA USAR LA FECHA EN FORMATO YYYY-MM-DD
function formatearFechaLocal(dateObj) {
    var anio = dateObj.getFullYear();
    var mes = String(dateObj.getMonth() + 1).padStart(2, '0');
    var dia = String(dateObj.getDate()).padStart(2, '0');
    return anio + '-' + mes + '-' + dia;
}

// MÉTODO DE NOTIFICACIÓN NORMAL Y CORRIENTE
function mostrarNotificacion(mensaje) {
    var notificacionExistente = $("#divNotificacion");
    if (notificacionExistente.length) {
        notificacionExistente.remove();
    }
    
    var divNotificacion = $("<div>", { id: "divNotificacion", class: "notificacion" })
                        .html(mensaje);
    
    var botonCerrar = $("<button>").text("Cerrar").on("click", function() {
        divNotificacion.remove();
    });
    
    divNotificacion.append("<br>").append(botonCerrar);
    $("body").append(divNotificacion);
    
    setTimeout(function() {
        if ($("body").has(divNotificacion).length) {
            divNotificacion.remove();
        }
    }, 3000);
}

// MÉTODO DE CONFIRMACIÓN, MUESTRO LA CARRERA A RESERVAR, TIENE DOS MÉTODOS COMO CALLBACKS
// DE CONFIRMACIÓN Y CANCELACIÓN
function mostrarConfirmacion(mensaje, datosReserva, onConfirm, onCancel) {
    var confirmacionExistente = $("#divConfirmacion");
    if (confirmacionExistente.length) {
        confirmacionExistente.remove();
    }

    var divConfirmacion = $("<div>", {
        id: "divConfirmacion",
        class: "notificacion",
        css: { 
            padding: "10px",
            maxHeight: "80vh",
            display: "flex",
            flexDirection: "column",
            overflow: "hidden"
        }
    });

    var contenido = $("<div>", {
        css: {
            overflowY: "auto",
            overflowX: "hidden",
            padding: "0 5px",
            flex: "1"
        }
    });

    var mensajeCompleto = mensaje + "<br><br>" +
        "<strong>Pista:</strong> " + datosReserva.pista + "<br>" +
        "<strong>Participantes:</strong> " + datosReserva.participantes + "<br>" +
        "<strong>Total a gastar:</strong> " + datosReserva.total + "<br>" +
        "<strong>Día:</strong> " + datosReserva.dia + "<br>" +
        "<strong>Hora:</strong> " + datosReserva.hora;

    contenido.html(mensajeCompleto);

    var checkboxLabel = $('<label>', {
        css: {
            display: 'inline-block',
            marginTop: '10px',
            marginBottom: '10px',
            fontSize: '20px',
            cursor: 'pointer',
            userSelect: 'none',
            verticalAlign: 'middle',
            wordWrap: 'break-word'
        }
    }).html(
        `<input type="checkbox" id="aceptarTerminos">
        Acepto los <a href="${urlCondiciones}" target="_blank" style="color: #007bff; text-decoration: underline; cursor: pointer;">términos y condiciones</a>`
    );

    contenido.append("<br><br>").append(checkboxLabel);
    divConfirmacion.append(contenido);

    var botonesContainer = $("<div>", {
        css: {
            display: "flex",
            justifyContent: "space-around", 
            padding: "10px 0",
            marginTop: "10px",
            flexShrink: "0"
        }
    });

    var btnConfirmar = $("<button>").text("Confirmar").on("click", function () {
        var checkbox = $("#aceptarTerminos");
        if (!checkbox.is(":checked")) {
            toastr.error("Debe aceptar los términos y condiciones para poder reservar la carrera", "Error de Validación");
            return;
        }
        if (onConfirm) onConfirm();
        divConfirmacion.fadeOut(400, function () {
            $(this).remove();
        });
    });

    var btnCancelar = $("<button>").text("Cancelar").on("click", function () {
        if (onCancel) onCancel();
        divConfirmacion.fadeOut(400, function () {
            $(this).remove();
        });
    });

    botonesContainer.append(btnConfirmar).append(btnCancelar);
    divConfirmacion.append(botonesContainer);
    $("body").append(divConfirmacion);
}

// MÉTODO PARA MOSTRAR LA CANTIDAD FINAL DE LA CARRERA POR
// PISTA Y CANTIDAD SELECCIONADA
function calcularTotal() {
    var pistaSelect = $("#pistaElegida");
    if (pistaSelect.find("option").length === 0) return;
    
    var opcionSeleccionada = pistaSelect.find("option:selected")[0];
    var precio = parseFloat($(opcionSeleccionada).data("precio")) || 0;
    var participantes = parseInt($("#inputCantidad").val());
    var total = precio * participantes;

    $("#divPrecio").text('Precio: ' + precio.toFixed(2) + '€/persona');
    $("#spanTotal").text('Total: ' + total.toFixed(2) + '€');
}

// MÉTODO PARA SUMAR Y RESTAR AL CAMBIAR CANTIDADES
function configurarControlesCantidad() {
    $("#botonSumar").on("click", function() {
        var input = $("#inputCantidad");
        input.val(parseInt(input.val()) + 1);
        calcularTotal();
    });

    $("#botonRestar").on("click", function() {
        var input = $("#inputCantidad");
        var valor = parseInt(input.val());
        if (valor > 1) { 
            input.val(valor - 1);
            calcularTotal();
        }
    });

    // SI DETECTA QUE NO HAY UN NÚMERO, ASIGNA EL 1
    $("#inputCantidad").on("input", function() {
        if (isNaN(parseInt($(this).val()))) { 
            $(this).val(1);
        }
        calcularTotal();
    });
}

// MÉTODO PARA QUE EN CASO DE EXISTIR SESIÓN INICIADA, RECOGER LAS RESERVAS DE CARRERAS
// DEL USUARIO CON LA SESIÓN ACTUAL
function cargarReservasUsuario() {
    return new Promise((resolve, reject) => {
        if (typeof datosSesion === "undefined" || !datosSesion.sesion_iniciada) {
            resolve();
            return;
        }
        
        $.ajax({
            url: base_url + "Usuario/obtenerReservasCarrerasActuales",
            method: "GET",
            dataType: "json",
            success: function(data) {
                reservasCarrerasUsuario = data;
                resolve();
            },
            error: function(error) {
                console.error("Error al cargar reservas:", error);
                reject(error);
            }
        });
    });
}

// MÉTODO PARA OBTENER LAS RESERVAS DEL USUARIO, RECORRO UN FOR PARA ESCOGER LA RESERVA CONCRETA
function obtenerReservaUsuario(franjaId, fechaStr) {
    if (typeof datosSesion === "undefined" || !datosSesion.sesion_iniciada) return null;
    
    for (var i = 0; i < reservasCarrerasUsuario.length; i++) {
        var r = reservasCarrerasUsuario[i];
        if (String(r.franja_horaria_id) === String(franjaId) && r.fecha === fechaStr) {
            return r;
        }
    }
    return null;
}

// MÉTODO PARA COMPROBAR SI EN ESA FECHA EXISTE UNA RESERVA

function usuarioTieneReservaEnDia(fechaStr) {
    if (typeof datosSesion === "undefined" || !datosSesion.sesion_iniciada) return false;
    
    for (var i = 0; i < reservasCarrerasUsuario.length; i++) {
        if (reservasCarrerasUsuario[i].fecha === fechaStr) {
            return true;
        }
    }
    return false;
}

// MÉTODO PARA GENERAR EL CALENDARIO, PARTICIONADO EN VARIOS MÉTODOS, YA QUE
// TIENE VARIAS PARTES

// MÉTODO PARA GENERAR EL CALENDARIO, PARTICIONADO EN VARIOS MÉTODOS, YA QUE
// TIENE VARIAS PARTES
function generarCalendario(anio, mes) {
    return new Promise((resolve) => {
        // SE REINICIAN LOS SELECTORES
        celdaDiaSeleccionado = null;
        divHorarioSeleccionado = null;
        $("#diaSeleccionado").text("Día seleccionado: Aún no se ha seleccionado");
        $("#fechaSeleccionada").text("Fecha: Aún no se ha seleccionado");
        $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");
        
        var calendario = $("#calendario");
        calendario.empty();
        
        // GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
        generarEncabezadoCalendario(anio, mes);
        
        // GENERAR DIAS DE LA SEMANA
        generarDiasSemana();
        
        // GENERAR TODOS LOS DIAS QUE TIENE ESE MES, Y GENERAR CLICK DE CADA CELDA
        generarCuadriculaCalendario(anio, mes);
        
        // EN EL CASO DE QUE HAYA SESIÓN INICIADA, SE CARGAN TAMBIÉN LAS RESERVAS DEL USUARIO
        cargarDatosReservas(anio, mes).then(resolve);
    });
}

// MÉTODO PARA GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
function generarEncabezadoCalendario(anio, mes) {
    var nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                       "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
    var encabezado = $("<div>").addClass("encabezado-calendario");
    
    var botonAnterior = $("<button>")
        .attr("id", "botonAnteriorMes")  
        .text("<")
        .on("click", function() {
            navegarMesAnterior(anio, mes);
        });
    
    var botonSiguiente = $("<button>")
        .attr("id", "botonSiguienteMes")  
        .text(">")
        .on("click", function() {
            navegarMesSiguiente(anio, mes);
        });
    
    var titulo = $("<span>").addClass("titulo-calendario")
                   .text(nombresMeses[mes] + " " + anio);
    
    encabezado.append(botonAnterior).append(titulo).append(botonSiguiente);
    $("#calendario").append(encabezado);
}

// MÉTODO PARA IR AL MES ANTERIOR
function navegarMesAnterior(anio, mes) {
  var nuevoAnio = anio;
  var nuevoMes = mes - 1;
  
  if (nuevoMes < 0) {
      nuevoMes = 11;
      nuevoAnio--;
  }
  
  // NO PERMITIR IR A MESES PASADOS
  if (nuevoAnio < fechaHoy.getFullYear() || 
     (nuevoAnio === fechaHoy.getFullYear() && nuevoMes < fechaHoy.getMonth())) {
      return;
  }
  
  fechaControl.setFullYear(nuevoAnio);
  fechaControl.setMonth(nuevoMes);
  generarCalendario(nuevoAnio, nuevoMes);
}

// MÉTODO PARA IR AL SIGUIENTE MES
function navegarMesSiguiente(anio, mes) {
  fechaControl.setMonth(mes + 1);
  generarCalendario(fechaControl.getFullYear(), fechaControl.getMonth());
}

// MÉTODO PARA GENERAR LOS DÍAS DE LA SEMANA
function generarDiasSemana() {
  var diasSemana = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
  var filaDias = $("<div>").addClass("fila-dias-calendario");
  
  for (var i = 0; i < diasSemana.length; i++) {
      filaDias.append($("<div>").addClass("nombre-dia-calendario").text(diasSemana[i]));
  }
  
  $("#calendario").append(filaDias);
}

// MÉTODO PARA GENERAR TODOS LOS DIAS QUE TIENE ESE MES, Y GENERAR CLICK DE CADA CELDA
function generarCuadriculaCalendario(anio, mes) {
  var primerDia = new Date(anio, mes, 1).getDay();
  // Ajustar para que el lunes sea el primer día (0=Lun, 6=Dom)
  var primerDiaAjustado = (primerDia === 0) ? 6 : primerDia - 1;
  var cuadricula = $("<div>").addClass("cuadricula-calendario");
  var diasDelMes = new Date(anio, mes + 1, 0).getDate();
  
  // Limpiar los horarios que existan en ese momento
  $("#timeSlotsContainer").empty();
  
  // Días vacíos al inicio
  for (var i = 0; i < primerDiaAjustado; i++) {
      cuadricula.append($("<div>").addClass("celda-calendario vacio"));
  }
  
  // Días del mes
  for (var d = 1; d <= diasDelMes; d++) {
      var celda = $("<div>").addClass("celda-calendario")
                   .text(d)
                   .css("background-color", "grey")
                   .attr("data-selectable", "true");
      
      // MÉTODO PARA CONTROLAR EL CLICK
      celda.on("click", function() {
          manejarClickDia(this, anio, mes);
      });
      
      cuadricula.append(celda);
  }
  
  $("#calendario").append(cuadricula);
}


//MÉTODO PARA MANEJAR EL CLICK
function manejarClickDia(celda, anio, mes) {
    var $celda = $(celda);

    // DESACTIVAR TEMPORALMENTE EL CLICK EN TODAS LAS CELDAS PARA EVITAR MÚLTIPLES ACTIVACIONES
    $(".celda-calendario").off("click");

    // DESACTIVAR LOS BOTONES DE NAVEGACIÓN DE MES
    $("#botonAnteriorMes").off("click");
    $("#botonSiguienteMes").off("click");

    // SI NO ES SELECCIONABLE, MUESTRO UN MENSAJE AL USUARIO
    if ($celda.attr("data-selectable") === "false") {
        var tipo = $celda.attr("data-tipo");
        var mensaje = tipo === "pasado" ? "No se pueden seleccionar días pasados." : 
                     "Este día no está disponible para seleccionar.";
        mostrarNotificacion(mensaje);

        // REACTIVAR EL CLICK EN TODAS LAS CELDAS AUNQUE NO SE HAGA LA CARGA PORQUE NO ES SELECCIONABLE
        $(".celda-calendario").on("click", function() {
            manejarClickDia(this, anio, mes);
        });

        // REACTIVAR LOS BOTONES DE NAVEGACIÓN DE MES
        $("#botonAnteriorMes").on("click", function() {
            navegarMesAnterior(anio, mes);
        });
        $("#botonSiguienteMes").on("click", function() {
            navegarMesSiguiente(anio, mes);
        });

        return;
    }

    // ESTO ES PARA DESELECCIONAR EL DÍA ANTERIOR, ME GUARDO EL ESTADO QUE TENÍA EL DÍA, YA QUE
    // AL SER SELECCIONADO SE PINTA DE NARANJA, PERO SI SELECCIONO OTRO DÍA, QUIERO QUE REGRESE
    // A SU COLOR ORIGINAL
    if (celdaDiaSeleccionado && celdaDiaSeleccionado !== celda) {
        $(celdaDiaSeleccionado).css("background-color", $(celdaDiaSeleccionado).data("status"));
    }

    // AHORA SE SELECCIONA EL DIA NUEVO Y SE PONE DE NARANJA
    celdaDiaSeleccionado = celda;
    $celda.css("background-color", "orange");

    var dia = parseInt($celda.text());
    $("#diaSeleccionado").text("Día seleccionado: " + dia);

    fechaSeleccionadaGlobal = new Date(anio, mes, dia);
    $("#fechaSeleccionada").text("Fecha: " + fechaSeleccionadaGlobal.toLocaleDateString());

    divHorarioSeleccionado = null;
    $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");

    // CARGA DE HORARIOS DE ESE DÍA
    cargarHorarios(anio, mes, dia).finally(() => {
        // REACTIVAR EL CLICK EN TODAS LAS CELDAS DESPUÉS DE QUE TERMINE LA CARGA
        $(".celda-calendario").on("click", function() {
            manejarClickDia(this, anio, mes);
        });

        // REACTIVAR LOS BOTONES DE NAVEGACIÓN DE MES
        $("#botonAnteriorMes").on("click", function() {
            navegarMesAnterior(anio, mes);
        });
        $("#botonSiguienteMes").on("click", function() {
            navegarMesSiguiente(anio, mes);
        });
    });
}

// MÉTODO PARA CARGAR TODAS LAS RESERVAS DE CARRERAS QUE EXISTAN
function cargarDatosReservas(anio, mes) {
    return new Promise((resolve) => {
        $.ajax({
            url: base_url + "calendario/getReservasCountCarreras",
            method: "GET",
            data: { anio: anio, mes: mes + 1 },
            dataType: "json",
            success: function(data) {
                // SI DEVUELVE INFORMACIÓN, SE PINTAN LAS CELDAS DEL CALENDARIO
                actualizarColoresCalendario(anio, mes, data);
                resolve();
            },
            error: function(error) {
                console.error("Error al cargar reservas:", error);
                resolve();
            }
        });
    });
}

// MÉTODO PARA PINTAR LAS CELDAS DE LOS DÍAS DEL CALENDARIO
function actualizarColoresCalendario(anio, mes, datosReservas) {
  $(".celda-calendario:not(.vacio)").each(function() {
      var dia = parseInt($(this).text());
      var fechaCelda = new Date(anio, mes, dia);
      var hoy = new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), fechaHoy.getDate());
      // DATOS RESERVAS ES EL DATA QUE HA GENERADO EL AJAX DE GET RESERVAS COUNT CARRERAS
      // TIENE TODOS LOS DÍAS DEL MES, CON LAS RESERVAS QUE HAY HECHAS CADA DÍA
      var fechaStr = formatearFechaLocal(fechaCelda);
      var count = datosReservas[dia] || 0;
      var color;

      // SI EL DÍA ES ANTERIOR AL ACTUAL, LA CELDA SE PINTA ROJA Y NO ES SELECCIONABLE
      if (fechaCelda < hoy) {
          color = "red";
          $(this).attr("title", "No se pueden seleccionar días pasados");
          $(this).attr("data-selectable", "false");
          $(this).attr("data-tipo", "pasado");
      }
      // SI EL DÍA ESTA RESERVADO, SE PINTA DE UN COLOR REPRESENTATIVO Y EL CLICK ES PULSABLE
      else if (usuarioTieneReservaEnDia(fechaStr)) {
          color = "lightblue";
          $(this).attr("title", "Tienes una reserva en este día");
          $(this).attr("data-selectable", "true");
      } 
      // SI EL DÍA TIENE RESERVAS A TOPE, SE PINTA DE ROJO TAMBIÉN Y NO SE HACE SELECCIONABLE
      else if (totalFranjas > 0 && count === totalFranjas) {
          color = "red";
          $(this).attr("title", "Día totalmente reservado");
          $(this).attr("data-selectable", "false");
          $(this).attr("data-tipo", "reservado");
      } 
      // SI NO HAY RESERVAS EN ESE DÍA, SE PINTA VERDE Y SE HACE SELECCIONABLE
      else if (count === 0) {
          color = "green";
          $(this).attr("data-selectable", "true");
      }
      // SI NO CUMPLE NINGUNO DE LOS CASOS ANTERIORES, ES QUE TIENE ENTRE +0 RESERVAS Y - DEL MÁXIMO, POR LO QUE SE PINTA GRIS 
      else {
          color = "grey";
          $(this).attr("data-selectable", "true");
      }
      
      $(this).css("background-color", color);
      $(this).data("status", color);
  });
}

// MÉTODO PARA CARGAR LOS HORARIOS DEL DÍA SELECCIONADO EN CASO DE QUE SEA SELECCIONABLE
// SE HACE UN AJAX PARA OBTENER TODOS LOS HORARIOS DE ESE DÍA
function cargarHorarios(anio, mes, dia) {
    return new Promise((resolve, reject) => {
        divHorarioSeleccionado = null;
        $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");
        
        // FECHA EN FORMATO YYYY-MM-DD
        var fecha = anio + '-' + String(mes + 1).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
        // CONTENEDOR DONDE SE VAN A CARGAR LAS TARJETAS DE LOS HORARIOS
        var contenedor = $("#timeSlotsContainer");
        // MENSAJE QUE SE MUESTRA ANTES DE PINTAR HORARIOS
        contenedor.html("");
        
        $.ajax({
            url: base_url + 'calendario/getHorariosDia',
            method: "GET",
            data: { fecha: fecha },
            dataType: "json",
            success: async function(data) {
                // SI ES CORRECTO EL AJAX, SE CARGAN LOS HORARIOS CON ESTE MÉTODO
                //  EL DATA CONTIENE UN ARRAY DE HORARIOS, CADA HORARIO CONTIENE ESTO:
                // 'franja_horaria_id' => $id,
                //  'hora_inicio'       => $horaInicio,
                //  'hora_fin'          => $franja['hora_fin'],
                //  'descripcion'       => $franja['descripcion'],
                //  'estado'            => $estado,
                // LA VARIABLE SLOT UTILIZADA MÁS ADELANTE USA ESTA VARIABLE
                // MÉTODO PARA PINTAR LOS HORARIOS DE EL DÍA ESCOGIDO
                
                try {
                    // Esperamos a que termine la animación de mostrar horarios
                    await mostrarHorarios(data, fecha, contenedor);
                    resolve(data);
                } catch (error) {
                    reject(error);
                }
            },
            error: function(error) {
                console.error("Error cargando horarios:", error);
                contenedor.html("Error al cargar horarios.");
                reject(error);
            }
        });
    });
}


function mostrarHorarios(horarios, fecha, contenedor) {
    return new Promise((resolver) => {
        contenedor.empty();
        // SIN HORARIOS (NO DEBERÍA DE ENTRAR, YA QUE UN DÍA SIN HORARIOS DISPONIBLES)
        // (DEBERÍA DE ESTAR COMO NO SELECCIONABLE)
        if (horarios.length === 0) {
            contenedor.html("No hay horarios disponibles para este día.");
            resolver();  // resuelve inmediatamente si no hay horarios
            return;
        }

        let retraso = 0; // tiempo que espera antes de mostrar cada horario (animación secuencial)
        const intervaloEntreTarjetasMs = 80; // milisegundos entre cada animación de horario
        let cantidadAnimacionesTerminadas = 0; // cuenta cuántas animaciones han finalizado

        if (seleccionandoDesdeURL) {
            // Si estamos cargando desde URL, mostrar todos los horarios sin retrasos ni animaciones secuenciales
            horarios.forEach((slot) => {
                var divHorario = crearDivHorario(slot, fecha);
                // Añadimos el horario al contenedor
                contenedor.append(divHorario);
            });
            resolver();  // resolvemos directamente ya que no hay animaciones
        } else {
            //  Fallback de seguridad en caso de que alguna animación no dispare "transitionend"
            const duracionTransicionMs = 400; // duración real en tu CSS (ajústalo si es diferente)
            const tiempoMaximoEsperaAnimaciones = (horarios.length - 1) * intervaloEntreTarjetasMs + duracionTransicionMs + 200;
            const temporizadorSeguridad = setTimeout(() => {
                if (cantidadAnimacionesTerminadas < horarios.length) {
                    //console.warn(" Se forzó resolver() en mostrarHorarios por timeout de seguridad");
                    // SE RESUELVE SI TARDA MUCHO LA ANIMACIÓN
                    resolver();
                }
            }, tiempoMaximoEsperaAnimaciones);

            // SE PINTA CADA HORARIO DE FORMA PROGRESIVA CON ANIMACIÓN CSS
            horarios.forEach((slot, indice) => {
                setTimeout(() => {
                    var divHorario = crearDivHorario(slot, fecha);

                    // Añadimos clase inicial para animación CSS (invisible y desplazado)
                    divHorario.addClass("inicial");

                    // Añadimos el horario al contenedor
                    contenedor.append(divHorario);

                    // Forzamos reflow para que el navegador detecte el DOM antes de activar transición
                    void divHorario[0].offsetWidth;

                    // Removemos la clase inicial para activar la transición hacia estado visible y sin desplazamiento
                    divHorario.removeClass("inicial");

                    // Escuchar cuando termina la transición de opacidad para contar animaciones completadas
                    divHorario.one("transitionend", (e) => {
                        if (e.originalEvent.propertyName === "opacity") {
                            cantidadAnimacionesTerminadas++;
                            if (cantidadAnimacionesTerminadas === horarios.length) {
                                // Cuando todas las animaciones hayan terminado, resolvemos la Promise
                                clearTimeout(temporizadorSeguridad); // cancelamos el fallback si ya se resolvió correctamente
                                resolver();
                            }
                        }
                    });
                }, retraso);

                retraso += intervaloEntreTarjetasMs; // aumentamos el retraso para la siguiente tarjeta
            });
        }
    });
}




// MÉTODO PARA PINTAR CADA HORARIO
function crearDivHorario(slot, fecha) {
  // COMO DATA-FRANJA SE ASIGNA LA FRANJA HORARIA ID DEL HORARIO
  var divHorario = $("<div>")
      .attr("data-franja", slot.franja_horaria_id || "")
      // COMO TEXTO SE PONE LA HORA DE INICIO Y HORA DE FIN 
      .text(formatearHora(slot.hora_inicio) + " - " + formatearHora(slot.hora_fin));

  // SI EL HORARIO RECIÉN PINTADO ES UNA RESERVA DEL USUARIO, SE PINTA DE AZUL, Y SI SE HACE CLICK EN EL, SE MUESTRAN DETALLES DEL EVENTO
  // SI DEVUELVE NULL, SIGNIFICA QUE NO HA ENCONTRADO UNA COINCIDENCIA, ES DECIR, QUE NO HAY RESERVA DE CARRERA PARA ESE DÍA Y ESE HORARIO
  // POR PARTE DEL USUARIO CON LA SESIÓN INICIADA
  var reservaUsuario = obtenerReservaUsuario(slot.franja_horaria_id, fecha);
  if (reservaUsuario) {
      divHorario
          .css("background-color", "lightblue")
          .addClass("horario reservado-usuario")
          .on("click", function(e) {
              e.stopPropagation();
              var mensaje = "Detalles de tu reserva:<br>" +
                  "<strong>Hora:</strong> " + formatearHora(slot.hora_inicio) + " - " + formatearHora(slot.hora_fin) + "<br>" +
                  "<strong>Pista:</strong> " + reservaUsuario.nombre_pista + "<br>" +
                  "<strong>Participantes:</strong> " + reservaUsuario.num_participantes + "<br>" +
                  "<strong>Total:</strong> " + reservaUsuario.cantidad + "€";
              mostrarNotificacion(mensaje);
          });
      return divHorario;
  }

  // SEGÚN EL ESTADO DEL HORARIO, SE PINTA DE UNA O OTRA FORMA
  switch(slot.estado) {
    // SI ESTA RESERVADO, SE PINTA COMO ROJO Y SE MUESTRA QUE YA ESTA RESERVADO
      case "reservado":
          divHorario
              .css("background-color", "red")
              .addClass("horario reservado")
              .on("click", function(e) {
                  e.stopPropagation();
                  mostrarNotificacion("Este horario no está disponible, ya que ha sido reservado.");
              });
          break;
    // SI ESTA EXPIRADO, SE PINTA COMO GRIS Y SE MUESTRA QUE YA ESTA EXPIRADO

      case "expirado":
          divHorario
              .css("background-color", "gray")
              .addClass("horario expirado")
              .on("click", function(e) {
                  e.stopPropagation();
                  mostrarNotificacion("Este horario ya ha pasado y no está disponible.");
              });
          break;

    // SI TENIA RESERVA Y ES EXPIRADA, SE PINTA COMO ROJO OSCURO Y SE MUESTRA QUE ES PASADO Y RESERVADO
      case "reservado_expirado":
          divHorario
              .css("background-color", "darkred")
              .addClass("horario reservado-expirado")
              .on("click", function(e) {
                  e.stopPropagation();
                  mostrarNotificacion("Este horario fue reservado, pero ya ha pasado.");
              });
          break;
      // POR DEFECTO SI NO, ES DISPONIBLE
      default:
          divHorario
              .css("background-color", "green")
              .addClass("horario disponible")
              .on("click", function(e) {
                  e.stopPropagation();
                  if (divHorarioSeleccionado && divHorarioSeleccionado !== this) {
                      $(divHorarioSeleccionado).css("background-color", $(divHorarioSeleccionado).data("status"));
                  }
                  divHorarioSeleccionado = this;
                  $(this).css("background-color", "orange");
                  $("#horaSeleccionada").text("Hora: " + formatearHora(slot.hora_inicio) + " - " + formatearHora(slot.hora_fin));
                  $("#horaSeleccionada").attr("data-franja", slot.franja_horaria_id);
              });
  }

  // GUARDO EL ESTADO DEL HORARIO COMO DATA, POR SI ES SELECCIONADO Y NO CAMBIA, DESPUÉS PINTARLO OTRA VEZ DE SU COLOR ANTERIOR
  divHorario.data("status", divHorario.css("background-color"));
  return divHorario;
}

//MÉTODO PARA QUE EN CASO DE RESERVAR, HABILITAR O NO AL USUARIO A PODER HACERLO
function manejarClickReservar(e) {
    e.preventDefault();
    
    // Validar sesión
    if (!(datosSesion && datosSesion.sesion_iniciada)) {
        mostrarNotificacion("Para reservar, primero debes iniciar sesión.");
        return;
    }
    
    // Validar selección básica
    if (!divHorarioSeleccionado || !fechaSeleccionadaGlobal) {
        mostrarNotificacion("Por favor, seleccione un día y horario para la reserva.");
        return;
    }
    
    // Obtener datos del horario seleccionado
    const franjaId = $(divHorarioSeleccionado).attr("data-franja");
    const fechaStr = formatearFechaLocal(fechaSeleccionadaGlobal);
    const horaInicio = $(divHorarioSeleccionado).text().split(' - ')[0];
    
    // Validación inmediata de fecha/hora (antes del AJAX)
    const ahora = new Date();
    const fechaHoy = ahora.toISOString().split('T')[0];
    const horaActual = ahora.getHours().toString().padStart(2, '0') + ':' + 
                      ahora.getMinutes().toString().padStart(2, '0');
    
    if (fechaStr < fechaHoy || (fechaStr === fechaHoy && horaInicio <= horaActual)) {
        mostrarNotificacion("No puede reservar horarios que ya han comenzado o pasado.");
        // Recuperar estado original en lugar de poner rojo
        const estadoOriginal = $(divHorarioSeleccionado).data("status");
        $(divHorarioSeleccionado)
            .css("background-color", estadoOriginal)
            .attr("title", "Horario no disponible");
        divHorarioSeleccionado = null;
        $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");
        return;
    }
    
    // VERIFICAR DISPONIBILIDAD DEL HORARIO
    $.ajax({
        url: base_url + "Carreras/verificarDisponibilidadHorario",
        method: "POST",
        data: {
            fecha: fechaStr,
            franja_horaria_id: franjaId
        },
        dataType: "json",
        success: function(response) {
            if (response.disponible) {
                verificarDisponibilidadEmpleado();
            } else {
                const mensaje = response.razon === 'horario_pasado' 
                    ? "No puede reservar horarios pasados" 
                    : "El horario ya está reservado";
                
                mostrarNotificacion(mensaje);
                // Recuperar estado original en lugar de poner rojo
                const estadoOriginal = $(divHorarioSeleccionado).data("status");
                $(divHorarioSeleccionado)
                    .css("background-color", estadoOriginal)
                    .attr("title", "Horario no disponible");
                divHorarioSeleccionado = null;
                $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");
            }
        },
        error: function() {
            mostrarNotificacion("Error al verificar disponibilidad. Intente nuevamente.");
        }
    });
}

// MÉTODO PARA VALIDAR QUE EL USUARIO PUEDA HACER UNA RESERVA DE CARRERA
// SE HACE UN AJAX PARA COMPROBAR QUE HAY EMPLEADOS DISPONIBLES ACTUALMENTE
// A LOS QUE PODER DESIGNAR LA CARRERA
function verificarDisponibilidadEmpleado() {
  $.ajax({
      url: base_url + "EmpleadoCarreras/verificarDisponibilidad",
      method: "GET",
      dataType: "json",
      success: function(response) {
        // EL RESPONSE DEVUELVE UN TRUE O UN FALSE, DEPENDE DE SI HAY EMPLEADOS DE CARRERAS DISPONIBLES
          if (!response.disponible) {
            // SI NO HAY, SE SACA MENSAJE DE ERROR Y SE TIRA ATRÁS
              mostrarNotificacion("No se pueden hacer reservas de carreras ahora, no hay empleados operativos.");
              return;
          }
          // SI EXISTEN EMPLEADOS DE CARRERAS DISPONIBLES, SE CONTINUA
          continuarProcesoReserva();
      },
      error: function() {
        // SI HAY ERROR, SE NOTIFICA AL USUARIO Y SE VA ATRÁS
          mostrarNotificacion("Error al verificar disponibilidad de empleados.");
      }
  });
}

function continuarProcesoReserva() {
    // Validaciones básicas
    // COMPROBACIÓN DE DÍA SELECCIONADO
    if (!celdaDiaSeleccionado || !fechaSeleccionadaGlobal) {
        mostrarNotificacion("Por favor, seleccione un día para la reserva.");
        return;
    }
    // COMPROBACIÓN DE HORARIO Y FRANJA EXISTENTE
    if (!divHorarioSeleccionado || !divHorarioSeleccionado.dataset.franja) {
        mostrarNotificacion("Por favor, seleccione una hora para la reserva.");
        return;
    }
    
    // Validar fecha máxima (2 años en el futuro)
    var fechaMaxima = new Date();
    fechaMaxima.setFullYear(fechaMaxima.getFullYear() + 2);
    
    // RESERVAS DE DOS AÑOS NO PERMITIDAS, COMPROBACIÓN
    if (fechaSeleccionadaGlobal > fechaMaxima) {
        mostrarNotificacion("No se pueden hacer reservas para más de 2 años en el futuro.");
        return;
    }
    
    // RECOGIDA DE DATOS PARA HACER LA RESERVA
    var participantes = parseInt($("#inputCantidad").val()) || 1;
    var opcionSeleccionada = $("#pistaElegida option:selected")[0];
    var precio = parseFloat($(opcionSeleccionada).data("precio")) || 0;
    var totalGastado = precio * participantes;
    
    // Formatear fecha directamente desde el objeto Date para evitar problemas con el formato
    var fechaFormateada = formatearFechaLocal(fechaSeleccionadaGlobal);
    
    // Mostrar confirmación
    var datosConfirmacion = {
        pista: $(opcionSeleccionada).text(),
        participantes: participantes,
        total: totalGastado.toFixed(2) + '€',
        dia: fechaSeleccionadaGlobal.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        }),
        hora: divHorarioSeleccionado.textContent.trim()
    };
    
    // MÉTODO PARA MOSTRAR LA INFORMACIÓN DE LA CARRERAY CONFIRMARLA
    mostrarConfirmacion("¿Desea proceder al pago?", datosConfirmacion, function() {
        // MÉTODO PARA FORMAR EL OBJETO QUE SE VA A GUARDAR EN SESIÓN PARA USAR EN LA PASARELA DE PAGO
        procesarReserva(fechaFormateada, opcionSeleccionada, participantes, totalGastado, precio);
    });
  }

// MÉTODO PARA FORMAR EL OBJETO QUE SE VA A GUARDAR EN SESIÓN PARA USAR EN LA PASARELA DE PAGO
function procesarReserva(fechaFormateada, opcionSeleccionada, participantes, totalGastado, precio) {
  var reservaData = {
      tipo: 'carrera',
      id_pistas: $(opcionSeleccionada).val(),
      fecha: fechaFormateada,
      franja_horaria_id: divHorarioSeleccionado.dataset.franja,
      num_participantes: participantes,
      cantidad: totalGastado.toFixed(2),
      pista_nombre: $(opcionSeleccionada).text(),
      horario_texto: divHorarioSeleccionado.textContent.trim(),
      id_usuario: usuarioId,
      metodo_pago: null,
      precio_unitario: precio.toFixed(2)
  };
  
  // AQUÍ DIRECTAMENTE SE VA A ENVIAR LA INFORMACIÓN A UN MÉTODO DEL CONTROLADOR DE PAGO PARA GUARDAR
  // EN LA SESIÓN LOS DATOS DE LA RESERVA DE LA CARRERA
  enviarReservaAlBackend(reservaData);
}

// MÉTODO QUE PASA EL OBJETO DE LA RESERVA PARA GUARDARLO EN LA SESIÓN
function enviarReservaAlBackend(reservaData) {
  var formData = new FormData();
  for (var key in reservaData) {
      formData.append(key, reservaData[key]);
  }

  $.ajax({
      url: base_url + "pago/guardar_reserva",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      success: function(response) {
          if (response.success) {
            // SI EL OBJETO SE HA GUARDADO EXITOSAMENTE EN LA SESIÓN,
            // SE EJECUTA MÉTODO PARA REDIRIGIR A PASARELA DE PAGO
              redirigirAPasarelaPago();
          } else {
            // MOSTRAR ERROR
              mostrarNotificacion(response.error || "Hubo un problema al procesar la reserva.");
          }
      },
      error: function(xhr, status, error) {
        // MOSTRAR ERROR
          mostrarNotificacion("Error al procesar la reserva: " + error);
      }
  });
}

// MÉTODO PARA REDIRIGIR A LA PASARELA DE PAGO YA DIRECTAMENTE, YA QUE LOS DATOS DE LA RESERVA YA
// ESTÁN GUARDADOS EN SESIÓN
function redirigirAPasarelaPago() {
  $.ajax({
      url: base_url + "pago/pasarela",
      method: 'GET',
      success: function(response) {
          if (response.success) {
              window.location.href = response.redirect;
          } else {
              mostrarNotificacion(response.error || "Hubo un problema al acceder a la pasarela.");
          }
      },
      error: function(xhr, status, error) {
          mostrarNotificacion("Error al comprobar sesión de reserva: " + error);
      }
  });
}

// MÉTODO PARA SELECCIONAR DÍA Y HORARIO DESDE PARÁMETROS URL
async function seleccionarDesdeURL() {
    const params = new URLSearchParams(window.location.search);
    const fechaParam = params.get("fecha");
    const franjaParam = params.get("franja");

    if (fechaParam && franjaParam) {
        seleccionandoDesdeURL = true; // Activar flag para evitar selección automática del día actual
        try {
            const [anio, mes, dia] = fechaParam.split('-').map(Number);
            const fechaSeleccionada = new Date(anio, mes - 1, dia);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0); // Normalizar para comparar solo fechas
            
            // Verificación 1: Rechazar fechas pasadas
            if (fechaSeleccionada < hoy) {
                mostrarNotificacion("No se pueden seleccionar días pasados");
                history.replaceState({}, "", window.location.pathname);
                return false;
            }
            
            // Cambiar de mes si es necesario (igual que original)
            if (fechaControl.getFullYear() !== anio || fechaControl.getMonth() !== mes - 1) {
                fechaControl = new Date(anio, mes - 1, dia);
                await generarCalendario(anio, mes - 1);
            }
            
            // Seleccionar el día (igual que original pero con verificación adicional)
            const celdaDia = $(`.celda-calendario:not(.vacio):contains(${dia})`).filter(function() {
                return parseInt($(this).text()) === dia;
            });
            
            // Verificación 2: Solo si el día es seleccionable
            if (celdaDia.length > 0 && celdaDia.attr("data-selectable") === "true") {
                // 1. Actualizar variables globales del día (igual que original)
                celdaDiaSeleccionado = celdaDia[0];
                fechaSeleccionadaGlobal = new Date(anio, mes - 1, dia);
                
                // 2. Marcar el día en naranja y actualizar UI (igual que original)
                $(celdaDiaSeleccionado).css("background-color", "orange");
                $("#diaSeleccionado").text("Día seleccionado: " + dia);
                
                // 3. Cargar los horarios para ese día (igual que original)
                await cargarHorarios(anio, mes - 1, dia);
                
                // 4. Buscar y seleccionar el horario específico (igual que original)
                const divHorario = $(`.horario[data-franja="${franjaParam}"]`);
                if (divHorario.length > 0) {
                    // Actualizar variable global del horario
                    divHorarioSeleccionado = divHorario[0];
                    
                    // Marcar el horario en naranja y actualizar UI
                    $(divHorarioSeleccionado).css("background-color", "orange");
                    $("#horaSeleccionada").text("Hora: " + divHorario.text().trim())
                        .attr("data-franja", franjaParam);
                    
                    // Formatear fecha como dd/mm/yyyy
                    const diaFormateado = String(dia).padStart(2, '0');
                    const mesFormateado = String(mes).padStart(2, '0');
                    const fechaFormateada = `${diaFormateado}/${mesFormateado}/${anio}`;
                    $("#fechaSeleccionada").text("Fecha: " + fechaFormateada);
                }
                
                // Limpiar parámetros de la URL (igual que original)
                history.replaceState({}, "", window.location.pathname);
                return true;
            } else {
                // Feedback cuando el día no es seleccionable
                mostrarNotificacion("El día seleccionado no está disponible para reservar");
                history.replaceState({}, "", window.location.pathname);
                return false;
            }
        } catch (error) {
            console.error("Error al seleccionar desde URL:", error);
            return false;
        }
    }
    // Si no hay parámetros en la URL para fecha y franja
    return false;
}

// Función para seleccionar el día actual en el calendario
function seleccionarDiaActual() {
    const hoy = new Date();
    const dia = hoy.getDate();
    const mes = hoy.getMonth();
    const anio = hoy.getFullYear();
    
    // Verificar si estamos en el mes correcto mostrado en el calendario
    if (fechaControl.getFullYear() === anio && fechaControl.getMonth() === mes) {
        // Buscar la celda del día actual
        const celdaDia = $(`.celda-calendario:not(.vacio):contains(${dia})`).filter(function() {
            return parseInt($(this).text()) === dia;
        });
        
        if (celdaDia.length > 0 && celdaDia.attr("data-selectable") === "true") {
            // Simular click en el día actual
            celdaDia.trigger('click');
        } else {
            console.log("El día actual no está disponible para seleccionar");
        }
    }
}

// MÉTODO PARA INICIALIZAR TODO LO QUE HAY RESPECTO A LA CARRERA EN EL DOCUMENT.READY
async function inicializarApartadoCarrera() {
    // TENER CONTROL DE LA CANTIDAD
    configurarControlesCantidad();

    // CARGAR SELECT DE PISTAS
    cargarPistas();

    // Agregar listener para cambios en la selección de pistas
    $("#pistaElegida").on("change", calcularTotal);

    // CARGAR LAS RESERVAS
    await cargarReservasUsuario();

    // MANEJAR EL CLICK AL DARLE A RESERVAR
    $("#btnReservar").on("click", manejarClickReservar);

    // OBTENER LAS FRANJAS HORARIAS Y PINTAR EL CALENDARIO
    try {
        const response = await $.ajax({
            url: base_url + "calendario/getTotalFranjas",
            method: "GET",
            dataType: "json"
        });
        totalFranjas = response.total || 14;
    } catch {
        totalFranjas = 14; // Valor por defecto
    }

    await generarCalendario(fechaControl.getFullYear(), fechaControl.getMonth());

      // PROCESAR PARÁMETROS DE URL SI EXISTEN Y USAR EL VALOR DE RETORNO
    const seleccionoDesdeURL = await seleccionarDesdeURL();

    seleccionandoDesdeURL = false;

    // Solo si NO se seleccionó desde URL, seleccionamos el día actual automáticamente
    if (!seleccionoDesdeURL) {
        seleccionarDiaActual();
    }
}

// Detectar click en el botón de ayuda y abrir el modal
$(document).on('click', '#btnAyudaCarreras', function (event) {
  event.preventDefault();
  $('#modalAyudaCarreras').modal('show');
});

// Iniciar todo cuando el documento esté listo
$(document).ready(async function() {
    // DIA, FECHA Y HORA POR DEFECTO NO SELECCIONADA
    $("#diaSeleccionado").text("Día seleccionado: Aún no se ha seleccionado");
    $("#fechaSeleccionada").text("Fecha: Aún no se ha seleccionado");
    $("#horaSeleccionada").text("Hora: Aún no se ha seleccionado");
    
    // MÉTODO PARA CONTROL BÁSICO INICIALIZADO EN EL DOCUMENT.READY
    await inicializarApartadoCarrera();
});

// MÉTODO PARA CARGAR TODAS LAS PISTAS QUE HAY DISPONIBLES EN EL SELECT PARA ESCOGER PISTA
function cargarPistas() {
  $.ajax({
      url: base_url + 'pistas/getPistas',
      method: "GET",
      dataType: "json",
      success: function(data) {
          var select = $("#pistaElegida");
          select.empty();
          
          if (data && data.length > 0) {
              $.each(data, function(index, pista) {
                  select.append(
                      $("<option>")
                          .val(pista.id)
                          .attr("data-precio", pista.precio)
                          .text(pista.nombre + " - " + pista.precio + "€")
                  );
              });
          } else {
              select.append($("<option>").val("").text("No hay pistas disponibles"));
          }
          
          calcularTotal();
      },
      error: function(error) {
          console.error("Error al cargar las pistas:", error);
      }
  });
}



