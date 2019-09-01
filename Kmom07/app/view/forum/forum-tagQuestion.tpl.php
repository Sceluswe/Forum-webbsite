<h1>Tag a question</h1>
<!---------------- All already created tags ---------------->
<?php foreach($tags as $tag):?>
	<a href="<?=$this->url->create("{$redirect['tagCreate']}{$tag->name}");?>" class="tag"><?=$tag->name?></a>
<?php endforeach;?>

<h2>Or create a new tag for your question</h2>
<!---------------- Button for creating a new tag. ---------------->
<h3 style="margin-top:0px;"><a href="<?=$this->url->create("{$redirect['tagCreate']}");?>" class="nodecoration">Create new tag</a></h3>
