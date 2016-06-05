<?php 
require __DIR__ . '/config_with_app.php';

$di = new \Anax\DI\CDIFactoryDefault();

// Create a new controller for the comments. 
$di->set('CommentController', function() use ($di)
{
	$controller = new Phpmvc\Comment\CommentController();
	$controller->setDI($di);
	return $controller;
});

$app = new \Anax\Kernel\CAnax($di);

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

$app->router->add('', function() use ($app) {
	$app->theme->setTitle("Me");
	$app->theme->addStyleSheet('css/comments.css');
	
	$content = $app->fileContent->get('me.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/page', 
	[
		'content' =>$content, 'byline' => $byline
	]);
	
	$app->dispatcher->forward(
	[
		'controller' 	=> 'comment',
		'action'	 	=> 'view',
		'params'		=> 
		[
			'fields' => 'me',
			'redirect' => '',
		],
	]);
	
	// Variables/params for the form.
	$app->views->add('comment/form', 
	[
		'mail'		=> null,
		'web'		=> null,
		'name'		=> null,
		'content'	=> null,
		'output'	=> null,
		'redirect'	=> '',
	]);
});

$app->router->add('redovisning', function() use ($app)
{
	$app->theme->setTitle("Redovisning");
	$content = $app->fileContent->get('redovisning.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/page', 
	[
		'content' => $content, 'byline' => $byline
	]);
});

$app->router->add('dice-menu', function() use ($app)
{
	 $app->theme->setTitle("Dice");
	 
	 $app->views->add('me/dice-menu', array(null));
});

$app->router->add('dice', function() use ($app)
{
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

$app->router->add('dice100', function() use ($app)
{
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

$app->router->add('comments', function() use ($app)
{
	$app->theme->addStyleSheet('css/comments.css');
	$app->theme->setTitle("Comments");
	
	// Display comments.
	$app->dispatcher->forward(
	[
		'controller' 	=> 'comment',
		'action'	 	=> 'view',
		'params'		=> 
		[
			'field' 	=> 'comments',
			'redirect' 	=> 'comments',
		],
	]);

	// Variables/params for the form.
	$app->views->add('comment/form', 
	[
		'mail'		=> null,
		'web'		=> null,
		'name'		=> null,
		'content'	=> null,
		'output'	=> null,
		'redirect'  => 'comments',
	]);
});

$app->router->add('source', function() use ($app)
{
	$app->theme->addStylesheet('css/source.css');
	$app->theme->setTitle("Källkod");
	
	$source = new \Mos\Source\CSource(
	[
		'secure_dir' => '..',
		'base_dir' => '..',
		'add_ignore' => ['.htaccess'],
	]);
	
	$app->views->add('me/source', 
	[
		'content' => $source->View(),
	]);
});

$app->router->handle();
$app->theme->render();