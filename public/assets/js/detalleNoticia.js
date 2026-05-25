$(document).ready(function() {
  // BOTÓN DE REGREASR Y EL CONTENIDO DE LA NOTICIA
  const botonRegresar = $('.back-button');
  const contenidoNoticia = $('.news-content');
  // PÍXELES QUE NECESITA EL SCROLL PARA HABILITAR SU BOTÓN DE REGRESAR
  const puntoActivacion = 300; // 
  const debounceDelay = 100; // TIEMPO DE ESPERA PARA QUE SE VUELVA A ESCONDER O MOSTRAR EL SCROLL
  
  // FUNCIÓN PARA MANEJAR EL SCROLL
  let timeout;
  function manejarScroll() {
    // CANCELO EL TEMPORIZADOR ANTERIOR SI AÚN NO HA TERMINADO
    // PARA EVITAR EJECUTAR LA FUNCIÓN VARIAS VECES SEGUIDAS
    clearTimeout(timeout);
    timeout = setTimeout(function() {
      const scrollActual = $(window).scrollTop();
      
      if (scrollActual > puntoActivacion) {
        botonRegresar.addClass('visible');
      } else {
        botonRegresar.removeClass('visible');
      }
    }, debounceDelay);
  }
  
  // FUNCIÓN PARA SUMAR VISITA
  function sumarVisita(noticiaId) {
    if (!noticiaId) return;
    // HAGO UNA CONSULTA QUE SUMA 1 A LA NOTICIA QUE SE VISITA
    fetch(`${base_url}noticias/sumarVisita/${noticiaId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
      console.log("Visita registrada:", data.message);
    })
    .catch(error => {
      console.error("Error al registrar visita:", error);
    });
  }
  // HAGO QUE SE TENGA EN CUENTA EL MÉTODO MANEJAR SCROLL
  // CADA VEZ QUE SE SCROLLEA EN LA PÁGINA
  $(window).on('scroll', manejarScroll);
  
  // SI SE HACE CLICK EN REGRESAR, ME REGRESA A LA ANTERIOR
  // VENTANA DE DONDE VENGO (DEBERÍA DE SER EL APARTADO DE NOTICIAS)
  botonRegresar.on('click', function(e) {
    e.preventDefault();
    window.history.back();
  });
  
  // SI EXISTE EL ID DE LA NOTICIA, LE SUME 1 A LA NOTÍCIA
  const contenedorNoticia = $('main.detalle-noticia');
  const noticiaId = contenedorNoticia.data('noticia-id');
  if (noticiaId) {
    sumarVisita(noticiaId);
  }
  
  // AL EJECUTAR EL CÓDIGO, SE EJECUTA EL MANEJAR SCROLL DE PRIMERAS.
  manejarScroll();
});
