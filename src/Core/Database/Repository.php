<?php

namespace App\Core\Database;

abstract class Repository
{
    protected string $tableName;

    abstract public function getQb(): QueryBuilder;
}