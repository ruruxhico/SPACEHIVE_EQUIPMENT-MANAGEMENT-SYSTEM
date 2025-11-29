<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index($perpage = 5)
    {

        // SECURITY CHECK: Only ITSO can access this
        if (session()->get('role') !== 'ITSO') {
            return redirect()->to('dashboard')->with('error', 'Access Denied: You are not authorized to view Users.');
        }

        $usersModel = new UserModel();

        $search = $this->request->getGet('search');
        if (!empty($search)) {
            $usersModel->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('school_id', $search)
            ->groupEnd();
        }

        $usersModel->orderBy('created_at', 'DESC');

        $data = [
            'title' => 'User Management',
            'users' => $usersModel->paginate($perpage),
            'pager' => $usersModel->pager,
            'search' => $search
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

        // 1. Get the Role (View uses 'position', DB needs 'role')
        $role = $this->request->getPost('position'); 

        // 2. Determine the School ID based on the Role
        $schoolId = '';
        if ($role === 'ASSOCIATE') {
            $schoolId = $this->request->getPost('associate_key');
        } elseif ($role === 'STUDENT') {
            $schoolId = $this->request->getPost('student_number');
        }

        // 3. Prepare Data
        $data = [
            'school_id'   => $schoolId, // Use the variable we calculated above
            'first_name'  => strtoupper($this->request->getPost('firstname')), // View uses 'firstname', not 'first_name'
            'middle_name' => strtoupper($this->request->getPost('middlename')),
            'last_name'   => strtoupper($this->request->getPost('lastname')),
            'email'       => $this->request->getPost('email'),
            'role'        => $role,
            'status'      => 'Active', 
            'password'    => $this->request->getPost('password'),
            'verifytoken' => bin2hex(random_bytes(16))
        ];

        // 4. Validate
        // We check school_id manually here since it came from different fields
        if (empty($data['school_id'])) {
            return redirect()->back()->withInput()->with('error', 'ID Number is required for the selected role.');
        }

        $rules = [
            'school_id' => 'is_unique[users.school_id]', // Check uniqueness
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'confirmpassword' => 'matches[password]' // Good practice to validate confirm pass
        ];

        if (!$this->validate($rules)) {
            // Merge our manual error if needed, or just let validation handle it
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // 5. Hash & Save
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $usersModel->insert($data);

        // 6. Email
        $email = service('email');
        $message = "Dear " . $data['first_name'] . ",<br><br>"
            . "Your ITSO System account has been created.<br>"
            . "Role: " . $role . "<br><br>"
            . "Please contact the admin if this was a mistake.";

        $email->setTo($data['email']);
        $email->setSubject('Account Created - ITSO Inventory System');
        $email->setMessage($message);
        $email->send();

        // Redirect to Login instead of Users list (since this is a signup)
        return redirect()->to('auth/login')->with('success', 'Account created successfully! Please Login.');
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
        // If not ITSO, we simply DO NOT add 'role' or 'status' to the $data array.
        // The database will keep the old values.

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
        $usersModel->delete($id);
        return redirect()->to('users')->with('success', 'User deleted successfully!');
    }
}