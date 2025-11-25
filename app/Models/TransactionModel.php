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

    public function getHistory()
    {
        return $this->select('transactions.*, 
                              users.first_name, users.last_name, 
                              equipment_assets.property_tag, equipment_types.name as item_name')
                    ->join('users', 'users.school_id = transactions.borrower_id')
                    ->join('equipment_assets', 'equipment_assets.property_tag = transactions.item_tag')
                    ->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id')
                    ->orderBy('borrowed_at', 'DESC')
                    ->findAll();
    }
}