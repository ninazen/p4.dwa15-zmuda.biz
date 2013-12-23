<?php

/*-------------------------------------------------------------------------------------------------
  Base Controller class - for general management
  Includes basic initialization features
-------------------------------------------------------------------------------------------------*/
class base_controller {
	
	public $user;
	public $userObj;
	public $template;
	public $email_template;

    /*-------------------------------------------------------------------------------------------------
      Construct instantiates, authenticates and populates the user object
      It also creates instances of the viewing templates and creates a 
      global variable for the current user
    -------------------------------------------------------------------------------------------------*/
	public function __construct() {
						
		# Instantiate User obj
			$this->userObj = new User();
			
		# Authenticate / load user data
			$this->user = $this->userObj->authenticate();					
						
		# Set up templates
			$this->template 	  = View::instance('_v_template');
			$this->email_template = View::instance('_v_email');			
								
		# So we can use $user in views			
			$this->template->set_global('user', $this->user);
	}
	
} # eoc
