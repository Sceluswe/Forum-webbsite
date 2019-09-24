<nav class='usersubmenu'>
	<ul>
	<?php if(isset($values)): ?>
    	<?php if(isset($url)): ?>
    		<?php foreach ($values as $value) : ?>
    			<li><a href='<?=$this->url->create($url . $value)?>'><?=$value?></a></li>
    		<?php endforeach; ?>
    	<?php else :?>
    		<?php foreach ($values as $value) : ?>
    			<li><a href='<?=$this->url->create($value)?>'><?=$value?></a></li>
    		<?php endforeach; ?>
    	<?php endif; ?>
	<?php endif; ?>
	</ul>
</nav>
