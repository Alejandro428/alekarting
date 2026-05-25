var noticiasData = []; // Almacena todas las noticias obtenidas

/**
 * Procesa una noticia asignando valores por defecto.
 */
function procesarNoticia(noticia) {
  return {
    id: noticia.id,
    titulo: noticia.titulo || "Sin título",
    subtitulo: noticia.subtitulo || "",
    imagen: noticia.imagen || "",
    categoria: noticia.nombre_categoria || "General",
    url: noticia.url || (base_url + "noticias/detalle/" + noticia.id),
    visitas: noticia.visitas || 0
  };
}

/**
 * Recorta un texto si supera una longitud máxima y añade puntos suspensivos.
 * text - El texto original.
 *  maxLen - Longitud máxima permitida.
 * returns string - El texto cortado con “…” si superaba maxLen, o el texto completo si no.
 */
function truncateText(text, maxLen) {
  // Si el texto ya es igual o más corto que el máximo, lo devolvemos tal cual
  if (text.length <= maxLen) {
    return text;
  }

  // Si es más largo:
  // 1. slice(0, maxLen - 1): tomamos los primeros maxLen - 1 caracteres
  //    (reservamos 1 carácter para el “…”).
  // 2. trimEnd(): eliminamos espacios al final para no dejar espacio antes de los puntos.
  // 3. + "…": añadimos el carácter de puntos suspensivos.
  return text.slice(0, maxLen - 1)
             .trimEnd()
             + "…";
}

/**
 *
 * CREAR LA TARJETA DE UNA NOTICIA
 */
function crearTarjetaNoticia(noticia) {
  var noticiaData = procesarNoticia(noticia);

  // LONGITUD DE TÍTULO Y SUBTÍTULO MÁXIMOS
  var maxLenTitulo = 60;
  var maxLenSubtitulo = 45;

  // APLICAR EL TRUNCADO PARA LAS NOTICIAS
  var tituloMostrar = truncateText(noticiaData.titulo, maxLenTitulo);
  var subtituloMostrar = truncateText(noticiaData.subtitulo, maxLenSubtitulo);

  var $enlace = $("<a>", { href: noticiaData.url });
  var $divTarjeta = $("<div>", { "class": "tarjeta-noticia" });

  var $imgNoticia = $("<img>", {
    "class": "noticia-img",
    src: noticiaData.imagen
      ? (base_url + "assets/imagenes/noticias/imgs/" + noticiaData.imagen)
      : (base_url + "assets/imagenes/noticias/imgs/ejemplo1Noticia.jpg"),
    alt: noticiaData.titulo
  });
  $divTarjeta.append($imgNoticia);

  var $divOverlay = $("<div>", { "class": "overlay-text" });

  var $h3Titulo = $("<h3>")
    .text(tituloMostrar)
    .attr("title", noticiaData.titulo);
  $divOverlay.append($h3Titulo);

  var $pSubtitulo = $("<p>")
    .text(subtituloMostrar)
    .attr("title", noticiaData.subtitulo);
  $divOverlay.append($pSubtitulo);

  var $pCategoria = $("<p>").text("Categoría: " + noticiaData.categoria);
  $divOverlay.append($pCategoria);

  $divTarjeta.append($divOverlay);
  $enlace.append($divTarjeta);

  return $enlace;
}


/**
 * CREAR LA TARJETA HTML DE UNA NOTICIA POPULAR
 */
function crearTarjetaNoticiaPopular(noticia) {
  var noticiaData = procesarNoticia(noticia);

  // LONGITUD DE TÍTULO Y SUBTÍTULO MÁXIMOS
  var maxLenTitulo = 60;
  var maxLenSubtitulo = 45;

  // APLICAR EL TRUNCADO PARA LAS NOTICIAS
  var tituloMostrar = truncateText(noticiaData.titulo, maxLenTitulo);
  var subtituloMostrar = truncateText(noticiaData.subtitulo, maxLenSubtitulo);

  var $enlace = $("<a>", { href: noticiaData.url });
  var $divTarjeta = $("<div>", { "class": "tarjeta-popular" });

  var $imgContainer = $("<div>", { "class": "img-container" });
  var $imgNoticia = $("<img>", {
    "class": "noticia-img",
    src: noticiaData.imagen
      ? (base_url + "assets/imagenes/noticias/imgs/" + noticiaData.imagen)
      : (base_url + "assets/imagenes/noticias/imgs/ejemplo1Noticia.jpg"),
    alt: noticiaData.titulo
  });
  $imgContainer.append($imgNoticia);
  $divTarjeta.append($imgContainer);

  var $divOverlay = $("<div>", { "class": "overlay-text-popular" });

  var $h3Titulo = $("<h3>")
    .text(tituloMostrar)
    .attr("title", noticiaData.titulo); 
  $divOverlay.append($h3Titulo);

  var $pSubtitulo = $("<p>")
    .text(subtituloMostrar)
    .attr("title", noticiaData.subtitulo); 
  $divOverlay.append($pSubtitulo);

  var $pCategoria = $("<p>").text("Categoría: " + noticiaData.categoria);
  $divOverlay.append($pCategoria);

  $divTarjeta.append($divOverlay);
  $enlace.append($divTarjeta);

  return $enlace;
}

/**
 * RENDERIZAR LAS NOTICIAS PRINCIPALES EN LA IZQUIERDA
 */
function pintarNoticias(noticias) {
  var $contenedorNoticias = $(".noticias-izquierda");

  // Verifica si el contenedor existe
  if (!$contenedorNoticias.length) {
    console.error("No se encontró el contenedor con clase 'noticias-izquierda'.");
    return;
  }

  // REALIZAR UNA TRANSICIÓN DE OCULTAR Y MOSTRAR EL CONTENEDOR DE NOTICIAS
  $contenedorNoticias.fadeOut(200, function () {
    // SI NO HAY NOTICIAS, MOSTRAR EL MENSAJE CORRESPONDIENTES
    if (noticias.length === 0) {
      $contenedorNoticias.html("<p class='no-noticias'>No existen noticias disponibles.</p>");
    } else {
      // SI HAY NOTICIAS, LIMPIAR EL CONTENEDOR Y AGREGAR LAS NUEVAS TARJETAS
      $contenedorNoticias.empty();
      $.each(noticias, function (i, noticia) {
        var $tarjeta = crearTarjetaNoticia(noticia);
        $contenedorNoticias.append($tarjeta);
      });
    }

    // VUELVE A MOSTRAR EL CONTENEDOR CON UNA ANIMACIÓN
    $contenedorNoticias.fadeIn(200);
  });
}


/**
 * PINTAR LAS NOTICIAS POPULARES EN LA DERECHA
 */
function pintarNoticiasPopulares(noticias) {
  var $contenedorPopulares = $(".noticias-derecha");
  if (!$contenedorPopulares.length) {
    console.error("No se encontró el contenedor con clase 'noticias-derecha'.");
    return;
  }
  $contenedorPopulares.html("<h3>Noticias Populares</h3>");

  if (noticias.length === 0) {
    $contenedorPopulares.append("<p class='no-noticias'>No existen noticias populares.</p>");
    return;
  }

  $.each(noticias, function(i, noticia) {
    var $tarjeta = crearTarjetaNoticiaPopular(noticia);
    $contenedorPopulares.append($tarjeta);
  });
}

/**
 * Filtra las noticias según la categoría y el nombre.
 */
function aplicarFiltros() {
  var categoriaSeleccionada = $("#filtro-categorias").val();
  var nombreFiltro = $("#filtro-nombre-noticia").val().toLowerCase().trim();
  var ordenFecha = $("#filtro-fecha").val();

  var noticiasFiltradas = noticiasData;

  // Filtrar por categoría
  if (categoriaSeleccionada !== "todas") {
    noticiasFiltradas = noticiasFiltradas.filter(function(noticia) {
      return noticia.id_categoria === categoriaSeleccionada;
    });
  }

  // Filtrar por nombre de noticia
  if (nombreFiltro !== "") {
    noticiasFiltradas = noticiasFiltradas.filter(function(noticia) {
      return noticia.titulo.toLowerCase().includes(nombreFiltro);
    });
  }

  // Ordenar por fecha
  if (ordenFecha === "recientes") {
    noticiasFiltradas.sort(function(a, b) {
      return new Date(b.fecha_publicacion) - new Date(a.fecha_publicacion); // más recientes primero
    });
  } else if (ordenFecha === "antiguas") {
    noticiasFiltradas.sort(function(a, b) {
      return new Date(a.fecha_publicacion) - new Date(b.fecha_publicacion); // más antiguas primero
    });
  }

  console.log("Noticias filtradas:", noticiasFiltradas);
  pintarNoticias(noticiasFiltradas);
}

/**
 * MÉTODO PARA CARGAR TODAS LAS NOTICIAS.
 */
function cargarNoticias() {
  $.ajax({
    url: base_url + "noticias/getNoticias",
    type: "GET",
    dataType: "json",
    success: function(data) {
      console.log("Noticias cargadas:", data); // LOG
      noticiasData = data;
      pintarNoticias(noticiasData);
    },
    error: function(xhr, status, error) {
      console.error("Error al obtener noticias:", error);
      $(".noticias-izquierda").html("<p>Error al cargar noticias.</p>");
    }
  });
}

/**
 * MÉTODO PARA CARGAR TODAS LAS NOTICIAS POPULARES.
 */
function cargarNoticiasPopulares() {
  $.ajax({
    url: base_url + "noticias/getNoticiasPopulares",
    type: "GET",
    dataType: "json",
    success: function(data) {
      console.log("Noticias populares cargadas:", data); // LOG
      pintarNoticiasPopulares(data);
    },
    error: function(xhr, status, error) {
      console.error("Error al obtener noticias populares:", error);
      $(".noticias-derecha").append("<p>Error al cargar noticias populares.</p>");
    }
  });
}

/**
 * MÉTODO PARA CARGAR LAS CATEGORÍAS PARA EL FILTRO.
 */
function llenarOpcionesCategorias() {
  $.ajax({
    url: base_url + "categorias/getCategorias",
    type: "GET",
    dataType: "json",
    success: function(data) {
      console.log("Categorías cargadas:", data); // LOG
      var $select = $("#filtro-categorias");
      $select.html('<option value="todas">Todas las categorías</option>');
      $.each(data, function(i, cat) {
        var $option = $("<option>", { value: cat.id, text: cat.nombre_categoria });
        $select.append($option);
      });
    },
    error: function(xhr, status, error) {
      console.error("Error al cargar categorías:", error);
    }
  });
}

// EVENTO PARA DETECTAR CLICK EN EL BOTÓN DE AYUDA Y ABRIR EL MODAL DE NOTICIAS
$(document).on('click', '#btnAyudaNoticias', function (event) {
  event.preventDefault();
  $('#modalAyudaNoticias').modal('show');
});

$(document).ready(function() {
  console.log("Inicializando...:", !!$.ui?.autocomplete); // LOG

  // CARGAR LAS NOTICIAS AL PRINCIPIO
  cargarNoticias();
  cargarNoticiasPopulares();
  llenarOpcionesCategorias();

  // REINICIAR FILTROS AL CARGAR LA PÁGINA
  $("#filtro-categorias").val("todas");
  $("#filtro-fecha").val("recientes");
  $("#filtro-nombre-noticia").val("");

  // PINTAR NOTICIAS SIN FILTROS AL PRINCIPIO
  pintarNoticias(noticiasData);

  // EVENTOS PARA APLICAR FILTROS
  $("#filtro-categorias").on("change", aplicarFiltros);
  $("#filtro-nombre-noticia").on("input", aplicarFiltros);
  $("#filtro-fecha").on("change", aplicarFiltros);  

  // BOTÓN DE LIMPIAR FILTROS
  $("#btn-limpiar-filtros").on("click", function() {
    $("#filtro-categorias").val("todas");
    $("#filtro-fecha").val("recientes"); 
    $("#filtro-nombre-noticia").val("");
    aplicarFiltros(); // LIMPIAR FILTROS Y VOLVER A MOSTRAR TODAS LAS NOTICIAS
  });

  // JQUERY UI AUTOCOMPLETE
  $("#filtro-nombre-noticia").autocomplete({
    source: function(request, response) {
      var termino = request.term.toLowerCase();
      var categoriaSeleccionada = $("#filtro-categorias").val();
      
      console.log("Autocomplete solicitado con término:", termino);
  
      // FILTRAR NOTICIAS POR CATEGORÍA Y TÉRMINO ESCRITO
      var resultados = noticiasData
        .filter(function(noticia) {
          var coincideCategoria = (categoriaSeleccionada === "todas") || (noticia.id_categoria === categoriaSeleccionada);
          var coincideTexto = noticia.titulo.toLowerCase().includes(termino); 
          return coincideCategoria && coincideTexto;
        });
  
      console.log("Resultados filtrados:", resultados);
  
      if (resultados.length > 0) {
        response(resultados.map(function(noticia) {
          return {
            label: noticia.titulo + " (" + noticia.nombre_categoria + ")",
            value: noticia.titulo
          };
        }));
      } else {
        console.log("No se encontraron resultados para el término:", termino);
        response([]);
      }
    },
    minLength: 1,
    select: function(event, ui) {
      console.log("Sugerencia seleccionada:", ui.item);
      $("#filtro-nombre-noticia").val(ui.item.value);
      aplicarFiltros();  
      return false;  
    }
  });
});

