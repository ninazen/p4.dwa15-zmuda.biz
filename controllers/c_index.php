<?php

/*-------------------------------------------------------------------------------------------------
  Index Controller class - Home Page for the site
  Includes the Home Page feature
-------------------------------------------------------------------------------------------------*/
class index_controller extends base_controller {
	
    /*-------------------------------------------------------------------------------------------------
      Construct simply calls the parent construct  
    -------------------------------------------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
	} 
		
   	/*-------------------------------------------------------------------------------------------------
      Index is the default controller method
      This default home page (/index/index) is loaded and rendered
    -------------------------------------------------------------------------------------------------*/
	public function index() {
		
		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
			$this->template->content = View::instance('v_index_index');
			
		# Now set the <title> tag
			$this->template->title = "aSkitter";

		# Now set the <middle_color> tag
			$this->template->middle_color = "azure";
	
		# CSS/JS includes (none required here)
			/*
			$client_files_head = Array("");
	    	$this->template->client_files_head = Utils::load_client_files($client_files);
	    	
	    	$client_files_body = Array("");
	    	$this->template->client_files_body = Utils::load_client_files($client_files_body);   
	    	*/
	      					     		
		# Render the view
			echo $this->template;

	} # End of method
	
	
} # End of class
