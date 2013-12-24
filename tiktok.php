<!DOCTYPE html>
<!--
    Document   : tiktok.php
    Author     : Nina Zmuda
    Class      : CSCE-15 Project 4
    Description: Main HTML for Tik Tok - a pomodoro-type timer
-->
<html>
<head>
	<title>TikTok</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

	<!-- CSS -->
	<link rel="stylesheet" href="css/tiktok.css" type="text/css">
	<link href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css">
	<link href="css/styles.css" rel="stylesheet" type="text/css">
		
</head>

<body>	
	
	<!-- JS : Moved this into the body, based on comments from grader for P3 -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.timer.js"></script>
	<script type="text/javascript" src="js/tiktok.js"></script>			
	<script type="text/javascript" src="js/forms.js"></script>

	<div id="wrapper"><br>

		<!-- Title area with logo -->
		<div id="logo" class="logo"></div>
		<h2>a pomodoro timer</h2>

		<!-- Provide link back to aSkitter -->
		<div id="askitter" class="askitter-button">
	        <a href="/"><img src="/images/tiktok.png" alt="aSkitter"></a>
	    </div>

		<!-- Graphic Timers area with countdown -->
		<div id="timers">

			<!-- Welcome Timer -->
			<div id="welcome-timer" class="pom-timer">
				<div id="welcome-msg">
				Welcome!<br>
				This timer is an aid<br>
				to help you practice the<br><br>
				<div class="pt-title"><br>Pomodoro<br>Technique</div><br>
				</div>
			</div>
				
			<!-- Pomodoro Timer -->				
			<div id="pom-timer" class="pom-timer">
				<div id="start-timer"></div>
			</div>

			<!-- Java Break Timer -->			
			<div id="java-timer" class="java-timer">
				<div id="break-timer"></div>
			</div>		 
			 
		</div>

		<!-- Worksheet Forms area (overlaps with Graphic Timers area) -->
		<div id="forms">

			<!-- To Do Form -->
			<div id="todo-form" class="todo-form seashell_body">

				<h3>TO DO TODAY</h3>
				    <div class="text_box">
						<input type='text' class="title-text" size='5' maxlength='1' value='Unplan' readonly>
						<input type='text' class="title-text" size='60' maxlength='60' value='Activity Name' readonly>
			  			<input type='text' class="title-text" size='10' maxlength='1' value='Actual Poms' readonly><br><br>
						<input type="button" class="button" id="load-todo" value="Load To Do List">
					</div><br>

					<!-- To Do List -->
					<div id="todo-list"></div>
					<br>
			</div>

			<!-- Inventory Form -->
			<div id="invent-form" class="invent-form azure_body">

				<h3>ACTIVITY INVENTORY</h3>

					<div align="center">
						<input type="button" class="button" id="clear-storage" value="Erase ALL Activities"><br>
					</div><br>

				    <div class="text_box">
						<input type='text' class="title-text" size='5' maxlength='1' value='Priority' readonly>
						<input type='text' class="title-text" size='60' maxlength='60' value='Activity Name' readonly>
			  			<input type='text' class="title-text" size='10' maxlength='1' value='Estim Poms' readonly><br>
						<input type='text' size='5' maxlength='1' id="activity-prio" value=''>
						<input type='text' size='60' maxlength='60' id="activity-name" value=''>
			  			<input type='text' size='10' maxlength='1' id="activity-estim" value='1'>
						<input type="button" class="button" id="add-activity" value="Add to Inventory"><br><br>
						<input type="button" class="button" id="load-inventory" value="Load Saved Inventory">
					</div><br>

					<!-- Inventory List -->
					<div id="inventory-list"></div>
					<br>
			</div>

			<div id="records-form" class="records-form honeydew_body">

				<h3>RECORDS</h3>
				    <div class="text_box">
						<input type='text' class="title-text" size='5' maxlength='1' value='Priority' readonly>
						<input type='text' class="title-text" size='5' maxlength='1' value='Unplan' readonly>
						<input type='text' class="title-text" size='60' maxlength='60' value='Activity Name' readonly>
			  			<input type='text' class="title-text" size='10' maxlength='1' value='Estim Poms' readonly>
			  			<input type='text' class="title-text" size='10' maxlength='1' value='Actual Poms' readonly><br><br>
						<input type="button" class="button" id="load-records" value="Load Records">
					</div><br>

					<!-- To Do List -->
					<div id="records-list"></div>
					<br>
			</div>
			 
		</div>

		<!-- Buttons area for all timer buttons (Start a Pom, Take a Break) -->
		<div id="buttons">

		<div id="timer-buttons">

			<!-- Start button -->				
			<div id="start-block">
				<input type="button" class="button-pom" id="start" value="Start a Pom">
				<input type="text" class="time-select text-field" id="pom-count" value="25" disabled="disabled"> <!-- NOTE: Change the "value" from 25 to 1 when testing -->
			</div>

			<!-- Break button -->
			<div id="break-block">
				<input type="button" class="button-break" id="break" value="Take a Break">
				<input type="text" class="time-select text-field" id="min-count" disabled="disabled">
				<br>

				<!-- Break time slider -->
				<div id="min-slider" class="time-slider" ></div><br>
			</div>
		</div>

		<!-- Buttons area for all info page buttons (Pom, What's a Pom?, Break) -->
		<div id="info-buttons">

			<!-- Informational and quickjump buttons -->
			<div class="info-buttons">
				<input type="button" class="info-button-link" id="pomLink" value="Pom">
				<input type="button" class="info-button-link" id="infoLink" value="What's a Pom?">
				<input type="button" class="info-button-link" id="breakLink" value="Break">
			</div>

			<!-- Informational Pages - for Pom, What's a Pom?, and Break -->
			<div id="breakInfoPage" class="info-page break-info-page">
				<div class="text-bold">Break = a respite from Poms</div><br>
				Take a 5 minute break between pomodoros. After every fourth pomodoro, take a longer break for 15-30 minutes.<br><br>
				<div class="text-italic">Double-click to reset break timer.</div>
			</div>

			<div id="infoPage" class="info-page pt-info-page">
				<div class="text-bold">Pomodoro = Tomato in Italian</div><br>
				The <a href="http://www.pomodorotechnique.com">Pomodoro Technique</a> is a deceptively simple-to-learn method of time management that can be life-changing when used effectively, with intuitive guidelines for tackling tasks of all sizes, managing distractions, and reducing burnout.<br><br>
			</div>

			<div id="pomInfoPage" class="info-page pom-info-page">
				<div class="text-bold">Pom = short for pomodoro</div><br>
				Poms are short sprints of focused activity that last 25 minutes. Breaks should always be taken between poms to refresh.<br><br>
				<div class="text-italic">Double-click to reset pom timer</div>
			</div>
		</div>

		<!-- Buttons area for all form buttons (To Do, Inventory, Records) -->
		<div id="form-buttons">

			<!-- Informational and quickjump buttons -->
			<div class="form-buttons">
				<input type="button" class="form-button-link" id="inventLink" value="Inventory">
				<input type="button" class="form-button-link" id="toDoLink" value="To Do">
				<input type="button" class="form-button-link" id="recordsLink" value="Records">
			</div>

			<!-- Forms Pages - for To Do, Inventory, and Records -->
			<div id="inventInfoPage" class="form-page invent-page">
				<div class="text-bold">Activity Inventory = activities list</div><br>
				The Activities Inventory is a comprehensive list of all the activities you want to track. 
				Move the urgent ones to the To Do list for completion each day.<br><br>
				<div class="text-italic">Double-click for Inventory form.</div>
			</div>

			<div id="toDoInfoPage" class="form-page todo-page">
				<div class="text-bold">To Do Today = urgent activities</div><br>
				The To Do Today form lists activities that you plan to do today. 
				Once completed, you move them to the Records form.<br><br>
				<div class="text-italic">Double-click for the To Do form.</div>
			</div>

			<div id="recordsInfoPage" class="form-page records-page">
				<div class="text-bold">Records = completed activities</div><br>
				Records forms are used to track completed activities. 
				You can see your progress on your pomodoros.<br><br>
				<div class="text-italic">Double-click for Records form.</div>
			</div>
		</div>

		</div>

       <div id="bottom">
            <p class="tinytext">
            Copyright 2013 aSkitter<br>
            Thanks to funnylogo.info and bestclipartblog.com for borrowed artwork.<br>
            </p>
		</div>

	</div>
<br> 

</body>
</html>