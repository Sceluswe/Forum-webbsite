<div class="<?=$class?>">
    <h2><?=$title?></h2>
    <?php foreach($tags as $tag):?>
    	<a href="<?=$this->url->create("{$redirect['menu']}{$tag->name}")?>" class="tag"><?=$tag->name?></a>
    <?php endforeach;?>
    <hr>
</div>
