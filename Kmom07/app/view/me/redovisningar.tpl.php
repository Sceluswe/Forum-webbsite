<h1><?=$title?></h1>

<?php $index = 0;
foreach($content as $item) : ?>
<div id='<?=$values[$index++]?>'>
	<?=$item?>
	<p><a href='<?=$this->url->create('Redovisning')?>'>Överst på sidan &raquo;</a></p>
</div>
<br/>
<hr>
<?php endforeach; ?>

<?php if(isset($byline)) : ?>
<footer class='byline'>
	<img width='64' height='64' src='<?=$this->url->asset("img/byline.png")?>' alt='Byline' />
	<?=$byline?>
</footer>
<?php endif; ?>