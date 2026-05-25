<?php

namespace App\Controllers;

use App\Models\PistaModel;
use CodeIgniter\API\ResponseTrait;

class Pistas extends BaseController
{
    use ResponseTrait;
    
    public function index()
    {
        return view('templates/navbar') . view('pista/pistasVista') . view('templates/footer');
    }
    
    /*
     MÉTODO REFERENCIADO EN carreraVista, gestionCarreras, nuevoCarrerasEmpleado, PARA OBTENER TODAS LAS PISTAS DISPONIBLES
    Y CARGARLAS PARA QUE EL USUARIO PUEDA ESCOGER LA PISTA QUE QUIERE, JUNTO CON SU PRECIO.
     */
    public function getPistas()
    {
        $pistaModel = new PistaModel();
        $pistas = $pistaModel->findAll();
        return $this->respond($pistas, 200);
    }
    
    /* Métodos opcionales para operaciones CRUD sobre pistas */
    
    public function create()
    {
        $pistaModel = new PistaModel();
        $data = $this->request->getPost();
        $pistaModel->insert($data);
        return $this->respondCreated($data);
    }
    
    public function update($id = null)
    {
        $pistaModel = new PistaModel();
        $data = $this->request->getRawInput();
        $pistaModel->update($id, $data);
        return $this->respond($data, 200);
    }
    
    public function delete($id = null)
    {
        $pistaModel = new PistaModel();
        $pistaModel->delete($id);
        return $this->respondDeleted(['id' => $id]);
    }

     // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), se encarga de crear la pista
    public function crearPista()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    try {
        $model = new PistaModel();
        $nombre = $this->request->getPost('nombre_pista');
        $precio = $this->request->getPost('precio_pista');
        
        // Validación básica
        if(empty($nombre) || empty($precio)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nombre y precio de pista requeridos'
            ])->setStatusCode(400);
        }
        
        if(!is_numeric($precio) || $precio <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El precio debe ser un número positivo'
            ])->setStatusCode(400);
        }
        
        // Verificar existencia
        if($model->verificarExistencia($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La pista ya existe'
            ])->setStatusCode(409);
        }
        
        // Crear pista
        $id = $model->crearPista($nombre, $precio);
        
        return $this->response->setJSON([
            'success' => true,
            'id' => $id,
            'message' => 'Pista creada exitosamente'
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error en crearPista: ' . $e->getMessage());
        return $this->failServerError('Error al crear la pista');
    }
}

 // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), se encarga de editar la pista
public function editarPista()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }
    try {
        $model = new PistaModel();
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre_pista');
        $precio = $this->request->getPost('precio_pista');
        
        if(empty($id) || empty($nombre) || empty($precio)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ])->setStatusCode(400);
        }
        
        if(!is_numeric($precio) || $precio <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El precio debe ser un número positivo'
            ])->setStatusCode(400);
        }
        
        if($model->verificarExistencia($nombre, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe otra pista con este nombre'
            ])->setStatusCode(409);
        }
        
        // Editar pista (actualizado)
        $model->editarPista($id, $nombre, $precio);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pista actualizada exitosamente'
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error en editarPista: ' . $e->getMessage());
        return $this->failServerError('Error al actualizar la pista');
    }
}
    // MÉTODO REFERENCIADO DESDE JS gestionCarreras (admin), se encarga de verificar que no existan
    // pistas ya con ese nombre
    public function verificarPista()
    {
        // Verificación simple de admin
        if (session('tipo_usuario') !== 'admin') {
            return $this->response->setStatusCode(403)
                                ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
        }

        try {
            $model = new PistaModel();
            $nombre = $this->request->getPost('nombre_pista');
            $excluirId = $this->request->getPost('id');

            if(empty($nombre)) {
                return $this->response->setJSON([
                    'existe' => false,
                    'mensaje' => 'Nombre de pista requerido'
                ])->setStatusCode(400);
            }

            $existe = $model->verificarExistencia($nombre, $excluirId);

            return $this->response->setJSON([
                'existe' => $existe,
                'mensaje' => $existe ? 'Ya existe una pista con este nombre' : ''
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en verificarPista: ' . $e->getMessage());
            return $this->failServerError('Error al verificar la pista');
        }
    }
    // MÉTODO REFERENCIADO EN EL js de gestionCarreras (admin), se encarga de verificar si la pista
    // ya se ha usado en alguna otra carrera
    public function verificarUsoPista()
    {
        // Verificación de admin
        if (session('tipo_usuario') !== 'admin') {
            return $this->response->setStatusCode(403)
                                ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
        }

        try {
            $model = new PistaModel();
            $id = $this->request->getPost('id');

            if(empty($id)) {
                return $this->response->setJSON([
                    'enUso' => false,
                    'mensaje' => 'ID de pista requerido',
                    'totalCarreras' => 0
                ])->setStatusCode(400);
            }

            $resultado = $model->verificarUsoEnCarreras($id);

            return $this->response->setJSON([
                'enUso' => $resultado['total'] > 0,
                'mensaje' => $resultado['total'] > 0 ? 'La pista está siendo usada en carreras' : '',
                'totalCarreras' => $resultado['total'],
                'carreras' => $resultado['carreras'] // Opcional
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en verificarUsoPista: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                                ->setJSON(['success' => false, 'message' => 'Error al verificar el uso de la pista']);
        }
    }

    public function eliminarPista() 
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
                    'mensaje' => 'ID de pista requerido'
                ])->setStatusCode(400);
            }

            $model = new PistaModel();
            
            // Verificar uso antes de eliminar
            $uso = $model->verificarUsoEnCarreras($id);
            if ($uso['total'] > 0) {
                return $this->response->setJSON([
                    'exito' => false,
                    'mensaje' => 'No se puede eliminar: la pista tiene carreras asociadas',
                    'totalCarreras' => $uso['total']
                ]);
            }

            $eliminado = $model->eliminarPista($id);

            return $this->response->setJSON([
                'exito' => $eliminado,
                'mensaje' => $eliminado ? 'Pista eliminada permanentemente' : 'No se pudo eliminar'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en eliminarPista: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                                ->setJSON(['exito' => false, 'mensaje' => 'Error al eliminar la pista']);
        }
    }

}