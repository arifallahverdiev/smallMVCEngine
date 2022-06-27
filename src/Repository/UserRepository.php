<?php

namespace App\Repository;

use App\Core\Database\QueryBuilder;
use App\Core\Database\Repository;
use App\Entity\User;

class UserRepository extends Repository
{
    protected string $tableName = 'user';

    public function getQb(): QueryBuilder
    {
        return new QueryBuilder($this->tableName, User::class);
    }

    public function findByName(string $username): ?User
    {
        return $this->getQb()
            ->select()
            ->where('username', $username);
    }
}