<?php

namespace App\Controllers;

class Horarios extends BaseController
{
    public function index()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

    // Si no es un empleado o no está logueado, muestra la página de horario
    return view('templates/navbar') . view('horario/horarioVista') . view('templates/footer');
}

public function indexCarreras()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no es un empleado o no está logueado, muestra la página de horario
    return view('templates/navbar') . view('horario/horarioCarrera') . view('templates/footer');
}

public function indexEventos()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no es un empleado o no está logueado, muestra la página de horario
    return view('templates/navbar') . view('horario/horarioEvento') . view('templates/footer');
}
}