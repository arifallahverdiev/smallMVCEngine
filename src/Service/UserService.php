<?php

namespace App\Service;

class UserService
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function auth(string $username, string $passwordHash): bool
    {
        return $this->username === $username && md5($this->password) === $passwordHash;
    }
}