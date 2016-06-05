<?php if($this->request->getPost('expand')):?>
<form method=post>
	<input class="postbutton" type='submit' name='minimize' value='Cancel'>
</form>
<div class='comment-form' id="post">
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
        <fieldset>
        <legend>Leave a comment</legend>
		<p><label>Name:<br/><input type='text' name='name' value='<?=$name?>'/></label></p>
        <p><label>Comment:<br/><textarea name='content'><?=$content?></textarea></label></p>
        <p><label>Homepage:<br/><input type='text' name='web' value='<?=$web?>'/></label></p>
        <p><label>Email:<br/><input type='text' name='mail' value='<?=$mail?>'/></label></p>
        <p class=buttons>
            <input type='submit' name='doCreate' value='Comment' onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
            <input type='reset' value='Reset'/>
            <input type='submit' name='doRemoveAll' value='Remove all' onClick="this.form.action = '<?=$this->url->create('comment/remove-all')?>'"/>
        </p>
        <output><?=$output?></output>
        </fieldset>
    </form>
</div>
<?php else:?>
<form method=post action="#post">
	<input class="postbutton" type='submit' name='expand' value='Post a comment'>
</form>
<p class="extend"></p>
<?php endif; ?>

