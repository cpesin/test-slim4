<?php

declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Slim\Container;

/** @var Container $container */
$container = require_once __DIR__ . '/bootstrap.php';

$entityManager = $container->get(EntityManager::class);

$commands = [
    // If you want to add your own custom console commands,
    // you can do so here.
];

return ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);
