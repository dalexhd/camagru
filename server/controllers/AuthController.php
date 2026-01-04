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

    /**
     * Handle user login.
     * 
     * Checks credentials, verifies account status, and sets up the session.
     * If they haven't clicked the email link yet, we tell them to go check their inbox.
     * 
     * @return void
     */
    public function login()
    {
        if ($this->isPost()) {
            $this->validateCSRF('auth/login');

            $nickname = trim($this->getPostData('nickname', ''));
            $password = $this->getPostData('password', '');

            if (empty($nickname) || empty($password)) {
                $this->flash('error', 'Please fill in all fields.');
                $this->render('auth/login');
                return;
            }

            $user = $this->userModel->findByNickname($nickname);

            if ($user && Security::verifyPassword($password, $user['password'])) {
                if ($user['verified'] == 0) {
                    $this->flash('error', 'Please verify your email address before logging in.');
                    $this->render('auth/login');
                    return;
                }

                $this->Session->set('user_id', $user['id']);
                $this->Session->set('user_email', $user['email']);
                $this->Session->set('user_name', $user['name']);
                $this->Session->set('user_nickname', $user['nickname']);
                $this->Session->set('user_avatar', $user['avatar'] ? $this->Url->asset($user['avatar']) : $this->Url->asset(User::DEFAULT_AVATAR));
                $this->Session->set('user_notifications_enabled', (bool) $user['notifications_enabled']);

                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home';
                $this->Url->{isset($_GET['redirect']) ? 'redirectToUrl' : 'redirect'}($redirect);
            } else {
                $this->flash('error', 'Invalid username or password');
                $this->render('auth/login');
            }
        } else {
            $this->render('auth/login');
        }
    }

    /**
     * Handle user registration.
     * 
     * Validates input, checks for duplicates, creates the user, and sends a verification email.
     * We require strong passwords because security is important.
     * 
     * @return void
     */
    public function register()
    {
        if ($this->isPost()) {
            $this->validateCSRF('auth/register');

            $name = trim($this->getPostData('name', ''));
            $nickname = trim($this->getPostData('nickname', ''));
            $email = trim($this->getPostData('email', ''));
            $password = $this->getPostData('password', '');
            $confirmPassword = $this->getPostData('confirm_password', '');

            if (empty($name) || empty($nickname) || empty($email) || empty($password)) {
                $this->flash('error', 'Please fill in all fields.');
                $this->render('auth/register');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Invalid email format.');
                $this->render('auth/register');
                return;
            }

            if ($this->userModel->findByNickname($nickname)) {
                $this->flash('error', 'Nickname already taken.');
                $this->render('auth/register');
                return;
            }

            if ($this->userModel->findByEmail($email)) {
                $this->flash('error', 'Email already registered.');
                $this->render('auth/register');
                return;
            }

            if (!Security::isValidPassword($password)) {
                $this->flash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
                $this->render('auth/register');
                return;
            }

            if ($password != $confirmPassword) {
                $this->flash('error', 'Passwords do not match');
                $this->render('auth/register');
                return;
            }

            $password = Security::hashPassword($password);
            $verificationToken = bin2hex(random_bytes(32));

            $this->userModel->create($name, $nickname, $email, $password, $verificationToken);

            $verifyLink = $this->Url->absoluteLink('verify', ['token' => $verificationToken]);

            $subject = 'Account Verification';
            $message = "
            <html>
            <head><title>Account Verification</title></head>
            <body>
                <h2>Welcome to Camagru!</h2>
                <p>Thank you for registering. Please click the link below to verify your account:</p>
                <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
                <p>If you did not register for this account, please ignore this email.</p>
            </body>
            </html>";

            Mail::send($email, $subject, $message);

            $this->flash('success', 'Registration successful! Please check your email to verify your account.');
            $this->redirect('login');
        } else {
            $this->render('auth/register');
        }
    }

    /**
     * Handle password recovery request.
     * 
     * Sends an email with a reset link if the account exists.
     * We pretend it worked even if the email isn't found to prevent user enumeration.
     * 
     * @return void
     */
    public function recover()
    {
        if ($this->isPost()) {
            $this->validateCSRF();

            $email = trim($this->getPostData('email', ''));
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Please provide a valid email address.');
                $this->redirect('recover');
                return;
            }

            $user = $this->userModel->findByEmail($email);
            if ($user) {
                $data = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'hash_check' => substr($user['password'], 0, 10),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $encryptedData = base64_encode(Security::encrypt(json_encode($data)));
                $recoveryLink = $this->Url->absoluteLink('reset', ['token' => $encryptedData]);

                $subject = 'Password Recovery';
                $message = "
                <html>
                <head><title>Password Recovery</title></head>
                <body>
                    <h2>Password Recovery</h2>
                    <p>You requested a password reset for your Camagru account.</p>
                    <p>Please click the link below to reset your password:</p>
                    <p><a href='{$recoveryLink}'>{$recoveryLink}</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you did not request this, please ignore this email.</p>
                </body>
                </html>";

                if (Mail::send($user['email'], $subject, $message)) {
                    $this->flash('success', 'Password recovery instructions have been sent to your email.');
                } else {
                    $this->flash('error', 'Failed to send recovery email. Please try again later.');
                }
            } else {
                $this->flash('error', 'No account found with that email address.');
            }
            $this->redirect('recover');
        } else {
            $this->render('auth/recover');
        }
    }

    /**
     * Handle password reset.
     * 
     * Verifies the token and updates the password.
     * The token contains encrypted data to verify identity and expiration.
     * 
     * @param string $token
     * @return void
     */
    public function reset($token)
    {
        try {
            $json = Security::decrypt(base64_decode($token));
            $data = json_decode($json, true);
        } catch (Exception $e) {
            $this->flash('error', 'Invalid or expired token');
            $this->redirect('login');
            return;
        }

        if (!$data || !isset($data['email']) || !isset($data['created_at']) || !isset($data['hash_check'])) {
            $this->flash('error', 'Invalid token structure');
            $this->redirect('login');
            return;
        }

        if (time() - strtotime($data['created_at']) > 3600) {
            $this->flash('error', 'Token has expired');
            $this->redirect('recover');
            return;
        }

        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || substr($user['password'], 0, 10) !== $data['hash_check']) {
            $this->flash('error', 'Invalid or expired token');
            $this->redirect('login');
            return;
        }

        if ($this->isPost()) {
            $this->validateCSRF('auth/reset', ['token' => $token]);

            $password = $this->getPostData('password', '');
            $confirmPassword = $this->getPostData('confirm_password', '');

            if ($password != $confirmPassword) {
                $this->flash('error', 'Passwords do not match');
                $this->render('auth/reset', ['token' => $token]);
                return;
            }

            if (!Security::isValidPassword($password)) {
                $this->flash('error', 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.');
                $this->render('auth/reset', ['token' => $token]);
                return;
            }

            $this->userModel->update($user['id'], ['password' => Security::hashPassword($password)]);
            $this->flash('success', 'Your password has been updated');
            $this->redirect('login');
        } else {
            $this->render('auth/reset', ['token' => $token]);
        }
    }

    /**
     * Verify user account.
     * 
     * Activates the account if the token is valid.
     * Allows them to log in afterwards.
     * 
     * @param string $token
     * @return void
     */
    public function verify($token)
    {
        $user = $this->userModel->findByVerificationToken($token);

        if ($user) {
            $this->userModel->update($user['id'], ['verified' => 1, 'verification_token' => null]);
            $this->flash('success', 'Your account has been verified. You can now log in.');
        } else {
            $this->flash('error', 'Invalid verification token.');
        }
        $this->redirect('login');
    }

    /**
     * Log out the user.
     * 
     * Destroys the session and redirects to home.
     * Bye bye!
     * 
     * @return void
     */
    public function logout()
    {
        $this->Session->destroy();
        $this->redirect('home');
    }
}
