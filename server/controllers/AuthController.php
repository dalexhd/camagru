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
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];

            // Find user by nickname
            $user = $this->userModel->findByNickname($nickname);

            if ($user && Security::verifyPassword($password, $user['password'])) {
                if ($user['verified'] == 0) {
                    $this->Session->setFlash('error', 'Please verify your email address before logging in.');
                    $this->View->render('auth/login');
                    return;
                }
                // Create user session
                $this->Session->set('user_id', $user['id']);
                $this->Session->set('user_email', $user['email']);
                $this->Session->set('user_name', $user['name']);
                $this->Session->set('user_nickname', $user['nickname']);
                $this->Session->set('user_avatar', $user['avatar'] ? $this->Url->asset($user['avatar']) : $this->Url->asset(User::DEFAULT_AVATAR));
                $this->Session->set('user_notifications_enabled', (bool) $user['notifications_enabled']);

                // Redirect to home page
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home';
                $this->Url->{isset($_GET['redirect']) ? 'redirectToUrl' : 'redirect'}($redirect);
            } else {
                // Invalid login
                $this->Session->setFlash('error', 'Invalid username or password');
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

            // Validate password complexity
            if (!Security::isValidPassword($password)) {
                $this->Session->setFlash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
                $this->View->render('auth/register');
                return;
            }

            // Validate password
            if ($password != $confirmPassword) {
                $this->Session->setFlash('error', 'Passwords do not match');
                $this->View->render('auth/register');
                return;
            }

            // Hash password
            $password = Security::hashPassword($password);
            $verificationToken = bin2hex(random_bytes(32));

            // Register user
            $this->userModel->create($name, $nickname, $email, $password, $verificationToken);

            // Send verification email
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            $verifyPath = $this->Url->link('verify', ['token' => $verificationToken]);

            if (strpos($verifyPath, 'http') !== 0) {
                $verifyLink = $protocol . $domainName . $verifyPath;
            } else {
                $verifyLink = $verifyPath;
            }

            $subject = 'Account Verification';
            $message = "
            <html>
            <head>
                <title>Account Verification</title>
            </head>
            <body>
                <h2>Welcome to Camagru!</h2>
                <p>Thank you for registering. Please click the link below to verify your account:</p>
                <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                <p>If you did not register for this account, please ignore this email.</p>
            </body>
            </html>
            ";

            Mail::send($email, $subject, $message);

            // Redirect to login page
            $this->Session->setFlash('success', 'Registration successful! Please check your email to verify your account.');
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

            // Validate password complexity
            if (!Security::isValidPassword($password)) {
                $this->Session->setFlash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
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

    public function verify($token)
    {
        $user = $this->userModel->findByVerificationToken($token);

        if ($user) {
            $this->userModel->update($user['id'], ['verified' => 1, 'verification_token' => null]);
            $this->Session->setFlash('success', 'Your account has been verified. You can now log in.');
        } else {
            $this->Session->setFlash('error', 'Invalid verification token.');
        }
        $this->Url->redirect('login');
    }

    public function logout()
    {
        // Unset all session values
        $this->Session->destroy();

        // Redirect to login page
        $this->Url->redirect('home');
    }
}
