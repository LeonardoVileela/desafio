<?php

namespace Infrastructure\Web\Controller;

use Core\Application\Service\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->authService->login($email, $password)) {

            header('Location: ' . BASE_URL . '/dashboard');
            exit();
        } else {
            echo "<p style='color: red;'>E-mail ou senha incorretos. Por favor, tente novamente.</p>";
            require_once __DIR__ . '/../View/auth/auth.php';
        }
    }

    public function logout()
    {
        $this->authService->logout();
    }

    public function showLogin()
    {
        require_once __DIR__ . '/../View/auth/auth.php';
    }
}
