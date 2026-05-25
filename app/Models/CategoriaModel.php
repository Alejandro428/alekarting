<?php
namespace App\Models;
use CodeIgniter\Model;
class CategoriaModel extends Model
{
    protected $table='categoria';
    protected $primaryKey ='id';
    protected $allowedFields =['nombre_categoria'];

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de verificar
    // si la categoría ya existe, CONTROLADOR Categorias, MÉTODO verificarCategoria
    public function verificarExistencia($nombre, $excluirId = null)
    {
        $builder = $this->builder();
        $builder->where('LOWER(nombre_categoria)', strtolower($nombre));
        
        if($excluirId !== null) {
            $builder->where('id !=', $excluirId);
        }
        
        return $builder->countAllResults() > 0;
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de crear
    // la categoría, CONTROLADOR Categorias, MÉTODO crearCategoria
    public function crearCategoria($nombre)
    {
        return $this->insert(['nombre_categoria' => $nombre]);
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de editar
    // la categoría, CONTROLADOR Categorias, MÉTODO editarCategoria
    public function editarCategoria($id, $nuevoNombre)
    {
        return $this->update($id, ['nombre_categoria' => $nuevoNombre]);
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de verificar si 
    // se esta utilizando ya la categoría por parte de una noticia
    // CONTROLADOR Categorias, MÉTODO crearCategoria
    public function verificarUsoEnNoticias($id_categoria)
    {
        // 1. Consulta única para obtener conteo y datos básicos
        $resultado = $this->db->table('noticias')
                            ->select('id, titulo, fecha_publicacion') // Solo estos 3 campos
                            ->where('id_categoria', $id_categoria)
                            ->orderBy('fecha_publicacion', 'DESC')
                            ->get()
                            ->getResultArray();
    
        return [
            'enUso' => !empty($resultado), // Booleano
            'total' => count($resultado),  // Conteo
            'noticias' => $resultado,      // Datos básicos
            'mensaje' => !empty($resultado) 
                ? 'Categoría en uso (' . count($resultado) . ' noticias)' 
                : 'Sin noticias asociadas'
        ];
    }

// MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de 
// eliminar la categoría CONTROLADOR Categorias, MÉTODO eliminarCategoria

public function eliminarCategoria($id)
{
    // 1. Verificación rápida de existencia
    if (!$this->find($id)) {
        return false;
    }

    // 2. Eliminación física directa (sin transacciones)
    return $this->db->table('categoria')
                  ->where('id', $id)
                  ->delete(); // DELETE FROM categorias WHERE id = $id
}

}
