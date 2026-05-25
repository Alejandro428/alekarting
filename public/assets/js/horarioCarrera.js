/** 
- Fecha actual de control para navegación del calendario 
 */
let fechaControlCarreras = new Date();

/** 
- Fecha de hoy para comparaciones 
 */
let fechaHoy = new Date();
fechaHoy.setHours(0, 0, 0, 0);

/*
 - Total de franjas horarias disponibles en el sistema 
 */
let totalFranjas = 14;

/*
 - Almacena el conteo de reservas por día {diaDelMes: cantidad} 
 */
let datosReservas = {};

/*
- Almacena las reservas del usuario actual 
 */
let reservasCarrerasUsuario = {};

/*
- Referencia a la celda de calendario seleccionada 
 */
let celdaSeleccionadaCarreras = null;

/*
- Fecha seleccionada en el calendario 
 */
let fechaSeleccionadaCarreras = null;

/*
   MÉTODO DE NOTIFICACIÓN NORMAL Y CORRIENTE
*/
function mostrarNotificacion(mensaje, fecha = null) {
    // Añade formato de fecha si se proporciona
    if (fecha instanceof Date) {
        mensaje += "<br><br><strong>" + formatearFechaCompleta(fecha) + "</strong>";
    }
    
    // Elimina notificaciones previas
    $("#divNotificacion").remove();
    
    // Crea el contenedor de la notificación
    let $divNotificacion = $("<div>", {
        id: "divNotificacion",
        class: "notificacion fadeIn",
        html: mensaje
    });
    
    // Añade botón de cierre
    let $botonCerrar = $("<button>").text("Cerrar").click(function(e) {
        e.stopPropagation();
        clearTimeout($divNotificacion.data("autoRemoveTimeout"));
        $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
        setTimeout(() => { $divNotificacion.remove(); }, 500);
    });
    
    $divNotificacion.append("<br>").append($botonCerrar);
    $("body").append($divNotificacion);
    
    // Configura auto-eliminación después de 3 segundos
    let autoRemoveTimeout = setTimeout(function() {
        $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
        setTimeout(() => { $divNotificacion.remove(); }, 500);
    }, 3000);
    $divNotificacion.data("autoRemoveTimeout", autoRemoveTimeout);
}

/**
 MÉTODO PARA MOSTRAR LA NOTIFICACIÓN PARA CUANDO EL USUARIO 
 HACE CLICK EN UN HORARIO DISPONIBLE
 * - fecha - Fecha de la reserva
 * - slot - Datos del horario {hora_inicio, hora_fin, etc.}
 * - enlace - URL para redirección
 */
 function mostrarNotificacionReservaCarrera(fecha, slot, enlaceBase) {
    let textoSeccion = "Carreras";
    
    // Formatear fecha como yyyy-mm-dd
    const fechaFormateada = fecha.toISOString().split('T')[0]; // ej: "2025-05-11"

    // Parámetros que quieres enviar
    let franjaID = encodeURIComponent(slot.franja_horaria_id);

    // Construcción de la URL con parámetros
    let enlaceConParametros = `${enlaceBase}?fecha=${fechaFormateada}&franja=${franjaID}`;

    let mensaje = `
        <p><strong>${textoSeccion}</strong></p>
        <p>El día <strong>${formatearFechaCompleta(fecha)}</strong>, 
        el horario <strong>${formatearHora(slot.hora_inicio)} - ${formatearHora(slot.hora_fin)}</strong> está disponible.</p>
        <p>¿Deseas ir a la sección de ${textoSeccion}?</p>`;
    
    mostrarNotificacionCarreraUI(mensaje, enlaceConParametros, textoSeccion);
}


/**
   MÉTODO PARA MOSTRAR LA NOTIFICACIÓN DE LA ACCIÓN QUE SE VAYA A REALIZAR 
   EN EL HORARIO DE LA CARRERA ESCOGIDA
 */
function mostrarNotificacionCarreraUI(mensaje, enlace, textoSeccion = "Carreras") {
    // Elimina notificaciones previas
    $("#divNotificacionReserva").remove();
    
    let $divNotificacion = $("<div>", { 
        id: "divNotificacionReserva", 
        class: "notificacionReserva fadeIn" 
    });
    
    let $contenedorDetalle = $("<div>", { 
        class: "detalle-reserva", 
        html: mensaje 
    });
    $divNotificacion.append($contenedorDetalle);
    
    let $contenedorBotones = $("<div>", { class: "contenedor-botones" });
    
    let $botonIr = $("<button>", { class: "boton-ir" })
        .text(`Ir a ${textoSeccion.toLowerCase()}`)
        .click(function(e) {
            e.stopPropagation();
            window.location.href = enlace;
        });
    
    let $botonCerrar = $("<button>", { class: "boton-cerrar" })
        .text("Cerrar")
        .click(function(e) {
            e.stopPropagation();
            $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
            setTimeout(() => { $divNotificacion.remove(); }, 500);
        });
    
    $contenedorBotones.append($botonIr).append($botonCerrar);
    $divNotificacion.append("<br><br>").append($contenedorBotones);
    $("body").append($divNotificacion);
}

/**
 MÉTODO PARA FORMATEAR LA FECHA A YYYY-MM-DD
 */
function formatearFechaLocal(fecha) {
    const año = fecha.getFullYear();
    const mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
    const dia = ('0' + fecha.getDate()).slice(-2);
    return `${año}-${mes}-${dia}`;
}

/*
  MÉTODO PARA FORMATEAR LA FECHA A DD-MM-YYYY
 */

function formatearFechaEuropeo(fecha) {
  if (typeof fecha === "string") {
      const partes = fecha.split("-");
      if (partes.length === 3) {
          const anio = parseInt(partes[0]);
          const mes = parseInt(partes[1]) - 1; 
          const dia = parseInt(partes[2]);
          fecha = new Date(anio, mes, dia);
      } else {
          return fecha; 
      }
  }

  if (!(fecha instanceof Date) || isNaN(fecha.getTime())) {
      return ""; // Fecha inválida
  }

  const dia = ("0" + fecha.getDate()).slice(-2);
  const mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
  const anio = fecha.getFullYear();
  return `${dia}-${mes}-${anio}`;
}

/**
 MÉTODO PARA DEVOLVER FECHA CON FORMATO COMPLETO
 EJ: LUNES 18 DE MAYO DE 2025
 */
function formatearFechaCompleta(fecha) {
    const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 
                  'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return `${dias[fecha.getDay()]} ${fecha.getDate()} de ${meses[fecha.getMonth()]} de ${fecha.getFullYear()}`;
}

/**
 MÉTODO PARA QUITAR SEGUNDOS DE LOS HORARIOS
 */
function formatearHora(hora) {
    return hora.split(":").slice(0, 2).join(":");
}

/**
 MÉTODO PARA ENCONTRAR LA RESERVA DE LA CARRERA DEL USUARIO CON UNA FECHA Y FRANJA
 PASADO POR PARÁMETRO Y DEVOLVERLA, O EN CASO DE QUE NO EXISTA RETORNAR NULL
 */
function obtenerReservaUsuarioCarreras(franjaId, fechaStr) {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        return reservasCarrerasUsuario.find(function(r) {
            return String(r.franja_horaria_id) === String(franjaId) && r.fecha === fechaStr;
        }) || null;
    }
    return null;
}

/**
 MÉTODO PARA COMPROBAR SI HAY RESERVA EN UN DÍA EN ESPECÍFICO POR PARTE DEL USUARIO
 CON LA ACTUAL SESIÓN INICIADA
 */
function usuarioTieneReservaEnDiaCarreras(fechaStr) {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        return reservasCarrerasUsuario.some(function(r) {
            return r.fecha === fechaStr;
        });
    }
    return false;
}

/*
 MÉTODO PARA PINTAR LAS CELDAS DE LOS DÍAS DEL CALENDARIO
 * cuenta - Número de reservas para ese día
   CUENTA EQUIVALE A LA CANTIDAD DE FRANJAS (HORARIOS) YA ESCOGIDOS
   ESE DÍA
 * fechaCelda - Fecha representada por la celda
 */
function estiloCeldaCarreras(cuenta, fechaCelda) {
    const fechaStr = formatearFechaEuropeo(fechaCelda);
    const fechaFormateada = formatearFechaLocal(fechaCelda);
    
    // PRIMERO VERIFICAMOS SI LA FECHA YA PASÓ (TIENE PRIORIDAD SOBRE TODO)
    if (fechaCelda < fechaHoy) {
        return { 
            color: "red", 
            selectable: false, 
            title: `El ${fechaStr} ya ha pasado` 
        };
    }
    
    // SI EN ESA FECHA HAY RESERVA POR PARTE DEL USUARIO, SE PINTA DE AZUL EL DÍA
    if (usuarioTieneReservaEnDiaCarreras(fechaFormateada)) {
        return { 
            color: "lightblue", 
            selectable: true, 
            title: `Tienes una reserva para el ${fechaStr}` 
        };
    }
    
    // SI ESTA COMPLETO, SE PINTA DE ROJO
    if (totalFranjas > 0 && cuenta === totalFranjas) {
        return { 
            color: "red", 
            selectable: false, 
            title: `El ${fechaStr} está completamente reservado` 
        };
    }
    
    // SI NO TIENE NINGUNA RESERVA EL DÍA, SE PINTA DE VERDE
    if (cuenta === 0) {
        return { 
            color: "green", 
            selectable: true, 
            title: `El ${fechaStr} está disponible` 
        };
    }
    
    // SI TIENE ALGUNA RESERVA HECHA, PERO NO EL MÁXIMO POSIBLE,
    // SE PINTA DE GRIS
    return { 
        color: "grey", 
        selectable: true, 
        title: `El ${fechaStr} está parcialmente reservado` 
    };
}

/**
 * MÉTODO QUE SIRVE PARA DAR ESTILOS A CADA CELDA DEL CALENDARIO, ADEMÁS DE PONER SI ES SELECCIONABLE O NO
 
 * cuadricula - ES LA CUADRÍCULA (LA ESTRUCTURA DEL CALENDARIO, CON LAS CELDAS DE LOS DÍAS)
 * (YA GENERADOS CORRECTAMENTE, CON CELDAS VACÍAS PARA QUE EN EL CALENDARIO CUADRE EL PRIMER)
 * (DÍA DEL MES CON SU DÍA DE LA SEMANA, Y TODAS LAS CELDAS DE LOS DÍAS (TODO ESO SE GENERA EN crearCuadriculaCalendario),
 *  ESTE MÉTODO SE ENCARGA) (DE DAR LOS ATRIBUTOS EN DATA Y EL COLOR CORRECTOS DE ESE DÍA)
 * anio - Año mostrado
 * mes - Mes mostrado (0-11)
 */
function actualizarEstiloCeldasCarreras($cuadricula, anio, mes) {
    // RECOJO DÍAS QUE NO SON VACÍOS (QUE TIENEN NÚMERO)
    let $celdas = $cuadricula.find(".celda-calendario:not(.vacio)");
    
    // RECORRO CADA UNA DE LAS CELDAS PARA DARLES COLOR Y ACCIÓN
    $celdas.each(function(indice) {
        setTimeout(() => {
            let dia = parseInt($(this).text()); // NÚMERO DEL DÍA DE LA CELDA (EJ: 3)
            let fechaCelda = new Date(anio, mes, dia); // FECHA FORMADA POR EL MES Y AÑO DE PARÁMETROS Y EL DÍA RECIÉN SACADO
            let cuenta = datosReservas[dia] || 0; // CANTIDAD DE RESERVAS DE ESE DÍA DEL MES (EJ: 5 RESERVAS EL DÍA 14)
            let estado = estiloCeldaCarreras(cuenta, fechaCelda); // RETORNA EL ESTADO (COLOR DE CELDA, SI ES SELECCIONABLE Y TITLE PARA LA CELDA)
            // ESTILO CELDA CARRERAS, MÉTODO ANTERIOR, SE ENCARGA DE CALCULAR QUE ACCIÓN TIENE EL DÍA, Y DESPUÉS CON ESA INFORMACIÓN SE ASIGNA COLOR, SELECCIONABLE, ETC
            $(this).css("backgroundColor", estado.color) // ASIGNACIÓN DE COLOR
                   .attr("data-selectable", estado.selectable ? "true" : "false") // ASIGNACIÓN DE SI ES SELECCIONABLE
                   .attr("title", estado.title) // ASIGNACIÓN DEL TÍTULO (CUANDO SE TIENE EL CLICK ENCIMA DE LA CELDA)
                   .attr("data-status", estado.color); // ME GUARDO EN DATA EL COLOR DE LA CELDA COMO ESTADO, YA QUE AL HACER CLICK EN ELLA,
                   // CAMBIA A COLOR NARANJA, Y ME INTERESA QUE DESPUÉS PUEDA REGRESAR A SU COLOR ORIGINAL
        }, indice * 10); // RETARDO PARA QUE HAYA UNA ANIMACIÓN AL PINTAR LOS DÍAS
    });
}

/*
 MÉTODO QUE UTILIZA UN AJAX PARA OBTENER TODOS LOS DÍAS DEL MES DEL AÑO INDICADO,
 Y QUE CONTIENE LAS RESERVAS QUE HAY HECHAS EN CADA DÍA (EJ: DÍA 15: 6 RESERVAS)
 anio - Año a consultar
 mes - Mes a consultar (0-11)
 */
function obtenerReservasCarreras(anio, mes) {
    return $.ajax({
        url: `${base_url}calendario/getReservasCountCarreras?anio=${anio}&mes=${mes + 1}`,
        type: "GET",
        dataType: "json"
    });
}

/*
  MÉTODO QUE UTILIZA UN AJAX PARA OBTENER LA CANTIDAD DE FRANJAS EXISTENTES, SON 14
 */
function obtenerTotalFranjas() {
    return $.ajax({
        url: `${base_url}calendario/getTotalFranjas`,
        type: "GET",
        dataType: "json"
    }).then(function(data) {
        return data.total;
    });
}

/**
 MÉTODO PARA CARGAR LOS HORARIOS DE UN DÍA EN CONCRETO
 */
function cargarHorarios(anio, mes, dia, alTerminar) {
    // CONTENEDOR HORARIOS ES EL PADRE DONDE SE VAN A CREAR LAS TARJETAS DE CADA HORARIO
    let $contenedorHorarios = $("#reservas");
    if ($contenedorHorarios.length === 0) {
        console.error("No se encontró el contenedor de horarios ('reservas').");
        if (alTerminar) alTerminar();
        return;
    }
    
    $contenedorHorarios.html("");
    const fecha = `${anio}-${(mes + 1).toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}`;
    
    // AJAX QUE OBTIENE EL ESTADO QUE TIENE EL DÍA PASADO POR EL PARÁMETRO
    $.ajax({
        url: `${base_url}calendario/getHorariosDia?fecha=${fecha}`,
        type: "GET",
        dataType: "json",
        success: function(data) {
            // SI ES CORRECTO EL AJAX, SE CARGAN LOS HORARIOS CON ESTE MÉTODO
            // EL DATA CONTIENE UN ARRAY DE HORARIOS, CADA HORARIO CONTIENE ESTO:
            // 'franja_horaria_id' => $id,
            // 'hora_inicio'       => $horaInicio,
            // 'hora_fin'          => $franja['hora_fin'],
            // 'descripcion'       => $franja['descripcion'],
            // 'estado'            => $estado,
            $contenedorHorarios.empty();
            
            if (!data || data.length === 0) {
                $contenedorHorarios.html("No hay horarios disponibles para este día.");
                if (alTerminar) alTerminar();
                return;
            }
            
            // LA VARIABLE SLOT SE UTILIZA PARA RECORRER CADA HORARIO, AHORA CON EL ESTADO
            const intervaloEntreTarjetasMs = 20; // Retraso de 80 ms entre cada celda
            
            $.each(data, function(i, slot) {
                setTimeout(() => {
                    crearElementoHorario($contenedorHorarios, slot, fecha, i);
                    // Cuando se crea el último horario, llamamos al callback
                    if (i === data.length - 1 && alTerminar) {
                        alTerminar();
                    }
                }, i * intervaloEntreTarjetasMs);
            });
        },
        error: function(error) {
            console.error("Error cargando horarios:", error);
            $contenedorHorarios.html("Error al cargar horarios.");
            if (alTerminar) alTerminar();
        }
    });
}

/*
 contenedor - Contenedor donde se va a insertar el horario
 slot - Toda la información del horario junto con su estado
 fecha - Fecha en formato YYYY-MM-DD
*/
function crearElementoHorario($contenedor, slot, fecha, index = 0) {
    // Crear div con clase base y texto de horario
    let $divHorario = $("<div>")
        .addClass("horario")
        .attr("data-franja", slot.franja_horaria_id || "")
        .text(`${formatearHora(slot.hora_inicio)} - ${formatearHora(slot.hora_fin)}`);

    // Obtener reserva de usuario si sesión iniciada
    let reservaUsuario = null;
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        reservaUsuario = obtenerReservaUsuarioCarreras(slot.franja_horaria_id, fecha);
    }

    // Configurar estilo según estado reserva o normal
    if (reservaUsuario) {
        configurarHorarioReservadoUsuario($divHorario, slot, reservaUsuario);
    } else {
        configurarHorarioPorEstado($divHorario, slot, fecha);
    }

    // Añadir al contenedor antes de animar
    $contenedor.append($divHorario);

    // USO EL OFFSET HEIGHT PARA QUE SE APLIQUE CORRECTAMENTE LA ANIMACIÓN
    $divHorario[0].offsetHeight;

    // Animar con retraso escalonado para que cada div se muestre suave y ordenado
    setTimeout(() => {
        $divHorario.addClass("animarEntrada");
    }, 50 * index);
}



/**
 * MÉTODO PARA CONFIGURAR EL HORARIO EN CONCRETO EN CASO DE QUE ESE HORARIO
 * DE ESA FECHA ESTE RESERVADO POR EL USUARIO DE LA SESIÓN ACTUAL
 * elemento - ES EL DIV DEL HORARIO SOBRE EL QUE SE VAN A APLICAR LOS ESTILOS Y EL CLICK 
 * slot - DATOS DEL HORARIO, CON EL ESTADO Y TODO
 * reserva - DATOS DE TODA LA RESERVA DE ESE DIA Y CON ESE HORARIO
 */
function configurarHorarioReservadoUsuario($elemento, slot, reserva) {
    // SE ASIGNA AL DIV EL ESTILO PARA HORARIO RESERVADO POR EL USUARIO:
    // COLOR DE FONDO, COLOR DE TEXTO Y CURSOR DE PUNTERO
    $elemento.css({ 
        backgroundColor: "lightblue", 
        color: "white", 
        cursor: "pointer" 
    })
    // SE LE AÑADE LA CLASE BASE 'horario' Y LA CLASE 'reservado-usuario' PARA ESTILOS CSS
    .addClass("horario reservado-usuario")
    // SE CONFIGURA EL EVENTO CLICK PARA MOSTRAR DETALLES DE LA RESERVA
    .on("click", function(e) {
        // EVITAR PROPAGACIÓN DEL CLICK A OTROS ELEMENTOS
        e.stopPropagation();
        // MENSAJE CON LOS DETALLES DE LA RESERVA
        const mensajeDetalle = "Detalles de tu reserva:<br>" +
            "<strong>Hora:</strong> " + formatearHora(slot.hora_inicio) + " - " + formatearHora(slot.hora_fin) + "<br>" +
            "<strong>Pista:</strong> " + reserva.nombre_pista + "<br>" +
            "<strong>Participantes:</strong> " + reserva.num_participantes + "<br>" +
            "<strong>Total:</strong> " + reserva.cantidad + "€";
        // MOSTRAR NOTIFICACIÓN CON LOS DETALLES
        mostrarNotificacion(mensajeDetalle);
    });
}


/**
 * MÉTODO PARA CONFIGURAR CUALQUIERA DE LOS HORARIOS
 * elemento - ES EL DIV DEL HORARIO SOBRE EL QUE SE VAN A APLICAR LOS ESTILOS Y EL CLICK 
 * slot - DATOS DEL HORARIO, CON EL ESTADO Y TODO
 * fecha - Fecha en formato YYYY-MM-DD
 */
function configurarHorarioPorEstado($elemento, slot, fecha) {
    // SEGÚN EL ESTADO DEL HORARIO, SE CONFIGURA EL ESTILO Y EL EVENTO CLICK CORRESPONDIENTE
    switch(slot.estado) {
        // HORARIO RESERVADO: COLOR ROJO, TEXTO BLANCO Y CLICK QUE AVISA QUE YA ESTÁ RESERVADO
        case "reservado":
            $elemento.addClass("horario reservado")
                .css({ 
                    backgroundColor: "red", 
                    color: "white",
                    cursor: "pointer"
                })
                .on("click", function(e) {
                    e.stopPropagation();
                    mostrarNotificacion(`El horario ${slot.hora_inicio} - ${slot.hora_fin} ya está reservado.`);
                });
            break;

        // HORARIO RESERVADO EXPIRADO: COLOR ROJO OSCURO Y CLICK QUE AVISA QUE YA PASÓ EL PLAZO
        case "reservado_expirado":
            $elemento.addClass("horario reservado-expirado")
                .css({ 
                    backgroundColor: "darkred", 
                    color: "white",
                    cursor: "pointer"
                })
                .on("click", function(e) {
                    e.stopPropagation();
                    mostrarNotificacion("Este horario fue reservado, pero ya ha pasado.");
                });
            break;

        // HORARIO EXPIRADO: COLOR GRIS Y CLICK QUE AVISA QUE EL HORARIO NO ESTÁ DISPONIBLE
        case "expirado":
            $elemento.addClass("horario expirado")
                .css({ 
                    backgroundColor: "gray", 
                    color: "white",
                    cursor: "pointer"
                })
                .on("click", function(e) {
                    e.stopPropagation();
                    mostrarNotificacion("Este horario ya ha pasado y no está disponible.");
                });
            break;

        // HORARIO DISPONIBLE: COLOR VERDE Y CLICK PARA MANEJAR LA SELECCIÓN DEL HORARIO
        case "disponible":
            $elemento.addClass("horario disponible")
                .css({ 
                    backgroundColor: "green", 
                    color: "white",
                    cursor: "pointer" 
                })
                .on("click", function(e) {
                    e.stopPropagation();
                    manejarSeleccionHorarioDisponible($elemento, slot, fecha);
                });
            break;
    }
}


/**
 * MÉTODO QUE SIRVE PARA QUITAR EL SELECCIONADO AL ANTERIOR HORARIO SELECCIONADO SI EXISTE
 * Y PONERLE SU COLOR ORIGINAL, Y DESPUÉS PONER COMO SELECCIONADA EL HORARIO SOBRE
 * EL QUE SE HA HECHO CLICK, Y MOSTRAR EL MÉTODO DE MOSTRAR LA RESERVA DE LA CARRERA
 * elemento - ES EL DIV DEL HORARIO SOBRE EL QUE SE VAN A APLICAR LOS ESTILOS Y EL CLICK 
 * slot -  DATOS DEL HORARIO, CON EL ESTADO Y TODO
 * fecha - Fecha en formato YYYY-MM-DD
 */
function manejarSeleccionHorarioDisponible($elemento, slot, fecha) {
    // SE RECOGE EL HORARIO SELECCIONADO ACTUAL
    let $horarioSeleccionado = $(".horario.seleccionado");
    
    // EN CASO DE QUE EXISTA UN HORARIO SELECCIONADO Y NO SEA DISTINTO AL
    // DIV DEL HORARIO ACTUAL, SE LE BORRA QUE ESTE SELECCIONADO Y SE LE 
    // VUELVE A PONER SU COLOR ORIGINAL
    if ($horarioSeleccionado.length && !$horarioSeleccionado.is($elemento)) {
        $horarioSeleccionado.removeClass("seleccionado")
            .css("backgroundColor", function() {
                if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada && 
                    usuarioTieneReservaEnDiaCarreras(fecha)) {
                    return "lightblue";
                }
                return $(this).hasClass("disponible") ? "green" : 
                       $(this).hasClass("reservado") ? "red" :
                       $(this).hasClass("reservado-expirado") ? "darkred" : "gray";
            });
    }
    
    // SE ASIGNA AL HORARIO ESCOGIDO EL NARANJA PARA QUE RESALTE COMO SELECCIONADO
    $elemento.addClass("seleccionado").css("backgroundColor", "orange");
    // SE MUESTRA LA NOTIFICACIÓN DE LA RESERVA DE LA CARRERA, POR SI EL USUARIO
    // QUIERE REDIRIGIRSE A LA CARRERA PARA RESERVARLA
    mostrarNotificacionReservaCarrera(new Date(fecha), slot, `${base_url}Carreras`);
}

/*
MÉTODO PARA PRIMERO COMPROBAR QUE SE PUEDE VER EL HORARIO DE ESE DÍA, Y DESPUÉS CARGAR
LOS HORARIOS EN CASO AFIRMATIVO DE ESA FECHA INDICADA
fecha - Fecha seleccionada
Función alTerminar - Función que se ejecuta cuando termina la carga de horarios (callback opcional)
*/

function verHorariosCarreras(fecha, alTerminar) {
    // Si la fecha es anterior a hoy, muestra una notificación y llama al callback si existe
    if (fecha < fechaHoy) {
        mostrarNotificacion(`El día ${formatearFechaEuropeo(fecha)} es un día pasado. No se pueden reservar carreras.`, fecha);
        if (alTerminar) alTerminar();
        return;
    }
    // Si la fecha es válida, carga los horarios y llama al callback al terminar
    cargarHorarios(fecha.getFullYear(), fecha.getMonth(), fecha.getDate(), alTerminar);
}

/*
MÉTODO PARA GENERAR EL CALENDARIO, PARTICIONADO EN VARIOS MÉTODOS, YA QUE
TIENE VARIAS PARTES
 */
function generarCalendarioCarreras() {
    // SE RECOGE EL CONTENEDOR DEL CALENDARIO
    let $contenedor = $("#calendarioCarreras");
    // SE RESTABLECE LA FECHA Y LA CELDA SELECCIONADA
    celdaSeleccionadaCarreras = null;
    fechaSeleccionadaCarreras = null;
    // SE SACA AÑO Y MES
    const anio = fechaControlCarreras.getFullYear();
    const mes = fechaControlCarreras.getMonth();
    $contenedor.empty();
    
    // GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
    crearEncabezadoCalendario($contenedor, anio, mes);
    
    // GENERAR DIAS DE LA SEMANA
    crearFilaDiasSemana($contenedor);
    
    // GENERAR TODOS LOS DIAS QUE TIENE ESE MES, Y LUEGO GENERAR CLICK DE CADA CELDA
    let $cuadricula = crearCuadriculaCalendario(anio, mes);
    $contenedor.append($cuadricula);
    
    // POR ÚLTIMO, SE REFERENCIA AL MÉTODO PARA OBTENER
    // LAS RESERVAS DE LA CARRERA DE ESE MES, Y CUANDO LLEGA LA INFORMACIÓN,
    // SE ACTUALIZAN LAS CELDAS DE CARRERAS, Y EN VEZ DE SER TODAS LAS CELDAS 
    // DE LOS DÍAS GRISES Y SELECCIONABLES, SE LES ASIGNA SU ACCIÓN ADECUADAMENTE.
    obtenerReservasCarreras(anio, mes)
        .done(function(datos) {
            datosReservas = datos;
            // MÉTODO QUE SE ENCARGA DE ACTUALIZAR EL ESTILO DE CADA CELDA DEL MES
            // Y AÑO INTRODUCIDOS EN EL CALENDARIO, Y HACER QUE SEAN SELECCIONABLES LOS DÍAS
            actualizarEstiloCeldasCarreras($cuadricula, anio, mes);
        })
        .fail(function(error) {
            console.error("Error obteniendo reservas de carreras:", error);
        });
}

/**
   // MÉTODO PARA GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
 * contenedor - ES EL DIV DEL CONTENEDOR DEL CALENDARIO
 * anio - AÑO QUE SE MUESTRA EN EL CALENDARIO
 * mes - MES MOSTRADO EN EL CALENDARIO (0-11)
 */
function crearEncabezadoCalendario($contenedor, anio, mes) {
    let $encabezado = $("<div>", { class: "encabezado-calendario" });

    // AL CAMBIAR DE MES, LIMPIO LOS HORARIOS QUE PUDIERAN ESTAR CARGADOS
    $("#reservas").empty();

    // CONTROL AL HACER CLICK A MES ANTERIOR, SI EL MES ES ANTERIOR AL MES DE LA ACTUALIDAD,
    // NO SE DEJA RETROCEDER
    // SE VUELVE A LLAMAR A GENERAR CALENDARIO CON LA FECHA DE CONTROL DE CARRERAS ACTUALIZADA
    let $btnAnterior = $("<button>", { id: "btnMesAnteriorCarreras" }).text("<").click(function() {
        let nuevoMes = fechaControlCarreras.getMonth() - 1;
        let nuevoAnio = fechaControlCarreras.getFullYear();
        if (nuevoMes < 0) { nuevoMes = 11; nuevoAnio--; }
        if (new Date(nuevoAnio, nuevoMes, 1) < new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), 1)) return;
        fechaControlCarreras = new Date(nuevoAnio, nuevoMes, 1);
        generarCalendarioCarreras();
    });

    // CONTROL AL HACER CLICK A MES SIGUIENTE, SE VUELVE A LLAMAR A GENERAR CALENDARIO CON 
    // LA FECHA DE CONTROL DE CARRERAS ACTUALIZADA
    let $btnSiguiente = $("<button>", { id: "btnMesSiguienteCarreras" }).text(">").click(function() {
        fechaControlCarreras = new Date(fechaControlCarreras.getFullYear(), fechaControlCarreras.getMonth() + 1, 1);
        generarCalendarioCarreras();
    });

    // NOMBRE DE TODOS LOS MESES DEL AÑO
    let nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                       "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    // PONGO EN EL TÍTULO DE LA CABECERA DEL CALENDARIO EL MES Y AÑO
    let $titulo = $("<span>", { class: "titulo-calendario" }).text(`${nombresMeses[mes]} ${anio}`);

    // SE AÑADE EL BOTÓN ANTERIOR, TÍTULO Y BOTÓN SIGUIENTE
    $encabezado.append($btnAnterior, $titulo, $btnSiguiente);
    // POR ÚLTIMO, SE AÑADE EL ENCABEZADO
    $contenedor.append($encabezado);
}


/**
 MÉTODO PARA CREAR LOS DIVS SOBRE LOS QUE SE VE EN LA CABECERA LOS DÍAS
 DE LA SEMANA (LUNES, MARTES...)
 contenedor - DIV PERTENECIENTE AL CONTENEDOR DEL CALENDARIO
 */
function crearFilaDiasSemana($contenedor) {
    let $filaDias = $("<div>", { class: "fila-dias-calendario" });
    let diasSemana = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
    
    // SE CREA EL DIV, SE LE METE EL TEXTO DEL DÍA DE LA SEMANA, Y SE
    // RECORRE UN BUCLE QUE VA AÑADIENDO TODOS LOS DÍAS
    $.each(diasSemana, function(i, dia) {
        $("<div>", { class: "nombre-dia-calendario" })
            .text(dia)
            .css("border", "1px solid black")
            .appendTo($filaDias);
    });
    
    $contenedor.append($filaDias);
}

/**
// MÉTODO PARA GENERAR TODOS LOS DIAS QUE TIENE ESE MES, Y GENERAR CLICK DE CADA CELDA
 * anio - AÑO USADO
 * mes - MES USADO (0-11)
 */
function crearCuadriculaCalendario(anio, mes) {
    // SE SELECCIONA LA CUADRÍCULA DEL CALENDARIO
    let $cuadricula = $("<div>", { class: "cuadricula-calendario" });
    
    // SE RECOGE EL PRIMER DÍA DEL MES PARA GENERAR TODO DESDE EL PRIMER HASTA EL ÚLTIMO DÍA DEL MES
    const primerDiaOriginal = new Date(anio, mes, 1).getDay();
    const primerDiaAjustado = (primerDiaOriginal === 0) ? 6 : primerDiaOriginal - 1;
    
    // SE GENERAN LAS CELDAS QUE VAN A ESTAR VACÍAS HASTA LLEGAR AL PRIMER DÍA DEL MES, PARA QUE LOS
    // DÍAS ESTÉN COLOCADOS DONDE TOCA EN EL HORARIO (EN LA ZONA DE MARTES, MIÉRCOLES, ETC)
    for (let i = 0; i < primerDiaAjustado; i++) {
        $("<div>", { class: "celda-calendario vacio" }).appendTo($cuadricula);
    }
    
    // SE GENERAN LAS CELDAS PARA CADA DÍA DEL MES
    const totalDias = new Date(anio, mes + 1, 0).getDate();
    for (let d = 1; d <= totalDias; d++) {
        crearCeldaDia($cuadricula, anio, mes, d);
    }
    // AL FINAL SE RETORNA LA CUADRÍCULA FORMADA POR COMPLETO
    return $cuadricula;
}

/**
   MÉTODO PARA CREAR CADA CELDA DEL DÍA QUE VA A TENER EL CALENDARIO
 * cuadricula - DIV PADRE SOBRE EL QUE SE VAN A INSERTAR LAS CELDAS DE LOS DÍAS
 * anio - AÑO
 * es - MES (0-11)
 * diaNumero - DÍA DEL MES QUE VA A GENERAR LA CELDA
 */
function crearCeldaDia($cuadricula, anio, mes, diaNumero) {
    // SE GENERA LA CELDA DEL DÍA Y SE PONE QUE ES SELECCIONABLE DE PRIMERAS
    let $celda = $("<div>", { 
        class: "celda-calendario", 
        "data-selectable": "true" 
    })
    // COMO TEXTO SE LE PONE EL NÚMERO DEL DÍA QUE LE TOCA
    .text(diaNumero)
    // SE PINTA COMO GRIS DE PRIMERAS
    .css("backgroundColor", "grey")
    .click(function(e) {
    // SE LE PROPORCIONA LA ACCIÓN DE CLICK QUE VA A TENER ESE DÍA
        manejarClicCeldaDia($(this), anio, mes, diaNumero);
    });
    // SE AÑADE LA CELDA AL CALENDARIO
    $cuadricula.append($celda);
}

/*
 MÉTODO PARA ASIGNAR EL COMPORTAMIENTO QUE VA A TENER EL CLICK DE ESA CELDA DEL CALENDARIO
 * celda - CELDA DEL DÍA DEL CALENDARIO SOBRE EL QUE SE VA A MANEJAR SU CLICK
 * anio - AÑO
 * mes - MES (0-11)
 * diaNumero - DÍA DEL MES
 */
function manejarClicCeldaDia($celda, anio, mes, diaNumero) {
    // SE GUARDA EN UNA VARIABLE LA FECHA DE LA CELDA
    let fechaCelda = new Date(anio, mes, diaNumero);
    // RETORNA EL ESTADO (COLOR DE CELDA, SI ES SELECCIONABLE Y TITLE PARA LA CELDA)
    let estado = estiloCeldaCarreras(datosReservas[diaNumero] || 0, fechaCelda);
    // SI NO ES SELECCIONABLE SE MUESTRA UNA NOTIFICACÍON Y SE TIRA PARA ATRÁS
    if (!estado.selectable) {
        mostrarNotificacion(estado.title, fechaCelda);
        return;
    }

    // EN CASO DE QUE EXISTA UNA CELDA SELECCIONADA Y SEA DISTINTA AL
    // DIV DE LA CELDA DEL DÍA ACTUAL, SE LE VUELVE A PONER SU COLOR ORIGINAL
    if (celdaSeleccionadaCarreras && celdaSeleccionadaCarreras !== $celda.get(0)) {
        $(celdaSeleccionadaCarreras).css("backgroundColor", $(celdaSeleccionadaCarreras).attr("data-status"));
    }

    // SE SELECCIONA LA NUEVA CELDA
    celdaSeleccionadaCarreras = $celda.get(0);
    // SE LE DA UN COLOR NARANJA
    $celda.css("backgroundColor", "#ffa500");
    // LA FECHA SELECCIONADA SE PONE COMO LA SELECCIONADA
    fechaSeleccionadaCarreras = fechaCelda;

    // DESACTIVAMOS EL CLICK EN LA CELDA MIENTRAS CARGAMOS LOS HORARIOS
    $(".celda-calendario").off("click");

    // DESACTIVAMOS TEMPORALMENTE LOS BOTONES DEL CALENDARIO
    $("#btnMesAnteriorCarreras").off("click");
    $("#btnMesSiguienteCarreras").off("click");

    // SE MUESTRA EL HORARIO DE LAS CARRERAS, PERO PRIMERO SE COMPRUEBA
    // QUE LA FECHA SEA MENOR, PARA VER SI SE PUEDE O NO CARGAR LOS HORARIOS
    verHorariosCarreras(fechaCelda, function() {
        // REACTIVAMOS EL CLICK EN LAS CELDAS
        $(".celda-calendario").on("click", function() {
            manejarClicCeldaDia($(this), anio, mes, parseInt($(this).text()));
        });

        // REACTIVAMOS LOS BOTONES DE NAVEGACIÓN DEL CALENDARIO
        $("#btnMesAnteriorCarreras").on("click", function() {
            let nuevoMes = fechaControlCarreras.getMonth() - 1;
            let nuevoAnio = fechaControlCarreras.getFullYear();
            if (nuevoMes < 0) { nuevoMes = 11; nuevoAnio--; }
            if (new Date(nuevoAnio, nuevoMes, 1) < new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), 1)) return;
            fechaControlCarreras = new Date(nuevoAnio, nuevoMes, 1);
            generarCalendarioCarreras();
        });

        $("#btnMesSiguienteCarreras").on("click", function() {
            fechaControlCarreras = new Date(fechaControlCarreras.getFullYear(), fechaControlCarreras.getMonth() + 1, 1);
            generarCalendarioCarreras();
        });
    });
}

/**
// MÉTODO PARA QUE EN CASO DE EXISTIR SESIÓN INICIADA, RECOGER LAS RESERVAS DE CARRERAS
   DE EL USUARIO CON LA SESIÓN ACTUAL
 */
function cargarReservasUsuarioCarreras() {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        $.ajax({
            url: base_url + "Usuario/obtenerReservasCarrerasActuales",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // SE RECOGE LA INFORMACIÓN
                reservasCarrerasUsuario = data;
                console.log("Reservas de carreras del usuario:", reservasCarrerasUsuario);
                // SE GENERA EL HORARIO TENIENDO EN CUENTA LAS RESERVAS DEL USUARIO
                generarCalendarioCarreras();
            },
            error: function(error) {
                console.error("Error al cargar reservas del usuario:", error);
                generarCalendarioCarreras();
            }
        });
    } else {
        generarCalendarioCarreras();
    }
}

// DETECTAR EL CLICK EN EN BOTÓN DE AYUDA Y ABRIR EL MODAL
$(document).on('click', '#btnAyudaCarreras', function (event) {
  event.preventDefault();
  $('#modalAyudaCarreras').modal('show');
});

/**
 * INICIALIZAR EL CALENDARIO AL CARGAR LA PÁGINA
 */
$(document).ready(function() {
    // VARIABLE PARA LA FECHA DE CONTROL, DE PRIMERAS LA FECHA ACTUAL
    fechaControlCarreras = new Date();
    
    // SE OBTIENEN LAS FRANJAS HORARIAS (SON 14)
    obtenerTotalFranjas()
        .then(function(total) {
            totalFranjas = total;
            // SE CARGAN LAS RESERVAS DE USUARIOS DE CARRERAS
            cargarReservasUsuarioCarreras();
        })
        .catch(function(error) {
            console.error("Error al obtener la información de franjas:", error);
            alert("Error al obtener la información de franjas. No se podrá generar el calendario.");
        });
});