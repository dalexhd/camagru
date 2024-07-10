<?php

class AuthController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->setLayout('layouts/auth');
    }

    public function showLogin() {
        $this->render('auth/login');
    }

    public function showRegister() {
        $this->render('auth/register');
    }

    public function register() {
        $username = Security::sanitize($_POST['username']);
        $email = Security::sanitize($_POST['email']);
        $password = Security::hashPassword($_POST['password']);
        $token = bin2hex(random_bytes(16));

        $user = $this->model('User');
        $user->create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'token' => $token
        ]);

        // Send verification email
        $subject = "Verify your email";
        $message = "Please click the link to verify your email: " . getenv('BASE_URL') . "/auth/verify?token=" . $token;
        Mail::send($email, $subject, $message);

        Session::setFlash('success', 'Registration successful! Please check your email to verify your account.');
        $this->render('auth/verify', ['email' => $email]);
    }

    public function login() {
        $email = Security::sanitize($_POST['email']);
        $password = $_POST['password'];

        $user = $this->model('User')->findByEmail($email);
        if ($user && Security::verifyPassword($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['scopes'] = $this->getScopesForUser($user);
            Session::setFlash('success', 'Login successful!');
            header('Location: ' . getenv('BASE_URL'));
            exit();
        } else {
            Session::setFlash('error', 'Invalid email or password');
            $this->render('auth/login');
        }
    }

    public function verify() {
        $token = Security::sanitize($_GET['token']);
        $this->model('User')->verifyUser($token);

        Session::setFlash('success', 'Your email has been verified. You can now log in.');
        header('Location: ' . getenv('BASE_URL') . '/login');
        exit();
    }

    public function logout() {
        session_destroy();
        Session::setFlash('success', 'You have been logged out.');
        header('Location: ' . getenv('BASE_URL') . '/login');
        exit();
    }

    private function getScopesForUser($user) {
        $scopes = [];
        if ($user['role'] === 'admin') {
            $scopes[] = 'admin';
        }
        $scopes[] = 'user';
        return $scopes;
    }
}
