<!---------------- Users ----------------->
<div class="homeUsers" style="padding-left: 24px;">
    <h2><?=$title?></h2>
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
    </table>
</div>
