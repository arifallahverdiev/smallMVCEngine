<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Exception\MissingMandatoryParametersException;
use App\Core\Exception\NotFoundException;
use App\Core\Http\Response;
use App\Core\Routing\Annotation\Route;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\Hydrator;
use App\Validator\TaskFormValidator;
use ReflectionException;

class TaskController extends Controller
{
    #[Route(path: '/add', methods: ['get', 'post'])]
    public function add(): Response
    {
        if ($this->requestMethod() === 'post') {
            $task = new Task();
            $validator = new TaskFormValidator($_POST);
            $errors = $validator->validate();
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash($error, 'danger');
                }
                $this->redirect('/add');
            }

            try {
                (new Hydrator())->loadFromArray($_POST, $task);
            } catch (ReflectionException $e) {
                $this->addFlash('Произошла ошибка. Повторите попытку позже.', 'danger');
            }
            $repository = new TaskRepository();
            $repository->add($task);
            $this->addFlash('Задача успешно добавлена', 'success');
            $this->redirect('/');
        }

        return $this->render('task/add');
    }

    #[Route(path: '/update', methods: ['get', 'post'], isPublic: false)]
    public function update(): Response
    {
        if (!isset($_GET['id'])) {
            throw new MissingMandatoryParametersException('Required parameter "id" is missing');
        }

        $task = (new TaskRepository())
            ->getQb()
            ->find($_GET['id']);

        if (!$task instanceof Task) {
            throw new NotFoundException('Page not found');
        }

        if ($this->requestMethod() === 'post') {
            try {
                (new Hydrator())->loadFromArray($_POST, $task);
            } catch (ReflectionException $e) {
                $this->addFlash('Произошла ошибка. Повторите попытку позже.', 'danger');
            }
            $repository = new TaskRepository();
            $repository->update($task);
            $this->addFlash('Задача успешно сохранена', 'success');
            $this->redirect("/update?id=" . $task->getId());
        }

        return $this->render('task/update', [
            'task' => $task
        ]);
    }
}