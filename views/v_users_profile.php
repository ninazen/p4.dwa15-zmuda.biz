<!-- User Profile Page -->

<?php if(isset($user)): ?>

	<h1><?=$user->first_name?> <?=$user->last_name?></h1>
	<h3>Profile</h3>

	<form method='POST' action='/users/edit_profile'>

		First Name: <?=$user->first_name?><br>
		Last Name:  <?=$user->last_name?><br>
		Email: <?=$user->email?><br>
		Password: ********

	    <br><br>
		<input type='submit' value='Edit'>

<?php else: ?>

	<h1>This area is for members only.</h1>

<?php endif; ?>