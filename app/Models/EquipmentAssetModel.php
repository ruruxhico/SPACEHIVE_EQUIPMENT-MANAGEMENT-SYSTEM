<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentAssetModel extends Model
{
    protected $table            = 'equipment_assets';
    protected $primaryKey       = 'property_tag';
    protected $useAutoIncrement = false;
    protected $allowedFields    = ['property_tag', 'type_id', 'status', 'remarks'];

    public function generateAssetId($typeCode)
    {
        $builder = $this->builder();
        $prefix = "ITSO-" . $typeCode;
        
        $count = $builder->like('property_tag', $prefix, 'after')->countAllResults();
        
        $nextNum = $count + 1;

        $formattedNum = str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        return $prefix . '-' . $formattedNum;
    }
    
    public function getAssetsWithDetails()
    {
        return $this->select('equipment_assets.*, equipment_types.name, equipment_types.type_code')
                    ->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id')
                    ->findAll();
    }
}