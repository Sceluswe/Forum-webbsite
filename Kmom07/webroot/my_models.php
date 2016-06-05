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

$app->router->add('Redovisning', function() use ($app) {
	$app->theme->setTitle("Redovisning");
	$values = ['Kmom01', 'Kmom02', 'Kmom03', 'Kmom04', 'Kmom05', 'Kmom06'];
	$app->views->add('users/menu', [
		'values' => $values,
		'url'	 => 'Redovisning#'
	]);
	
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom01.md'), 'shortcode, markdown');
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom02.md'), 'shortcode, markdown');
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom03.md'), 'shortcode, markdown');
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom04.md'), 'shortcode, markdown');
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom05.md'), 'shortcode, markdown');
	$content[] = $app->textFilter->doFilter($app->fileContent->get('kmom06.md'), 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/redovisningar',
	[
		'values' => $values,
		'title' => 'Redovisning',
		'content' => $content, 
		'byline' => $byline
	]);
});

$app->router->add('Typography', function() use ($app) {
	$app->theme->setTitle('Typografi');
	$app->theme->addStyleSheet('css/Hgrid.css');
	
	$content = $app->fileContent->get('grid-test.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/page', ['content' => $content], 'flash');
	
	$app->views->add('me/page', ['content' =>$content], 'featured-1');
	$app->views->add('me/page', ['content' =>$content], 'featured-2');
	$app->views->add('me/page', ['content' =>$content], 'featured-3');

	$app->views->add('me/page', ['content' =>$content, 'byline' => $byline], 'main');
	
	$app->views->add('me/page', ['content' =>$content], 'triptych-1');
	$app->views->add('me/page', ['content' =>$content], 'triptych-2');
	$app->views->add('me/page', ['content' =>$content], 'triptych-3');
	
	$app->views->add('me/page', ['content' =>$content], 'footer-col-1');
	$app->views->add('me/page', ['content' =>$content], 'footer-col-2');
	$app->views->add('me/page', ['content' =>$content], 'footer-col-3');
	$app->views->add('me/page', ['content' =>$content], 'footer-col-4');
});

$app->router->add('Comments', function() use ($app) {
	$app->theme->setTitle("Comments");
	
	$app->dispatcher->forward([
		'controller' => 'Comment',
		'action'	 => 'view'
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

$app->router->add('Escaper', function() use ($app){
	$app->theme->setTitle("CEscaper");

	$app->dispatcher->forward([
		'controller' => 'Escaper',
		'action' 	 => 'menu'
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

$app->router->add('dice-menu', function() use ($app) {
	 $app->theme->setTitle("Dice");
	 
	 $app->views->add('me/dice-menu', array(null));
});

$app->router->add('dice', function() use ($app) {
	$app->theme->addStyleSheet('css/dice.css');
	$app->theme->setTitle("Dice Roll");
	
	$roll = isset($_GET['roll']) ? strip_tags($_GET['roll']) : null;

	$diceobj = new Mos\Dice\CDice();
	$diceobj->roll($roll);
	$results = $diceobj->getResults();
	$total = $diceobj->getTotal();
	
	$app->views->add('me/dice', 	 
	[
		 'roll' => $roll, 
		 'results' => $results, 
		 'total' => $total,
	 ]);
});

$app->router->add('dice100', function() use ($app) {
	$app->theme->addStyleSheet("css/dice.css");
	$app->theme->setTitle("Dice 100");
	
	$DiceGame = null;
	$DiceContent = null;
	
	if($app->session->has('dicegame'))
	{
		$DiceGame = $app->session->get('dicegame');
	}
	else
	{
		// Dependency Injection.
		$PlayerDice = new Mos\PlayerDice\CPlayerDice();
		$Turn = new Mos\Turn\CTurn($PlayerDice);
		$DiceGame = new Mos\DiceGame\CDiceGame($Turn);
		
		$app->session->set('dicegame', $DiceGame);
	}
	
	if(isset($_GET['roll']))
	{
		$DiceContent = $DiceGame->RollDice();
	}
	else if(isset($_GET['turn']))
	{
		$DiceContent = $DiceGame->EndTurn();
	}
	else if(isset($_GET['restart']))
	{
		$DiceContent = $DiceGame->RestartGame();
	}
	else
	{
		$DiceContent = $DiceGame->StartGame();
	}

	$app->views->add('me/dice100', 
	[
		'DiceContent' => $DiceContent,
	]);
});
// Handle routes and render theme.
$app->router->handle();
$app->theme->render();


