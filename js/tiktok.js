/* 
    Document   : tiktok.js
    Author     : Nina Zmuda
    Class      : CSCE-15 Project 4
    Description: Javascripts for tiktok program
*/

// Some global variables
var countdownTimer = "";
var clickSound = new Audio('sounds/done.wav');

// Function to disable return key from imitating a button press
function stopRKey(evt) {
	var evt  = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="button")) { return false; }
}
document.onkeypress = stopRKey;

// Function for padding a number with zeroes
function pad(number, length) {
	var str = '' + number;
	while (str.length < length) {str = '0' + str;}
	return str;
}

// Function for Timer Countdown Reset
function countdownReset() {
	if (countdownTimer != "") {
		countdownTimer.stop();
		countdownTimer = "";
	}
}

// Function to Swap in the Start Timer (Pomodoro)
function swapToStartTimer() {

	// Reset the countdown if requested
	countdownReset();

	// Hide the welcome message
	$('#welcome-timer').hide();

	// Hide the break timer and buttons
	$('#java-timer').hide();
	$('#break-timer').hide();
	$('#break-block').hide();
	$('#min-slider').hide();			

	// Hide the forms
	$('#forms').hide();
	$('#todo-form').hide(); 
	$('#invent-form').hide(); 
	$('#records-form').hide(); 

	// Show the start timer and start button
	$('#timers').show();
	if (countdownTimer == "") {
		$('#start-timer').hide();
		$('#start').prop('value','Start a Pom');
	}
	else {
		$('#start-timer').show();
		$('#start').prop('value','Quit');
	}
	$('#pom-timer').show();
	$('#start-block').show();
	//$('#start').prop('value','Start a Pom');
}

// Function to Swap in the Break Timer
function swapToBreakTimer() {

	// Reset the countdown if requested
	countdownReset();

	// Hide the welcome message
	$('#welcome-timer').hide();

	// Hide the start timer and button
	$('#pom-timer').hide();
	$('#start-timer').hide();
	$('#start-block').hide();

	// Hide the forms
	$('#forms').hide();
	$('#todo-form').hide(); 
	$('#invent-form').hide(); 
	$('#records-form').hide(); 

	// Show the break timer and break button
	$('#timers').show();
	$('#break-timer').hide(); // countdown - wait until timer starts to show this
	$('#java-timer').show();
	$('#break-block').show();
	$('#break').prop('value','Take a Break');
	$('#min-slider').show();	
}

// Function to Swap in the To Do Form
function swapToToDoForm() {

	// Hide the welcome message
	$('#welcome-timer').hide();

	$('#timers').hide();
	$('#timer-buttons').show();
	$('#buttons').show();

	// Show the To Do Form, hide other forms
	$('#forms').show();
	$('#todo-form').show(); 
	$('#invent-form').hide(); 
	$('#records-form').hide();
}

// Function to Swap in the Inventory Form
function swapToInventForm() {

	// Hide the welcome message
	$('#welcome-timer').hide();

	$('#timers').hide();
	$('#timer-buttons').show();
	$('#buttons').show();

	// Show the Inventory Form, hide other forms
	$('#forms').show();
	$('#todo-form').hide(); 
	$('#invent-form').show(); 
	$('#records-form').hide();

	// Display the inventory list
	loadInventory ();
}

// Function to Swap in the Records Form
function swapToRecordsForm() {

	// Hide the welcome message
	$('#welcome-timer').hide();

	$('#timers').hide();
	$('#timer-buttons').show();
	$('#buttons').show();

	// Show the Records Form, hide other forms
	$('#forms').show();
	$('#todo-form').hide(); 
	$('#invent-form').hide(); 
	$('#records-form').show();
}

/****** Main functions ******/

$(document).ready(function() {

	/* Initialization for Tik Tok */

	// Hide error messages
	$('#pomo-error').hide();

	// Hide the start and break timers
	// Show the start button but hide the break button
	$('#pom-timer').hide();
	$('#break-block').hide();
	$('#java-timer').hide();
	$('#min-slider').hide();

	// Hide the info pages
	$('#infoPage').hide(); 
	$('#breakInfoPage').hide(); 
	$('#pomInfoPage').hide(); 

	// Hide the form info pages
	$('#toDoInfoPage').hide(); 
	$('#inventInfoPage').hide(); 
	$('#recordsInfoPage').hide(); 

	// Hide the forms
	$('#forms').hide();
	$('#todo-form').hide(); 
	$('#invent-form').hide(); 
	$('#records-form').hide(); 


	/* Timer Slider Function - main function to initialize the slider */

    $(function() {
        $( "#min-slider" ).slider({
            orientation: "horizontal",

            // NOTE: Change these values from 5, 30, 5, 5 to 1, 5, 1, 1 when testing
            min: 5,
            max: 30,
            step: 5,
            value: 5,
            slide: function( event, ui ) {
                $( "#min-count" ).val( ui.value );
                var timeval = pad($('#min-count').val(),1);
				$('#break-timer').html(timeval);
            }
        });
        
        $( "#min-count" ).val( $( "#min-slider" ).slider( "value" ) );      
    });
	
	/* Start a Pomodoro - main function to start up the timer */
	
	$('#start').click(function() {

		// Default to a fixed 25 minutes for all pomodoros
		// NOTE: This value is set in pom-count in index.html - reduce it for testing

		// No countdown started yet - initiate the timer
		if (countdownTimer == "") {

			// Subtract one second off to avoid the initial flash
			var countdownCurrent = (($('#pom-count').val() * 6000)) - 1;

			// Calculate new timer value
			countdownTimer = $.timer(function() {
				var hour = parseInt (countdownCurrent/360000);
				var min = parseInt(countdownCurrent/6000)-(hour*60);
				var sec = parseInt(countdownCurrent/100)-(hour*3600)-(min*60);
				$('#start-timer').html(pad(min,2)+":"+pad(sec,2));

				// Timer completed, sound an alert and move to next step
				if(countdownCurrent == 0) {
					clickSound.play();
					countdownTimer.stop();
					alert('ALL DONE!');
					swapToBreakTimer();

				// Else keep counting down
				} else {
					countdownCurrent-=7;
					if(countdownCurrent < 0) {countdownCurrent=0;}
				}
			}, 70, true);

			// Hide the welcome message
			$('#welcome-timer').hide();

			// Hide the break button
			$('#break-timer').hide();

			// Change the button text to Quit
			$('#start').prop('value','Quit');
			$('#pom-timer').show();
			$('#start-timer').show();
			$('#start-block').show();			
		} 

		// User selects Quit to interrupt the timer
		else {

			// If user wants to quit early, confirm it
			if (confirm("Do you really want to QUIT this pomodoro?") ) {

				// If confirmed, stop the countdown and refresh the timer
				swapToStartTimer();			}
		}
	});
	
	/* Take a Break Function - main function to start up the timer */

	$('#break').click(function() {

		// Default to 5 minutes if no time is chosen
		if ($('#break-timer').html()=="") {
			$('#min-count').val("5");  // Note: Change this value from 5 to 1 when testing
		}	
		
		// No countdown started yet - initiate the timer
		if (countdownTimer == "") {

			// Subtract one second off to avoid the initial flash
			var countdownCurrent = (($('#min-count').val() * 6000))-1;

			// Calculate new timer value
			countdownTimer = $.timer(function() {
				var hour = parseInt (countdownCurrent/360000);
				var min = parseInt(countdownCurrent/6000)-(hour*60);
				var sec = parseInt(countdownCurrent/100)-(hour*3600)-(min*60);

				$('#break-timer').html(pad(min,2)+":"+pad(sec,2));

				// Timer completed, sound an alert and move to next step
				if(countdownCurrent == 0) {
					clickSound.play();
					countdownTimer.stop();
					alert('ALL DONE!');
					swapToStartTimer();

				// Else keep counting down
				} else {
					countdownCurrent-=7;
					if(countdownCurrent < 0) {countdownCurrent=0;}
				}
			}, 70, true);

			// Hide the start timer and button
			$('#pom-timer').hide();
			$('#start-timer').hide();
			$('#start-block').hide();

			// Change the button text to Quit
			$('#break').prop('value','Quit');
			$('#break-block').show();
			$('#java-timer').show();
			$('#break-timer').show();
			$('#min-slider').hide();			
		} 

		// User selects Quit to interrupt the timer
		else {

			// If user wants to quit early, confirm it
			if (confirm("Did you finish your break early?") ) {

				// If confirmed, stop the countdown and refresh the timer
				swapToBreakTimer();
			}
		}
	});

	/****** Global Navigation Links ******/

	// Note: It is a known artifact of jquery that the double click event also sends
	//       two single click events.  While there are workarounds for this,
	//		 it is actually the author's design decision to allow this to remain,
	//		 so that the message block flashes on and off (or off and on) on doubleclick.
	//		 This provides some visual feedback for the user on the doubleclick action.

	/* Informational and Quick-Jump Pages (Pom, What's a Pom, and Break) */

	// Pom button - Jump to the Start a Pom Page on double click
	$("#pomLink").dblclick(function() { 
		$('#pomInfoPage').hide(); 
		swapToStartTimer(); 
	} );

	// Pom button - Toggle display of the Pom info block on single click
	$("#pomLink").click(function() { 
		$('#pomInfoPage').toggle(); 
	} );

	// Pom info block - Close the Pom info block when clicked
	$("#pomInfoPage").click(function() { 
		$('#pomInfoPage').hide(); 
	} );

	// What's a Pom? button - No action for double click
	$("#infoLink").dblclick(function() { 
		// Do nothing for now
	} );

	// What's a Pom? button - Toggle display of the General info block on single click
	$("#infoLink").click(function() { 
		$('#infoPage').toggle(); 
	} );

	// What's a Pom? info block - Close the General info block when clicked
	$("#infoPage").click(function() { 
		$('#infoPage').hide(); 
	} );

	// Break button - Jump to the Take a Break page on double click
	$("#breakLink").dblclick(function() { 
		swapToBreakTimer(); 
	} );

	// Break button - Toggle display of the Break info block on single click
	$("#breakLink").click(function() { 
		$('#breakInfoPage').toggle(); 
	} );

	// Break info block - Close the Break info block when clicked
	$("#breakInfoPage").click(function() { 
		$('#breakInfoPage').hide(); 
	} );

	/* Forms and Quick-Jump Pages (To Do, Inventory, Records) */

	// To Do button - Jump to the To Do Today Page on double click
	$("#toDoLink").dblclick(function() { 
		$('#toDoInfoPage').hide(); 
		swapToToDoForm(); 
	} );

	// To Do button - Toggle display of the To Do block on single click
	$("#toDoLink").click(function() { 
		$('#toDoInfoPage').toggle(); 
	} );

	// To Do block - Close the To Do block when clicked
	$("#toDoInfoPage").click(function() { 
		$('#toDoInfoPage').hide(); 
	} );

	// Inventory button - Jump to the Inventory Page on double click
	$("#inventLink").dblclick(function() { 
		$('#inventInfoPage').hide(); 
		swapToInventForm(); 
	} );

	// Inventory button - Toggle display of the Inventory block on single click
	$("#inventLink").click(function() { 
		$('#inventInfoPage').toggle(); 
	} );

	// Inventory block - Close the Inventory block when clicked
	$("#inventInfoPage").click(function() { 
		$('#inventInfoPage').hide(); 
	} );

	// Records button - Jump to the Records page on double click
	$("#recordsLink").dblclick(function() { 
		swapToRecordsForm(); 
	} );

	// Records button - Toggle display of the Records block on single click
	$("#recordsLink").click(function() { 
		$('#recordsInfoPage').toggle(); 
	} );

	// Records block - Close the Records block when clicked
	$("#recordsInfoPage").click(function() { 
		$('#recordsInfoPage').hide(); 
	} );

	/* Logo Link */

	// Jump to the Start a Pom page (home) when the top logo is clicked
	$("#logo").click(function() { 
		swapToStartTimer(); 
	} );

	/****** Activities Database and Forms Management Functions ******/
	// 
	// Browser local storage is used to store the data for these forms.
	//
	// The key is the activity name, and the value contains a string of digits:
	//
	//	1.	The string "POM" is always first, to indicate this record is used by Tik Tok
	//		Note: Won't actually check for this string in version 1
	//
	//  2.	Next are 4 important values for tracking:
	//   	(P)riority, (E)stimated poms, (A)ctual poms, (S)tatus
	//      Note: To keep it simple this round, I'm limiting each to a single digit (0-9)
	//      Note: Although 0 isn't likely, I'll still allow it for now
	//      Status is 0/1, where 1 means the activity is completed
	//
	//  3.	That's followed by 3 more values for display:
	//      (T)oDo, (I)nventory, (R)ecords
	//      Values are 0/1, where 1 means the activity belongs in that display
	//      Note: activities can appear on any or all of the displays

	/* Clear Storage - Clear all activities in local storage */
	$("#clear-storage").click(function() {

		localStorage.clear();
		
		// Display the inventory list
		loadInventory ();
	});

	/* Add Activity Function - Add a new activity to the Activity form */
	$('#add-activity').click(function() {

		// Store an entry into the local storage table (see notes above for details on values)
		var keystring = $('#activity-name').val();  // use activity name as the lookup key

		if (keystring == "") {
			alert ("You need to enter an Activity Name! Priority and estimated POMs are optional.")
		}

		else {
			// Value = "POM + Priority + Estimated + Actual + Status + ToDo + Inventory + Records"
			// Note: Not all these values will be fully utilized in version 1
			var valstring = "POM" + $('#activity-prio').val() + $('#activity-estim').val() + "00" + "111";

			// Store it in local storage
			localStorage.setItem(keystring, valstring);

			// Clear the box for the next activity
			$('#activity-prio').val("");
			$('#activity-name').val("");
			$('#activity-estim').val("1");
			$('#activity-prio').html("");

			// Display the inventory list
			loadInventory ();
		}
	});
	
	/* Strike Out - Function to strike out completed tasks */
	$('.activity').live('click', function() {
		if ($(this).css('text-decoration')=='line-through') {
			$(this).find('input').attr('checked', false);
			$(this).css('color', 'black');
			$(this).css('text-decoration', 'none');
		} else {
			$(this).find('input').attr('checked', true);
			$(this).css('color', 'grey');
			$(this).css('text-decoration', 'line-through');
		}
	});

	// Load ToDo - Load in To Do Today list from local storage
	// Note: limiting to 10 entries for now
	$('#load-todo').click(function() {
		$('#todo-list').html("");
		if (localStorage.length > 0) {
			var storedValue;
			for (var i = 0; (i < localStorage.length) && (i < 10); i++){
				// display it only if it is flagged for the inventory page
				storedValue = localStorage.getItem(localStorage.key(i));
				if (storedValue[7] == '1'){
  		  			$('#todo-list').append(
	  		 			"<input type='text' size='5' maxlength='1' value='"+storedValue[3]+"' readonly> " +
						"<input type='text' size='60' maxlength='60' value='"+localStorage.key(i)+"' readonly> " +
			  			"<input type='text' size='10' maxlength='1' value='"+storedValue[4]+"' readonly><br>");
				}
			}
		}
	});

	$('#load-inventory').click(function() {
		// Display the inventory list
		loadInventory ();
	});

	// Load Inventory - Load in activity inventory from local storage 
	// Note: limiting to 10 entries for now
	function loadInventory () {		
		$('#inventory-list').html("");
		if (localStorage.length > 0) {
			var storedValue;
			for (var i = 0; (i < localStorage.length) && (i < 10); i++){
				// display it only if it is flagged for the inventory page
				storedValue = localStorage.getItem(localStorage.key(i));
				if (storedValue[8] == '1'){
  		  			$('#inventory-list').append(
	  		 			"<input type='text' size='5' maxlength='1' value='"+storedValue[3]+"' readonly> " +
						"<input type='text' size='60' maxlength='60' value='"+localStorage.key(i)+"' readonly> " +
			  			"<input type='text' size='10' maxlength='1' value='"+storedValue[4]+"' readonly><br>");
				}
			}
		}
	};

	// Load Records - Load in Records list from local storage
	// Note: limiting to 10 entries for now
	$('#load-records').click(function() {
		$('#records-list').html("");
		if (localStorage.length > 0) {
			var storedValue;
			for (var i = 0; (i < localStorage.length) && (i < 10); i++){
				// display it only if it is flagged for the inventory page
				storedValue = localStorage.getItem(localStorage.key(i));
				if (storedValue[9] == '1'){
  		  			$('#records-list').append(
	  		 			"<input type='text' size='5' maxlength='1' value='"+storedValue[3]+"' readonly> " +
	  		 			"<input type='text' size='5' maxlength='1' value='"+storedValue[5]+"' readonly> " +
						"<input type='text' size='60' maxlength='60' value='"+localStorage.key(i)+"' readonly> " +
			  			"<input type='text' size='10' maxlength='1' value='"+storedValue[4]+"' readonly> " +
			  			"<input type='text' size='10' maxlength='1' value='"+storedValue[6]+"' readonly><br>");
				}
			}
		}
	});

});