<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        // SCENARIO 1: User is Logged In -> Go to Dashboard
        if ($session->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        // SCENARIO 2: User is NOT Logged In -> Show Landing Page
        // This is your "Public Default" page
        $data['title'] = 'Welcome to ITSO Inventory';
        return view('include/view_head', $data)
             . view('view_landing'); // We will create this file next
    }
}