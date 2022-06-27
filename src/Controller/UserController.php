<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Routing\Annotation\Route;
use App\Core\Session;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Validator\UserLoginFormValidator;

class UserController extends Controller
{
    #[Route(path: '/login', methods: ['get'])]
    public function login()
    {
        return $this->render('user/login');
    }

    #[Route(path: '/login-handle', methods: ['post'])]
    public function loginHandle()
    {
        $validator = new UserLoginFormValidator($_POST);
        $errors = $validator->validate();

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->addFlash($error, 'danger');
            }
            $this->redirect('/login');
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findByName($_POST['username']);

        if ($user === null) {
            $this->addFlash(
                sprintf(
                    'Пользователь с именем "%s" не найден в базе данных',
                    $_POST['username']
                ),
                'danger'
            );
            $this->redirect('/login');
        } else {
            $userService = new UserService($_POST['username'], $_POST['password']);

            if (!$userService->auth($user->getUsername(), $user->getPassword())) {
                $this->addFlash(
                    sprintf(
                        'Неверный пароль для пользователя "%s"',
                        $_POST['username']
                    ),
                    'danger'
                );
                $this->redirect('/login');
            }
        }

        $this->auth($user);
        $this->redirect('/');
    }

    #[Route(path: '/logout', methods: ['get'], isPublic: false)]
    public function logout()
    {
        (new Session())
            ->sessionEnd();

        $this->redirect('/');
    }
}