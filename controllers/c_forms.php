<?php

/*-------------------------------------------------------------------------------------------------
  Users Controller class - for user management
  Includes Sign up, Log in, Log out, and Profile features
-------------------------------------------------------------------------------------------------*/
class users_controller extends base_controller {

    /*-------------------------------------------------------------------------------------------------
      Construct creates an instance of a user object   
    -------------------------------------------------------------------------------------------------*/
    public function __construct() {

        parent::__construct();
    } 

    /*-------------------------------------------------------------------------------------------------
      Index is the default controller method   
    -------------------------------------------------------------------------------------------------*/
    public function index() {
        
        # Set up the view
        $this->template->content = View::instance('v_index_index');
        $this->template->title   = "Welcome";

        # Set the body background color
        $this->template->middle_color = "azure";

        # Render the view
        echo $this->template;

        # Set up session-based messages for user messaging
        $_SESSION['userMsg']=NULL; 
        $_SESSION['userMsgClass']='active';

    }

    /*-------------------------------------------------------------------------------------------------
      Display the sign up form        
    -------------------------------------------------------------------------------------------------*/
    public function signup() {
        
        # Set up the view
        $this->template->content = View::instance('v_users_signup');
        $this->template->title   = "Signup";

        # Set the body background color
        $this->template->middle_color = "seashell";

        # Render the view
        echo $this->template;

    }

    /*-------------------------------------------------------------------------------------------------
      Process the sign up form inputs    
    -------------------------------------------------------------------------------------------------*/
    public function p_signup() {
        
        # Verify that none of the input form fields are blank
        if (empty($_POST["email"]) || empty($_POST["password"])||
          empty($_POST["first_name"]) || empty($_POST["last_name"]))
        {
            # If so, display an error message and refresh the form
            $_SESSION['userMsg']="Please fill in all the fields.";
            $_SESSION['userMsgClass']='danger';
            Router::redirect("/users/signup");
        }

        # Search the DB for the email
        $q =
            'SELECT user_id
            FROM users
            WHERE email = "'.$_POST['email'].'"
            ';

        # Retrieve the user_id if the email exists in the DB
        $user_id = DB::instance(DB_NAME)->select_field($q);

        # If the email exists, display an error message and refresh the form
        if ($user_id) 
        {
            # If so, display an error message and refresh the form
            $_SESSION['userMsg']="That email address is already registered.<br> ".
                "Try a new email or try logging in.";
            $_SESSION['userMsgClass']='danger';
            Router::redirect("/users/signup");
        }

        # NOTE: Store the time for timestamping 
        #    Creation - when the user profile was created
        #    Modified - when the user profile was last modified
        #    Cleared - when the user last cleared his post view
        #       (The user will not see any posts created prior to the cleared time)

        $_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();
        $_POST['cleared'] = Time::now();

        # Salt the password and hash it
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
            
        # Salt the token, add a random string and hash it
        $_POST['token']    = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
            
        # Add the new user to the users table
        DB::instance(DB_NAME)->insert_row('users', $_POST);

        # NOTE: New users always follow themselves, to be able to see their own posts
        #    Add an entry to the users_users table to link the user to itself
        #    The user cannot unfollow itself, so it does not show up on the follow list

        # Retrieve the user_id from the entry we just created in the users table
        $user_id = DB::instance(DB_NAME)->select_field($q);

        # Set up a new entry with the user id as both the follower and followee
        $data = Array(
            "created"          => Time::now(),
            "user_id"          => $user_id,
            "user_id_followed" => $user_id
        );

        # Insert the entry into the users_users table
        DB::instance(DB_NAME)->insert('users_users', $data);

        # Set the user message field to notify the user of success
        $_SESSION['userMsg'] = "Yay! You've created an account! <br> Now log into your new account...";
        $_SESSION['userMsgClass']='success';
            
        # Redirect to the login page (user must still log in after sign up)
        Router::redirect('/users/login');
    }

    /*-------------------------------------------------------------------------------------------------
      Display the login form     
    -------------------------------------------------------------------------------------------------*/
    public function login() {

        # Set up the view
        $this->template->content = View::instance('v_users_login');
        $this->template->title   = "Login";

        # Set the body background color
        $this->template->middle_color = "honeydew";

        # Render the view
        echo $this->template;
    }

    /*-------------------------------------------------------------------------------------------------
      Process the login form inputs    
    -------------------------------------------------------------------------------------------------*/
    public function p_login() {

        # Verify that none of the input form fields are blank
        if (empty($_POST["email"]) || empty($_POST["password"]))
        {
            # If so, display an error message and refresh the form
            $_SESSION['userMsg']="Please fill in all the fields.";
            $_SESSION['userMsgClass']='danger';
            Router::redirect("/users/login");
        }

        # encrypt the email with the password salt
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        # Search the DB for the email and password
        $q =
            'SELECT token
            FROM users
            WHERE email = "'.$_POST['email'].'"
            AND password = "'.$_POST['password'].'"
            ';

        # Retrieve the token if it's available
        $token = DB::instance(DB_NAME)->select_field($q);

        # If token is found in the user DB, login has succeeded
        if ($token) 
        {
            /*  Store the user token into a cookie 
                p1 = cookie name
                p2 = cookie value
                p3 = expiration (2 weeks from now)
                p4 = path (single slash sets it for the entire domain)
            */
            setcookie("token", $token, strtotime('+2 weeks'), '/');

            # Search the DB for the email
            $q =
                'SELECT *
                FROM users
                WHERE email = "'.$_POST['email'].'"
                ';

            # Retrieve the user_id if the email exists in the DB
            $this->user = DB::instance(DB_NAME)->select_field($q);

            # Advance to home page
            Router::redirect("/");
        }

        # No matching token found in DB, login fails
        else 
        {
            # Set the error message field
            $_SESSION['userMsg'] = "Log in failed. Please try again!";
            $_SESSION['userMsgClass']='danger';

            # Refresh the form
            Router::redirect("/users/login");
       }
    }

    /*-------------------------------------------------------------------------------------------------
      Log the user out and return to the home page (/index/index)
    -------------------------------------------------------------------------------------------------*/
    public function logout() {

        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

        # Create the data array we'll use with the update method
        # In this case, we're only updating one field, so our array only has one entry
        $data = Array("token" => $new_token);

        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

        # Delete their token cookie by setting it to a date in the past - effectively logging them out
        setcookie("token", "", strtotime('-1 year'), '/');

        # Send them back to the main index page
        Router::redirect("/");
    }

    /*-------------------------------------------------------------------------------------------------
      Display the user's profile page   
    -------------------------------------------------------------------------------------------------*/
    public function profile($user_name = NULL) {

        # If user is not logged in, redirect to the login page
        if(!$this->user) 
        {
            Router::redirect('/users/login');
        }

        # If they are logged in, setup their view of the page
        else 
        {
            $this->template->content = View::instance('v_users_profile');
            $this->template->user = $this->user;

            # Set the <middle_color> tag for the body background color
            $this->template->middle_color = "honeydew";
        
            $this->template->title   = "Profile";

            # Render the page
            echo $this->template;
        }
    }

    /*-------------------------------------------------------------------------------------------------
      Display the profile edit page   
    -------------------------------------------------------------------------------------------------*/
    public function edit_profile($user_name = NULL) {

        # If user is not logged in, redirect to the login page
        if(!$this->user) 
        {
            Router::redirect('/users/login');
        }

        # If they are logged in, setup their view of the page
        else 
        {
            $this->template->content = View::instance('v_users_edit_profile');
            $this->template->user = $this->user;

            # Set the <middle_color> tag for the body background color
            $this->template->middle_color = "honeydew";
        
            $this->template->title   = "Edit Profile";

            # Render the page
            echo $this->template;
        }
    }

    /*-------------------------------------------------------------------------------------------------
      Process the profile edit form inputs    
    -------------------------------------------------------------------------------------------------*/
    public function p_edit_profile() {
        
        # Verify that none of the input form fields are blank
        if (empty($_POST["email"]) || empty($_POST["password"]) ||
          empty($_POST["first_name"]) || empty($_POST["last_name"]))
        {
            # If so, display an error message and refresh the form
            $_SESSION['userMsg']="Please fill in all the fields (password is optional).";
            $_SESSION['userMsgClass']='danger';
            Router::redirect("/users/edit_profile");
        }

        # Search the DB for the email
        $q =
            'SELECT user_id
            FROM users
            WHERE email = "'.$_POST['email'].'"
            ';

        # Verify that the email is not already being used elsewhere
        $user_id = DB::instance(DB_NAME)->select_field($q);

        # If email is found in the user DB, display an error
        if (($user_id) && ($user_id != $this->user->user_id))
        {
            # Set the user message field to notify the user of success
            $_SESSION['userMsg'] = "That email is already being used!";
            $_SESSION['userMsgClass']='danger';
            
            # Redirect to the login page (user must still log in after sign up)
            Router::redirect('/users/edit_profile');
        }

        # Verify user is logged in
        if ($this->user->user_id)
        {
           # Set up a query to update the modified time in the user's profile
            $_POST['modified'] = Time::now();
            $condition = 'WHERE user_id = '.$this->user->user_id;

            # If password was unchanged, restore old one
            if ($_POST['password'] == "********")
            {
                $_POST['password'] = $this->user->password;
            }

            # Else encrypt the new one
            else
            {
                # Salt the password and hash it
                $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
            }
            
            # Update the user's clear field in the users table
            DB::instance(DB_NAME)->update('users', $_POST, $condition);
        }

        # Set the user message field to notify the user of success
        $_SESSION['userMsg'] = "You've successfully edited your profile!";
        $_SESSION['userMsgClass']='success';
            
        # Redirect to the login page (user must still log in after sign up)
        Router::redirect('/users/profile');
    }

} # end of the class