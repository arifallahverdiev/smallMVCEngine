<?php

namespace App\Core\Database;

use App\Entity\User;
use PDO;

class QueryBuilder
{
    protected string $pk = 'id';
    protected \PDOStatement $stmt;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected ?string $sortField = null;
    protected ?string $sortDirection = null;

    private PDO $db;
    private string $tableName;
    private string $className;

    /**
     * @param string $tableName
     * @param string $className
     */
    public function __construct(string $tableName, string $className)
    {
        $this->tableName = $tableName;
        $this->className = $className;
        $this->db = Db::getInstance();
    }

    public function select(string $fieldset = '*'): static
    {
        $sql = sprintf("select %s from `%s`", $fieldset, $this->tableName);
        $this->stmt = $this->db->prepare($sql);

        return $this;
    }


    public function limit(int $size): static
    {
        $this->limit = $size;

        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function sort(?string $sortField, ?string $sortDirection = 'asc'): static
    {
        $this->sortField = $sortField !== null ? addslashes($sortField) : null;
        $this->sortDirection = $sortDirection !== null ? addslashes($sortDirection) : null;

        return $this;
    }

    public function count(): int
    {
        $result = $this->db->query(sprintf('select count(*) from (%s) as src', $this->stmt->queryString));
        return $result->fetchColumn();
    }

    public function getResult(): array
    {
        $this->buildQuery();
        $this->stmt->execute();

        return $this->stmt->fetchAll(PDO::FETCH_CLASS, $this->className);
    }

    private function buildQuery(): void
    {
        if ($this->sortField !== null) {
            $sortQuery = sprintf('order by %s', $this->sortField);

            if ($this->sortDirection !== null) {
                $sortQuery = sprintf('%s %s', $sortQuery, $this->sortDirection);
            }

            $this->stmt = $this->db->prepare(sprintf('%s %s', $this->stmt->queryString, $sortQuery));
        }

        if ($this->limit !== null) {
            $this->stmt = $this->db->prepare(sprintf('%s limit %s', $this->stmt->queryString, $this->limit));
        }

        if ($this->offset !== null) {
            $this->stmt = $this->db->prepare(sprintf('%s offset %s', $this->stmt->queryString, $this->offset));
        }
    }

    public function insert(array $data): int
    {
        $sql = sprintf("insert into `%s` %s", $this->tableName, $this->generateInsertString($data));
        $this->stmt = $this->db->prepare($sql);
        $this->formatParam($data);
        $this->stmt->execute();

        return $this->stmt->rowCount();
    }

    public function formatParam(array $params = []): void
    {
        foreach ($params as $param => &$value) {
            $param = is_int($param) ? $param + 1 : ':' . ltrim($param, ':');
            $this->stmt->bindParam($param, $value);
        }
    }

    private function generateInsertString(array $data): string
    {
        $fields = [];
        $names = [];

        foreach ($data as $field => $value) {
            $fields[] = sprintf("`%s`", $field);
            $names[] = sprintf(":%s", $field);
        }

        return sprintf(
            "(%s) values (%s)",
            implode(', ', $fields),
            implode(', ', $names)
        );
    }

    public function find(int $id)
    {
        $sql = sprintf("select * from `%s` where `%s` = :id", $this->tableName, $this->pk);
        $this->stmt = $this->db->prepare($sql);
        $this->stmt->execute([':id' => $id]);
        return $this->stmt->fetchObject($this->className);
    }

    public function update(int $id, array $data): int
    {
        $sql = sprintf(
            "update `%s` set %s where `%s` = %d",
            $this->tableName,
            $this->generateUpdateString($data),
            $this->pk,
            $id
        );
        $this->stmt = $this->db->prepare($sql);
        $this->formatParam($data);

        $this->stmt->execute();

        return $this->stmt->rowCount();
    }

    private function generateUpdateString(array $data): string
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = :%s", $key, $key);
        }

        return implode(',', $fields);
    }

    private function generateWhereString(array $data): string
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = :%s", $key, $key);
        }

        return implode(',', $fields);
    }

    public function where(string $field, string $value)
    {
        $this->stmt = $this
            ->db
            ->prepare(sprintf(
                '%s where `%s` = :%s',
                $this->stmt->queryString,
                $field,
                $field
            ));
        $this->stmt->execute([sprintf(':%s', $field) => $value]);
        return $this->stmt->fetchObject($this->className);
    }
}