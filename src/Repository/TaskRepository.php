<?php

namespace App\Repository;

use App\Core\Database\QueryBuilder;
use App\Core\Database\Repository;
use App\Entity\Task;

class TaskRepository extends Repository
{
    protected string $tableName = 'task';

    public function getQb(): QueryBuilder
    {
        return new QueryBuilder($this->tableName, Task::class);
    }

    public function add(Task $task): int
    {
        return $this
            ->getQb()
            ->insert([
                'name' => $task->getName(),
                'email' => $task->getEmail(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus()
            ]);
    }

    public function update(Task $task): int
    {
        return $this
            ->getQb()
            ->update($task->getId(), [
                'name' => $task->getName(),
                'email' => $task->getEmail(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'updated_at' => $task->getUpdatedAt()
            ]);
    }
}