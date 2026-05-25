<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas para el perfil y usuario
$routes->get('Perfil', 'Perfil::index');
$routes->post('Perfil/actualizarCredenciales', 'Perfil::actualizarCredenciales');
$routes->get('Usuario/obtenerHistorialReservasEventos', 'Usuario::obtenerHistorialReservasEventos');
$routes->get('Usuario/obtenerHistorialReservasCarreras', 'Usuario::obtenerHistorialReservasCarreras');

// Rutas de inicio y secciones generales
$routes->get('Inicio', 'Inicio::index');
$routes->get('Sobre_Nosotros', 'SobreNosotros::index');

// Rutas para noticias
$routes->get('Noticias', 'Noticias::inicioNoticias');
$routes->get('noticias/getNoticias', 'Noticias::getNoticias');
$routes->get('noticias/getNoticiasPopulares', 'Noticias::getNoticiasPopulares');
$routes->post('noticias/sumarVisita/(:num)', 'Noticias::sumarVisita/$1');
$routes->get('noticias/detalle/(:num)', 'Noticias::detalle/$1');

// Rutas para categorías
$routes->get('categorias/getCategorias', 'Categorias::getCategorias');

// Rutas para eventos
$routes->get('Eventos', 'Eventos::index');
$routes->get('eventos/proximos', 'Eventos::getEventosProximos');
$routes->get('Usuario/obtenerReservasEventosActuales', 'Usuario::obtenerReservasEventosActuales');
//$routes->post('reservaseventos/reservarEvento', 'ReservasEventos::reservarEvento');

// Rutas para tipo de eventos
$routes->get('tipoEventos/getTipoEventos', 'TipoEvento::getTipoEventos');

// Rutas para carreras
$routes->get('Carreras', 'Carreras::index');
$routes->post('carreras/reservar', 'Carreras::reservar');
$routes->get('EmpleadoCarreras/verificarDisponibilidad', 'EmpleadoCarreras::verificarDisponibilidad');
$routes->get('Usuario/obtenerReservasCarrerasActuales', 'Usuario::obtenerReservasCarrerasActuales');

// Rutas para pistas
$routes->get('pistas/getPistas', 'Pistas::getPistas');

// Rutas para el calendario
$routes->get('calendario/getDiasReservadosEventos', 'Calendario::getDiasReservadosEventos');
$routes->get('calendario/getReservasCountCarreras', 'Calendario::getReservasCountCarreras');
$routes->get('calendario/getReservasCountEventos', 'Calendario::getReservasCountEventos');
$routes->get('calendario/getHorariosDia', 'Calendario::getHorariosDia');
$routes->get('calendario/getTotalFranjas', 'Calendario::getTotalFranjas');
$routes->get('calendario/getEventosPorMes', 'Calendario::getEventosPorMes');
$routes->get('calendario/getEventosConReservas', 'Calendario::getEventosConReservas');

// Rutas adicionales
$routes->get('Horarios', 'Horarios::index');

$routes->get('Horarios/Carreras', 'Horarios::indexCarreras');
$routes->get('Horarios/Eventos', 'Horarios::indexEventos');

$routes->get('Contacto', 'Contacto::index');
$routes->get('Iniciar_Sesion', 'Usuario::inicioSesionShow');
$routes->post('Procesar_Sesion', 'Usuario::procesarSesion');
$routes->get('Registro', 'Usuario::registroShow');
$routes->post('Usuario/comprobarExistencia', 'Usuario::comprobarExistencia');
$routes->post('Usuario/crear', 'Usuario::crearUsuario');
$routes->get('Cerrar_Sesion', 'Usuario::cerrarSesion');

// Rutas para empleados y administración
$routes->get('Empleado', 'Empleado::index');
$routes->get('Inicio_Empleado', 'Empleado::inicio'); // Para empleados
$routes->get('Empleado/cambiarCredenciales', 'Empleado::indexCambiarCredenciales'); // Para empleados
$routes->post('Empleado/actualizarCredenciales', 'Empleado::actualizarCredencialesEmpleado');

$routes->get('Empleado/Carreras', 'EmpleadoCarreras::indexCarreras');
$routes->get('Empleado/Eventos', 'EmpleadoEventos::indexEventos');
$routes->get('Empleado/Noticias', 'EmpleadoNoticias::indexNoticias');
// Nueva ruta para usuarios activos
$routes->get('Empleado/getUsuariosActivos', 'Empleado::getUsuariosClientesActivos');

// Rutas para EmpleadoNoticias
$routes->post('EmpleadoNoticias/crearNoticias', 'EmpleadoNoticias::crearNoticias');
$routes->get('EmpleadoNoticias/obtenerNoticiasDeUsuario', 'EmpleadoNoticias::obtenerNoticiasDeUsuario');
$routes->get('EmpleadoNoticias/obtenerNoticia/(:num)', 'EmpleadoNoticias::obtenerNoticia/$1');
$routes->post('EmpleadoNoticias/editarNoticias/(:num)', 'EmpleadoNoticias::editarNoticias/$1');
$routes->post('EmpleadoNoticias/eliminarNoticias/(:num)', 'EmpleadoNoticias::eliminarNoticias/$1');

// Rutas para EmpleadoEventos
$routes->get('EmpleadoEventos/obtenerEventosEmpleado', 'EmpleadoEventos::obtenerEventosEmpleado');
$routes->get('EmpleadoEventos/obtenerEvento/(:num)', 'EmpleadoEventos::obtenerEventoEdicion/$1');

$routes->get('EmpleadoEventos/obtenerClientesEvento/(:num)', 'EmpleadoEventos::obtenerClientesEvento/$1');
$routes->get('EmpleadoEventos/obtenerHorariosDisponibles', 'EmpleadoEventos::obtenerHorariosDisponibles');
$routes->post('EmpleadoEventos/crearEvento', 'EmpleadoEventos::crearEvento');   
$routes->post('EmpleadoEventos/editarEvento/(:num)', 'EmpleadoEventos::editarEvento/$1');
$routes->get('EmpleadoEventos/verificarReservasEvento/(:num)', 'EmpleadoEventos::verificarReservasEvento/$1');
$routes->post('EmpleadoEventos/eliminarEvento/(:num)', 'EmpleadoEventos::eliminarEvento/$1');
$routes->get('EmpleadoEventos/obtenerReservasEventoEscogido/(:num)', 'EmpleadoEventos::obtenerReservasEventoEscogido/$1');
$routes->get('EmpleadoEventos/obtenerReservaEventoUsuario/(:num)', 'EmpleadoEventos::obtenerReservaEventoUsuario/$1');
$routes->post('EmpleadoEventos/crearReserva', 'EmpleadoEventos::crearReserva');
//$routes->post('EmpleadoEventos/editarReserva/(:num)', 'EmpleadoEventos::editarReserva/$1'); COMENTADO PORQUE NO TIENE SENTIDO EDITAR UNA RESERVA YA EXISTENTE
$routes->post('EmpleadoEventos/eliminarReserva/(:num)', 'EmpleadoEventos::eliminarReserva/$1');

// Rutas para EmpleadoCarreras

$routes->get('EmpleadoCarreras/obtenerReservasCarrerasEmpleado', 'EmpleadoCarreras::obtenerReservasEmpleado');
$routes->get('EmpleadoCarreras/obtenerDiasDisponibles', 'EmpleadoCarreras::obtenerDiasDisponibles');
$routes->get('EmpleadoCarreras/obtenerHorariosDisponibles', 'EmpleadoCarreras::obtenerHorariosDisponibles');
$routes->get('EmpleadoCarreras/obtenerReservaCarreraEdicion/(:num)', 'EmpleadoCarreras::obtenerReservaCarreraEdicion/$1');
$routes->post('EmpleadoCarreras/crearReservaCarrera', 'EmpleadoCarreras::crearReservaCarrera');
$routes->post('EmpleadoCarreras/editarReservaCarrera/(:num)', 'EmpleadoCarreras::editarReservaCarrera/$1');
$routes->post('EmpleadoCarreras/eliminarReservaCarrera/(:num)', 'EmpleadoCarreras::eliminarReservaCarrera/$1');

$routes->get('Admin', 'Admin::index');
$routes->get('Admin/gestionUsuarios', 'Admin::indexGestionUsuarios');
$routes->get('Admin/gestionEmpleados', 'Admin::indexGestionEmpleados');
$routes->get('Admin/obtenerUsuariosClientes', 'Admin::obtenerUsuariosClientes');
$routes->post('Admin/desactivarUsuario/(:num)', 'Admin::desactivarUsuario/$1');
$routes->post('Admin/activarUsuario/(:num)', 'Admin::activarUsuario/$1');
$routes->get('Admin/obtenerUsuarioEdicion/(:num)', 'Admin::obtenerUsuarioEdicion/$1');
$routes->post('Admin/editarUsuario/(:num)', 'Admin::editarUsuario/$1');
$routes->get('Admin/obtenerEmpleados', 'Admin::obtenerEmpleados');

$routes->get('Admin/verificarEventosPendientes/(:num)', 'Admin::verificarEventosPendientes/$1');
$routes->post('Admin/desactivarEmpleadoEvento/(:num)', 'Admin::desactivarEmpleadoEvento/$1');
$routes->post('Admin/activarEmpleadoEvento/(:num)', 'Admin::activarEmpleadoEvento/$1');

$routes->post('Admin/activarEmpleadoNoticia/(:num)', 'Admin::activarEmpleadoNoticia/$1');
$routes->post('Admin/desactivarEmpleadoNoticia/(:num)', 'Admin::desactivarEmpleadoNoticia/$1');

$routes->get('Admin/verificarCarrerasPendientes/(:num)', 'Admin::verificarCarrerasPendientes/$1');
$routes->post('Admin/desactivarEmpleadoCarrera/(:num)', 'Admin::desactivarEmpleadoCarrera/$1');
$routes->post('Admin/activarEmpleadoCarrera/(:num)', 'Admin::activarEmpleadoCarrera/$1');

$routes->get('Admin/verificarRolesEmpleado/(:num)', 'Admin::verificarRolesEmpleado/$1');
$routes->post('Admin/desactivarEmpleado/(:num)', 'Admin::desactivarEmpleado/$1');
$routes->post('Admin/activarEmpleado/(:num)', 'Admin::activarEmpleado/$1');
$routes->post('Admin/validarCamposUnicos', 'Admin::validarCamposUnicos');
$routes->post('Admin/crearEmpleado', 'Admin::crearEmpleado');
$routes->post('Admin/editarEmpleado/(:num)', 'Admin::editarEmpleado/$1');
$routes->post('Admin/crearAdministrador', 'Admin::crearAdministrador');

$routes->get('Admin/gestionNoticias', 'Admin::indexGestionNoticias');
$routes->get('EmpleadoNoticias/obtenerTodasLasNoticias', 'EmpleadoNoticias::obtenerTodasLasNoticias');
$routes->post('Categorias/crearCategoria', 'Categorias::crearCategoria');
$routes->post('Categorias/editarCategoria', 'Categorias::editarCategoria');
$routes->post('Categorias/eliminarCategoria', 'Categorias::eliminarCategoria');
$routes->post('Categorias/verificarCategoria', 'Categorias::verificarCategoria');
$routes->post('Categorias/verificarUsoCategoria', 'Categorias::verificarUsoCategoria');

$routes->get('Admin/gestionCarreras', 'Admin::indexGestionCarreras');
$routes->get('EmpleadoCarreras/obtenerTodasLasCarreras', 'EmpleadoCarreras::obtenerTodasLasCarreras');
$routes->get('Admin/getEmpleadosCarreras', 'Admin::getEmpleadosCarreras');

$routes->post('Pistas/crearPista', 'Pistas::crearPista');
$routes->post('Pistas/editarPista', 'Pistas::editarPista');
$routes->post('Pistas/verificarPista', 'Pistas::verificarPista');
$routes->post('Pistas/verificarUsoPista', 'Pistas::verificarUsoPista');
$routes->post('Pistas/eliminarPista', 'Pistas::eliminarPista');

// Rutas DELETE para cancelar reservas
$routes->delete('Usuario/cancelarReservaEvento', 'Usuario::cancelarReservaEvento');
$routes->delete('Usuario/cancelarReservaCarrera', 'Usuario::cancelarReservaCarrera');
$routes->post('Usuario/actualizarCredenciales', 'Usuario::actualizarCredenciales');

$routes->get('Admin/gestionEventos', 'Admin::indexGestionEventos');
$routes->get('EmpleadoEventos/obtenerTodosLosEventos', 'EmpleadoEventos::obtenerTodosLosEventos');
$routes->get('Admin/getEmpleadosEventos', 'Admin::getEmpleadosEventos');

$routes->post('TiposEvento/crearTipoEvento', 'TipoEvento::crearTipoEvento');
$routes->post('TiposEvento/editarTipoEvento', 'TipoEvento::editarTipoEvento');
$routes->post('TiposEvento/verificarTipoEvento', 'TipoEvento::verificarTipoEvento');
$routes->post('TiposEvento/verificarUsoTipoEvento', 'TipoEvento::verificarUsoTipoEvento');
$routes->post('TiposEvento/eliminarTipoEvento', 'TipoEvento::eliminarTipoEvento');

$routes->get('Admin/getEmpleadosNoticias', 'Admin::getEmpleadosNoticias');

$routes->get('Admin/cambiarCredenciales', 'Admin::indexCambiarCredenciales'); // Para empleados

$routes->get('pasarela', 'Pago::indexPasarela');
$routes->post('pago/guardar_reserva', 'Pago::guardar_reserva');
$routes->get('pago/pasarela', 'Pago::pasarela');
$routes->get('pago/fallo', 'Pago::fallo');

// Ruta para procesar el pago (POST)
$routes->post('pago/procesar', 'Pago::procesar');

// Ruta para la página de pago completado (GET)
$routes->get('pago/completado', 'Pago::completado');

// Ruta para el webhook de Stripe (POST)
$routes->post('pago/limpiarSesionResidual', 'Pago::limpiarSesionResidual');

$routes->post('Admin/crearReservaCarrera', 'Admin::crearReservaCarreraAdmin');
$routes->post('Admin/editarReservaCarrera/(:num)', 'Admin::editarReservaCarreraAdmin/$1');

$routes->post('EmpleadoEventos/comprobarReservaExistente', 'EmpleadoEventos::comprobarReservaExistente');

$routes->get('eventos/proximosEmpleado', 'Eventos::getEventosProximosEmpleado');

$routes->post('Admin/verificarContrasenaActual', 'Admin::verificarContrasenaActual');
$routes->post('Admin/cambiarContrasena', 'Admin::cambiarContrasena');

$routes->get('Admin/obtenerAdmins', 'Admin::obtenerAdmins');

$routes->post('Admin/crearUsuario', 'Admin::crearUsuario');
$routes->post('Admin/editarUsuario/(:num)', 'Admin::editarUsuario/$1');
$routes->get('Admin/obtenerEmpleadoEdicion/(:num)', 'Admin::obtenerEmpleadoEdicion/$1');

$routes->post('EmpleadoEventos/confirmarPago/(:num)', 'EmpleadoEventos::confirmarPago/$1');

$routes->get('EmpleadoEventos/obtenerFechaEvento/(:num)', 'EmpleadoEventos::obtenerFechaEvento/$1');

$routes->post('EmpleadoCarreras/confirmarPago/(:num)', 'EmpleadoCarreras::confirmarPago/$1');

$routes->get('Admin/pagosPorDia', 'RegistroPagos::index');
$routes->get('RegistroPagos/obtenerTodosLosDiasConPagos', 'RegistroPagos::obtenerTodosLosDiasConPagos');

$routes->post('Contactos/enviar', 'CorreoController::enviar');
$routes->post('Contactos/enviarContacto', 'CorreoController::enviarContacto');

$routes->get('Usuario/getUsuarioPorId/(:num)', 'Usuario::getUsuarioPorId/$1');

$routes->post('Carreras/verificarDisponibilidadHorario', 'Carreras::verificarDisponibilidadHorario');

$routes->get('Recuperar', 'Usuario::recuperarContrasenaShow');

$routes->post('Correo/solicitarRecuperacion', 'CorreoController::solicitarRecuperacion');
$routes->get('restablecer-contrasena', 'CorreoController::mostrarFormularioRestablecer');
$routes->post('restablecer-contrasena', 'CorreoController::restablecerContrasena');

$routes->post('Usuario/verificarContrasenaActual', 'Usuario::verificarContrasenaActual');

$routes->get('Privacidad', 'SeccionLegal::indexPrivacidad');
$routes->get('Condiciones', 'SeccionLegal::indexCondiciones');
$routes->get('Cookies', 'SeccionLegal::indexCookies');
$routes->get('Aviso', 'SeccionLegal::indexAviso');

// Ruta por defecto
$routes->get('/', 'Inicio::index');
