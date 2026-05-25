<?php

namespace App\Controllers;

class SeccionLegal extends BaseController

{
    public function indexPrivacidad()
{
    return view('seccionlegal/privacidad') 
        . view('templates/footer');
}

    public function indexCondiciones()
{
    return view('seccionlegal/condiciones') 
        . view('templates/footer');
}

    public function indexCookies()
{
    return view('seccionlegal/cookies') 
        . view('templates/footer');
}

    public function indexAviso()
{
    return view('seccionlegal/aviso') 
        . view('templates/footer');
}

}