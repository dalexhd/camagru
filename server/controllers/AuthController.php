<?php

use core\Controller;
use core\Security;
use app\models\User;
use core\Mail;

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

    public function recover()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];

            // Check if user exists
            $user = $this->userModel->findByEmail($email);
            if ($user) {
                $data = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'hash_check' => substr($user['password'], 0, 10), // Include part of hash for invalidation
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $encryptedData = base64_encode(Security::encrypt(json_encode($data)));

                // Construct full URL
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $domainName = $_SERVER['HTTP_HOST'];
                $resetPath = $this->Url->link('reset', ['token' => $encryptedData]);

                // If UrlHelper returns a relative path, prepend protocol and domain
                if (strpos($resetPath, 'http') !== 0) {
                    $recoveryLink = $protocol . $domainName . $resetPath;
                } else {
                    $recoveryLink = $resetPath;
                }

                // Send recovery email
                $subject = 'Password Recovery';
                $message = "
                <html>
                <head>
                    <title>Password Recovery</title>
                </head>
                <body>
                    <h2>Password Recovery</h2>
                    <p>You requested a password reset for your Camagru account.</p>
                    <p>Please click the link below to reset your password:</p>
                    <p><a href='{$recoveryLink}'>{$recoveryLink}</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you did not request this, please ignore this email.</p>
                </body>
                </html>
                ";

                $success = Mail::send($user['email'], $subject, $message);
                if ($success) {
                    $this->Session->setFlash('success', 'Password recovery instructions have been sent to your email.');
                } else {
                    $this->Session->setFlash('error', 'Failed to send recovery email. Please try again later.');
                }
            } else {
                $this->Session->setFlash('error', 'No account found with that email address.');
            }
            $this->Url->redirect('recover');
        } else {
            $this->View->render('auth/recover');
        }
    }

    public function reset($token)
    {
        // Decrypt token
        try {
            $json = Security::decrypt(base64_decode($token));
            $data = json_decode($json, true);
        } catch (Exception $e) {
            $this->Session->setFlash('error', 'Invalid or expired token');
            $this->Url->redirect('login');
            return;
        }

        if (!$data || !isset($data['email']) || !isset($data['created_at']) || !isset($data['hash_check'])) {
            $this->Session->setFlash('error', 'Invalid token structure');
            $this->Url->redirect('login');
            return;
        }

        // Check token expiration (e.g., 1 hour)
        $createdAt = strtotime($data['created_at']);
        if (time() - $createdAt > 3600) {
            $this->Session->setFlash('error', 'Token has expired');
            $this->Url->redirect('recover');
            return;
        }

        // Check if token is invalidated (password changed)
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user) {
            $this->Session->setFlash('error', 'User not found');
            $this->Url->redirect('login');
            return;
        }

        if (substr($user['password'], 0, 10) !== $data['hash_check']) {
            $this->Session->setFlash('error', 'This password reset link is invalid because the password has already been changed.');
            $this->Url->redirect('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password != $confirmPassword) {
                $this->Session->setFlash('error', 'Passwords do not match');
                $this->View->render('auth/reset', ['token' => $token]);
                return;
            }

            // Update password
            $hash = Security::hashPassword($password);
            $this->userModel->update($user['id'], ['password' => $hash]);
            $this->Session->setFlash('success', 'Your password has been updated');
            $this->Url->redirect('login');
        } else {
            $this->View->render('auth/reset', ['token' => $token]);
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
