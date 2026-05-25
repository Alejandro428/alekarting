<?php

namespace App\Models;

use CodeIgniter\Model;

class PistaModel extends Model
{
    protected $table = 'pistas'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'precio'];

    // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), CONTROLADOR Pista, método verificarPista, 
    // se encarga de verificar que no existan pistas ya con ese nombre
    public function verificarExistencia($nombre, $excluirId = null)
    {
        $builder = $this->builder();
        $builder->where('LOWER(nombre)', strtolower($nombre));
        
        if($excluirId !== null) {
            $builder->where('id !=', $excluirId);
        }
        
        return $builder->countAllResults() > 0;
    }

     // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), CONTROLADOR Pistas, método crearPista,  
     // se encarga de crear la pista
    public function crearPista($nombre, $precio)
    {
        return $this->insert([
            'nombre' => $nombre,
            'precio' => $precio
        ]);
    }

     // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), CONTROLADOR Pistas, método editarPista, 
     // se encarga de editar la pista
    public function editarPista($id, $nuevoNombre, $nuevoPrecio)
    {
        return $this->update($id, [
            'nombre' => $nuevoNombre,
            'precio' => $nuevoPrecio
        ]);
    }

    // MÉTODO REFERENCIADO EN EL js de gestionCarreras (admin), CONTROLAR Pistas, método verificarUsoPista
    // se encarga de verificar si la pista ya se ha usado en alguna otra carrera
    public function verificarUsoEnCarreras($id_pista)
    {
        $resultado = $this->db->table('carreras')
                            ->select('id, fecha, num_participantes') 
                            ->where('id_pistas', $id_pista)
                            ->orderBy('fecha', 'DESC')
                            ->get()
                            ->getResultArray();
    
        return [
            'enUso' => !empty($resultado), // Booleano
            'total' => count($resultado),  // Conteo
            'carreras' => $resultado,      // Datos básicos
            'mensaje' => !empty($resultado) 
                ? 'Pista en uso (' . count($resultado) . ' carreras)' 
                : 'Sin carreras asociadas'
        ];
    }

    /**
     * Elimina una pista permanentemente
     * int $id ID de la pista a eliminar
     */
    public function eliminarPista($id)
    {
        // 1. Verificación rápida de existencia
        if (!$this->find($id)) {
            return false;
        }
        
        // 2. Eliminación física directa
        return $this->db->table('pistas')
                      ->where('id', $id)
                      ->delete();
    }

    /**
     * Obtiene todas las pistas ordenadas por nombre
     */
    public function obtenerPistas()
    {
        return $this->orderBy('nombre', 'ASC')
                   ->findAll();
    }

}