<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $usersModel = new UserModel();

        // --- 1. GET FILTER REQUESTS ---
        $search   = $this->request->getGet('search');
        $sort     = $this->request->getGet('sort');       // name_asc, name_desc
        $role     = $this->request->getGet('role');       // ITSO, ASSOCIATE, STUDENT
        $status   = $this->request->getGet('status');     // Active, Inactive
        $perPage  = $this->request->getGet('per_page') ?? 5; // Default 5 rows

        // --- 2. APPLY FILTERS ---
        
        // Search (ID, First Name, or Last Name)
        if (!empty($search)) {
            $usersModel->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('school_id', $search)
            ->groupEnd();
        }

        // Role Filter
        if (!empty($role)) {
            $usersModel->where('role', $role);
        }

        // Status Filter
        if (!empty($status)) {
            $usersModel->where('status', $status);
        }

        // --- 3. SORTING ---
        if ($sort == 'name_asc') {
            $usersModel->orderBy('last_name', 'ASC');
        } elseif ($sort == 'name_desc') {
            $usersModel->orderBy('last_name', 'DESC');
        } else {
            // Default Sort: Newest users first
            $usersModel->orderBy('created_at', 'DESC');
        }

        // --- 4. PREPARE DATA ---
        $data = [
            'title' => 'User Management',
            'users' => $usersModel->paginate($perPage),
            'pager' => $usersModel->pager,
            
            // Pass filters back to view so they stay selected
            'current_search'   => $search,
            'current_sort'     => $sort,
            'current_role'     => $role,
            'current_status'   => $status,
            'current_per_page' => $perPage
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('users/view_users', $data);
    }

    public function add()
    {
        $data['title'] = 'Add New User';
        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('users/view_add');
    }

    public function insert()
    {
        $validation = service('validation');
        $usersModel = new UserModel();

        // 1. Data Mapping
        $data = [
            'school_id'   => $this->request->getPost('school_id'),
            'first_name'  => strtoupper($this->request->getPost('first_name')),
            'last_name'   => strtoupper($this->request->getPost('last_name')),
            'email'       => $this->request->getPost('email'),
            'role'        => $this->request->getPost('role'),
            'status'      => 'Active', 
            'password'    => $this->request->getPost('password'),
            'verifytoken' => bin2hex(random_bytes(16))
        ];

        // 2. Manual Check
        if (empty($data['school_id'])) {
             return redirect()->back()->withInput()->with('error', 'ID Number is required.');
        }

        // 3. Validation Rules
        $rules = [
            'school_id' => 'required|is_unique[users.school_id]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('users/add')->withInput()->with('errors', $validation->getErrors());
        }

        // 4. Save
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $usersModel->insert($data);

        return redirect()->to('users')->with('success', 'User added successfully!');
    }


    public function edit($id)
    {
        $usersModel = new UserModel();

        $data = [
            'title' => 'Edit User',
            'user'  => $usersModel->find($id)
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('users/view_edit', $data);
    }

    public function update($id)
    {
        $usersModel = new UserModel();
        $session = session();
        $currentUserRole = $session->get('role');
        
        // 1. Prepare Basic Data (Everyone can change these)
        $data = [
            'first_name' => strtoupper($this->request->getPost('first_name')),
            'last_name'  => strtoupper($this->request->getPost('last_name')),
            'email'      => $this->request->getPost('email'),
        ];

        // 2. SECURITY CHECK: Only ITSO can change Role and Status
        if ($currentUserRole === 'ITSO') {
            $data['role']   = $this->request->getPost('role');
            $data['status'] = $this->request->getPost('status');
        }
        

        // 3. Password Update (Everyone can change this)
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usersModel->update($id, $data);

        // Redirect based on who is editing
        if ($currentUserRole === 'ITSO') {
            return redirect()->to('users')->with('success', 'User updated successfully!');
        } else {
            return redirect()->to('dashboard')->with('success', 'Profile updated successfully!');
        }
    }


    public function delete($id)
    {
        $usersModel = new UserModel();
        
        $usersModel->update($id, ['status' => 'Inactive']);
        
        return redirect()->to('users')->with('success', 'User has been deactivated (Archived).');
    }

        public function view($id)
    {
        $usersModel = new UserModel();
        
        // 1. Fetch User Profile
        $user = $usersModel->find($id);

        if (!$user) {
            return redirect()->to('users')->with('error', 'User not found.');
        }

        // 2. Fetch Borrowing History
        $history = [];
        // Check if TransactionModel exists before trying to use it
        if (file_exists(APPPATH . 'Models/TransactionModel.php')) {
            $transModel = new \App\Models\TransactionModel();
            $history = $transModel->getUserHistory($id);
        }

        $data = [
            'title'   => 'User Profile',
            'user'    => $user,
            'history' => $history
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('users/view_user', $data); // We create this file next
    }
}