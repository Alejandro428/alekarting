<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CalendarioModel;
use App\Models\EventoModel;

class Calendario extends ResourceController
{
    use ResponseTrait;

    /**
     * Carga la vista del calendario interactivo.
     */
    public function index()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

    // Si no es un empleado o no está logueado, muestra la página de calendario
    return view('calendario/calendarioVista');
}

    /**
     * Obtiene los días reservados para eventos para un año y mes determinados.
     * Ejemplo: /calendario/getDiasReservadosEventos?anio=2025&mes=07
     */
    public function getDiasReservadosEventos()
    {
        $anio = $this->request->getGet('anio');
        $mes  = $this->request->getGet('mes');

        if (!$anio || !$mes) {
            return $this->fail('Faltan los parámetros anio y/o mes', 400);
        }

        $model = new CalendarioModel();
        $dias = $model->getDiasReservadosEventos($anio, $mes);
        return $this->respond($dias, 200);
    }

    /*
    MÉTODO USADO EN JS carreraVista, SE USA PARA OBTENER TODAS LAS CARRERAS RESERVADAS
    DE EL MES Y AÑO PASADO POR GET
    
    TAMBIÉN USADO EN horarioCarrera
     */
    public function getReservasCountCarreras()
    {
        $anio = $this->request->getGet('anio');
        $mes  = $this->request->getGet('mes');

        if (!$anio || !$mes) {
            return $this->fail('Faltan los parámetros anio y/o mes', 400);
        }

        $model = new CalendarioModel();
        $counts = $model->getReservasCountCarreras($anio, $mes);
        return $this->respond($counts, 200);
    }

    /*
    MÉTODO USADO EN carreraVista, horarioCarrera, SE USA PARA OBTENER TODAS LAS CARRERAS RESERVADAS
    DE EL MES Y AÑO PASADO POR GET
    TAMBIÉN SE USA EN EL JS horarioCarrera
     */
    public function getHorariosDia()
    {
        $fecha = $this->request->getGet('fecha');

        if (!$fecha) {
            return $this->fail('Falta el parámetro fecha', 400);
        }

        $model = new CalendarioModel();
        $horarios = $model->getHorariosPorDia($fecha);
        return $this->respond($horarios, 200);
    }

    /*
    MÉTODO REFERENCIADO DESDE carreraVista y horarioCarrera, sirve para obtener
    la confirmación de la existencia de las 14 franjas horarias (horarios)
     */
    public function getTotalFranjas()
    {
        $model = new CalendarioModel();
        $total = $model->getTotalFranjas();
        return $this->respond($total, 200);
    }

    /* 
* MÉTODO REFERENCIADO DESDE horarioEvento, CONTROLADOR Calendario, Método getEventosconReservas,
* sirve para obtener los eventos de la fecha pasada por parámetro, además, también obtiene
* datos como son el total de reservados que hay en los eventos, para después
* pintar los días, dejar accesible o no el acceder a ese evento, etc.
*/
    public function getEventosConReservas()
    {
        $fecha = $this->request->getGet('fecha');
        if (!$fecha) {
            return $this->fail('Fecha no especificada', 400);
        }
        $model = new EventoModel();
        $eventos = $model->getEventosConReservas($fecha);
        return $this->respond($eventos, 200);
    }
}