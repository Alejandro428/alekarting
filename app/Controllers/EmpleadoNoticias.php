<?php

namespace App\Controllers;

use App\Models\NoticiasEmpleadoModel;
use App\Models\NoticiasModel;


class EmpleadoNoticias extends BaseController
{
    protected $noticiasEmpleadoModel;
    
    public function __construct()
    {
        $this->noticiasEmpleadoModel = new NoticiasEmpleadoModel();
        $this->noticiasModel = new NoticiasModel(); 
    }
    
    // Método principal para mostrar la vista de noticias del empleado
    public function indexNoticias()
    {
        $session = session();
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return redirect()->to(base_url());
        }
        if ((int)$session->get('emp_noticia') !== 1) {
            $data['error'] = "No tienes permiso para gestionar Noticias.";
            return view('empleado/navbarEmpleado') . view('empleado/inicioEmpleado', $data) . view('templates/footer');
        }
        
        // Obtener las noticias creadas por el empleado usando el nuevo modelo
        $data['noticias'] = $this->noticiasEmpleadoModel->obtenerNoticiasEmpleado($session->get('id'));
        return view('empleado/navbarEmpleado') . view('empleado/noticiasEmpleado', $data) . view('templates/footer');
    }
    
    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de obtener 
    // las noticias del empleado, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerNoticiasDeUsuario
    public function obtenerNoticiasDeUsuario()
    {
        $session = session();
        if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'empleado') {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }
        if ((int)$session->get('emp_noticia') !== 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permiso para gestionar Noticias.']);
        }
        // Se asume que en la sesión se guarda 'empleado_id'
        $empleado_id = $session->get('empleado_id');
        if (!$empleado_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se encontró el id de empleado en la sesión.']);
        }
        
        $noticias = $this->noticiasEmpleadoModel->obtenerNoticiasEmpleado($empleado_id);
        return $this->response->setJSON(['success' => true, 'data' => $noticias]);
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de obtener 
    // la noticia, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerNoticia
public function obtenerNoticia($id)
{
    $session = session();

    // Validar que la sesión esté iniciada y que el usuario sea empleado
    if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['No autorizado']
        ]);
    }

    // Validar permisos para gestionar noticias
    if (strtolower($session->get('tipo_usuario')) === 'empleado' && (int)$session->get('emp_noticia') !== 1) {
    return $this->response->setJSON([
        'success' => false,
        'errors'  => ['No tienes permiso para gestionar Noticias.']
    ]);
}

    // Llamar al modelo para obtener el detalle de la noticia (incluyendo la categoría)
    $noticia = $this->noticiasModel->getNoticiaDetalle($id);

    if ($noticia) {
        return $this->response->setJSON([
            'success' => true,
            'data'    => $noticia
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'errors'  => ['Noticia no encontrada.']
        ]);
    }
}
    
    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado), se encarga de crear 
    // la noticia, CONTROLADOR EmpleadoNoticias, MÉTODO crearNoticias
    public function crearNoticias()
    {
        log_message('debug', 'Iniciando el método crearNoticias');
    
        $session = session();
        
        // Validar que la sesión esté iniciada y que el usuario sea empleado o admin
        if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No autorizado']]);
        }
    
        // Asegurarse de que tanto empleados como administradores tengan ID
        if (!$session->has('empleado_id')) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No se encontró el identificador del empleado.']]);
        }
    
        // Permitir que los administradores pasen sin validar emp_noticia
        if ($session->get('tipo_usuario') !== 'admin' && (int)$session->get('emp_noticia') !== 1) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No tienes permiso para gestionar Noticias.']]);
        }
    
        // Recoger datos enviados por POST
        $data = $this->request->getPost();
        $errors = [];
        if (empty($data['titulo'])) {
            $errors[] = "El campo título es obligatorio.";
        }
        if (empty($data['subtitulo'])) {
            $errors[] = "El campo subtítulo es obligatorio.";
        }
        if (empty($data['contenido'])) {
            $errors[] = "El campo contenido es obligatorio.";
        }
        if (empty($data['id_categoria'])) {
            $errors[] = "El campo categoría es obligatorio.";
        }
        if (empty($data['fecha_publicacion'])) {
            $errors[] = "El campo fecha_publicacion es obligatorio.";
        }
        if (!empty($errors)) {
            return $this->response->setJSON(['success' => false, 'errors' => $errors]);
        }
    
        // Procesar la imagen
        $img = $this->request->getFile('imagen');
        if (!$img->isValid()) {
            return $this->response->setJSON(['success' => false, 'errors' => [$img->getErrorString()]]);
        }
    
        // Validar tamaño máximo de imagen (2MB)
        if ($img->getSize() > 2 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'errors' => ['La imagen no puede superar los 2MB.']]);
        }
    
        // Validar que sea una imagen y que tenga un MIME permitido
        $validationRule = 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]';
        if (!$this->validate(['imagen' => $validationRule])) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }
    
        // Validar dimensiones mínimas
        $imgTempPath = $img->getTempName();
        $dimensions = getimagesize($imgTempPath);
        if ($dimensions === false) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No se pudo determinar las dimensiones de la imagen.']]);
        }
        $width = $dimensions[0];
        $height = $dimensions[1];
        if ($width < 800 || $height < 600) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['La imagen debe tener al menos 800 píxeles de ancho y 600 píxeles de alto.']
            ]);
        }
    
        // Procesar el video si existe
        $videoName = null;
        $video = $this->request->getFile('video');
        if ($video->isValid()) {
            // Validar tamaño máximo de video (6MB)
            if ($video->getSize() > 7 * 1024 * 1024) {
                return $this->response->setJSON(['success' => false, 'errors' => ['El video no puede superar los 7MB.']]);
            }
    
            // Validar que sea un video MP4
            if ($video->getMimeType() !== 'video/mp4' || $video->getClientExtension() !== 'mp4') {
                return $this->response->setJSON(['success' => false, 'errors' => ['Solo se permiten videos en formato MP4.']]);
            }
    
            $videoName = $video->getRandomName();
            if (!$video->move(FCPATH . 'assets/imagenes/noticias/videos/', $videoName)) {
                return $this->response->setJSON(['success' => false, 'errors' => ['Error al mover el video.']]);
            }
        }
    
        $newName = $img->getRandomName();
        if (!$img->move(FCPATH . 'assets/imagenes/noticias/imgs/', $newName)) {
            // Si falla el movimiento de la imagen, borrar el video si se subió
            if ($videoName && file_exists(FCPATH . 'assets/imagenes/noticias/videos/' . $videoName)) {
                unlink(FCPATH . 'assets/imagenes/noticias/videos/' . $videoName);
            }
            return $this->response->setJSON(['success' => false, 'errors' => ['Error al mover la imagen.']]);
        }
    
        // Preparar los datos para guardar la noticia
        $dataToSave = [
            'titulo'            => $data['titulo'],
            'subtitulo'         => $data['subtitulo'],
            'contenido'         => $data['contenido'],
            'imagen'            => $newName,
            'video'             => $videoName, // Puede ser null si no se subió video
            'fecha_publicacion' => $data['fecha_publicacion'],
            'id_categoria'      => $data['id_categoria'],
            'empleado_id'       => $session->get('empleado_id'),
            'visitas'           => 0
        ];
    
        $insertedId = $this->noticiasEmpleadoModel->insert($dataToSave);
        if ($insertedId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Noticia creada correctamente.',
                'id'      => $insertedId
            ]);
        } else {
            // Si falla la inserción, borrar los archivos subidos
            if (file_exists(FCPATH . 'assets/imagenes/noticias/imgs/' . $newName)) {
                unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $newName);
            }
            if ($videoName && file_exists(FCPATH . 'assets/imagenes/noticias/videos/' . $videoName)) {
                unlink(FCPATH . 'assets/imagenes/noticias/videos/' . $videoName);
            }
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['Error al crear la noticia.']
            ]);
        }
    }

    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de editar
    // la noticia, CONTROLADOR EmpleadoNoticias, MÉTODO editarNoticia
    public function editarNoticias($id)
    {
        log_message('debug', 'Iniciando editarNoticias para id: ' . $id);
        log_message('debug', 'Datos POST recibidos: ' . print_r($this->request->getPost(), true));
        log_message('debug', 'Archivos recibidos: ' . print_r($this->request->getFiles(), true));
    
        $session = session();
        
        // Validar sesión y permisos
        if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No autorizado']]);
        }
    
        if (!$session->has('empleado_id')) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No se encontró el identificador del empleado.']]);
        }
    
        if ((strtolower($session->get('tipo_usuario')) === 'empleado' && (int)$session->get('emp_noticia') !== 1)) {
            return $this->response->setJSON(['success' => false, 'errors' => ['No tienes permiso para gestionar Noticias.']]);
        }
    
        // Validar campos obligatorios
        $data = $this->request->getPost();
        $errors = [];
        if (empty($data['titulo'])) $errors[] = "El campo título es obligatorio.";
        if (empty($data['subtitulo'])) $errors[] = "El campo subtítulo es obligatorio.";
        if (empty($data['contenido'])) $errors[] = "El campo contenido es obligatorio.";
        if (empty($data['id_categoria'])) $errors[] = "El campo categoría es obligatorio.";
        if (empty($data['fecha_publicacion'])) $errors[] = "El campo fecha_publicacion es obligatorio.";
        if (!empty($errors)) return $this->response->setJSON(['success' => false, 'errors' => $errors]);
    
        // Obtener noticia existente
        $noticiaExistente = $this->noticiasEmpleadoModel->find($id);
        if (!$noticiaExistente) {
            return $this->response->setJSON(['success' => false, 'errors' => ['Noticia no encontrada.']]);
        }
    
        // Procesar imagen (2MB máximo)
        $img = $this->request->getFile('imagen');
        $newImageName = $noticiaExistente['imagen']; // Mantener la existente por defecto
        
        if ($img && $img->isValid() && $img->getSize() > 0) {
            // Validar tamaño
            if ($img->getSize() > 2 * 1024 * 1024) {
                return $this->response->setJSON(['success' => false, 'errors' => ['La imagen no puede superar los 2MB.']]);
            }
    
            $validationRule = 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]';
            if (!$this->validate(['imagen' => $validationRule])) {
                return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
            }
    
            $newImageName = $img->getRandomName();
            if (!$img->move(FCPATH . 'assets/imagenes/noticias/imgs/', $newImageName)) {
                return $this->response->setJSON(['success' => false, 'errors' => ['Error al mover la nueva imagen.']]);
            }
    
            // Eliminar imagen anterior si existe
            if (!empty($noticiaExistente['imagen']) && file_exists(FCPATH . 'assets/imagenes/noticias/imgs/' . $noticiaExistente['imagen'])) {
                unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $noticiaExistente['imagen']);
            }
        }
    
        // Procesar video (6MB máximo)
        $video = $this->request->getFile('video');
        $newVideoName = $noticiaExistente['video'] ?? null; // Mantener el existente por defecto
        
        if ($video && $video->isValid() && $video->getSize() > 0) {
            // Validar tamaño
            if ($video->getSize() > 7 * 1024 * 1024) {
                // Si subimos nueva imagen, hay que borrarla porque falló el video
                if ($newImageName !== $noticiaExistente['imagen']) {
                    unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $newImageName);
                }
                return $this->response->setJSON(['success' => false, 'errors' => ['El video no puede superar los 7MB.']]);
            }
    
            // Validar formato MP4
            if ($video->getMimeType() !== 'video/mp4' || $video->getClientExtension() !== 'mp4') {
                if ($newImageName !== $noticiaExistente['imagen']) {
                    unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $newImageName);
                }
                return $this->response->setJSON(['success' => false, 'errors' => ['Solo se permiten videos en formato MP4.']]);
            }
    
            $newVideoName = $video->getRandomName();
            if (!$video->move(FCPATH . 'assets/imagenes/noticias/videos/', $newVideoName)) {
                if ($newImageName !== $noticiaExistente['imagen']) {
                    unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $newImageName);
                }
                return $this->response->setJSON(['success' => false, 'errors' => ['Error al mover el nuevo video.']]);
            }
    
            // Eliminar video anterior si existe
            if (!empty($noticiaExistente['video']) && file_exists(FCPATH . 'assets/imagenes/noticias/videos/' . $noticiaExistente['video'])) {
                unlink(FCPATH . 'assets/imagenes/noticias/videos/' . $noticiaExistente['video']);
            }
        }
    
        // Preparar datos para actualizar
        $dataUpdate = [
            'titulo'            => $data['titulo'],
            'subtitulo'         => $data['subtitulo'],
            'contenido'         => $data['contenido'],
            'id_categoria'      => $data['id_categoria'],
            'fecha_publicacion' => $data['fecha_publicacion'],
            'imagen'            => $newImageName,
            'video'             => $newVideoName
        ];
    
        if ($this->noticiasEmpleadoModel->update($id, $dataUpdate)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Noticia actualizada correctamente.']);
        } else {
            // Si falla la actualización, borrar archivos nuevos subidos
            if ($newImageName !== $noticiaExistente['imagen']) {
                unlink(FCPATH . 'assets/imagenes/noticias/imgs/' . $newImageName);
            }
            if ($newVideoName !== ($noticiaExistente['video'] ?? null)) {
                unlink(FCPATH . 'assets/imagenes/noticias/videos/' . $newVideoName);
            }
            return $this->response->setJSON(['success' => false, 'errors' => ['Error al actualizar la noticia.']]);
        }
    }
    
    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), nuevoNoticiaEmpleado (empleado) se encarga de eliminar 
    // la noticia escogida, CONTROLADOR EmpleadoNoticias, MÉTODO eliminarNoticias

    public function eliminarNoticias($id)
    {
        $session = session();
        
        // Validar que la sesión esté iniciada y que el usuario sea empleado o admin
        if (!$session->get('sesion_iniciada') || !in_array(strtolower($session->get('tipo_usuario')), ['empleado', 'admin'])) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No autorizado'
            ]);
        }
    
        // Asegurarse de que ambos tipos tengan ID de empleado
        if (!$session->has('empleado_id')) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No se encontró el identificador del empleado.'
            ]);
        }
    
        // Validar permisos para gestionar noticias solo para empleados
        if (strtolower($session->get('tipo_usuario')) === 'empleado' && (int)$session->get('emp_noticia') !== 1) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'No tienes permiso para gestionar Noticias.'
            ]);
        }
    
        // Obtener la noticia existente
        $noticia = $this->noticiasEmpleadoModel->find($id);
        if (!$noticia) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Noticia no encontrada.'
            ]);
        }
    
        // Si la noticia tiene una imagen asociada, eliminarla
        if (!empty($noticia['imagen'])) {
            $rutaImagen = FCPATH . 'assets/imagenes/noticias/imgs/' . $noticia['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
    
        // Si la noticia tiene un video asociado, eliminarlo
        if (!empty($noticia['video'])) {
            $rutaVideo = FCPATH . 'assets/imagenes/noticias/videos/' . $noticia['video'];
            if (file_exists($rutaVideo)) {
                unlink($rutaVideo);
            }
        }
    
        // Eliminar el registro de la noticia
        if ($this->noticiasEmpleadoModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Noticia eliminada correctamente.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error al eliminar la noticia.'
            ]);
        }
    }
    
    // MÉTODO REFERENCIADO EN js de gestionNoticias (admin), se encarga de obtener
    // todas las noticias existentes en la web, CONTROLADOR EmpleadoNoticias, MÉTODO obtenerTodasLasNoticias

    public function obtenerTodasLasNoticias()
{
    $session = session();
    
    // Verificar si la sesión está iniciada y el usuario es ADMIN
    if (!$session->get('sesion_iniciada') || strtolower($session->get('tipo_usuario')) !== 'admin') {
        return $this->response->setJSON(['success' => false, 'message' => 'Acceso no autorizado']);
    }

    // Obtener todas las noticias (sin filtrar por empleado)
    $noticias = $this->noticiasEmpleadoModel->obtenerTodasLasNoticias();

    return $this->response->setJSON(['success' => true, 'data' => $noticias]);
}

    
}
