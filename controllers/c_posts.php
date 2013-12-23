<?php

/*-------------------------------------------------------------------------------------------------
  Posts Controller class - for post management
  Includes Post Viewing features
-------------------------------------------------------------------------------------------------*/
class posts_controller extends base_controller {
        
    /*-------------------------------------------------------------------------------------------------
      Construct creates an instance of a post object   
    -------------------------------------------------------------------------------------------------*/
    public function __construct() {
                
        # Make sure the base controller construct gets called
        parent::__construct();
                
        # Only let logged in users access the methods in this controller
        if(!$this->user) {
            die("Members only");
        }      
    } 
         
    /*-------------------------------------------------------------------------------------------------
      Index / All aSkitter is the default controller method
      Display all posts - This is the main viewing page for posts
    -------------------------------------------------------------------------------------------------*/
    public function index() {
                
        # Set up view
        $this->template->content = View::instance('v_posts_index');

        # Set the body background color
        $this->template->middle_color = "honeydew";
                
        # Get the last cleared timestamp from the users DB
        $q =
            'SELECT cleared
                FROM users
                WHERE user_id = "'.$this->user->user_id.'"
            ';

        # Run query        
        $cleared = DB::instance(DB_NAME)->select_field($q);

        # Set up query and order by post modification date, latest first
        $q = 'SELECT 
                posts.content,
                posts.created,
                posts.user_id AS post_user_id,
                users_users.user_id AS follower_id,
                users.first_name,
                users.last_name
                FROM posts
                    INNER JOIN users_users 
                        ON posts.user_id = users_users.user_id_followed
                        INNER JOIN users 
                            ON posts.user_id = users.user_id
                                WHERE users_users.user_id = '.$this->user->user_id.
                                ' AND posts.modified > '.$cleared.
                                ' ORDER BY posts.modified DESC';

        # Run query        
        $posts = DB::instance(DB_NAME)->select_rows($q);
                
        # Pass $posts array to the view
        $this->template->content->posts = $posts;
                
        # Render view
        echo $this->template;

    }
        
    /*-------------------------------------------------------------------------------------------------
      Clear all posts
        
        NOTE: Only the Administrator (user_id=0) can erase the entire post table.
        Individual users can only do a local erase which will appear to clear the table.

        However, we cannot truly erase the posts, as other users may want to view them.
        What we essentially do is hide all posts that were created prior to the 
        last cleared time from that individual user's view.

        The Administrator user_id must be set to 0 by the DB admin.
        It cannot be created by a normal sign-up (for security reasons).

    -------------------------------------------------------------------------------------------------*/
    public function clear() {
                
        # For admins (user_id = 0)
        if ($this->user->user_id == 0) {

            # Clear out all contents of the Posts DB 
            #    (but don't DELETE the table structure)
            DB::instance(DB_NAME)->query('TRUNCATE TABLE posts');            
        }

        # For everyone who is not an admin
        else {

            # Hide all contents of the Posts DB prior to the current time
            # by setting the clear time in the user's profile

            # Set up a query to update the clear time in the user's profile
            $_POST['cleared'] = Time::now();
            $condition = 'WHERE user_id = '.$this->user->user_id;

            # Update the user's clear field in the users table
            DB::instance(DB_NAME)->update('users', $_POST, $condition);
        }

        # Redisplay the posts listing
        Router::redirect('/posts/');
            
    }

    /*-------------------------------------------------------------------------------------------------
      Display a form to add new posts
    -------------------------------------------------------------------------------------------------*/
    public function add() {
                
        # Set up the view for adding posts
        $this->template->content = View::instance("v_posts_add");

        # Set the body background color
        $this->template->middle_color = "seashell";
                
        # Render the page
        echo $this->template;

    }        
        
    /*-------------------------------------------------------------------------------------------------
      Process new posts
    -------------------------------------------------------------------------------------------------*/
    public function p_add() {
                
        # Set up the post parameters to add to the DB
        $_POST['user_id']  = $this->user->user_id;
        $_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();
                
        # Add the post into the post DB
        DB::instance(DB_NAME)->insert('posts',$_POST);  

        # Redirect to the post display page    
        Router::redirect('/posts/');
    }

    /*-------------------------------------------------------------------------------------------------
      View all users with their followed states
    -------------------------------------------------------------------------------------------------*/
    public function users() {
                
        # Create a viewing template
        $this->template->content = View::instance("v_posts_users");

        # Set the body background color
        $this->template->middle_color = "azure";
                
        # Set up a query to get all users from the users table
        $q = 'SELECT *
            FROM users';
                        
        # Get all users
        $users = DB::instance(DB_NAME)->select_rows($q);
                
        # Set up a query to get all connections from the users_users table
        $q = 'SELECT *
            FROM users_users
            WHERE user_id = '.$this->user->user_id;
                        
        # Get the connections between users
        $connections = DB::instance(DB_NAME)->select_array($q,'user_id_followed');
                
        # Set up the view template with all users and connections
        # NOTE: Add 'myself' so the template can suppress displaying my own user id
        $this->template->content->myself      = $this->user->user_id;
        $this->template->content->users       = $users;
        $this->template->content->connections = $connections;
                
        # Render the view
         echo $this->template;          
    
    }
        
    /*-------------------------------------------------------------------------------------------------
      Create a follow between the current user and a specified user to follow
    -----------------------------------------------------------------------------------------------*/
    public function follow($user_id_followed) {
        
        # Set up the entry with both follower and followee user ids
        $data = Array(
            "created"          => Time::now(),
            "user_id"          => $this->user->user_id,
            "user_id_followed" => $user_id_followed
        );
        
        # Insert the entry into the users_users table
        DB::instance(DB_NAME)->insert('users_users', $data);
        
        # Redirect to the users viewing list
        Router::redirect("/posts/users");
    
    }
                
    /*-------------------------------------------------------------------------------------------------
      Remove the follow between the current user and a specified followed user
    -------------------------------------------------------------------------------------------------*/
    public function unfollow($user_id_followed) {
        
        # Create the query to find the entry by both follower and followee user ids
        $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
            
        # Delete the entry from the users_users table
        DB::instance(DB_NAME)->delete('users_users', $where_condition);
        
        # Redirect to the users viewing list
        Router::redirect("/posts/users");
        
    }

} # eoc