<?php

use core\Controller;
use core\Security;
use app\models\User;

class AuthController extends Controller
{
    private $userModel;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->userModel = new User();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Find user by email
            $user = $this->userModel->findByEmail($email);

            if ($user && Security::verifyPassword($password, $user['password'])) {
                // Create user session
                $this->Session->set('user_id', $user['id']);
                $this->Session->set('user_email', $user['email']);
                $this->Session->set('user_name', $user['name']);
                $this->Session->set('user_nickname', $user['nickname']);
                $this->Session->set('user_avatar', $user['avatar'] ? $this->Url->asset($user['avatar']) : null);

                // Redirect to home page
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home';
                $this->Url->{isset($_GET['redirect']) ? 'redirectToUrl' : 'redirect'}($redirect);
            } else {
                // Invalid login
                $this->Session->setFlash('error', 'Invalid email or password');
                $this->View->render('auth/login');
            }
        } else {
            $this->View->render('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $nickname = $_POST['nickname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validate password
            if ($password != $confirmPassword) {
                $this->Session->setFlash('error', 'Passwords do not match');
                $this->View->render('auth/register');
                return;
            }

            // Hash password
            $password = Security::hashPassword($password);

            // Register user
            $this->userModel->create($name, $nickname, $email, $password);

            // Redirect to login page
            $this->Session->setFlash('success', 'You are registered and can log in');
            $this->Url->redirect('login');
        } else {
            $this->View->render('auth/register');
        }
    }

    public function logout()
    {
        // Unset all session values
        $this->Session->destroy();

        // Redirect to login page
        $this->Url->redirect('home');
    }
}
