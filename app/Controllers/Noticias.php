<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\NoticiasModel;

class Noticias extends ResourceController
{
    use ResponseTrait;

    public function inicioNoticias()
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

     // Verifica si la sesión está iniciada y el tipo de usuario es "admin"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'admin') {
        // Si está logueado como admin, redirige al área de inicio del admin
        return redirect()->to(base_url('Admin'));
    }

    // Si no es un empleado o no está logueado, muestra la vista de noticias
    return view('templates/navbar') . view('noticia/noticiaVista') . view('templates/footer');
}

public function detalle($id = null)
{
    $session = session();

    // Verifica si la sesión está iniciada y el tipo de usuario es "empleado"
    if ($session->get('sesion_iniciada') && strtolower($session->get('tipo_usuario')) === 'empleado') {
        // Si está logueado como empleado, redirige al área de inicio del empleado
        return redirect()->to(base_url('Empleado'));
    }

    if ($id === null) {
        // Si no se envía un id, redirige a la lista de noticias
        return redirect()->to(base_url('Noticias'));
    }
    
    $model = new NoticiasModel();
    // Suponemos que getNoticiaDetalle realiza el left join para obtener el nombre de la categoría
    $noticia = $model->getNoticiaDetalle($id);
                         
    if (!$noticia) {
        // Lanza un error 404 si no se encuentra la noticia
        throw new PageNotFoundException("No se encontró la noticia con el id $id");
    }
    
    // Carga la vista 'detalleNoticia' pasando la noticia encontrada, junto con las vistas comunes (navbar y footer)
    return view('templates/navbar') 
         . view('noticia/detalleNoticia', ['noticia' => $noticia])
         . view('templates/footer');
}
    
    public function index()
    {
        $model = new NoticiasModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    // MÉTODO REFERENCIADO EN js de noticiaVista, se encarga de 
    // obtener todas las noticias de la web
    public function getNoticias()
    {
        $noticiaModel = new NoticiasModel();
        $noticias = $noticiaModel->getNoticias();
        
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode($noticias));
    }

    // MÉTODO REFERENCIADO EN js de noticiaVista, se encarga de 
    // obtener las 3 noticias más populares de la web
    public function getNoticiasPopulares()
    {
        $noticiaModel = new NoticiasModel();
        $data = $noticiaModel->getNoticiasPopulares();
        return $this->response
                    ->setContentType('application/json')
                    ->setBody(json_encode($data));
    }

   // MÉTODO LLAMADO DESDE EL JS DE detalleNoticia, se encarga
   // de sumar 1 a la noticia visitada
public function sumarVisita($id)
{
    $model = new NoticiasModel();
    // Incrementa el contador de visitas
    $noticia = $model->find($id);
    if ($noticia) {
        $data = ['visitas' => $noticia['visitas'] + 1];
        $model->update($id, $data);
        return $this->respond(['message' => 'Visita sumada'], 200);
    } else {
        return $this->failNotFound("Noticia no encontrada");
    }
}

    public function show($id = null)
    {
        if ($id === null) {
            return $this->failNotFound('No category ID provided');
        }
        $model = new NoticiasModel();
        $data = $model->select('noticias.*, categorias.nombre_categoria')
                      ->join('categorias', 'noticias.id_categoria = categorias.id')
                      ->where('categorias.id', $id)
                      ->findAll();
    
        if (!empty($data)) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    public function create()
    { 
        $model = new NoticiasModel();
        $data = [
            'titulo'            => $this->request->getPost('titulo'),
            'contenido'         => $this->request->getPost('contenido'),
            'imagen'            => $this->request->getPost('imagen'),
            'fecha_publicacion' => $this->request->getPost('fecha_publicacion'),
            'id_categoria'      => $this->request->getPost('id_categoria'),
            'empleado_id'       => $this->request->getPost('empleado_id'),
            // Puedes definir 'visitas' aquí si lo deseas, por ejemplo: 'visitas' => 0,
        ];

        $model->insert($data);
        return $this->respondCreated($data, 201);
    }
    
    public function update($id = null)
    {
        $model = new NoticiasModel();
        $json = $this->request->getJSON();
        
        if($json){
            $data = [
                'titulo'       => $json->titulo,
                'contenido'    => $json->contenido,
                'imagen'       => $json->imagen,
                'fecha_publicacion' => $json->fecha_publicacion,
                'id_categoria' => $json->id_categoria,
                'empleado_id'  => $json->empleado_id,
            ];
        }else{
            $input = $this->request->getRawInput();
            $data = [
                'titulo'       => $input['titulo'],
                'contenido'    => $input['contenido'],
                'imagen'       => $input['imagen'],
                'fecha_publicacion' => $input['fecha_publicacion'],
                'id_categoria' => $input['id_categoria'],
                'empleado_id'  => $input['empleado_id'],
            ];
        }
    
        $model->update($id, $data);
        $response = [
            'status'=> 200,
            'error'=> null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $model = new NoticiasModel();
        $data = $model->find($id);
        
        if($data){
            $model->delete($id);
            $response = [
                'status'=> 200,
                'error'=> null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
        
            return $this->respondDeleted($response);
        
        }else{
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }
}