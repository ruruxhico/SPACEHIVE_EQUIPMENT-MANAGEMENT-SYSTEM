<?php

namespace App\Controllers;

 use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        $data ['title'] = 'Dashboard';
        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_index', $data);
    }

    public function login()
    {
        $data['title'] = 'Login';
        return view('include\view_head', $data)
            .view('auth\view_login');
    }

    public function loginSubmit()
    {
        //dd('LoginSubmit reached');
        $session = session();
        $usersModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $usersModel->where('email', $email)->first();

        /* if ($user) {
            //if ($password === $user['password']) {
            if (password_verify($password, $user['password'])) {
                // Set session data
                $sessionData = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Incorrect password.');
                return redirect()->back()->withInput();
            }
        } else {
            $session->setFlashdata('error', 'Email not found.');
            return redirect()->back()->withInput();
        } */  

            if (!$user) {
                $session->setFlashdata('error', 'Email not found.');
                return redirect()->back()->withInput();
            }

            // Correct way to verify hashed password
            if (!password_verify($password, $user['password'])) {
                $session->setFlashdata('error', 'Incorrect password.');
                return redirect()->back()->withInput();
            }

            // Login success — set session
            $sessionData = [
                'id' => $user['id'],
                'email' => $user['email'],
                'isLoggedIn' => true,
            ];

            $session->set($sessionData);
            return redirect()->to('/dashboard');
    }

    public function signup()
    {
        $data['title'] = 'Sign Up';
        return view('include\view_head', $data)
            .view('auth\view_signup');
    }

    public function signupSubmit()
    {
        $session = session();
        $validation = service('validation');
        $usersModel = new UserModel();

        $data = array(
            'position' => strtoupper($this->request->getPost('position')),
            'firstname' => strtoupper($this->request->getPost('firstname')),
            'middlename' => strtoupper($this->request->getPost('middlename')),
            'lastname' => strtoupper($this->request->getPost('lastname')),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'verifytoken' => bin2hex(random_bytes(16))
        );  

        if(! $validation->run($data, 'insert')){
            //validation fails = reload signup form
            $data['title'] = 'Sign Up';
            $data['errors'] = $validation->getErrors();
            return view('include\view_head', $data)
                .view('auth\view_signup', $data);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['confirmpassword']);
        $usersModel->insert($data);

        $session->setFlashdata('success', 'Account created successfully. Please log in.');
        
        $email = service('email');

        $message = "Dear ".$data['firstname']." ".$data['lastname'].",<br><br>"
            ."Your staff account has been created successfully. Please click the link to verify your account:<br>"
            ."<a href=".base_url('auth/verify/'.$data['verifytoken']).">Verify Account</a><br><br>"
            ."Best regards,<br>"
            ."Lola Nena's Sisigan Team";

        $email->setTo($data['email']);
        $email->setSubject('Account Verification - Lola Nena\'s Sisigan');
        $email->setMessage($message);
        $email->send();

        return redirect()->to(base_url('auth/login'));
    }

    public function verify($token){
        $usersModel = model('Model_Users');

        $user = $usersModel->where('verifytoken', $token)->first();

        if($user){
            $usersModel->update($user['id'], [
                'isverified' => 1
            ]);

            return redirect()->to('auth/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have logged out successfully.');
    }
}

?>