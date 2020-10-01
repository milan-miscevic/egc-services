<?php

declare(strict_types=1);

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

return [
    'config' => require __DIR__ . '/config.php',
    AdapterInterface::class => function (ContainerInterface $container) {
        $config = $container->get('config');
        return new Adapter($config['database']);
    },
];
