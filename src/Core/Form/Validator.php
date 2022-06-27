<?php

namespace App\Core\Form;

abstract class Validator
{
    private array $errors = [];

    abstract function validate(): array;

    public function addError($message): void
    {
        $this->errors[] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}