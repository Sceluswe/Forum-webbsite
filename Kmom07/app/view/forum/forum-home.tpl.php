<!---------------- Questions ----------------->
<div class="div-menu-questions width60" style="padding-right: 85px;">
<h2><?=$title1?></h2>
<?php if(!empty($admin)):?>
<h3><a href="<?=$this->url->create($redirect['addQuestion'])?>" class="nodecoration">Post Question</a></h3>
<?php endif;?>
<?php if(!empty($questions)) : ?>
	<table class="menu-questions">
	<tr class="menu-table-header">
		<th class="menu-headers-data">Rating</th>
		<th class="menu-headers-data">Answered</th>
		<th class="menu-headers-data">Question</th>
		<th class="menu-headers-data">User</th>
		<th class="menu-headers-data">Posted</th>
	</tr>

	<?php foreach ($questions as $item) : ?>
		<tr>
			<td class="menu-data"><?=$item->rating?></td>
			<td class="menu-data"><?=$item->answered?></td>
			<td class="menu-data"><a class="nodecoration" href="<?=$this->url->create($redirect['question'] . $item->id);?>"><h3><?=$item->title?></h3></a></td>
			<td class="menu-data"><a class="nodecoration" href="<?=$this->url->create($redirect['profile'] . $item->userid);?>"><h6 class="menu-user"><?=ucfirst($item->user)?></h6></a></td>
			<td class="menu-data"><?=$item->timestamp?></td>
		</tr>
	<?php endforeach;?>
<?php endif; ?>
</table>
</div>

<!---------------- Users ----------------->
<div class="homeUsers">
<h2><?=$title2?></h2>
<ul>
<table>
<?php foreach($users as $user):?>
<tr>
	<td><img width="64" height="64" src="https://www.gravatar.com/avatar/<?=md5(strtolower(trim($user->email)))?>" alt="No image"></td>
	<td class="homeData">
	<p class="homeName">
		<a class="nodecoration" href="<?=$this->url->create($redirect['profile'] . $user->id);?>">
			<h3 class="menu-user"><?=ucfirst($user->name)?></h3>
		</a>Score:<?=$user->score?>
	</p>
	</td>
</tr>
<?php endforeach;?>
</ul>
</table>
</div>

<!---------------- Tags ----------------->
<div class="clear">
	<h2 class="homeHeader"><?=$title3?></h2>

	<?php foreach($tags as $tag):?>
		<p class="tag-list"><?=$tag->name?> x <?=$tag->num?></p>
	<?php endforeach;?>
</div>
