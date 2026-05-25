<?php
namespace App\Models;
use CodeIgniter\Model;
class NoticiasModel extends Model
{
     
     protected $table      = 'noticias';
     protected $primaryKey = 'id';
 
    
     protected $allowedFields = [
         'titulo',
         'contenido',
         'imagen',
         'video',
         'fecha_publicacion',
         'id_categoria',
         'empleado_id',
         'visitas'
     ];

     // MÉTODO REFERENCIADO EN js de noticiaVista, CONTROLADOR NOTICIAS, MÉTODO getNoticias,
     // se encarga de obtener todas las noticias de la web
        public function getNoticias()
    {
        return $this->select('noticias.*, categoria.nombre_categoria')
                    ->join('categoria', 'noticias.id_categoria = categoria.id', 'left')
                    ->orderBy('fecha_publicacion', 'DESC')
                    ->findAll();
    }

    // MÉTODO REFERENCIADO EN js de noticiaVista, CONTROLADOR NOTICIA, MÉTODO getNoticiasPopulares, 
    // se encarga de obtener las 4 noticias más populares de la web
        public function getNoticiasPopulares()
    {
        return $this->select('noticias.*, categoria.nombre_categoria')
                    ->join('categoria', 'noticias.id_categoria = categoria.id', 'left')
                    ->orderBy('visitas', 'DESC')
                    ->limit(4)
                    ->findAll();
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de obtener 
    // la noticia, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerNoticia
    public function getNoticiaDetalle($id)
    {
        return $this->select('noticias.*, categoria.nombre_categoria')
                    ->join('categoria', 'noticias.id_categoria = categoria.id', 'left')
                    ->find($id);
    }

    public function incrementarVisitas($id)
{
    // Puedes usar query builder para hacer un UPDATE que sume 1 a visitas
    return $this->db->table($this->table)
        ->set('visitas', 'visitas+1', false)
        ->where('id', $id)
        ->update();
}
}
