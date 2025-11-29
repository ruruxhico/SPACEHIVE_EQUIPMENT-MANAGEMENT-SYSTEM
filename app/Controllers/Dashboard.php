<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $role = $session->get('role');
        $userId = $session->get('id');
        
        $data['title'] = 'Dashboard';

        // --- 1. ADMIN / ITSO DASHBOARD ---
        if ($role === 'ITSO') {
            $userModel = new UserModel();
            
            // FIX: Fetch Admin Profile Data so we can show the card
            $data['admin'] = $userModel->find($userId);

            return view('include/view_head', $data)
                 . view('include/view_nav')
                 . view('dashboard/view_admin', $data);
        } 
        
        // --- 2. ASSOCIATE DASHBOARD ---
        elseif ($role === 'ASSOCIATE') {
            $userModel = new UserModel();
            $transModel = new TransactionModel();

            $data['associate'] = $userModel->find($userId);
            
            if (class_exists('App\Models\TransactionModel')) {
                $data['history'] = $transModel->getUserHistory($userId);
            } else {
                $data['history'] = [];
            }

            return view('include/view_head', $data)
                 . view('include/view_nav')
                 . view('dashboard/view_associate', $data);
        } 
        
        // --- 3. STUDENT DASHBOARD ---
        else {
            $userModel = new UserModel();
            $transModel = new TransactionModel();

            $data['student'] = $userModel->find($userId);
            
            if (class_exists('App\Models\TransactionModel')) {
                $data['history'] = $transModel->getUserHistory($userId);
            } else {
                $data['history'] = [];
            }

            return view('include/view_head', $data)
                 . view('include/view_nav')
                 . view('dashboard/view_student', $data); 
        }
    }
}