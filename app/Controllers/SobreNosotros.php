<?php

namespace App\Controllers;

class SobreNosotros extends BaseController
{
    public function index()
{
    $sesion = session();

    // Verifica si el usuario está logueado como empleado
    if (strtolower($sesion->get('tipo_usuario')) === 'empleado') {
        return redirect()->to(base_url('Empleado'));  // Redirige a la página de inicio del empleado
    }

     // Verifica si el usuario está logueado como admin
    if (strtolower($sesion->get('tipo_usuario')) === 'admin') {
        return redirect()->to(base_url('Admin'));  // Redirige a la página de inicio del admin
    }
    
    // Si no es empleado, muestra la página de "Sobre Nosotros"
    return view('templates/navbar') 
        . view('sobreNosotros/sobreNosotrosVista') 
        . view('templates/footer');
}
}