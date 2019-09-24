<?=$content?>

<?php if(isset($byline)) : ?>
    <footer class='byline'>
    	<img width='64' height='64' src='<?=$this->url->asset("img/byline.png")?>' alt='Byline' />
    	<?=$byline?>
    </footer>
<?php endif; ?>

<hr>
