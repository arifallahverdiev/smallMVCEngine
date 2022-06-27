<?php

namespace App\Validator;

use App\Core\Form\Validator;

class TaskFormValidator extends Validator
{
    private array $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function validate(): array
    {
        $request = $this->request;

        if (!$request["name"]) {
            $this->addError('Имя пользователя не может быть пустым');
        }

        if (!$request["email"]) {
            $this->addError('Email не может быть пустым');
        } elseif (!filter_var($request["email"], FILTER_VALIDATE_EMAIL)) {
            $this->addError('E-mail адрес указан верно');
        }

        if (!$request["description"]) {
            $this->addError('Текст задачи не может быть пустым');
        }

        return $this->getErrors();
    }
}