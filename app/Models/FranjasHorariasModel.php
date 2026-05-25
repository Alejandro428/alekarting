<?php

namespace App\Models;

use CodeIgniter\Model;

class FranjasHorariasModel extends Model
{
    protected $table      = 'franjas_horarias';
    protected $primaryKey = 'id';

    protected $allowedFields = ['hora_inicio', 'hora_fin'];
}