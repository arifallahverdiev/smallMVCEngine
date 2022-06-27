<?php

namespace App\Core;

use App\Core\Exception\TemplateNotFoundException;

class View
{
    protected string $templatesPath;
    protected string $layout = 'main';
    private Session $session;
    public $title = 'Список задач';

    public function __construct(string  $templatesPath, Session $session)
    {
        $this->templatesPath = $templatesPath;
        $this->session = $session;
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    public function render(string $templateName, array $variables): string
    {
        $templatePath = $this->getTemplatePath($templateName);
        $layout = $this->getLayoutPath();

        if (file_exists($templatePath)) {
            extract($variables);
            ob_start();
            require $templatePath;
            $content = ob_get_clean();
            ob_start();
            require $layout;
            return ob_get_contents();
        } else {
            throw new TemplateNotFoundException(sprintf('Template "%s" not found', $templateName));
        }
    }

    private function getTemplatePath(string $templateName): string
    {
        return $this->templatesPath . $templateName . '.php';
    }

    private function getLayoutPath(): string
    {
        return $this->templatesPath . 'layouts' . DIRECTORY_SEPARATOR . $this->layout . '.php';
    }

    public function getFlashes(): array
    {
        return $this->session->getFlashes();
    }

    public function isAuth(): bool
    {
        return $this->session->isAuth();
    }
}