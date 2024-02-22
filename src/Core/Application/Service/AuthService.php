<?php

namespace Core\Application\Service;

use Infrastructure\Persistence\Repository\UserRepository;

class AuthService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function login($email, $password): bool {

        if ($this->userRepository->verifyCredentials($email, $password)) {

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $email;

            return true;
        }

        return false;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: " . BASE_URL . "/login");
        exit();
    }
}

