<h1><?=$title?></h1>
<div class='user'>
	<p><img src="https://www.gravatar.com/avatar/<?=md5(strtolower(trim($user->email)))?>" alt="No image"></p>
	<h3 style="display: inline; text-transform: uppercase;"> <?=$user->id?>. <?=$user->acronym?></h3>
	<p><h5 style="display: inline;">Name:</h5> <?=$user->name?></p>
	<p><h5 style="display: inline;">E-Mail:</h5> <?=$user->email?></p>
	<p><h5 style="display: inline;">Created:</h5> <?=$user->created?></p>
	<p><h5 style="display: inline;">Updated:</h5> <?=$user->updated?></p>

	<p><h5 style="display: inline;">Currently active:</h5>
	<?php if(!empty($user->active) && (empty($user->deleted))) : ?>
		<i class='fa fa-check-circle-o'></i></p>
		<p><h5 style="display: inline;">Last active:</h5> <?=$user->active?></p>
	<?php else : ?>
		<i class='fa  fa-times-circle'></i></p>
		<p><h5 style="display: inline;">Deleted:</h5> <?=$user->deleted?></p>
	<?php endif; ?>

	<ul class="userlist">
	<?php if(!empty($admin) && $user->acronym != "admin"): ?>
		<li><a class="userbutton" href="<?=$this->url->create($redirect["update"] . $user->id)?>"><i class="fa fa-wrench"></i> Update</a></li>
		<li><a class="userbutton" href="<?=$this->url->create($redirect["softDelete"] . $user->id)?>"><i class="fa fa-trash"></i> Trashbin</a></li>
		<li><a class="userbutton" href="<?=$this->url->create($redirect["restore"] . $user->id)?>"><i class="fa fa-wrench"></i> Restore</a></li>
		<?php if(!empty($superadmin)):?>
		<li><a class="userbutton" href="<?=$this->url->create($redirect["delete"] . $user->id)?>"><i class="fa fa-user-times"></i> Delete</a></li>
		<?php endif;?>
	<?php endif;?>
	</ul>
</div>
