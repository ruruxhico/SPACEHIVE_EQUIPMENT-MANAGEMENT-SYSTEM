<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeAccessoryModel extends Model
{
    protected $table            = 'type_accessories';
    protected $primaryKey       = 'accessory_id';
    protected $allowedFields    = ['type_id', 'accessory_name'];
}