<h2>Comments:</h2>

<?php if (is_array($comments)) : ?>
	<?php foreach ($comments as $comment) : ?>
		<?php if ($stamp == $comment['timestamp']): ?>
			<div class='comment comment-form' id="<?=$comment['id']?>">
				<p class="commentHeader">
					<h3 class="inline">#<?=$comment['id']?></h3>
					<h5 class="italic inline"> - <?=$comment['timeelapsed']?></h5>
				</p>
				
				<h2 class="inline"><?=$comment['name']?></h2>
				
				<form method=post>
					<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
					<input type=hidden name="timestamp" value="<?=$comment['timestamp']?>"/>
					
					<p><label>Name:<br/><input type='text' name='name' value="<?=$comment['name']?>"/></label></p>
					<p><label>Comment:<br/><textarea name='content'><?=$comment['content']?></textarea></label></p>
					<p><label>Email:<br/><input type='text' name='mail' value="<?=$comment['mail']?>"/></label></p>
					<p><label>Homepage:<br/><input type='text' name='web' value="<?=$comment['web']?>"/></label></p>
					
					<p class=buttons>
						<input type='submit' name='doEdit' value='Edit' onClick="this.form.action = '<?=$this->url->create('comment/edit')?>'"/>
						<input type='reset' value='Reset'/>
						<input type='submit' value='Cancel' onClick="this.form.action = '<?=$this->url->create($redirect)?>'"/>
					</p>
				</form>
			</div>
		<?php else :?>
			<div class='comment'>
				<p class="commentHeader">
					<form method=post action="#<?=$comment['id']?>">
					<input type=hidden name="redirect" value="<?=$redirect?>">
					<input type=hidden name="editstamp" value="<?=$comment['timestamp']?>"/>
					<input class="editbutton" type='submit' value="#<?=$comment['id']?>"/>
					<h5 class="italic inline"> - <?=$comment['timeelapsed']?></h5>
					</form>
				</p>
				
				<img class="commentimg" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($comment['mail'])));?>.jpg?s=80"/>
				
				<h2 class="inline"><?=$comment['name']?></h2>
				
				<p class="commentContent"><?=$comment['content']?></p>
				
				<hr class="clear"/>
				
				<p class="commentinfo">
					Email: <?=$comment['mail']?>
					<br/>
					Website: <?=$comment['web']?>
				<form method=post class="deletebutton">
					<input type=hidden name="timestamp" value="<?=$comment['timestamp']?>"/>
					<input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>"/>
					<input type="submit" name="doDelete" value="Delete" onClick="this.form.action = '<?=$this->url->create('comment/delete')?>'"/>
				</form>
				</p>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<hr>
