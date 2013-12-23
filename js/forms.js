
$(document).ready(function() {

	/* Browser Check -- check if browser supports HTML5 local storage, display error if unsupported */
	try {
    	return 'localStorage' in window && window['localStorage'] !== null;
  	} catch (e) {
    	alert("Your browser does not support HTML5 local storage. Please try a newer browser version.");
  	}
}

$(document).ready(function() {

	/* Load Activities - Load in saved list of activities from local storage */
	$('#load-activities').click(function() {
		if (localStorage.length <= 0) {
			// do nothing
		} else {		
			for (var i = 0; i < localStorage.length; i++){
    			$('#saved-activities').append("<input class='button loaded-activities' type='button' value='"+localStorage.key(i)+"'>");
			}
			$('#load-activities').hide();
		}
	});	
	
	/* Clear all saved activities */
	$('#clear-storage').click(function() {
		localStorage.clear();
	});
	
	/* Add saved activities to list */
	$('.loaded-activities').live('click', function() {
		var index = $(this).val();
		$('#activity-entries').append(localStorage.getItem(index));
		$(this).hide();
	});
	
	/* Process activity description */
	$('#activity-name').keyup(function() {
		// Check for too many characters - Thanks Susan
		var name = $(this).val();
		var length = name.length;
		if(length == 20) {
			$('#activity-error').html("The max amount of characters is 20");
			$('#activity-error').show();
		}
		else {
			$('#activity-error').html("");
			$('#activity-error').hide();
		}	
	});
	
	/* Add activity to list */
	$('#add-activity').click(function() {

		// variable for holding our activity string
		var activitiestring = "";
		
		// Add activity name
		activitiestring = "<div class='activity'>"+"<input type='checkbox'>"+$('#activity-name').val()+"</div>";
		
		// Split activity description
		var activity = $('#activity-desc').val().split("\n");
		
		// Add activity description
		$.each(activity, function() {
			activitiestring = activitiestring +"<div class='activity task'>"+"<input type='checkbox'>"+ this + "</div>";
		});
		
		// Add to local storage
		localStorage[$('#activity-name').val()] = activitiestring;
		
		// Add the activity to the list	
		$('#activity-entries').append(activitiestring);
		
		// Clear the box for the next activity
		$('#activity-name').val("");
		$('#activity-desc').val("");
			
	});

	/* Grey out completed tasks */
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
	
	/* Print activity list */
	/*-- Taken from Susan's awesome card generator--*/
	$('#print-button').click(function() {
		
		// Setup the window we're about to open	    
	    var print_window =  window.open('','_blank','');
	    
	    // Get the content we want to put in that window - this line is a little tricky to understand, but it gets the job done
	    var contents = $('<div>').html($('#right-side').clone()).html();
	    
	    // Build the HTML content for that window, including the contents
	    var html = '<html><head><link rel="stylesheet" href="/css/activities.css" type="text/css"></head><body>' + contents + '</body></html>';
	    
	    // Write to our new window
	    print_window.document.open();
	    print_window.document.write(html);
	    print_window.document.close();
	    		
	});
	
	/* Clear the page (by reloading) */
	$('#clear-page').click(function() {
		history.go(0);
	});
	

});