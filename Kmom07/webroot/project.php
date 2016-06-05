<?php
// Get environment & autoloader.
require __DIR__ . '/config.php';

// Create services and inject into the app.
$di = new \Anax\DI\CDIFactoryDefault();

$di->set('form', '\Mos\HTMLForm\CForm');

$di->set('CommentController', function() use ($di) {
	$controller = new Anax\Comments\CommentController();
	$controller->setDI($di);
	return $controller;
});

$di->set('EscaperController', function() use ($di) {
	$controller = new Anax\Escaper\EscaperController();
	$controller->setDi($di);
	return $controller;
});

$di->set('ForumController', function() use ($di) {
	$controller = new Anax\Forum\ForumController();
	$controller->setDi($di);
	return $controller;
});

$di->set('users', '\Anax\Users');

$di->set('UsersController', function() use ($di)
{
	$controller = new \Anax\Users\UsersController();
	$controller->setDI($di);
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
	
	$content = $app->fileContent->get('me.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/me', 
	[
		'content' =>$content, 'byline' => $byline
	]);
	
	$app->dispatcher->forward([
		'controller' => 'Comment',
		'action'	 => 'view'
	]);
});

$app->router->add('Questions', function() use ($app){
	$app->theme->setTitle("Questions");

	$app->dispatcher->forward([
		'controller' => 'Forum',
		'action' 	 => 'menu'
	]);
});

$app->router->add('Login', function() use ($app){
	$app->theme->setTitle("Questions");

	$app->dispatcher->forward([
		'controller' => 'Users',
		'action' 	 => 'login'
	]);
});

$app->router->add('Users', function() use ($app){
	$app->theme->setTitle("Users menu");

	$app->dispatcher->forward([
		'controller' => 'Users',
		'action' 	 => 'menu'
	]);
		
	$content = "<h1>User menu</h1><p>Choose an action.</p>";

	$app->views->add('me/page',
	[
		'content' =>$content
	]);
});

$app->router->add('Setup', function() use ($app) {

	$app->theme->setTitle("Setup");
	
	$app->dispatcher->forward([
		'controller' 	=> 'Users',
		'action'		=> 'setup'
	]);
});

$app->router->add('Users/Add', function() use ($app){
	$app->theme->setTitle("Users menu");
	
	$app->dispatcher->forward([
		'controller' => 'Users',
		'action'	 => 'add'
	]);
});

$app->router->add('Users/List-all', function() use ($app) {
	
	$app->theme->setTitle("All Users");
	
	$app->dispatcher->forward([
		'controller' 	=> 'Users',
		'action'		=> 'list'
	]);
});

$app->router->add('Users/List-active', function() use ($app) {
	
	$app->theme->setTitle("All Users");
	
	$app->dispatcher->forward([
		'controller' 	=> 'Users',
		'action'		=> 'active'
	]);
});

$app->router->add('Users/List-trash', function() use ($app) {
	
	$app->theme->setTitle("All Users");
	
	$app->dispatcher->forward([
		'controller' 	=> 'Users',
		'action'		=> 'deleted'
	]);
});


// Handle routes and render theme.
$app->router->handle();
$app->theme->render();


