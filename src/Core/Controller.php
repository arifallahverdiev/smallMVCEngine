<?php

namespace App\Core;

use App\Core\Http\Response;
use App\Entity\User;

abstract class Controller
{
    private View $view;
    private Session $session;

    public function __construct(
        View    $view,
        Session $session
    )
    {
        $this->view = $view;
        $this->session = $session;
    }

    public function render(string $templateName, array $variables = []): Response
    {
        $content = $this->renderView($templateName, $variables);

        return new Response($content);
    }

    public function renderView(string $templateName, array $variables = []): string
    {
        return $this->view->render($templateName, $variables);
    }

    public function changeLayout(string $layout): void
    {
        $this->view->setLayout($layout);
    }

    public function requestMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function redirect(string $route): void
    {
        header('Location: ' . $route);
        exit();
    }

    public function addFlash(string $message, string $type): void
    {
        $this->session->addFlash($message, $type);
    }

    public function getFlashes(): array
    {
        return $this->session->getFlashes();
    }

    public function auth(User $user)
    {
        $this->session->set('auth', $user);
    }
}