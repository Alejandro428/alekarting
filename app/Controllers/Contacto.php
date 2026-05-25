<?php

namespace App\Controllers;

class Contacto extends BaseController
{
    public function index()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al inicio de empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no está logueado como empleado, muestra la página de contacto
    return view('templates/navbar') . view('contacto/contactoVista') . view('templates/footer');
}
}