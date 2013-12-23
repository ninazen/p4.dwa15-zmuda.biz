<!DOCTYPE html>
<html lang="en">

<!-- Basic Viewing Template -->

<head>
    <title><?php if(isset($title)) echo $title; ?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />        

    <!-- CSS Files we want on every page -->
    <link href="/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
    <link href="/css/styles.css" rel="stylesheet" type="text/css">
    <link href="/css/tiktok.css" rel="stylesheet" type="text/css">
                                        
    <!-- JS Files we want on every page -->
    <!-- <script src="html/js/jquery-1.8.2.js"></script> -->
    <!-- <script src="html/js/bootstrap.js"></script> -->
    <!-- <script src="html/js/scripts.js"></script> -->
                                                                                
    <!-- Controller Specific JS/CSS -->
    <?php if(isset($client_files_head)) echo $client_files_head; ?>

    <!-- Viewer Specific Headers -->
	<?php if(isset($header)) echo $header; ?>

</head>

<body>	

    <div class="container">
        <div id="top">
   		
       		<!-- Logo, also links to the home page -->
            <a href="/"><img src="/images/aSkitterLogo.jpg" alt="Home Page"></a>

            <!-- Tik Tok link, for all users -->
                <div class="tiktok-button">
                    <a href="/tiktok.php"><img src="/images/tiktok-btn.png" alt="Tik Tok"></a>
                </div>

       		<!-- Welcome Message for logged in users -->
        	<?php if($user): ?>
    	        <br> Hello <a href='/users/profile'><?=$user->first_name?>!</a><br>
    	    <?php endif; ?>

        </div>

        <!-- Global Navigation -->
        <div id="navigation">
    	    <nav class="nav navbar nav-list">
                <!-- Removed 'Home' link to declutter, just use logo -->    

                    <!-- Nav Links for logged in users -->
                	<?php if($user): ?>

                        <div class="btn btn-default navbar-btn">
                            <a href='/posts/'><b>All aSkitter!</b>
                        </div>
 
                        <div class="btn btn-default navbar-btn">
                            <a href='/posts/add'><b>Add a Skeet</b></a>
                        </div>
 
                        <div class="btn btn-default navbar-btn">
                            <a href='/posts/users'><b>Follow Skeeters</b></a>
                        </div>

                    <!-- Nav Links for anonymous users -->
                	<?php else: ?>

                        <div class="btn btn-default navbar-btn">
                        	<a href='/users/login'><b>Log In</b></a>
                        </div>
 
                        <div class="btn btn-default navbar-btn">
                            <a href='/users/signup'><b>Sign Up</b></a>
                        </div>
 
                	<?php endif; ?>
            </nav>
        </div>

        <div id="middle" class="<?=$middle_color?>_body">

            <!-- A thin divider line -->
            <hr style="height:1px">

            <?php /* Check for user messages (success, warning, danger, active) */ ?>
            <div id="messages">

                <?php if(isset($_SESSION['userMsg'])): ?>
                    <?php $_SESSION['userMsgClass']; ?>
                        <div class="<?=$_SESSION['userMsgClass']?>">
                        <?php echo $_SESSION['userMsg']; ?>
                        </div>
                    <?php $_SESSION['userMsg']=NULL; ?>
                    <?php $_SESSION['userMsgClass']='active'; ?>
                <?php endif; ?>

            </div>

    		<?php /* View-specific content */ ?>
    		<?php if(isset($content)) echo $content; ?>

    		<?php /* View-specific files */ ?>
    		<?php if(isset($client_files_body)) echo $client_files_body; ?>

            <br><br><br>

            <!-- Another thin divider line -->
            <hr style="height:1px">

        </div>

        <div id="bottom">

    		<?php /* If user is logged in, show the Logout link */ ?>
            <?php if($user): ?>
                <a href='/users/logout'>Logout</a></li>
            <?php endif; ?>


            <p class="tinytext">
            Copyright 2013 aSkitter<br>
            Thanks to funnylogo.info and bestclipartblog.com for borrowed artwork.<br>
            </p>

        </div>
    </div>

</body>
</html>