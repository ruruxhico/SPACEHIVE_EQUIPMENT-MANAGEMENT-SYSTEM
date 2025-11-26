<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentTypeModel extends Model
{
    protected $table            = 'equipment_types';
    protected $primaryKey       = 'type_id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = ['type_code', 'name', 'description', 'total_quantity', 'available_quantity'];

    public function getAccessories($typeId)
    {
        $db = \Config\Database::connect();
        return $db->table('type_accessories')
                  ->where('type_id', $typeId)
                  ->get()
                  ->getResultArray();
    }
}