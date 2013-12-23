<!-- Post Creation Page - to add a post -->

<h1>Add a Skeet</h1>

Squeak now or forever hold your peace!<br><br>

<?php # Note: Add a link to clear the form and start over?>
<a href='/posts/add'>start over!</a>

<?php # Display the input form ?>
<form method='post' action='/posts/p_add'>

        <textarea name='content'></textarea>
        <br>
        
        <input type='Submit' value='Squeak!'>
         
</form>