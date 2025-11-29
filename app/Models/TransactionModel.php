<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'transaction_id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = [
        'borrower_id', 'item_tag', 'reservation_id', 
        'borrowed_at', 'expected_return', 'returned_at', 
        'status', 'issued_by', 'received_by'
    ];

    // THIS IS THE MISSING FUNCTION CAUSING THE ERROR
    public function getUserHistory($userId)
    {
        return $this->select('
                transactions.*, 
                equipment_assets.property_tag, 
                equipment_types.name as item_name,
                equipment_types.image
            ')
            ->join('equipment_assets', 'equipment_assets.property_tag = transactions.item_tag')
            ->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id')
            ->where('transactions.borrower_id', $userId)
            ->orderBy('transactions.borrowed_at', 'DESC')
            ->findAll();
    }
}