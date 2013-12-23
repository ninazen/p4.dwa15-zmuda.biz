<!-- Follow Users Page - to show all users and allow follow/unfollow -->

<h1>Follow Skeeters</h1>

You can follow or unfollow any of these skeeters.<br><br>

<?php foreach($users as $user): ?>

	<?php # Don't want to show myself in the list ?>
	<?php # I am always following myself - I can't unfollow me ?>
	<?php if($user['user_id']!=$myself): ?>

		<?php # I'm following this user ?>
        <?php if(isset($connections[$user['user_id']])): ?>
		    <?=$user['first_name']?> <?=$user['last_name']?><br>
            <a href='/posts/unfollow/<?=$user['user_id']?>'>Unfollow</a>

		<?php # I'm NOT following this user ?>
        <?php else: ?>
		    <?=$user['first_name']?> <?=$user['last_name']?><br>
            <a href='/posts/follow/<?=$user['user_id']?>'>Follow</a>

        <?php endif; ?>        
        
        <br><br>

    <?php endif; ?>

<?php endforeach ?>