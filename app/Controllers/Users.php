<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index($perpage = 5)
    {
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

        $role = $this->request->getPost('role');
        
        $data = [
            'school_id'   => $this->request->getPost('school_id'),
            'first_name'  => strtoupper($this->request->getPost('first_name')),
            'last_name'   => strtoupper($this->request->getPost('last_name')),
            'email'       => $this->request->getPost('email'),
            'role'        => $role,
            'status'      => 'Active', 
            'password'    => $this->request->getPost('password'),
            'verifytoken' => bin2hex(random_bytes(16))
        ];

        $rules = [
            'school_id' => 'required|is_unique[users.school_id]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('users/add')->withInput()->with('errors', $validation->getErrors());
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $usersModel->insert($data);

        $email = service('email');
        $message = "Dear " . $data['first_name'] . ",<br><br>"
            . "Your ITSO System account has been created.<br>"
            . "Role: " . $role . "<br><br>"
            . "Please contact the admin if this was a mistake.";

        $email->setTo($data['email']);
        $email->setSubject('Account Created - ITSO Inventory System');
        $email->setMessage($message);
        $email->send();

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
        
        $data = [
            'first_name' => strtoupper($this->request->getPost('first_name')),
            'last_name'  => strtoupper($this->request->getPost('last_name')),
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role'),
            'status'     => $this->request->getPost('status'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usersModel->update($id, $data);

        return redirect()->to('users')->with('success', 'User updated successfully!');
    }


    public function delete($id)
    {
        $usersModel = new UserModel();
        $usersModel->delete($id);
        return redirect()->to('users')->with('success', 'User deleted successfully!');
    }
}