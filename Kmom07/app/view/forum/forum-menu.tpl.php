<div class="div-menu-questions">
<h2><?=$title?></h2>

<?php if(!empty($admin)):?>
<h3>
    <a href="<?=$this->url->create($redirect['addQuestion'])?>" class="nodecoration">Post Question</a>
</h3>
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
			<td class="menu-data">
                <a class="nodecoration" href="<?=$this->url->create($redirect['question'] . $item->id);?>">
                    <h3><?=$item->title?></h3>
                </a>
            </td>
			<td class="menu-data">
                <a class="nodecoration" href="<?=$this->url->create($redirect['user'] . $item->userid);?>">
                    <h6 class="menu-user"><?=ucfirst($item->user)?></h6>
                </a>
            </td>
			<td class="menu-data"><?=$item->timestamp?></td>
		</tr>
	<?php endforeach;?>
<?php endif; ?>
</table>
</div>
