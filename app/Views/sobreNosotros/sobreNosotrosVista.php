<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sobre Nosotros - Karting Experience</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css') ?>" />
  <style>
    :root {
      --azul-principal: #1565c0;
      --rojo-principal: #d32f2f;
      --rojo-oscuro: #b71c1c;
      --gris-suave: #f5f5f5;
      --gris-texto: #555;
      --sombra-suave: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background-color: var(--gris-suave);
      color: #333;
    }

    .sobre-nosotros-section {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 4rem 2rem;
      background-color: #fff;
      border-radius: 16px;
      box-shadow: var(--sombra-suave);
      animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .sobre-nosotros-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .sobre-nosotros-header h1 {
      font-size: 3.2rem;
      color: var(--azul-principal);
      margin-bottom: 0.5rem;
    }

    .sobre-nosotros-header p {
      font-size: 1.2rem;
      color: var(--gris-texto);
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .sobre-nosotros-contenido {
      display: flex;
      flex-wrap: wrap;
      gap: 2.5rem;
      margin-top: 3rem;
    }

    .sobre-nosotros-texto {
      flex: 1 1 600px;
      line-height: 1.8;
    }

    .sobre-nosotros-texto h2 {
      color: var(--azul-principal);
      margin-top: 2.2rem;
      font-size: 1.6rem;
    }

    .sobre-nosotros-texto p {
      margin-bottom: 1.4rem;
      font-size: 1.05rem;
      color: #444;
    }

    .sobre-nosotros-imagenes {
      flex: 1 1 400px;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .sobre-nosotros-imagenes img {
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .sobre-nosotros-imagenes img:hover {
      transform: scale(1.02);
    }

    @media (max-width: 768px) {
      .sobre-nosotros-contenido {
        flex-direction: column;
      }

      .sobre-nosotros-header h1 {
        font-size: 2.4rem;
      }
    }
  </style>
</head>
<body>
  <section class="sobre-nosotros-section">
    <div class="sobre-nosotros-header">
      <h1>Sobre Nosotros</h1>
      <p>Una historia de pasión por la velocidad, la innovación y las experiencias inolvidables sobre ruedas.</p>
    </div>

    <div class="sobre-nosotros-contenido">
      <div class="sobre-nosotros-texto">

        <h2>Nuestra Historia</h2>
        <p>
          Karting Experience nació en 2010 con un único objetivo: acercar la emoción del automovilismo a todos. Desde nuestros inicios como un pequeño circuito en las afueras de la ciudad, hemos crecido gracias a la confianza de nuestros visitantes, la calidad de nuestros servicios y nuestra obsesión por brindar una experiencia inolvidable. Hoy contamos con una de las pistas más modernas del país y una comunidad apasionada que nos impulsa a seguir creciendo.
        </p>

        <h2>Filosofía y Valores</h2>
        <p>
          Creemos que el karting es más que una competencia: es una forma de conectar con la adrenalina, la precisión, y el trabajo en equipo. Nuestros valores están basados en el respeto, la seguridad y la excelencia. Nos esforzamos por mantener una cultura de inclusión, donde cualquier persona —sin importar edad, género o experiencia— pueda disfrutar del mundo del karting.
        </p>

        <h2>La Experiencia del Cliente</h2>
        <p>
          Lo que nos diferencia es la forma en la que cuidamos cada detalle. Desde el momento en que llegas, nuestro equipo te recibe con profesionalismo y entusiasmo. Te guiamos en todo el proceso, desde la selección del equipo de seguridad hasta las técnicas básicas de conducción. Nuestras instalaciones están diseñadas para que toda la familia disfrute: zonas de espera cómodas, cafetería, tienda de merchandising y actividades para niños.
        </p>

        <h2>Compromiso con la Seguridad</h2>
        <p>
          La seguridad es nuestra prioridad absoluta. Todos nuestros karts están equipados con sistemas de corte remoto, frenos de alta precisión y carrocería reforzada. Además, renovamos nuestros cascos y trajes de seguridad regularmente, y nuestro personal está entrenado en primeros auxilios y protocolos de emergencia. Queremos que te diviertas con total tranquilidad.
        </p>

        <h2>Nuestro Equipo</h2>
        <p>
          Detrás de cada curva hay un equipo humano apasionado. Desde mecánicos con años de experiencia en competición, hasta instructores certificados que forman nuevos talentos día a día. También contamos con un staff administrativo que trabaja para organizar eventos corporativos, torneos y actividades personalizadas para cada cliente. Todos compartimos la misma meta: hacerte sentir como un verdadero piloto.
        </p>

        <p>
          Gracias por ser parte de esta historia. Si aún no nos has visitado, te invitamos a ponerte el casco, apretar el acelerador y descubrir por qué somos mucho más que un kartódromo.
        </p>
      </div>

      <div class="sobre-nosotros-imagenes">
        <img src="<?= base_url('assets/imagenes/kartRojoSobreNosotros.jpg') ?>" alt="Piloto en kart rojo en curva">
        <img src="<?= base_url('assets/imagenes/imagen2SobreNosotrosMod.webp') ?>" alt="Vista de pista profesional de karting">
      </div>
    </div>
  </section>
</body>
</html>
