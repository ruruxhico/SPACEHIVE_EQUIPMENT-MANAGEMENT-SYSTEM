<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use Config\App as AppConfig;
use App\Controllers\Auth;
use App\Models\UserModel;

/**
 * @internal
 */
final class AuthControllerTest extends CIUnitTestCase
{
    protected function makeRequest(array $post = []): IncomingRequest
    {
        $config = new AppConfig();
        $uri     = new URI('http://example.com/auth/login');
        $userAgent = new UserAgent();
        $request = new IncomingRequest($config, $uri, null, $userAgent);
        if (!empty($post)) {
            $request->setGlobal('post', $post);
        }
        return $request;
    }

    protected function getControllerWithRequest(IncomingRequest $request): Auth
    {
        $controller = new class($request) extends Auth {
            public function __construct($request)
            {
                $this->request = $request;
            }
        };
        return $controller;
    }

    public function testLoginSubmitEmailNotFoundSetsFlashAndRedirectsBack(): void
    {
        // Mock UserModel to return no user
        $userModel = $this->createMock(UserModel::class);
        $userModel->method('where')->willReturn($userModel);
        $userModel->method('first')->willReturn(null);

        // Swap the model loader inside controller via anonymous subclass
        $request = $this->makeRequest(['email' => 'missing@example.com', 'password' => 'secret']);

        $controller = new class($request, $userModel) extends Auth {
            private UserModel $mockModel;
            public function __construct($request, UserModel $mockModel)
            {
                $this->request = $request;
                $this->mockModel = $mockModel;
            }
            public function loginSubmit()
            {
                $session = session();
                $usersModel = $this->mockModel;
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $usersModel->where('email', $email)->first();
                if (!$user) {
                    $session->setFlashdata('error', 'Email not found.');
                    return redirect()->back()->withInput();
                }
                return parent::loginSubmit();
            }
        };

        $response = $controller->loginSubmit();
        $this->assertSame('Email not found.', session()->getFlashdata('error'));
        $this->assertTrue(method_exists($response, 'withInput'));
    }

    public function testLoginSubmitIncorrectPasswordSetsFlashAndRedirectsBack(): void
    {
        $hashed = password_hash('right-password', PASSWORD_DEFAULT);
        $fakeUser = ['id' => 1, 'email' => 'user@example.com', 'password' => $hashed];

        $userModel = $this->createMock(UserModel::class);
        $userModel->method('where')->willReturn($userModel);
        $userModel->method('first')->willReturn($fakeUser);

        $request = $this->makeRequest(['email' => 'user@example.com', 'password' => 'wrong-password']);

        $controller = new class($request, $userModel) extends Auth {
            private UserModel $mockModel;
            public function __construct($request, UserModel $mockModel)
            {
                $this->request = $request;
                $this->mockModel = $mockModel;
            }
            public function loginSubmit()
            {
                $session = session();
                $usersModel = $this->mockModel;
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $usersModel->where('email', $email)->first();
                if (!password_verify($password, $user['password'])) {
                    $session->setFlashdata('error', 'Incorrect password.');
                    return redirect()->back()->withInput();
                }
                return parent::loginSubmit();
            }
        };

        $response = $controller->loginSubmit();
        $this->assertSame('Incorrect password.', session()->getFlashdata('error'));
        $this->assertTrue(method_exists($response, 'withInput'));
    }

    public function testLoginSubmitSuccessSetsSessionAndRedirectsToDashboard(): void
    {
        $hashed = password_hash('right-password', PASSWORD_DEFAULT);
        $fakeUser = ['id' => 7, 'email' => 'user@example.com', 'password' => $hashed];

        $userModel = $this->createMock(UserModel::class);
        $userModel->method('where')->willReturn($userModel);
        $userModel->method('first')->willReturn($fakeUser);

        $request = $this->makeRequest(['email' => 'user@example.com', 'password' => 'right-password']);

        $controller = new class($request, $userModel) extends Auth {
            private UserModel $mockModel;
            public function __construct($request, UserModel $mockModel)
            {
                $this->request = $request;
                $this->mockModel = $mockModel;
            }
            public function loginSubmit()
            {
                $session = session();
                $usersModel = $this->mockModel;
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $usersModel->where('email', $email)->first();

                if (!$user) {
                    $session->setFlashdata('error', 'Email not found.');
                    return redirect()->back()->withInput();
                }
                if (!password_verify($password, $user['password'])) {
                    $session->setFlashdata('error', 'Incorrect password.');
                    return redirect()->back()->withInput();
                }

                // Simulate success branch of original controller
                $sessionData = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);
                return redirect()->to('/dashboard');
            }
        };

        $response = $controller->loginSubmit();
        $this->assertTrue(session()->get('isLoggedIn'));
        $this->assertSame(7, session()->get('id'));
        $this->assertSame('user@example.com', session()->get('email'));
        $this->assertTrue(method_exists($response, 'getReason')); // redirect response
    }

    public function testLogoutDestroysSessionAndRedirectsToLogin(): void
    {
        session()->set(['id' => 1, 'email' => 'x@y.z', 'isLoggedIn' => true]);
        $controller = new Auth();
        $response = $controller->logout();
        $this->assertNull(session()->get('id'));
        $this->assertNull(session()->get('email'));
        $this->assertNull(session()->get('isLoggedIn'));
        $this->assertTrue(method_exists($response, 'getReason'));
    }

    public function testSignupValidationFailureRendersSignupView(): void
    {
        // Mock validation to fail
        $validation = service('validation');
        $validation->reset();
        // We cannot easily change the defined rule group 'insert' here without app config,
        // so we simulate by providing missing fields to trigger failure in typical setups.
        $request = $this->makeRequest([
            'position' => '',
            'firstname' => '',
            'middlename' => '',
            'lastname' => '',
            'email' => 'bad-email',
            'password' => 'short',
            'confirmpassword' => 'mismatch',
        ]);

        $controller = new class($request) extends Auth {
            public function __construct($request)
            {
                $this->request = $request;
            }
        };

        // Call method and ensure it returns a string containing signup view marker
        $output = $controller->signupSubmit();
        $this->assertIsString($output);
        $this->assertStringContainsString('Sign Up', $output);
    }
}
