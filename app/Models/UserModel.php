<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'school_id'; 
    protected $useAutoIncrement = false; 
    protected $useTimestamps    = true;  
    
    protected $allowedFields    = [
        'school_id', 'first_name', 'last_name', 
        'email', 'password_hash', 'role', 'status'
    ];

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}