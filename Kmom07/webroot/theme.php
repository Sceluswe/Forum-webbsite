<?php 
require __DIR__ . '/config_with_app.php';

$di = new \Anax\DI\CDIFactoryDefault();

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/dbform-navbar-me.php');

$app->router->add('Theme', function() use ($app) {
	
	$app->theme->setTitle("Min Me-sida");
	
	$content = $app->fileContent->get('me.md');
	$content = $app->textFilter->doFilter($content, 'shortcode, markdown');
	
	$byline = $app->fileContent->get('byline.md');
	$byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
	
	$app->views->add('me/page', ['content' =>$content, 'byline' => $byline]);
});


$app->router->add('Regioner', function() use ($app) {
 
   $app->theme->setTitle("Regioner");
 
    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
});

$app->router->add('FATest', function() use ($app)
{
	$app->theme->setTitle('Fa Test');
	
	$fa = "<i class='fa fa-home'></i> normal<br>
	<i class='fa fa-camera-retro fa-lg'></i> fa-lg
	<i class='fa fa-camera-retro fa-2x'></i> fa-2x
	<i class='fa fa-camera-retro fa-3x'></i> fa-3x
	<i class='fa fa-camera-retro fa-4x'></i> fa-4x
	<i class='fa fa-camera-retro fa-5x'></i> fa-5x";
	
	$app->views->add('me/page', ['content' =>$fa]);
});

$app->router->add('Rutnat', function() use ($app)
{
	$app->theme->setTitle('Rutnät');
	$app->theme->addStyleSheet('css/grid.css');
	
	$app->views->addString('flash', 'flash')
		   ->addString('featured-1', 'featured-1')
		   ->addString('featured-2', 'featured-2')
		   ->addString('featured-3', 'featured-3')
		   ->addString('main', 'main')
		   ->addString('sidebar', 'sidebar')
		   ->addString('triptych-1', 'triptych-1')
		   ->addString('triptych-2', 'triptych-2')
		   ->addString('triptych-3', 'triptych-3')
		   ->addString('footer-col-1', 'footer-col-1')
		   ->addString('footer-col-2', 'footer-col-2')
		   ->addString('footer-col-3', 'footer-col-3')
		   ->addString('footer-col-4', 'footer-col-4');
});

$app->router->add('Typografi', function() use ($app)
{
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

$app->router->handle();
$app->theme->render();