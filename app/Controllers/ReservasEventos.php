<?php

namespace App\Controllers;

use App\Models\EventoModel;
use App\Models\FranjasHorariasModel;
use App\Models\ReservasEventosModel;

class ReservasEventos extends BaseController
{
    /**
     * Realiza la reserva de un evento.
     * Recibe los datos en formato JSON con al menos:
     * - evento_id: ID del evento a reservar.
     * - cantidad: número de participantes.
     * 
     * Retorna JSON con true si se insertó correctamente o false en caso contrario.
      */

    
   /* public function reservarEvento()
{
    // Iniciar la sesión y obtener el ID del usuario actual
    $session = session();
    $usuario_id = $session->get('id');
    if (!$usuario_id) {
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode(false));
    }

    // Obtener los datos enviados en formato JSON
    $data = $this->request->getJSON(true);

    // Validar campos mínimos necesarios
    if (!isset($data['evento_id'], $data['cantidad'])) {
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode(false));
    }

    $evento_id = $data['evento_id'];
    $cantidad  = $data['cantidad'];

    // Valores predefinidos para método de pago y fecha de pago
    $metodo_pago = "tarjeta";
    $fecha_pago  = date('Y-m-d'); // Usamos fecha actual para el pago

    // Obtener el evento
    $eventoModel = new EventoModel();
    $evento = $eventoModel->find($evento_id);
    if (!$evento) {
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode(false));
    }

    // Validar que el evento tenga franja horaria definida
    if (!isset($evento['franja_horaria_id'])) {
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode(false));
    }

    // Validar que la franja existe
    $franjasModel = new FranjasHorariasModel();
    $franja = $franjasModel->find($evento['franja_horaria_id']);
    if (!$franja) {
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode(false));
    }

    // Calcular el precio final
    $total = $evento['precio'] * $cantidad;

    // Construir datos de reserva (sin fecha ni franja_horaria_id)
    $datosReserva = [
        'usuario_id'         => $usuario_id,
        'evento_id'         => $evento_id,
        'cantidad'          => $cantidad,
        'total'             => $total,
        'estado'            => 'pagado',
        'metodo_pago'       => $metodo_pago,
        'fecha_pago'        => $fecha_pago
    ];

    // Insertar la reserva
    $reservasEventosModel = new ReservasEventosModel();
    $insertId = $reservasEventosModel->insert($datosReserva);
    
    return $this->response
                ->setContentType('application/json')
                ->setBody(json_encode($insertId ? true : false));
}
ESTE METODO LO USABA PREVIO A HACER LOS PAGOS, AHORA UTILIZO EL RESERVAR EVENTO DE EL CONTROLADOR DE PAGOS                
*/
    
}