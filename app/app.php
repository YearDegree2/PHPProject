<?php

require __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

use Model\Connection;
use Model\StatusFinder;
use Model\Status;
use Model\StatusDataMapper;
use Model\User;
use Model\UserFinder;
use Model\UserDataMapper;
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
$userFinder = new UserFinder($connection);
$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new GetSetMethodNormalizer());
$serializer = new Serializer($normalizers, $encoders);
$statusDataMapper = new StatusDataMapper($connection);
$userDataMapper = new UserDataMapper($connection);

/**
 * Index
 */
$app->get('/', function () use ($app) {
    $app->redirect('/statuses');
});

/**
 * Get all statuses
 */
$app->get('/statuses', function (Request $request) use ($app, $memoryFinder, $serializer) {
    session_start();
    $_SESSION['page'] = 'index';
    $statuses = $memoryFinder->findAll(intval($request->getParameter("limit"), 10), $request->getParameter("orderBy"), $request->getParameter("direction"));
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

/**
 * Get status by id
 */
$app->get('/statuses/(\d+)', function (Request $request, $id) use ($app, $memoryFinder, $serializer) {
    session_start();
    $_SESSION['page'] = 'status';
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

/**
 * Get all statuses by user
 */
$app->get('/statuses/([a-zA-Z0-9]*)', function (Request $request, $username) use ($app, $memoryFinder, $serializer) {
    session_start();
    $_SESSION['page'] = 'indexByPeople';
    if ($username !== $_SESSION['username']) {
        $app->redirect('/');
    }
    $statuses = $memoryFinder->findAll(null, null, null, $username);
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

/**
 * Sign in
 */
$app->get('/signin', function () use ($app) {
    session_start();
    if (isset($_SESSION['username'])) {
        return $app->redirect('/');
    }

    return $app->render('signin.php');
});

/**
 * Log in
 */
$app->get('/login', function () use ($app) {
    session_start();
    if (isset($_SESSION['username'])) {
        return $app->redirect('/');
    }

    return $app->render('login.php');
});

/**
 * Log out
 */
$app->get('/logout', function () use ($app) {
    session_start();
    session_destroy();

    return $app->redirect('/');
});

/**
 * Add status
 */
$app->post('/statuses', function (Request $request) use ($app, $statusDataMapper) {
    session_start();
    $username = null;
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    }
    if (!isset($_SESSION['username'])) {
        $username = 'Anonymous';
    }
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

/**
 * Sign in form filled
 */
$app->post('/signin', function (Request $request) use ($app, $userDataMapper) {
    $login = $request->getParameter("newLogin");
    $password = $request->getParameter("newPassword");
    $user = new User(null, $login, $password);
    if ($user->isValid()) {
        $return = $userDataMapper->persist($user);
        if (-1 === $return) {
            throw new HttpException(400, 'Username or password fields too large (30 characters maximum).');
        }

        return $app->redirect('/');
    }
    throw new HttpException(400, "Username already exists or empty fields.");
});

/**
 * Log in form filled
 */
$app->post('/login',  function (Request $request) use ($app, $userFinder) {
    $username = $request->getParameter("login");
    $password = $request->getParameter("password");
    if ($userFinder->verifyPassword($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        session_regenerate_id();

        return $app->redirect('/');
    }

    return $app->render('login.php', [ 'username'   => $username]);
});

/**
 * Delete status
 */
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
