<?php

require __DIR__ . '/../autoload.php';

use Model\JsonFinder;
use Model\Status;
use Http\Request;
use Exception\HttpException;

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
    if (null === $status) {
        throw new HttpException(404, 'Status ' . $id . ' not exists');
    }

    return $app->render('status.php', array(
        'status'  => $status,
    ));
});

$app->post('/statuses', function (Request $request) use ($app, $file) {
    $memoryFinder = new JsonFinder($file);
    $username = $request->getParameter('username');
    $message = $request->getParameter('message');
    $memoryFinder->addStatus(new Status($message, Status::getNextId($file), $username, new DateTime()));

    $app->redirect('/statuses');
});

$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $file) {
    $memoryFinder = new JsonFinder($file);
    $status = $memoryFinder->findOneById($id);
    if (null === $status) {
        throw new HttpException(404, 'Status ' . $id . ' not exists');
    }
    $memoryFinder->deleteStatus($status);

    $app->redirect('/statuses');
});

return $app;
