<?php

require __DIR__ . '/../autoload.php';

use Model\InMemoryFinder;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

/**
 * Index
 */
$app->get('/', function () use ($app) {
    return $app->render('index.php');
});

$app->get('/statuses', function () use ($app) {
    $memoryFinder = new InMemoryFinder();
    $statuses = $memoryFinder->findAll();

    return $app->render('statuses.php', array(
        'statuses'  => $statuses
    ));
});

return $app;
