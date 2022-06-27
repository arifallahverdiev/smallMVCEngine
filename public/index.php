<?php

use App\Core\Controller;
use App\Core\Kernel;
use App\Core\Session;
use App\Core\View;
use DI\ContainerBuilder;
use function DI\create;
use function DI\get;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$config = require(__DIR__ . '/../config/config.php');

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    Kernel::class => create(Kernel::class)
        ->constructor($config),
    Controller::class => create(Controller::class)
        ->constructor(get(View::class)),
    View::class => create(View::class)
        ->constructor($config['templatePath'], create(Session::class)),
]);

$container = $containerBuilder->build();
$container->get(Kernel::class)->run($container);