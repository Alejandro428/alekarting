<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Categorias extends ResourceController
{
    use ResponseTrait;

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado), noticiaVista, se encarga de obtener
    // todas las categorias existentes en la web, CONTROLADOR Categorias, MÉTODO eliminarReserva
    public function getCategorias()
    {
        try {
            $model = new CategoriaModel();
            $data = $model->findAll();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            // Registrar el error para debug (puedes usar log_message)
            log_message('error', 'Error en getCategorias: ' . $e->getMessage());
            return $this->failServerError('Error interno del servidor');
        }
    }
    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de crear
    // la categoría, CONTROLADOR Categorias, MÉTODO crearCategoria
    public function crearCategoria()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $model = new CategoriaModel();
        $nombre = $this->request->getPost('nombre_categoria');

        // Validación básica
        if(empty($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nombre de categoría requerido'
            ])->setStatusCode(400);
        }

        // Verificar existencia (usando el método renombrado del modelo)
        if($model->verificarExistencia($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'La categoría ya existe'
            ])->setStatusCode(409);
        }

        // Crear categoría (usando el nuevo método del modelo)
        $id = $model->crearCategoria($nombre);

        return $this->response->setJSON([
            'success' => true,
            'id' => $id,
            'message' => 'Categoría creada exitosamente'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en crearCategoria: ' . $e->getMessage());
        return $this->failServerError('Error al crear la categoría');
    }
}

// MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de editar
// la categoría, CONTROLADOR Categorias, MÉTODO editarCategoria
public function editarCategoria()
{
    // Verificación de admin
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $model = new CategoriaModel();
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre_categoria');

        if(empty($id) || empty($nombre)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ])->setStatusCode(400);
        }

        if($model->verificarExistencia($nombre, $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ya existe otra categoría con este nombre'
            ])->setStatusCode(409);
        }

        // Uso del método renombrado del modelo
        $model->editarCategoria($id, $nombre);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en editarCategoria: ' . $e->getMessage());
        return $this->failServerError('Error al actualizar la categoría');
    }
}
      // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de verificar
      // si la categoría ya existe, CONTROLADOR Categorias, MÉTODO verificarCategoria
    public function verificarCategoria()
    {
        // Verificación simple de admin (exactamente como lo pediste)
        if (session('tipo_usuario') !== 'admin') {
            return $this->response->setStatusCode(403)
                                ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
        }

        try {
            $model = new CategoriaModel();
            $nombre = $this->request->getPost('nombre_categoria');
            $excluirId = $this->request->getPost('id');

            if(empty($nombre)) {
                return $this->response->setJSON([
                    'existe' => false,
                    'mensaje' => 'Nombre de categoría requerido'
                ])->setStatusCode(400);
            }

            $existe = $model->verificarExistencia($nombre, $excluirId);

            return $this->response->setJSON([
                'existe' => $existe,
                'mensaje' => $existe ? 'Ya existe una categoría con este nombre' : ''
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en verificarCategoria: ' . $e->getMessage());
            return $this->failServerError('Error al verificar la categoría');
        }
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de verificar si 
    // se esta utilizando ya la categoría por parte de una noticia
    // CONTROLADOR Categorias, MÉTODO crearCategoria
    public function verificarUsoCategoria()
{
    // Verificación de admin (igual que en tu ejemplo)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['success' => false, 'message' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $model = new CategoriaModel();
        $id = $this->request->getPost('id');
        
        if(empty($id)) {
            return $this->response->setJSON([
                'enUso' => false,
                'mensaje' => 'ID de categoría requerido',
                'totalNoticias' => 0
            ])->setStatusCode(400);
        }

        $resultado = $model->verificarUsoEnNoticias($id);
        
        return $this->response->setJSON([
            'enUso' => $resultado['total'] > 0,
            'mensaje' => $resultado['total'] > 0 ? 'La categoría está siendo usada' : '',
            'totalNoticias' => $resultado['total'],
            'noticias' => $resultado['noticias'] // Opcional
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en verificarUsoCategoria: ' . $e->getMessage());
        return $this->response->setStatusCode(500)
                            ->setJSON(['success' => false, 'message' => 'Error al verificar el uso de la categoría']);
    }
}

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de 
    // eliminar la categoría
    // CONTROLADOR Categorias, MÉTODO eliminarCategoria

public function eliminarCategoria() 
{
    // Verificación de admin (como en tu estructura actual)
    if (session('tipo_usuario') !== 'admin') {
        return $this->response->setStatusCode(403)
                            ->setJSON(['exito' => false, 'mensaje' => 'Acceso denegado: Se requiere admin']);
    }

    try {
        $id = $this->request->getPost('id');
        
        if (empty($id)) {
            return $this->response->setJSON([
                'exito' => false,
                'mensaje' => 'ID de categoría requerido'
            ])->setStatusCode(400);
        }

        $model = new CategoriaModel();
        $eliminado = $model->eliminarCategoria($id); // Nombre exacto que solicitaste
        
        return $this->response->setJSON([
            'exito' => $eliminado,
            'mensaje' => $eliminado ? 'Categoría eliminada permanentemente' : 'No se pudo eliminar'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en eliminarCategoria: ' . $e->getMessage());
        return $this->response->setStatusCode(500)
                            ->setJSON(['exito' => false, 'mensaje' => 'Error al eliminar la categoría']);
    }
}


}