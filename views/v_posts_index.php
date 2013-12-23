<!-- Post Viewing Page - to view all posts -->

<h1>All aSkitter!</h1>

<?php # Erase the posts (if user is the admin) or Hide the posts (if she is not) ?>
Here's the latest skeets ... <a href='/posts/clear'>[erase them all?]</a>
<br><br>

<?php # Check for an empty posts table ?>
<?php if (!empty($posts)): ?>

	<?php # Display each post in the table ?>
	<?php foreach($posts as $post): ?>

		<?php # Show name, date, and post content ?>
	    <div class="text_box">
        	<strong><?=$post['first_name']?> posted on <?=Time::display($post['created'])?></strong><br>
        	<?=$post['content']?><br><br>
        </div>

	<?php endforeach; ?>

<?php # Empty table ?>
<?php else: ?>
	<strong>Awww, there are no skeets here! <br>
	Try <a href='/posts/users'>following</a> someone ...</strong>

<?php endif; ?>

