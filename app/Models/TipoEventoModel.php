<?php 
    namespace App\Models;
    use CodeIgniter\Model;

    class TipoEventoModel extends Model
    {
        protected $table = 'tipo_evento';
        protected $primaryKey = 'id';
        protected $allowedFields = ['nombre']; 

        // METODO LLAMADO DESDE JS DE eventoVista, SE ENCARGA
        // DE OBTENER TODOS LOS TIPOS DE EVENTOS

        // MÉTODO REFERENCIADO EN js gestionEventos, nuevoEventoEmpleado (empleado) CONTROLADOR ADMIN, MÉTODO getTipo eventos,
        // se encarga de obtener todos los tipos de eventos
        public function getTipoEventos()
        {
            return $this->findAll();
        }

        // MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
        // de comprobar que no hayan tipos de eventos con ese nombre ya existente, CONTROLADOR TIPOEvento, MÉTODO verificarTipoEvento
        public function verificarExistencia($nombre, $excluirId = null)
        {
            $builder = $this->builder();
            $builder->where('LOWER(nombre)', strtolower($nombre));
            
            if($excluirId !== null) {
                $builder->where('id !=', $excluirId);
            }
            
            return $builder->countAllResults() > 0;
        }

        // MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
        // de crear un nuevo tipo de evento, CONTROLADOR TipoEvento, MÉTODO crearTipoEvento
        public function crearTipoEvento($nombre)
        {
            return $this->insert(['nombre' => $nombre]);
        }
    
        public function editarTipoEvento($id, $nuevoNombre)
        {
            return $this->update($id, ['nombre' => $nuevoNombre]);
        }

        // MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
        // de verificar si se esta utilizando ese tipo de evento actualmente, CONTROLADOR TIPOEVENTO, MÉTODO verificarUsoTipoEvento
        public function verificarUsoEnEventos($id_tipo_evento)
        {
            // Asumiendo que tienes una tabla 'eventos' con un campo 'id_tipo_evento'
            $builder = $this->db->table('eventos');
            $builder->select('COUNT(*) as total, GROUP_CONCAT(nombre) as eventos');
            $builder->where('tipo_evento_id', $id_tipo_evento);
            
            $query = $builder->get();
            $resultado = $query->getRowArray();
            
            return [
                'total' => $resultado['total'] ?? 0,
                'eventos' => $resultado['eventos'] ?? ''
            ];
        }

        // MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
        // de eliminar el tipo de evento, CONTROLDOR TIPO EVENTO, MÉTODO eliminarTipoEvento
        public function eliminarTipoEvento($id)
        {
            // 1. Verificación rápida de existencia
            if (!$this->find($id)) {
                return false;
            }
            
            // 2. Eliminación física directa (sin transacciones)
            return $this->db->table('tipo_evento')
                        ->where('id', $id)
                        ->delete(); // DELETE FROM tipo_evento WHERE id = $id
        }

    }