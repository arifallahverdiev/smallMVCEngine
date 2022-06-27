<?php

namespace App\Core\Routing;

class Route
{
    private string $path;
    private string $controller;
    private string $action;
    private array $methods;
    private string $pattern;
    private bool $isPublic;

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @param array $methods
     * @param bool $isPublic
     */
    public function __construct(
        string $path,
        string $controller,
        string $action,
        array  $methods,
        bool   $isPublic
    )
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->methods = $methods;
        $this->pattern = "#^$path$#";
        $this->isPublic = $isPublic;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }
}