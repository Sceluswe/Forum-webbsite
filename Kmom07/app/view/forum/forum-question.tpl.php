<!-- ---------------- The Question ---------------- -->
<div class="div-element">
<h1><?=$question->title?></h1>
<hr class="question-hr">
<?php if(!empty($question)) : ?>
	<table class="width100">
	<tr>
		<td class="question-rating-data">
			<i class="fa fa-chevron-up"></i><br>
			<h2 class="question-rating"><?=$question->rating?></h2>
			<i class="fa fa-chevron-down"></i>
		</td>
		<td class="question-content">
			<?=$this->textFilter->doFilter($question->content, 'markdown');?>
			<div class="question-signature">
				<?=$question->timestamp?>
				<h3>&mdash;<a href="<?=$this->url->create($redirect['user'] . $question->userid);?>" class="nodecoration"><?=ucfirst($question->user)?></a></h3>
			</div>
			<br>
			<h3>Comments</h3>
		</td>
	</tr>
<!-- The questions comments -->
	<?php if(!empty($questionComments)):?>
		<?php foreach($questionComments as $item):?>
			<tr> <!-- Comment -->
				<td></td>
				<td class="question-comment">
					<?=$this->textFilter->doFilter(
					"{$item->content}  &mdash;<a href='" 
					. $this->url->create("{$redirect['user']}{$item->userid}")
					. "' class='bold nodecoration'>" . ucfirst($item->user) 
					."</a>, {$item->timestamp}", 'markdown')?>
				</td>
			</tr>
		<?php endforeach;?>
	<?php endif;?>
	</table>
<?php endif;?>
</div>


<!-- ---------------- Answers ---------------- -->
<h2 class="answer-header">Answers</h2>
<hr class="answer-hr">
<?php if(!empty($answers)):?>
<?php foreach($answers as $answer): ?>
<div class="div-element">
	<table class="width100">
	<tr>
		<td class="question-rating-data">
			<?php if($answer->accepted == 1):?>
			<p class="fa fa-check"></p>
			<?php endif;?>
			<i class="fa fa-chevron-up"></i><br>
			<h2 class="question-rating"><?=$answer->rating?></h2>
			<i class="fa fa-chevron-down"></i>
		</td>
		<td class="question-content">
			<?=$this->textFilter->doFilter($answer->content, 'markdown');?>
			<div class="question-signature">
				<?=$answer->timestamp?>
				<h3>&mdash;<a href="<?=$this->url->create($redirect['user'] . $answer->userid);?>" class="nodecoration"><?=ucfirst($answer->user)?></a></h3>
			</div>
			<br>
			<h3>Comments</h3>
		</td>
	</tr>
<!-- The Answers comments -->
	<?php if(!empty($answerComments[$answer->id])):?>
		<?php foreach($answerComments[$answer->id] as $comment):?>
			<tr> <!-- Comment -->
				<td class="question-rating-data"></td>
				<td class="question-comment">
					<?=$this->textFilter->doFilter(
					"{$item->content}  &mdash;<a href='" 
					. $this->url->create("{$redirect['user']}{$comment->userid}")
					. "' class='nodecoration'>" . ucfirst($comment->user) 
					."</a>, {$item->timestamp}", 'markdown')?>
				</td>
			</tr>
		<?php endforeach;?>
	<?php endif; ?>
	</table>
</div>
<?php endforeach;?>
<?php endif;?>

<!--
	<table class="menu-questions">
	<tr class="menu-headers">
		<th class="menu-headers-data">Rating</th>
		<th class="menu-headers-data">Answered</th>
		<th class="menu-headers-data">Question</th>
		<th class="menu-headers-data">User</th>
		<th class="menu-headers-data">Posted</th>
	</tr>

	<?php foreach ($questions as $item) : ?>
		<tr class="menu-rows">
			<td class="menu-data"><?=$item->rating?></td>
			<td class="menu-data"><i class="<?=$item->answered?>" aria-hidden="true"></i></td>
			<td class="menu-data"><a href="<?=$this->url->create($redirect['question'] . $item->id);?>"><h3><?=$item->id?>. <?=$item->title?></h3></a></td>
			<td class="menu-data"><a href="<?=$this->url->create($redirect['user'] . $item->userid);?>"><h6 class="menu-user"><?=$item->user?></h6></a></td>
			<td class="menu-data"><?=$item->timestamp?></td>
		</tr>
	<?php endforeach;?>

</table>-->