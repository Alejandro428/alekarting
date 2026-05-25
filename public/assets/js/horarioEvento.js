/** 
 - Fecha actual de control para navegación del calendario 
 */
let fechaControlEventos = new Date();

/*
 - Fecha de hoy para comparaciones 
 */
let fechaHoy = new Date();
fechaHoy.setHours(0, 0, 0, 0);

/*
 - Almacena las reservas de eventos del usuario actual 
 */
let reservasEventosUsuario = [];

/*
 - Referencia a la celda de calendario seleccionada 
 */
let celdaSeleccionadaEventos = null;

/** 
 - Fecha seleccionada en el calendario 
 */
let fechaSeleccionadaEventos = null;

/**
 MÉTODO PARA QUITAR SEGUNDOS DE LOS HORARIOS
 */
function formatearHora(hora) {
    return hora.split(":").slice(0, 2).join(":");
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

/**
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
 MÉTODO DE NOTIFICACIÓN NORMAL Y CORRIENTE
 */
function mostrarNotificacion(mensaje, fecha = null) {
    if (fecha instanceof Date) {
        mensaje += "<br><br><strong>" + formatearFechaCompleta(fecha) + "</strong>";
    }
    
    // Elimina notificaciones previas
    $("#divNotificacion").remove();
    
    // Crea el contenedor
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
    
    // Auto-eliminación después de 3 segundos
    let autoRemoveTimeout = setTimeout(function() {
        $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
        setTimeout(() => { $divNotificacion.remove(); }, 500);
    }, 3000);
    $divNotificacion.data("autoRemoveTimeout", autoRemoveTimeout);
}

/**
 MÉTODO QUE SE ENCARGA DE PREPARAR EL MENSAJE DE EL EVENTO SELECCIONADO, 
 EL MENSAJE ACLARA SI EL EVENTO ESTA COMPLETO O NO, Y ADEMÁS TIENE UN 
 ENLACE QUE REDIRIGE A EVENTOS Y USA EL FILTRO DE BÚSQUEDA PARA QUE
 SALGA EL EVENTO SELECCIONADO
 fecha - FECHA DEL EVENTO
 slot - DATOS DE TODO EL EVENTO
 enlace - URL PARA REDIRECCIÓN
 */
function mostrarNotificacionReservaEvento(fecha, slot, enlace) {
    let textoSeccion = "Eventos";
    let mensaje = "";
    
    // SE COMPRUEBA SI EL EVENTO TIENE UNA RESERVA POR EL USUARIO CON LA SESIÓN INICIADA Y
    // LA DISPONIBILIDAD DEL EVENTO
    const tieneReserva = (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) ?
        obtenerReservaUsuarioEventos(slot.id, formatearFechaLocal(fecha)) !== null : false;
    // SI SE ENCUENTRA UNA RESERVA POR PARTE DEL USUARIO SE QUEDA EN TRUE, SINO, SE QUEDA EN FALSE
    const estaCompleto = parseInt(slot.total_reservados) >= parseInt(slot.capacidad);
    // VARIABLE PARA SABER SI ESTA COMPLETO O NO EL EVENTO
    // MENSAJE COMPLETO DEL EVENTO
    mensaje += `
        <div class="cabecera-evento">
            <h3 style="${estaCompleto ? 'color: #d9534f;' : ''} ${tieneReserva ? 'color: #5bc0de;' : ''}">
                ${slot.nombre}
            </h3>
            ${estaCompleto ? '<div class="badge-completo">COMPLETO</div>' : ''}
            ${tieneReserva ? '<div class="badge-reserva">RESERVADO</div>' : ''}
        </div>`;
    
    mensaje += `
        <div class="detalles-evento">
            <p>${slot.descripcion}</p>
            <p><strong>Tipo:</strong> ${slot.tipo_evento || "N/D"}</p>
            <p><strong>Precio:</strong> ${slot.precio} €</p>
            <p><strong>Capacidad:</strong> ${slot.capacidad}</p>
            <p><strong>Reservados:</strong> ${slot.total_reservados}</p>
            <div class="disponibilidad">
                <p><strong>Estado:</strong> ${
                    tieneReserva ? '<span style="color: #5bc0de;">Tienes una reserva</span>' :
                    estaCompleto ? '<span style="color: #d9534f;">No hay disponibilidad</span>' :
                    '<span style="color: #5cb85c;">Hay plazas disponibles</span>'
                }</p>
            </div>
            <p><strong>Fecha:</strong> ${formatearFechaCompleta(fecha)}</p>
            <p><strong>Horario:</strong> ${formatearHora(slot.hora_inicio)} - ${formatearHora(slot.hora_fin)}</p>
        </div>`;
    
    if (estaCompleto) {
        mensaje += `
            <div class="alerta-completo">
                <p>Este evento ha alcanzado su capacidad máxima. No se aceptan más reservas.</p>
            </div>`;
    }
    
    if (tieneReserva) {
        mensaje += `
            <div class="alerta-reserva">
                <p>¡Tienes una reserva para este evento!</p>
            </div>`;
    }
    
    mensaje += `<p class="texto-accion">¿Deseas ir a la sección de ${textoSeccion} para más información?</p>`;
    
    // AL ACABAR DE FORMAR EL MENSAJE, SE LLAMA A MOSTRAR NOTIFICACIÓN RESERVA UI QUE SE ENCARGA DE YA MOSTRAR
    // LA NOTIFICACIÓN
    mostrarNotificacionReservaUI(mensaje, enlace, estaCompleto, tieneReserva, textoSeccion);
}

/**
 * MÉTODO PARA MOSTRAR LA INFORMACIÓN DEL EVENTO, Y EN CASO DE QUE EL USUARIO QUIERA, PUEDE REDIRIGIRSE A EVENTOS Y
 * VER O RESERVAR EL EVENTO
 * mensaje - MENSAJE COMPLETO DEL EVENTO
 * enlace - URL PARA REDIRECCIÓN
 * estaCompleto - VARIABLE PARA SABER SI ESTA COMPLETO EL EVENTO
 * tieneReserva - VARIABLE PARA SABER SI HAY RESERVA DEL EVENTO POR PARTE DEL USUARIO CON LA SESIÓN INICIADA
 * [textoSeccion="Reservas"] - NOMBRE DE LA SECCIÓN
 */
function mostrarNotificacionReservaUI(mensaje, enlace, estaCompleto = false, tieneReserva = false, textoSeccion = "Reservas") {
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
    
    // SE CREAN LOS DOS BOTONES, DE BOTON IR O CERRAR
    let $contenedorBotones = $("<div>", { class: "contenedor-botones" });

    // SE CONSTRUYE LA URL Y SE LE AÑADE LA VARIABLE EVENTO CON EL NOMBRE DEL EVENTO
    let urlConEvento = enlace + (enlace.includes('?') ? '&' : '?') + 
                      'evento=' + encodeURIComponent($contenedorDetalle.find('h3').text().trim());
    
    let $botonIr = $("<button>", { 
        class: "boton-ir" 
    })
    .text(`Ir a ${textoSeccion.toLowerCase()}`)
    .click(function(e) {
        e.stopPropagation();
        // AL HACER CLICK EN EL BOTÓN IR, SE REDIRIGE A LA URL CON LA VARIABLE DEL EVENTO SELECCIONADO
        window.location.href = urlConEvento;
    });
    
    if (estaCompleto) {
        $botonIr.attr("title", "Este evento está completo");
    } else if (tieneReserva) {
        $botonIr.attr("title", "Ya tienes una reserva para este evento");
    }
    
    let $botonCerrar = $("<button>", { class: "boton-cerrar" })
        .text("Cerrar")
        .click(function(e) {
            e.stopPropagation();
            // SI SE CANCELA, SE ELIMINA LA NOTIFICACIÓN
            $divNotificacion.removeClass("fadeIn").addClass("fadeOut");
            setTimeout(() => { $divNotificacion.remove(); }, 500);
        });
    
    $contenedorBotones.append($botonIr).append($botonCerrar);
    $divNotificacion.append("<br><br>").append($contenedorBotones);
    $("body").append($divNotificacion);
}

/**
  MÉTODO PARA ENCONTRAR LA RESERVA DEL EVENTO DEL USUARIO CON UNA FECHA Y ID
  DEL EVENTO POR PARÁMETRO, EN CASO DE QUE NO EXISTA RETORNA NULL
  eventoId - ID DEL EVENTO
  fechaStr - FECHA EN FORMATO YYYY-MM-DD
 */
function obtenerReservaUsuarioEventos(eventoId, fechaStr) {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        return reservasEventosUsuario.find(function(r) {
            return String(r.evento_id) === String(eventoId) && r.fecha === fechaStr;
        }) || null;
    }
    return null;
}

/**
   MÉTODO PARA COMPROBAR SI HAY RESERVA EN UN DÍA EN ESPECÍFICO POR PARTE DEL USUARIO
   CON LA ACTUAL SESIÓN INICIADA
   fechaStr - FECHA EN FORMATO YYYY-MM-DD
 */
function usuarioTieneReservaEnDiaEventos(fechaStr) {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        return reservasEventosUsuario.some(function(r) {
            return r.fecha === fechaStr;
        });
    }
    return false;
}

/**
 * MÉTODO PARA PINTAR LAS CELDAS DE LOS DÍAS DEL CALENDARIO DEL EVENTO
 * cuenta - Número de reservas para ese día
   CUENTA EQUIVALE A LA CANTIDAD DE FRANJAS (HORARIOS) YA ESCOGIDOS
   ESE DÍA
 * fechaCelda - Fecha representada por la celda
 * reservasAgregadas - TOTAL DE RESERVAS PARA ESE DÍA, ESTO SE UTILIZA YA
 * QUE SI HAY UN EVENTO COMPLETO, PERO EL MISMO DÍA HAY OTRO EVENTO QUE NO ESTA
 * COMPLETO, NO QUIERO PINTAR EL DÍA ROJO, YA QUE SI SE PODRÁN HACER RESERVAS,
 * LO QUE TENDRÉ QUE TENER EN CUENTA EN OTRO MÉTODO ES EL PINTAR LOS EVENTOS DE 
 * ESE DÍA DEL COLOR QUE CORRESPONDA DEPENDIENDO DE SUS RESERVAS
 * fechaCelda - FECHA DEL DIA DEL CALENDARIO
 * capacidadAgregada - CAPACIDAD TOTAL DE LOS EVENTOS, SE UTILIZA PARA CALCULAR
 * SI ESE DÍA ESTA COMPLETO DE RESERVAS O NO CON LAS RESERVAS AGREGADAS
 * tieneEventos - VARIABLE PARA SABER SI HAY EVENTOS ESE DÍA
 */
function estiloCeldaEventos(reservasAgregadas, fechaCelda, capacidadAgregada, tieneEventos) {
    const fechaStr = formatearFechaEuropeo(fechaCelda);
    const fechaFormateada = formatearFechaLocal(fechaCelda);

    // PRIMERO SE COMPRUEBA SI LA FECHA ES ANTERIOR A LA ACTUAL
    // (ESTA VERIFICACIÓN DEBE TENER PRIORIDAD MÁXIMA)
    if (fechaCelda < fechaHoy) {
        return { 
            color: "red", 
            selectable: false, 
            title: `El ${fechaStr} ya ha pasado` 
        };
    }

    // SEGUNDO SE COMPRUEBA SI EXISTEN RESERVAS POR PARTE DEL USUARIO
    // EN ALGUNO DE LOS EVENTOS DE ESE DÍA, EN CASO AFIRMATIVO, SE PINTA
    // EL DÍA DE AZUL
    if (usuarioTieneReservaEnDiaEventos(fechaFormateada)) {
        return { 
            color: "lightblue", 
            selectable: true, 
            title: `Tienes una reserva para el ${fechaStr}` 
        };
    }
    
    // TERCERO SE COMPRUEBA SI LA CAPACIDAD ES MAYOR A 0 Y SI HAY EVENTOS EN ESE
    // DÍA, SI NO SE CUMPLE SE PONE EL DÍA EN ROJO ENNEGRECIDO Y SE MANDA QUE LA
    // FECHA NO TIENE EVENTOS
    if (capacidadAgregada > 0 || tieneEventos) {
        if (reservasAgregadas === 0) {
            return { 
                color: "green", 
                selectable: true, 
                title: `El ${fechaStr} tiene eventos disponibles (sin reservas)` 
            };
        } else if (reservasAgregadas > 0 && reservasAgregadas < capacidadAgregada) {
            return { 
                color: "grey", 
                selectable: true, 
                title: `El ${fechaStr} tiene eventos con disponibilidad` 
            };
        } else {
            return { 
                color: "red", 
                selectable: true, 
                title: `El ${fechaStr} está completamente reservado` 
            };
        }
    } 
    
    // CUARTO: Días sin eventos
    return { 
        color: "#63d471", 
        selectable: false, 
        title: `El ${fechaStr} no tiene eventos` 
    };
}

/**
 * ACTUALIZAR LOS ESTILOS DE LAS CELDAS DEL CALENDARIO
 * $cuadricula - DIV PADRE QUE CONTIENE LAS CELDAS DE LOS DÍAS
 * anio - Año mostrado
 * mes - Mes mostrado (0-11)
 */
function actualizarEstiloCeldasEventos($cuadricula, anio, mes) {
    // ESCOJO LAS CELDAS QUE NO ESTÁN VACÍAS (LAS QUE VOY A PINTAR)
    let $celdas = $cuadricula.find(".celda-calendario:not(.vacio)");
    // RECORRO ESAS CELDAS DE DÍAS
    $celdas.each(function(indice) {
        // ESE FOREACH ES PARA QUE HAYA ANIMACIÓN, Y QUE VAYA PINTANDO LOS DÍAS DE DÍA 1 AL ÚLTIMO DEL MES UNO A UNO
        // Y NO TODOS AL MISMO TIEMPO
        setTimeout(() => {
            // RECOJO EL DÍA DE LA CELDA
            let dia = parseInt($(this).text());
            // CALCULO ESA FECHA CON EL MES Y AÑO QUE TENGO Y CON EL DÍA DE LA CELDA
            let fechaCelda = new Date(anio, mes, dia);
            // ME QUEDO CON LA FECHA FORMATEADA
            let fechaFormateada = formatearFechaLocal(fechaCelda);
            
            // HAGO UN AJAX CON EL QUE OBTENGO LOS DATOS QUE HAY DE CADA EVENTO EN CADA DÍA
            $.ajax({
                url: `${base_url}calendario/getEventosConReservas?fecha=${fechaFormateada}`,
                type: "GET",
                dataType: "json",
                success: (data) => {
                    // SI ME LLEGA UN ARRAY, HAGO TODO LO SIGUIENTE
                    data = $.isArray(data) ? data : [];
                    // COMPRUEBO SI EL DÍA TIENE EVENTOS
                    let tieneEventos = data.length > 0;
                    // ME QUEDO CON EL TOTAL DE RESERVAS AGREGADAS QUE HAY EN ESE DÍA (LAS RESERVAS AGREGADAS SERÁN LA SUMA DE 
                    // LAS RESERVAS DE TODOS LOS EVENTOS QUE TENGA EL DÍA)
                    let reservasAgregadas = data.reduce((suma, evento) => suma + parseInt(evento.total_reservados || 0), 0);
                    // ME QUEDO CON LA CAPACIDAD QUE HAY EN ESE DÍA (LA CAPACIDAD SERÁ LA SUMA DE 
                    //  LA CAPACIDAD QUE TENGAN TODOS LOS EVENTOS QUE TENGA EL DÍA)
                    let capacidadAgregada = data.reduce((suma, evento) => suma + parseInt(evento.capacidad || 0), 0);
                    // LE PASO A ESE MÉTODO ESAS VARIABLES Y ME RETORNA UN OBJETO CON COMO TENGO QUE PINTAR EL DÍA
                    // SI ES SELECCIONABLE, Y SU TITLE EN LA CELDA
                    let estado = estiloCeldaEventos(reservasAgregadas, fechaCelda, capacidadAgregada, tieneEventos);
                    
                    // AHORA LE PONGO COMO ATRIBUTOS TODOS LOS DATOS NECESARIOS AL DÍA
                    $(this).attr("data-reservas", reservasAgregadas)
                           .attr("data-capacidad", capacidadAgregada)
                           .attr("data-tiene-eventos", tieneEventos)
                           .css("backgroundColor", estado.color)
                           .attr("data-selectable", estado.selectable ? "true" : "false")
                           .attr("title", estado.title)
                           .attr("data-status", estado.color);
                },
                error: (error) => {
                    console.error(`Error obteniendo eventos para ${fechaFormateada}:`, error);
                }
            });
        }, indice * 10); // RETARDO PARA TENER ANIMACIÓN A PROPOSITO AL CARGAR EL CALENDARIO 
    });
}

/**
 * CARGAR Y MOSTRAR LOS EVENTOS DE UN DÍA EN ESPECÍFICO
 * fecha - FECHA A CONSULTAR 
 * alTerminar - CallBack
 */
function verEventos(fecha, alTerminar) {
    // CONTENEDOR DONDE SE MUESTRAN LOS DETALLES DE LOS EVENTOS
    let $divDetalles = $("#detalles");
    $divDetalles.html(``);  // SE LIMPIA EL CONTENIDO PREVIO

    // ME QUEDO CON LA FECHA FORMATEADA
    const fechaFormateada = formatearFechaLocal(fecha);

    // HAGO UNA PETICIÓN AJAX PARA OBTENER TODOS LOS EVENTOS DE ESE DÍA 
    $.ajax({
        url: `${base_url}calendario/getEventosConReservas?fecha=${fechaFormateada}`,
        type: "GET",
        dataType: "json",
        success: function(data) {
            // SI NO HAY EVENTOS, SE MUESTRA UN MENSAJE PARA AVISAR AL USUARIO
            if (!$.isArray(data) || data.length === 0) {
                $divDetalles.html(`<p>No hay eventos para ${fecha.toLocaleDateString()}.</p>`);
                if (alTerminar) alTerminar();
                return;
            }

            // SE CREA EL CONTENEDOR PARA LOS EVENTOS
            let $contenedorEventos = $("<div>", { class: "contenedor-eventos" });

            // INTERVALO DE ANIMACIÓN ENTRE CADA EVENTO
            const intervaloEntreTarjetasMs = 40;

            // SE RECORRE TODOS LOS EVENTOS Y SE LES METE UN PEQUEÑO RETRASO
            $.each(data, function(i, evento) {
                setTimeout(() => {
                    // MÉTODO PARA IR CREANDO CADA EVENTO
                    crearElementoEvento($contenedorEventos, evento, fechaFormateada, i);
                    // SI ES EL ÚLTIMO EVENTO, YA SE LLAMA AL CALLBACK PARA QUE
                    // HABILITE DE NUEVO PODER HACER CLICK EN OTROS DÍAS
                    // PARA PODER CONTROLAR CORRECTAMENTE LOS DÍAS Y SUS EVENTOS,
                    // MIENTRAS ESTÁN CON SU ANIMACIÓN, ES NECESARIO DESHABILITAR SUS
                    // BOTONES, AL ACABAR, SE VUELVEN A HABILITAR 
                    if (i === data.length - 1 && alTerminar) {
                        alTerminar();
                    }
                }, i * intervaloEntreTarjetasMs);
            });

            $divDetalles.empty().append($contenedorEventos);
        },
        error: function(error) {
            console.error(`Error obteniendo eventos para ${fechaFormateada}`, error);
            $divDetalles.html(`<p>Error al cargar eventos.</p>`);

            // EN CASO DE ERROR, SE VUELVE A HABILITAR LOS BOTONES TAMBIÉN
            if (alTerminar) alTerminar();
        }
    });
}

/**
 * CREAR Y CONFIGURAR UN ELEMENTO DE EVENTO PARA MOSTRARLO CON ANIMACIÓN
 * $contenedor - CONTENEDOR DONDE AÑADIR EL EVENTO
 * evento - DATOS DEL EVENTO
 * fechaFormateada - FECHA EN FORMATO YYYY-MM-DD
 * index - ÍNDICE PARA ESCALONAR LA ANIMACIÓN
 */
function crearElementoEvento($contenedor, evento, fechaFormateada, index = 0) {
    // CREO EL DIV PARA EL EVENTO 
    let $divEvento = $("<div>", { class: "evento" });

    let fechaInicioEvento = new Date(`${evento.fecha} ${evento.hora_inicio}`);
    let fechaActual = new Date();
    let esPasado = fechaActual > fechaInicioEvento;

    // PONGO EN EL DIV EL NOMBRE DEL EVENTO PARA PODER RECONOCERLO FÁCILMENTE 
    $divEvento.html(`<h4>${evento.nombre}</h4>`);

    // SI EL EVENTO ES PASADO, LO PINTO DE ROJO Y LE ENSEÑO EN CASO DE HACERLE CLICK QUE ES UN EVENTO PASADO, JUNTO CON SU DÍA
    if (esPasado) {
        $divEvento.css("backgroundColor", "red").click(function (e) {
            e.stopPropagation();
            mostrarNotificacion(
                `<strong>Evento pasado:</strong> ${evento.nombre}<br>
                <strong>Fecha:</strong> ${formatearFechaCompleta(new Date(evento.fecha))}<br>
                <strong>Horario:</strong> ${formatearHora(evento.hora_inicio)} - ${formatearHora(evento.hora_fin)}<br>
                Este evento ya ha comenzado o finalizado.`
            );
        });
    } 
    // SI EL EVENTO ES FUTURO, ASIGNO LO QUE TIENE EL EVENTO 
    else {
        // SI TIENE LA SESIÓN INICIADA EL USUARIO, OBTENGO LOS EVENTOS QUE TENGA RESERVADOS EL USUARIO Y ME LOS GUARDO
        let reservaUsuario = null;
        if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
            reservaUsuario = obtenerReservaUsuarioEventos(evento.id, fechaFormateada);
        }

        // SI EL EVENTO ESTA RESERVADO, LO PINTO DE AZUL PARA REPRESENTAR QUE ESTA RESERVADO
        if (reservaUsuario) {
            $divEvento.css("backgroundColor", "lightblue");
        } else {
        // SINO, SIMPLEMENTE CALCULO DEPENDIENDO DE LAS RESERVAS QUE TIENE ESE EVENTO Y LA CAPACIAD QUE TIENE DISPONIBLE
        // EL COMO PINTAR EL EVENTO, SI ESTA SIN RESERVAS LO PONGO VERDE, SI ESTA CON VARIAS RESERVAS, PERO SIN LLEGAR AL TOPE
        // LO PINTO DE GRIS, Y SI ESTA LLENO, LO PINTO DE ROJO    
            let reservas = parseInt(evento.total_reservados) || 0;
            let capacidad = parseInt(evento.capacidad) || 0;

            if (reservas === 0) {
                $divEvento.css("backgroundColor", "green");
            } else if (reservas > 0 && reservas < capacidad) {
                $divEvento.css("backgroundColor", "grey");
            } else if (reservas >= capacidad) {
                $divEvento.css("backgroundColor", "red");
            }
        }

        // DESPUÉS DE ESO, LE CONFIGURO EL MENSAJE QUE TIENE QUE MOSTRAR AL HACER CLICK A ESE EVENTO
        $divEvento.click(function (e) {
            e.stopPropagation();
            mostrarNotificacionReservaEvento(new Date(evento.fecha), evento, `${base_url}Eventos`);
        });
    }

    // AÑADO EL EVENTO AL CONTENEDOR DEL EVENTO
    $contenedor.append($divEvento);

    // USO EL OFFSET HEIGHT PARA QUE SE APLIQUE CORRECTAMENTE LA ANIMACIÓN
    $divEvento[0].offsetHeight;

    // SE HACE LA ANIMACIÓN APLICANDO UNA CLASE
    setTimeout(() => {
        $divEvento.addClass("animarEntrada");
    }, 50 * index);
}

/*
MÉTODO PARA GENERAR EL CALENDARIO, PARTICIONADO EN VARIOS MÉTODOS, YA QUE
TIENE VARIAS PARTES
 */
function generarCalendarioEventos() {
    // SE RECOGE EL CONTENEDOR DEL CALENDARIO
    let $contenedor = $("#calendarioEventos");
    // SE RESTABLECE LA FECHA Y LA CELDA SELECCIONADA
    celdaSeleccionadaEventos = null;
    fechaSeleccionadaEventos = null;
    
    const anio = fechaControlEventos.getFullYear();
    const mes = fechaControlEventos.getMonth();
    $contenedor.empty();
    
    // GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
    crearEncabezadoCalendario($contenedor, anio, mes);
    
    // GENERAR DIAS DE LA SEMANA
    crearFilaDiasSemana($contenedor);
    
     // GENERAR TODOS LOS DIAS QUE TIENE ESE MES, Y LUEGO GENERAR CLICK DE CADA CELDA
    let $cuadricula = crearCuadriculaCalendario(anio, mes);
    $contenedor.append($cuadricula);
    
    // POR ÚLTIMO, PINTO LAS CELDAS DE ESE MES DEPENDIENDO DE LOS EVENTOS QUE TENGA ESE MES
    actualizarEstiloCeldasEventos($cuadricula, anio, mes);
}

/**
   // MÉTODO PARA GENERAR ENCABEZADO Y CONTROL DE MES SIGUIENTE Y ANTERIOR
 * contenedor - ES EL DIV DEL CONTENEDOR DEL CALENDARIO
 * anio - AÑO QUE SE MUESTRA EN EL CALENDARIO
 * mes - MES MOSTRADO EN EL CALENDARIO (0-11)
 */
function crearEncabezadoCalendario($contenedor, anio, mes) {
    let $encabezado = $("<div>", { class: "encabezado-calendario" });
    
    // AL CAMBIAR DE MES, LIMPIO LOS EVENTOS QUE PUDIERAN ESTAR CARGADOS
    $("#detalles").empty();

    // CONTROL AL HACER CLICK A MES ANTERIOR, SI EL MES ES ANTERIOR AL MES DE LA ACTUALIDAD,
    // NO SE DEJA RETROCEDER
    // SE VUELVE A LLAMAR A GENERAR CALENDARIO CON LA FECHA DE CONTROL DE CARRERAS ACTUALIZADA
    let $btnAnterior = $("<button>", { id: "btnMesAnteriorEventos" }).text("<").click(function() {
        let nuevoMes = fechaControlEventos.getMonth() - 1;
        let nuevoAnio = fechaControlEventos.getFullYear();
        if (nuevoMes < 0) { nuevoMes = 11; nuevoAnio--; }
        if (new Date(nuevoAnio, nuevoMes, 1) < new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), 1)) return;
        fechaControlEventos = new Date(nuevoAnio, nuevoMes, 1);
        generarCalendarioEventos();
    });
    
    // CONTROL AL HACER CLICK A MES SIGUIENTE, SE VUELVE A LLAMAR A GENERAR CALENDARIO CON 
    // LA FECHA DE CONTROL DE CARRERAS ACTUALIZADA
    let $btnSiguiente = $("<button>", { id: "btnMesSiguienteEventos" }).text(">").click(function() {
        fechaControlEventos = new Date(fechaControlEventos.getFullYear(), fechaControlEventos.getMonth() + 1, 1);
        generarCalendarioEventos();
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
    
    $.each(diasSemana, function(i, dia) {
        $("<div>", { class: "nombre-dia-calendario" })
            .text(dia)
            .css("border", "1px solid black")
            .appendTo($filaDias);
    });
    
    $contenedor.append($filaDias);
}

/**
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
    // ME GUARDO TANTO LAS RESERVAS QUE TIENE CADA EVENTO COMO LA CAPACIDAD QUE TIENE, PARA VER DE QUE
    // COLOR PINTO EL DÍA
    let reservasAgregadas = parseInt($celda.attr("data-reservas")) || 0;
    let capacidadAgregada = parseInt($celda.attr("data-capacidad")) || 0;
    // COMPRUEBO SI EL DÍA TIENE EVENTOS
    let tieneEventos = $celda.attr("data-tiene-eventos") === "true";

    // SI LA FECHA ES ANTERIOR, MUESTRO QUE ES UN DÍA PASADO
    if (fechaCelda < fechaHoy) {
        mostrarNotificacion(`El ${formatearFechaEuropeo(fechaCelda)} ya ha pasado.`, fechaCelda);
        $("#detalles").empty();
        return;
    }

    // SI NO TIENE EVENTOS SIMPLEMENTE DIGO QUE NO TIENE EVENTOS
    if (!tieneEventos) {
        mostrarNotificacion(`El ${formatearFechaEuropeo(fechaCelda)} no tiene eventos.`, fechaCelda);
        $("#detalles").empty();
        return;
    }

    // SI ESTÁ COMPLETO NOTIFICO QUE EL EVENTO ESTÁ COMPLETO
    if (reservasAgregadas >= capacidadAgregada) {
        mostrarNotificacion(
            `Los eventos para el ${formatearFechaEuropeo(fechaCelda)} están completos.<br>
            Puedes ver los detalles pero no podrás reservar.`, 
            fechaCelda
        );
    }

    // SI YA EXISTE UNA CELDA SELECCIONADA, Y ESA CELDA ES DISTINTA A LA QUE ACABO DE SELECCIONAR, PINTO
    // EL DÍA ANTERIOR DE SU COLOR ORIGINAL, YA QUE QUIERO QUE SOLO ESTE COMO SELECCIONADO EL DÍA QUE
    // ACABO DE SELECCIONAR, NO EL DE ANTES
    if (celdaSeleccionadaEventos && celdaSeleccionadaEventos !== $celda.get(0)) {
        $(celdaSeleccionadaEventos).css("backgroundColor", $(celdaSeleccionadaEventos).attr("data-status"));
    }
    // HAGO QUE LA CELDA SELECCIONADA SEA LA QUE ACABO DE SELECCIONAR
    celdaSeleccionadaEventos = $celda.get(0);
    // LE DOY UN BACKGROUND COLOR DE NARANJA
    $celda.css("backgroundColor", "#ffa500");
    // ME GUARDO TAMBIÉN LA FECHA SELECCIONADA
    fechaSeleccionadaEventos = fechaCelda;

    // DESACTIVAR CLICK EN CELDAS MIENTRAS CARGAMOS EVENTOS
    $(".celda-calendario").off("click");

    // DESACTIVAR BOTONES DE NAVEGACIÓN DEL CALENDARIO
    $("#btnMesAnteriorEventos").off("click");
    $("#btnMesSiguienteEventos").off("click");

    // MUESTRO LOS EVENTOS QUE TIENE EL DÍA SELECCIONADO, Y ADEMÁS LE PASO UN CALLBACK
    // PARA QUE CUANDO TERMINE DE HACER LO QUE TIENE QUE HACER, QUE HABILITE DE NUEVO
    // EL CLICK A LAS CELDAS, Y QUE SE PUEDA VOLVER A PASAR DE MES EN EL CALENDARIO
    verEventos(fechaCelda, function() {
        // REACTIVAR CLICK EN CELDAS
        $(".celda-calendario").on("click", function() {
            manejarClicCeldaDia($(this), anio, mes, parseInt($(this).text()));
        });

        // REACTIVAR BOTONES DE NAVEGACIÓN
        $("#btnMesAnteriorEventos").on("click", function() {
            let nuevoMes = fechaControlEventos.getMonth() - 1;
            let nuevoAnio = fechaControlEventos.getFullYear();
            if (nuevoMes < 0) { nuevoMes = 11; nuevoAnio--; }
            if (new Date(nuevoAnio, nuevoMes, 1) < new Date(fechaHoy.getFullYear(), fechaHoy.getMonth(), 1)) return;
            fechaControlEventos = new Date(nuevoAnio, nuevoMes, 1);
            generarCalendarioEventos();
        });

        $("#btnMesSiguienteEventos").on("click", function() {
            fechaControlEventos = new Date(fechaControlEventos.getFullYear(), fechaControlEventos.getMonth() + 1, 1);
            generarCalendarioEventos();
        });
    });
}

/**
 * CARGA LAS RESERVAS DE EVENTOS DEL USUARIO ACTUAL
 */
function cargarReservasUsuarioEventos() {
    if (typeof datosSesion !== "undefined" && datosSesion.sesion_iniciada) {
        $.ajax({
            url: base_url + "Usuario/obtenerReservasEventosActuales",
            type: "GET",
            dataType: "json",
            success: function(data) {
                reservasEventosUsuario = data;
                console.log("Reservas de eventos del usuario:", reservasEventosUsuario);
                generarCalendarioEventos();
            },
            error: function(error) {
                console.error("Error al cargar reservas de eventos:", error);
                generarCalendarioEventos();
            }
        });
    } else {
        generarCalendarioEventos();
    }
}

// DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL DE EVENTOS
$(document).on('click', '#btnAyudaEventos', function (event) {
  event.preventDefault();
  $('#modalAyudaEventos').modal('show');
});

/**
 * INICIALIZAR EL CALENDARIO AL CARGAR LA PÁGINA
 */
$(document).ready(function() {
    fechaControlEventos = new Date();
    cargarReservasUsuarioEventos();
});