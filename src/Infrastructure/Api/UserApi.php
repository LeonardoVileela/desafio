<?php

namespace Infrastructure\Api;

class UserApi
{
    public function getUserById($id)
    {
        return json_encode([
            'id' => $id,
            'name' => 'João Silva',
            'email' => 'joao.silva@exemplo.com',
        ]);
    }

    public function createUser($userData)
    {
        return json_encode([
            'success' => true,
            'id' => rand(100, 999),
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }
}

