<?php

namespace Infrastructure\Persistence\Repository;

use Infrastructure\Persistence\DatabaseConnection;
use PDO;

class UserRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getConnection();
    }


    public function verifyCredentials($email, $password): bool
    {
        $stmt = $this->connection->prepare("SELECT password FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return true;
        }

        return false;
    }

}