<?php

declare(strict_types=1);

session_start();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__.'/../vendor/autoload.php';

/** @var DI\Container $container */
$container = require_once __DIR__.'/../config/bootstrap.php';

/** @var Slim\App $app */
$app = AppFactory::createFromContainer($container);

// Create Twig
$twig = Twig::create(__DIR__.'/../templates/', ['cache' => false]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::createFromContainer($app));

/*
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.

 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$app->addErrorMiddleware(true, true, true);

// Index page
$app->get('/', function (Request $request, Response $response) {
    $page = $this->get('IndexController');
    $page->init('index', $request, $response);
    $page->getRender();

    return $page->response;
})->setName('index');

// Articles page
$app->get('/articles', function (Request $request, Response $response) {
    $page = $this->get('ArticlesController');
    $page->init('articles', $request, $response);
    $page->getRender();

    return $page->response;
})->setName('articles');

// Authors page
$app->get('/auteurs', function (Request $request, Response $response) {
    $page = $this->get('AuthorsController');
    $page->init('authors', $request, $response);
    $page->getRender();

    return $page->response;
})->setName('authors');

// Contact page
$app->get('/contact', function (Request $request, Response $response) {
    $page = $this->get('ContactController');
    $page->init('contact', $request, $response);
    $page->getRender();

    return $page->response;
})->setName('contact');

// Send contact form
$app->post('/contact', function (Request $request, Response $response) {
    $page = $this->get('ContactController');
    $page->init('contact', $request, $response);

    if (true === $page->sendForm($request)) {
        return $page->response->withStatus(301)->withHeader('Location', '/contact');
    }

    $page->getRender();

    return $page->response;
});

// Run
$app->run();
