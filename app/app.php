<?php

require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

use Model\JsonFinder;
use Model\Status;
use Http\Request;
use Http\Response;
use Exception\HttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

$file = __DIR__ .  DIRECTORY_SEPARATOR . '../data/statuses.json';
$memoryFinder = new JsonFinder($file);
$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new GetSetMethodNormalizer());
$serializer = new Serializer($normalizers, $encoders);

/**
 * Index
 */
$app->get('/', function () use ($app) {
    return $app->render('index.php');
});

$app->get('/statuses', function (Request $request) use ($app, $memoryFinder, $serializer) {
    $statuses = $memoryFinder->findAll();
    $format = $request->guessBestFormat();
    if ('json' !== $format && 'xml' !== $format) {
        return $app->render('statuses.php', array(
            'statuses'  => $statuses,
        ));
    }
    $response = null;
    if ('json' === $format) {
        $response = new Response($serializer->serialize($statuses, $format), 200, array(
            'Content-Type' => 'application/json',
        ));
    }
    if ('xml' === $format) {
        $response = new Response($serializer->serialize($statuses, $format), 200, array(
            'Content-Type' => 'application/xml',
        ));
    }

    $response->send();
});

$app->get('/statuses/(\d+)', function (Request $request, $id) use ($app, $memoryFinder, $serializer) {
    $status = $memoryFinder->findOneById($id);
    if (null === $status) {
        throw new HttpException(404, 'Status ' . $id . ' not exists');
    }
    $format = $request->guessBestFormat();
    if ('json' !== $format && 'xml' !== $format) {
        return $app->render('status.php', array(
            'status'  => $status,
        ));
    }
    $response = null;
    if ('json' === $format) {
        $response = new Response($serializer->serialize($status, $format), 200, array(
            'Content-Type' => 'application/json',
        ));
    }
    if ('xml' === $format) {
        $response = new Response($serializer->serialize($status, $format), 200, array(
            'Content-Type' => 'application/xml',
        ));
    }

    $response->send();
});

$app->post('/statuses', function (Request $request) use ($app, $file, $memoryFinder) {
    $username = $request->getParameter('username');
    $message = $request->getParameter('message');
    $status = new Status($message, Status::getNextId($file), $username, new DateTime());
    $memoryFinder->addStatus($status);
    $format = $request->guessBestFormat();
    if ('json' !== $format) {
        $app->redirect('/statuses');
    }
    $response = null;
    if ('json' === $format) {
        $response = new Response(json_encode($status), 201, array(
            'Content-Type' => 'application/json',
        ));
    }

    $response->send();
});

$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $memoryFinder) {
    $status = $memoryFinder->findOneById($id);
    if (null === $status) {
        throw new HttpException(404, 'Status ' . $id . ' not exists');
    }
    $memoryFinder->deleteStatus($status);
    $format = $request->guessBestFormat();
    if ('json' !== $format) {
        $app->redirect('/statuses');
    }
    $response = null;
    if ('json' === $format) {
        $response = new Response('{"status": "Status suppressed"}', 204, array(
            'Content-Type' => 'application/json',
        ));
    }

    $response->send();
});

return $app;
