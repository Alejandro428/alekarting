<?php 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TipoEventoModel;

class TipoEvento extends ResourceController
{
    // METODO LLAMADO DESDE JS DE eventoVista, SE ENCARGA
    // DE OBTENER TODOS LOS TIPOS DE EVENTOS
    // MÉTODO REFERENCIADO EN js gestionEventos, se encarga de
    // obtener todos los tipos de eventos
    public function getTipoEventos()
    {
        $model = new TipoEventoModel();
        $tipos = $model->getTipoEventos();
        return $this->response->setJSON($tipos);
    }

    // MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
    // de comprobar que no hayan tipos de eventos con ese nombre ya existente, MÉTODO verificarTipoEvento
    public function verificarTipoEvento()
{
    // Verificación de admin (igual que en categorías)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    
    try {
        $model = new TipoEventoModel();
        $nombre = $this->request->getPost('nombre_tipo_evento');
        $excluirId = $this->request->getPost('id');
        
        if(empty($nombre)) {
            return $this->response->setJSON([
                'existe' => false,
                'mensaje' => 'Nombre del tipo de evento requerido'
            ])->setStatusCode(400);
        }
        
        $existe = $model->verificarExistencia($nombre, $excluirId);
        
        return $this->response->setJSON([
            'existe' => $existe,
            'mensaje' => $existe ? 'Ya existe un tipo de evento con este nombre' : ''
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en verificarTipoEvento: ' . $e->getMessage());
        return $this->failServerError('Error al verificar el tipo de evento');
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
// de crear un nuevo tipo de evento, MÉTODO crearTipoEvento
public function crearTipoEvento()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    try {
        $model = new TipoEventoModel();
        $nombre = $this->request->getPost('nombre_tipo_evento');
        
        // Validación básica
        if(empty($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nombre del tipo de evento requerido'
            ])->setStatusCode(400);
        }
        
        // Verificar existencia
        if($model->verificarExistencia($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El tipo de evento ya existe'
            ])->setStatusCode(409);
        }
        
        // Crear tipo de evento
        $id = $model->crearTipoEvento($nombre);
        
        return $this->response->setJSON([
            'success' => true,
            'id' => $id,
            'message' => 'Tipo de evento creado exitosamente'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en crearTipoEvento: ' . $e->getMessage());
        return $this->failServerError('Error al crear el tipo de evento');
    }
}

public function editarTipoEvento()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    try {
        $model = new TipoEventoModel();
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre_tipo_evento');
        
        if(empty($id) || empty($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ])->setStatusCode(400);
        }
        
        if($model->verificarExistencia($nombre, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe otro tipo de evento con este nombre'
            ])->setStatusCode(409);
        }
        
        // Actualizar tipo de evento
        $model->editarTipoEvento($id, $nombre);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Tipo de evento actualizado exitosamente'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en editarTipoEvento: ' . $e->getMessage());
        return $this->failServerError('Error al actualizar el tipo de evento');
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
// de verificar si se esta utilizando ese tipo de evento actualmente, MÉTODO verificarUsoTipoEvento
public function verificarUsoTipoEvento()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    
    try {
        $model = new TipoEventoModel();
        $id = $this->request->getPost('id');
        
        if(empty($id)) {
            return $this->response->setJSON([
                'enUso' => false,
                'mensaje' => 'ID de tipo de evento requerido',
                'totalEventos' => 0
            ])->setStatusCode(400);
        }
        
        $resultado = $model->verificarUsoEnEventos($id);
        
        return $this->response->setJSON([
            'enUso' => $resultado['total'] > 0,
            'mensaje' => $resultado['total'] > 0 ? 'El tipo de evento está siendo usado' : '',
            'totalEventos' => $resultado['total'],
            'eventos' => $resultado['eventos'] // Opcional: lista de eventos que usan este tipo
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en verificarUsoTipoEvento: ' . $e->getMessage());
        return $this->response->setStatusCode(500)
                            ->setJSON(['success' => false, 'message' => 'Error al verificar el uso del tipo de evento']);
    }
}

// MÉTODO REFERENCIADO DESDE js gestionEventos (admin), se encarga
// de eliminar el tipo de evento, MÉTODO eliminarTipoEvento
public function eliminarTipoEvento() 
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['exito' => false, 'mensaje' => 'Acceso denegado: Se requiere admin']);
    }
    
    try {
        $id = $this->request->getPost('id');
        
        if (empty($id)) {
            return $this->response->setJSON([
                'exito' => false,
                'mensaje' => 'ID de tipo de evento requerido'
            ])->setStatusCode(400);
        }
        
        $model = new TipoEventoModel();
        $eliminado = $model->eliminarTipoEvento($id); // Usamos el mismo nombre de método que solicitaste
        
        return $this->response->setJSON([
            'exito' => $eliminado,
            'mensaje' => $eliminado ? 'Tipo de evento eliminado permanentemente' : 'No se pudo eliminar'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error en eliminarTipoEvento: ' . $e->getMessage());
        return $this->response->setStatusCode(500)
                            ->setJSON(['exito' => false, 'mensaje' => 'Error al eliminar el tipo de evento']);
    }
}

}