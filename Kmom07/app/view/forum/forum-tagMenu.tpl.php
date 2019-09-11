<h2 style="margin-top: 24px;"><?=$title?></h2>
<?php foreach($tags as $tag):?>
	<a href="<?=$this->url->create("{$redirect['menu']}{$tag->id}")?>" class="tag"><?=$tag->name?></a>
<?php endforeach;?>
<hr>
