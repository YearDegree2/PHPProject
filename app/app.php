<?php

require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

use Model\Connection;
use Model\StatusFinder;
use Model\Status;
use Model\StatusDataMapper;
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

$connection = new Connection("mysql", "uframework", "localhost", "uframework", "passw0rd");
$memoryFinder = new StatusFinder($connection);
$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new GetSetMethodNormalizer());
$serializer = new Serializer($normalizers, $encoders);
$statusDataMapper = new StatusDataMapper($connection);

/**
 * Index
 */
$app->get('/', function () use ($app) {
    $app->redirect('/statuses');
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

$app->post('/statuses', function (Request $request) use ($app, $statusDataMapper) {
    $username = null != $request->getParameter('username') ? $request->getParameter('username') : 'Anonymous';
    $message = $request->getParameter('message');
    $status = new Status($message, null, $username, new DateTime());
    $value = $statusDataMapper->persist($status);
    if (-1 === $value) {
        throw new HttpException(400, 'Status content too large');
    }
    if (-2 === $value) {
        throw new HttpException(400, 'Status content empty');
    }
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

$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $memoryFinder, $statusDataMapper) {
    $status = $memoryFinder->findOneById($id);
    if (null === $status) {
        throw new HttpException(404, 'Status ' . $id . ' not exists');
    }
    $statusDataMapper->remove($status);
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
