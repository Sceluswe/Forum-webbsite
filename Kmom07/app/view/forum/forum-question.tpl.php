<!-- ---------------- The Question ---------------- -->
<div class="div-element">
<h1><?=$question->title?></h1>
<hr class="question-hr">
<?php if(!empty($question)) : //Start question?>
	<table class="width100">
	<tr>
		<td class="question-rating-data">
			<a href="<?=$this->url->create($redirect['rateQuestion'] . $question->id . '/1')?>"><i class="fa fa-chevron-up"></i></a><br>
			<h2 class="question-rating"><?=$question->rating?></h2>
			<a href="<?=$this->url->create($redirect['rateQuestion'] . $question->id . '/-1')?>"><i class="fa fa-chevron-down"></i></a>
		</td>
		<td class="question-content">
			<?=$this->textFilter->doFilter($question->content, 'markdown');?>
			 
			<div class="question-signature">
				<?=$question->timestamp?>
				<h3>&mdash;<a href="<?=$this->url->create($redirect['user'] . $question->userid);?>" class="nodecoration"><?=ucfirst($question->user)?></a></h3>
			</div>
			<?php if($questionAdmin): ?>
				<a href="<?=$this->url->create("{$redirect['tagButton']}")?>" class='addtag right'>
				<h4 class="question-comment-header">Add a tag</h4>
				</a>
			<?php endif;?>
			
			<br>
			<h3>Comments</h3>
		</td>
	</tr>
<!----------------- The questions comments ---------------->
	<?php if(!empty($questionComments)):?>
		<?php foreach($questionComments as $item):?>
			<tr> <!-- Comment -->
			<td class="question-rating-data">
			<?php if(!empty($admin))?>
				<a href="<?=$this->url->create($redirect['rateComment'] . $item->id . '/1')?>"><i class="fa fa-chevron-up chevFontSize"></i></a><br>
				<h2 class="question-rating chevFontSize"><?=$item->rating?></h2>
				<a href="<?=$this->url->create($redirect['rateComment'] . $item->id . '/-1')?>"><i class="fa fa-chevron-down chevFontSize"></i></a>
			</td>
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
<!---------------- Post Comment ----------------->
	<?php if(!empty($admin)): ?>
		<tr> 
			<td></td>
			<td class="question-comment">
				<a href="<?=$this->url->create("{$redirect['addQComment']}{$question->id}/{$question->id}")?>" class='nodecoration'>
				<h4 class="question-comment-header">Post a comment</h4>
				</a>
			</td>
		</tr>
	<?php endif;?>
	</table>
</div>


<!-- ---------------- Answers ---------------- -->
<h2 class="answer-header">Answers</h2>
<hr class="answer-hr">
<!---------------- Post Answer ---------------->

<?php if(!empty($admin)):?>
<a href="<?=$this->url->create("{$redirect['addAnswer']}{$question->id}")?>" class="nodecoration left paddingLeft"><h4>Post answer</h4></a>
<?php endif;?>
<a href="<?=$this->url->create("{$redirect['question']}{$question->id}/timestamp")?>" class="nodecoration left paddingLeft"><h4>Sort by newest date</h4></a>
<a href="<?=$this->url->create("{$redirect['question']}{$question->id}/rating")?>" class="nodecoration left paddingLeft"><h4>Sort by highest rating</h4></a>

<hr class="answer-hr" style="clear:both">

<!----------------- Answers ----------------->
<?php if(!empty($answers)): // Start of if answers?>
<?php foreach($answers as $answer): //Foreach answer?> 
<div class="div-element">
	<table class="width100">
	<tr>
		<td class="question-rating-data">
			<?php if($answer->accepted == 1):?>
				<p class="fa fa-check"></p>
			<?php elseif($questionAdmin):?>
				<a href="<?=$this->url->create($redirect['accepted'] . $answer->id)?>" class="nodecoration">Accept Answer</a>
			<?php endif;?>
			<a href="<?=$this->url->create($redirect['rateAnswer'] . $answer->id . '/1')?>"><i class="fa fa-chevron-up"></i></a><br>
			<h2 class="question-rating"><?=$answer->rating?></h2>
			<a href="<?=$this->url->create($redirect['rateAnswer'] . $answer->id . '/-1')?>"><i class="fa fa-chevron-down"></i></a>
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
<!----------------- The Answers comments ------------------>
	<?php if(!empty($answerComments[$answer->id])):?>
		<?php foreach($answerComments[$answer->id] as $comment):?>
			<tr> <!-- Comment -->
				<td class="question-rating-data">
					<a href="<?=$this->url->create($redirect['rateComment'] . $comment->id . '/1')?>"><i class="fa fa-chevron-up chevFontSize"></i></a><br>
					<h2 class="question-rating chevFontSize"><?=$comment->rating?></h2>
					<a href="<?=$this->url->create($redirect['rateComment'] . $comment->id . '/-1')?>"><i class="fa fa-chevron-down chevFontSize"></i></a>
				</td>
				<td class="question-comment">
					<?=$this->textFilter->doFilter(
					"{$comment->content}  &mdash;<a href='" 
					. $this->url->create("{$redirect['user']}{$comment->userid}")
					. "' class='nodecoration'>" . ucfirst($comment->user) 
					."</a>, {$comment->timestamp}", 'markdown')?>
				</td>
			</tr>
		<?php endforeach;?>
	<?php endif; ?>
<!---------------- Post comment ---------------->
	<?php if(!empty($admin)): ?>
		<tr> <!-- Post Comment -->
			<td></td>
			<td class="question-comment">
				<a href="<?=$this->url->create("{$redirect['addAComment']}{$question->id}/{$answer->id}")?>" class='nodecoration'>
				<h4 class="question-comment-header">Post a comment</h4>
				</a>
			</td>
		</tr>
	<?php endif;?>
	</table>
</div>
<?php endforeach; // End of foreach answer?>
<?php endif; // end of if answers?>
<?php endif; // end question?>