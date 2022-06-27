<?php

namespace App\Core;

use App\Controller\UserController;
use App\Core\Exception\AccessDeniedException;
use App\Core\Exception\MethodNotAllowedException;
use App\Core\Exception\NotFoundException;
use App\Core\Routing\Route;
use DI\Container;

class Router
{
    private array $routes;
    private array $request;
    private Container $container;

    /**
     * @param array $routes
     * @param Container $container
     */
    public function __construct(
        array     $routes,
        Container $container
    )
    {
        $this->request = $_SERVER;
        $this->routes = $routes;
        $this->container = $container;
    }

    public function match(): ?Route
    {
        /** @var Route $route */
        foreach ($this->routes as $route) {
            if (preg_match($route->getPattern(), $this->getUrl())) {
                return $route;
            }
        }

        return null;
    }

    public function run(Session $session): void
    {
        $matchedRoute = $this->match();

        if (!$this->validateRoute($matchedRoute)) {
            throw new NotFoundException('Page not found', 404);
        }

        if (!$matchedRoute->isPublic() && !$session->isAuth()) {
            $this->container->get(UserController::class)->login();
            exit();
        }

        if (!$this->validateMethod($matchedRoute)) {
            throw new MethodNotAllowedException(sprintf(
                'Method "%s" not allowed. (Allowed method(s): %s)',
                $this->getMethod(),
                implode(', ', $matchedRoute->getMethods())
            ), 405);
        }

        $controller = $this->container->get($matchedRoute->getController());
        $controller->{$matchedRoute->getAction()}();
    }

    private function getUrl(): string
    {
        $url = $this->request['REQUEST_URI'];
        $position = strpos($url, '?');
        return $position === false ? $url : substr($url, 0, $position);
    }

    private function getMethod(): string
    {
        return strtolower($this->request['REQUEST_METHOD']);
    }

    /**
     * @param Route|null $route
     * @return bool
     */
    private function validateRoute(?Route $route): bool
    {
        if ($route === null
            || !class_exists($route->getController())
            || !method_exists($route->getController(), $route->getAction())
        ) {
            return false;
        }
        return true;
    }

    private function validateMethod(Route $route): bool
    {
        if (empty($route->getMethods())) {
            return true;
        }

        return in_array($this->getMethod(), array_map(static function (string $method) {
            return strtolower($method);
        }, $route->getMethods()));
    }
}