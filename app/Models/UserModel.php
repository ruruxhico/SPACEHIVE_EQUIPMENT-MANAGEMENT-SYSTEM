<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    
    protected $primaryKey       = 'school_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    
    protected $useSoftDeletes   = false; 
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'school_id', 
        'first_name', 
        'middle_name',
        'last_name', 
        'email', 
        'password', 
        'role', 
        'status', 
        'verifytoken',
        'isverified'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // or 'updated_at' if you have it
    protected $deletedField  = ''; 
}