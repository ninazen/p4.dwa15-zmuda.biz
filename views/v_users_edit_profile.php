<!-- User Edit Profile Page -->

<?php if(isset($user)): ?>

	<h1><?=$user->first_name?> <?=$user->last_name?></h1>
	<h3>Profile</h3>

	<form method='POST' action='/users/p_edit_profile'>

		First Name <input type='text' name='first_name' value='<?=$user->first_name?>'><br>
		Last Name <input type='text' name='last_name' value='<?=$user->last_name?>'><br>
		Email &nbsp; &nbsp; &nbsp; &nbsp;  <input type='text' name='email' value='<?=$user->email?>'><br>
		Password &nbsp; <input type='password' name='password' value='*********'><br>

	    <br><br>
		<input type='submit' value='Done'>

		<br><br><a href='/users/profile'>Never mind!</a>

<?php else: ?>

	<h1>This area is for members only.</h1>

<?php endif; ?>