<?php
namespace App\Controllers;

class Users extends BaseController {
    public function index($perpage = 5){
        // Instantiate a model object
        $usersModel = model('UserModel');

        //$queryResult = $usersModel->findAll();
        $usersModel->orderBy('last_name');
        $queryResult = $usersModel->paginate($perpage);

        // dd($queryResult);

        $data = array(
            'title' => 'User Records',
            'users' => $queryResult,
            'pager' => $usersModel->pager
        );

        // dd($data);

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('users\view_users', $data);
    }

    public function add(){
        $data['title'] = 'Add New Users';
        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('users\view_add');
    }

    public function insert(){
        //validation library
        $validation = service('validation');
        $usersModel = model('UserModel');

        $role = $this->request->getPost('role');
        /* $email = $this->request->getPost('email');
        $confirm = $this->request->getPost('confirmpassword'); */

        /* // Check if passwords match
        $existingUser = $usersModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email is already registered!')->withInput();
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match!')->withInput();
        }
        */

        /* $password = $this->request->getPost('password');
        // Hash the password before saving
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); */
        
        $data = array(
            'school_id' => $this->request->getPost('school_id'),
            'first_name' => strtoupper($this->request->getPost('first_name')),
            'middle_name' => strtoupper($this->request->getPost('middle_name')),
            'last_name' => strtoupper($this->request->getPost('last_name')),
            'email' => $this->request->getPost('email'),
            'role' => strtoupper($role),
            'status' => strtoupper($this->request->getPost('status')),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'verifytoken' => bin2hex(random_bytes(16))
        );  

        // Dynamically add extra fields based on position
        if ($role === 'ASSOCIATE') {
            // You would need a column like 'associate_id' in your users table for this
            $data['associate_key'] = $this->request->getPost('associate_key');
        } elseif ($role === 'STUDENT') {
            // You would need columns like 'student_id' and 'course'
            $data['student_number'] = $this->request->getPost('student_number');
        }
        
        if(! $validation->run($data, 'insert')){
            //validation fails = reload signup form
            $data['title'] = 'Add New Users';
            
            //$this->session->setFlashdata('error', $validation->getErrors());

            /*$data['errors'] = $validation->getErrors();
            return view('include\view_head', $data)
                .view('include\view_nav')
                .view('users\view_add', $data); */
                return redirect()->to('users/add')
                                ->withInput()
                                ->with('errors', $validation->getErrors());
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['confirmpassword']);

        $usersModel->insert($data);

        //return redirect()->to(base_url('users'))->with('success', 'User added successfully!');
        session()->setFlashdata('success', 'Adding new user account is successful.');
        
        $email = service('email');

        $message = "Dear ".$data['firstname']." ".$data['lastname'].",<br><br>"
            ."Your staff account has been created successfully. Please click the link to verify your account:<br>"
            ."<a href='".base_url('users/verify/'.$data['verifytoken'])."'>Verify Account</a><br><br>"
            ."Best regards,<br>"
            ."Lola Nena's Sisigan Team";

        $email->setTo($data['email']);
        $email->setSubject('Account Verification - Lola Nena\'s Sisigan');
        $email->setMessage($message);
        $email->send();
        return redirect()->to('users');
    }

    public function verify($token){
        $usersModel = model('UserModel');

        $user = $usersModel->where('verifytoken', $token)->first();

        if($user){
            $usersModel->update($user['id'], [
                'isverified' => 1
            ]);

            return redirect()->to('users');
        }
    }

    public function view($id){
        $usersModel = model('UserModel');

        $data = array (
            'title' => 'View Staff Account',
            'user' => $usersModel->find($id)
        );

        // dd($data);

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('users\view_user', $data);
    }

    public function edit($id){
        $usersModel = model('Model_Users');

        $data = array (
            'title' => 'Edit Staff Account',
            'user' => $usersModel->find($id)
        );

        // dd($data);

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('users\view_edit', $data);
    }

    public function update($id) {
        $usersModel = model('UserModel');
        $validation = service('validation');
        
        $data = array(
            'first_name' => strtoupper($this->request->getPost('first_name')),
            'middle_name' => strtoupper($this->request->getPost('middle_name')),
            'last_name' => strtoupper($this->request->getPost('last_name')),
            'email' => $this->request->getPost('email'),
            'role' => strtoupper($this->request->getPost('role')),
            'status' => strtoupper($this->request->getPost('status')),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
        );

        $existingUser = $usersModel
            ->where('email', $data['email'])
            ->where('id !=', $id)
            ->first();

        if($existingUser) {
            return redirect()->back()
            ->with('error', 'Email is already registered!')
            ->withInput();
        }

        if(! $validation->run($data, 'edit')){
            //validation fails = reload signup form
            $data['title'] = 'Edit Users';

            session()->setFlashdata('error', $validation->getErrors());
            /* $data['errors'] = $validation->getErrors();
            $data['user'] = $usersModel->find($id);
            return view('include\view_head', $data)
                .view('include\view_nav')
                .view('users\view_edit', $data); */
            return redirect()->to('users/edit');
        }

        if(!empty($data['password'])){
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }


        unset($data['confirmpassword']);
        $usersModel->update($id, $data);

        session()->setFlashdata('success', 'User account updated successfully.');
        return redirect()->to('users');
        //return redirect()->to(base_url('users'))->with('success', 'User updated successfully!');
    }

    public function delete($id){
        $usersModel = model('Model_Users');
        $usersModel->delete($id);
        return redirect()->to(base_url('users'))->with('success', 'User deleted successfully!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have logged out successfully.');
    }
}
?>