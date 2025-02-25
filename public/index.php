<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$definitions = require __DIR__ . '/../config/container.php';
$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
$container = $builder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/', EgcServices\Home\Action\Home::class);
$app->post('/armies/add', EgcServices\Api\Action\ArmyAdd::class);
$app->get('/games', EgcServices\Api\Action\GameList::class);
$app->post('/games/add', EgcServices\Api\Action\GameAdd::class);
$app->post('/simulator', EgcServices\Api\Action\Simulator::class);

$app->run();
