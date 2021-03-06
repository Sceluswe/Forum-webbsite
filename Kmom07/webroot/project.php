<?php
// Get environment & autoloader.
require __DIR__ . '/config.php';

// Create services and inject into the app.
$di = new \Anax\DI\CDIFactoryDefault();

$di->set('form', '\Mos\HTMLForm\CForm');

$di->set('ForumController', function () use ($di) {
    $controller = new Anax\Forum\ForumController();
    $controller->setDi($di);
    return $controller;
});

$di->set('users', '\Anax\Users');
$di->set('UsersController', function () use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('EscaperController', function () use ($di) {
    $controller = new Anax\Escaper\EscaperController();
    $controller->setDi($di);
    return $controller;
});

$app = new \Anax\MVC\CApplicationBasic($di);


// Select my theme for usage with the config file for Cdatabase and Cform.
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/theme-navbar-me.php');

// Home
$app->router->add('Home', function () use ($app) {
    $app->theme->setTitle('Home');
    $app->dispatcher->forwardTo('Forum', 'home');
});

$app->router->add('Questions', function () use ($app) {
    $app->theme->setTitle("Questions");
    $app->dispatcher->forwardTo('Forum', 'menu');
});

$app->router->add('Tag', function () use ($app) {
    $app->theme->setTitle("Questions");
    $app->dispatcher->forwardTo('Users', 'status');
    $app->dispatcher->forwardTo('Forum', 'tagMenu');
});

$app->router->add('Login', function () use ($app) {
    $app->theme->setTitle("Questions");
    $app->dispatcher->forwardTo('Users', 'login');
});

$app->router->add('Users', function () use ($app) {
    $app->theme->setTitle("Users menu");
    $app->dispatcher->forwardTo('Users', 'menu');

    $app->views->add('me/page', [
        'content' => "<h1>User menu</h1><p>Choose an action.</p>"
    ]);
});

$app->router->add('About', function () use ($app) {
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->utility->renderDefaultPage("", $content);
});

$app->router->add('Setup', function () use ($app) {
    $app->theme->setTitle("Setup");
    $app->dispatcher->forwardTo('Users', 'setup');
});

$app->router->add('Users/Add', function () use ($app) {
    $app->theme->setTitle("Users menu");
    $app->dispatcher->forwardTo('Users', 'add');
});

$app->router->add('Users/List-all', function () use ($app) {
    $app->theme->setTitle("All Users");
    $app->dispatcher->forwardTo('Users', 'list');
});

$app->router->add('Users/List-active', function () use ($app) {
    $app->theme->setTitle("All Users");
    $app->dispatcher->forwardTo('Users', 'active');
});

$app->router->add('Users/List-trash', function () use ($app) {
    $app->theme->setTitle("All Users");
    $app->dispatcher->forwardTo('Users', 'deleted');
});


// Handle routes and render theme.
$app->router->handle();
$app->theme->render();
