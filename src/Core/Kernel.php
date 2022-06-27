<?php

namespace App\Core;

use App\Core\Exception\AccessDeniedException;
use App\Core\Exception\HttpException;
use App\Core\Http\Response;
use App\Core\Routing\Annotation\Route;
use DI\Container;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SplFileInfo;

class Kernel
{
    protected array $config;
    private Container $container;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run(Container $container): void
    {
        $this->container = $container;
        $this->initDbConfig();
        $this->initRoute();
    }

    private function getRoutes(): array
    {
        $classes = new RecursiveDirectoryIterator(dirname(__FILE__) . '/../Controller');
        $routes = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator($classes) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $className = $this->getClassNameFromFile($file);

            if (!$className) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($className);
            } catch (ReflectionException $e) {
                throw new RuntimeException($e->getMessage());
            }

            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);
                foreach ($attributes as $attribute) {
                    /** @var Route $annotation */
                    $annotation = $attribute->newInstance();
                    $routes[] = new Routing\Route(
                        $annotation->getPath(),
                        $className,
                        $method->getName(),
                        $annotation->getMethods(),
                        $annotation->isPublic()
                    );
                }
            }
        }

        return $routes;
    }

    private function getClassNameFromFile(SplFileInfo $file): bool|string
    {
        $className = sprintf(
            'App\\%s\\%s',
            str_replace(
                '/',
                '\\',
                str_replace(
                    __DIR__ . '/../',
                    '',
                    $file->getPath()
                )),
            str_replace(
                '.php',
                '',
                $file->getFilename()
            )
        );

        return class_exists($className) ? $className : false;
    }

    private function initRoute(): void
    {
        try {
            (new Router($this->getRoutes(), $this->container))
                ->run($this->container->get(Session::class));
        } catch (AccessDeniedException $exception) {
            header();
        }
    }

    private function initDbConfig(): void
    {
        if ($this->config['database']) {
            define('DB_HOST', $this->config['database']['host']);
            define('DB_NAME', $this->config['database']['dbname']);
            define('DB_USERNAME', $this->config['database']['username']);
            define('DB_PASSWORD', $this->config['database']['password']);
        }
    }
}