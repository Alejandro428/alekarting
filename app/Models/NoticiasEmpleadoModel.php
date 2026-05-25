<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiasEmpleadoModel extends Model
{
    protected $table = 'noticias';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'titulo', 
        'subtitulo', 
        'contenido', 
        'imagen', 
        'video', 
        'fecha_publicacion', 
        'id_categoria', 
        'empleado_id', 
        'visitas'
    ];
     // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de obtener 
    // las noticias del empleado, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerNoticiasDeUsuario
    public function obtenerNoticiasEmpleado($empleado_id)
{
    return $this->select('noticias.*, categoria.nombre_categoria, usuarios.nombre_usuario')
                ->join('categoria', 'categoria.id = noticias.id_categoria', 'left')
                ->join('empleados', 'empleados.id = noticias.empleado_id', 'left') // Primero a empleados
                ->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left') // Luego a usuarios
                ->where('noticias.empleado_id', $empleado_id)
                ->orderBy('noticias.fecha_publicacion', 'DESC')
                ->findAll();
}

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de obtener
    // todas las noticias existentes en la web, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerTodasLasNoticias
public function obtenerTodasLasNoticias()
{
    return $this->select('noticias.*, categoria.nombre_categoria, usuarios.nombre_usuario')
                ->join('categoria', 'categoria.id = noticias.id_categoria', 'left')
                ->join('empleados', 'empleados.id = noticias.empleado_id', 'left') // Relación con empleados
                ->join('usuarios', 'usuarios.id = empleados.usuario_id', 'left') // Relación con usuarios
                ->orderBy('noticias.fecha_publicacion', 'DESC')
                ->findAll();
}


}
