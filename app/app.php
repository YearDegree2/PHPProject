<?php

require __DIR__ . '/../autoload.php';

use Model\JsonFinder;
use Http\Request;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

$file = __DIR__ .  DIRECTORY_SEPARATOR . '../data/statuses.json';

/**
 * Index
 */
$app->get('/', function () use ($app) {
    return $app->render('index.php');
});

$app->get('/statuses', function (Request $request) use ($app, $file) {
    $memoryFinder = new JsonFinder($file);
    $statuses = $memoryFinder->findAll();

    return $app->render('statuses.php', array(
        'statuses'  => $statuses,
    ));
});

$app->get('/statuses/(\d+)', function (Request $request, $id) use ($app, $file) {
    $memoryFinder = new JsonFinder($file);
    $status = $memoryFinder->findOneById($id);

    return $app->render('status.php', array(
        'status'  => $status,
    ));
});

return $app;
