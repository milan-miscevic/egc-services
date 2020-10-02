<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;

require __DIR__ . '/../vendor/autoload.php';

$definitions = require __DIR__ . '/../config/container.php';
$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
$container = $builder->build();

/** @var Adapter $adapter */
$adapter = $container->get(AdapterInterface::class);

$armyTable = "
    CREATE TABLE IF NOT EXISTS `army` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `units` tinyint(3) unsigned NOT NULL DEFAULT 0,
        `strategy` enum('random','weakest','strongest') NOT NULL,
        `position` tinyint(4) NOT NULL DEFAULT 0,
        `game_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`id`),
        KEY `FK_army_game` (`game_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$gameTable = "
    CREATE TABLE IF NOT EXISTS `game` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `status` enum('active','finished') NOT NULL DEFAULT 'active',
        `next` int(10) unsigned DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `FK_game_army` (`next`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$adapter->createStatement($armyTable)->execute();
$adapter->createStatement($gameTable)->execute();

$armyConstraint = "ALTER TABLE `army` ADD CONSTRAINT `FK_army_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`);";
$gameConstraint = "ALTER TABLE `game` ADD CONSTRAINT `FK_game_army` FOREIGN KEY (`next`) REFERENCES `army` (`id`);";

$adapter->createStatement($armyConstraint)->execute();
$adapter->createStatement($gameConstraint)->execute();
