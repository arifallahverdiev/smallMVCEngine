<?php

namespace App\Validator;

use App\Core\Form\Validator;

class UserLoginFormValidator extends Validator
{
    private array $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    function validate(): array
    {
        $request = $this->request;

        if (!$request["username"]) {
            $this->addError('Имя пользователя не может быть пустым');
        }

        if (!$request["password"]) {
            $this->addError('Пароль не может быть пустым');
        }

        return $this->getErrors();
    }
}