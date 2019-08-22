<h1><?=$title?></h1>

<?php foreach ($users as $user) : ?>
<div class='user'>
	<h3 style="display: inline; text-transform: uppercase;"> <?=$user->id?>. <?=$user->acronym?></h3>
	<p><h5 style="display: inline;">Name:</h5> <?=$user->name?></p>
	<p><h5 style="display: inline;">E-Mail:</h5> <?=$user->email?></p>

	<p><h5 style="display: inline;">Currently active:</h5>
	<?php if(!empty($user->active) && (empty($user->deleted))) : ?>
		<i class='fa fa-check-circle-o'></i></p>
	<?php else : ?>
		<i class='fa fa-times-circle'></i></p>
	<?php endif; ?>

		<ul class="userlist">
			<li><a class="userbutton" href="<?=$this->url->create($redirect["profile"] . $user->id)?>"><i class="fa fa-user"></i> Profile</a></li>
			<?php if($admin && $user->acronym != "admin"):?>
			<li><a class="userbutton" href="<?=$this->url->create($redirect["update"] . $user->id)?>"><i class="fa fa-pencil"></i> Update</a></li>
			<li><a class="userbutton" href="<?=$this->url->create($redirect["softDelete"] . $user->id)?>"><i class="fa fa-trash"></i> Trashbin</a></li>
			<li><a class="userbutton" href="<?=$this->url->create($redirect["restore"] . $user->id)?>"><i class="fa fa-wrench"></i> Restore</a></li>
			<?php endif;?>
		</ul>
</div>

<?php endforeach; ?>
