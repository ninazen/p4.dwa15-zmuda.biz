<!-- Main Index Page (Home Page) -->
<h1> Welcome to P4! </h1>
<b>Here's where you can see the latest news, rumors, and gossip from people in your world. </b><br><br>

Okay, that's fun, but ... <br>
What else can you do here? <br>
You can see skeets from everyone you're following and post your own skeets.<br>
You can follow other skeeters on the site and they can follow you.<br>
You can click your name under the logo to see your info.<br>
You can also edit your information from that page.<br>
And you can clear out all the skitter too.<br>

<?php # Display links for logged in users ?>
<?php if ($user): ?>
	Come in and check out what's <br>
	<a href='/posts/'><b>All aSkitter!</a></b>

<?php # Display links for anonymous users ?>
<?php else: ?>
	<a href='/users/signup'><b>Sign up</b></a> now or
	<a href='/users/login'><b>log in</b></a> to start <br> seeing what's all<br> 
	<strong>aSkitter!</strong>

<?php endif; ?>