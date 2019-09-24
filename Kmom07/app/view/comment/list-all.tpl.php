<h1><?=$title?></h1>

<div class='commentMenu'>
	<a class="commentbutton" href="<?=$this->url->create($redirect["deleteAll"])?>"><i class="fa fa-pencil"></i> Delete all comments</a>
	<a class="commentbutton" href="<?=$this->url->create($redirect["setup"])?>"><i class="fa fa-commenting"></i> Reset database</a>
</div>

<?php foreach ($comments as $comment) : ?>
    <div class='comment'>
    	<div class="commentHeader">
    		<h2 style="display: inline;"><?=$comment->id?>.</h2>
    		<p style="display: inline;"><?=$comment->name?></p>
    	</div>

    	<p class="commentContent"><?=$comment->content?></p>
    		<h5 style="margin-left: 10px; display: inline;">E-Mail:</h5> <?=$comment->email?><br>
    		<h5 style="margin-left: 10px; display: inline;">Webpage:</h5> <?=$comment->web?>
    	</p>

    	<hr>

    	<ul class="commentlist">
    		<li><a class="commentbutton" href="<?=$this->url->create($redirect["update"] . $comment->id)?>"><i class="fa fa-pencil"></i> Update</a></li>
    		<li><a class="commentbutton" href="<?=$this->url->create($redirect["delete"] . $comment->id)?>"><i class="fa fa-trash"></i> Delete</a></li>
    		<li><pre class="commentTimestamp">created <?=$comment->timestamp?></pre></li>
    	</ul>
    </div>
<?php endforeach; ?>

<a class="commentbutton" href="<?=$this->url->create($redirect["add"])?>"><i class="fa fa-commenting"></i> Create comment</a>
