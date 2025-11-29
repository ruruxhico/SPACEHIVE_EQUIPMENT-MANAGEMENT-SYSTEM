<?php 

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        $data['title'] = 'Dashboard';
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
        $session = session();
        $usersModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $usersModel->where('email', $email)->first();

        // 1. Check if Email Exists
        if (!$user) {
            $session->setFlashdata('error', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // ============================================================
        // 2. CRITICAL CHECK: IS THE USER ACTIVE?
        // This prevents "Archived/Deactivated" users from logging in.
        // ============================================================
        if ($user['status'] !== 'Active') {
            $session->setFlashdata('error', 'Your account is inactive or deactivated. Please contact the administrator.');
            return redirect()->back()->withInput();
        }

        // 3. Check Password
        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // 4. Login Success
        $sessionData = [
            'id' => $user['school_id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ];

        $session->set($sessionData);
        return redirect()->to('/dashboard');
    }

    // 1. Show the Sign Up Page (GET)
    public function signup()
    {
        $data['title'] = 'Sign Up';
        return view('include\view_head', $data)
            .view('auth\view_signup');
    }

    // 2. Handle the Form Submission (POST)
    public function signupSubmit()
    {
        $usersModel = new UserModel();
        $validation = service('validation');

        // Get the Role (View uses 'position', DB uses 'role')
        $role = $this->request->getPost('position'); 

        // Determine School ID based on Role
        $schoolId = '';
        if ($role === 'ASSOCIATE') {
            $schoolId = $this->request->getPost('associate_key');
        } elseif ($role === 'STUDENT') {
            $schoolId = $this->request->getPost('student_number');
        }

        // Prepare Data
        $data = [
            'school_id'   => $schoolId, 
            'first_name'  => strtoupper($this->request->getPost('firstname')),
            'middle_name' => strtoupper($this->request->getPost('middlename')),
            'last_name'   => strtoupper($this->request->getPost('lastname')),
            'email'       => $this->request->getPost('email'),
            'role'        => $role,
            'status'      => 'Active',
            'password'    => $this->request->getPost('password'),
            'verifytoken' => bin2hex(random_bytes(16))
        ];

        // Manual Check for ID
        if (empty($data['school_id'])) {
            return redirect()->back()->withInput()->with('error', 'ID Number is required for the selected role.');
        }

        // Validation Rules
        $rules = [
            'email'           => 'required|valid_email|is_unique[users.email]',
            'password'        => 'required|min_length[6]',
            'confirmpassword' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            $data['title'] = 'Sign Up';
            $data['errors'] = $validation->getErrors();
            return view('include\view_head', $data)
                .view('auth\view_signup', $data); // Pass errors back to view
        }

        // Hash & Save
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $usersModel->insert($data);

            // --- 8. SEND CUSTOM EMAIL ---
            $email = service('email');
            
            // Prepare data for the View
            $emailData = [
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'role' => $role,
                'link' => base_url("auth/verify/" . $data['verifytoken'])
            ];

            // Load the HTML View as a String
            $message = view('emails/verify_account', $emailData);

            $email->setTo($data['email']);
            $email->setSubject('Activate Your Account - ITSO Inventory');
            $email->setMessage($message); // Send the HTML we just loaded
            
            if ($email->send()) {
                return redirect()->to('auth/login')->with('success', 'Registration successful! Please check your email to verify.');
            } else {
                return redirect()->to('auth/login')->with('error', 'Account created, but email failed. Contact Admin.');
            }    }

    public function verify($token)
    {
        $usersModel = new UserModel();
        $user = $usersModel->where('verifytoken', $token)->first();

        if($user){
            $usersModel->update($user['school_id'], ['isverified' => 1]);
            return redirect()->to('auth/login')->with('success', 'Account verified!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'You have logged out successfully.');
    }
}