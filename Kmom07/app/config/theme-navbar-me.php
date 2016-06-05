<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu structure
    'items' => [
		// This is a menu item
        'home'  => [
			'FA'	=> 'fa fa-home',
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create('Home'),
            'title' => 'Home',
        ],
		// This is a menu item
        'questions'  => [
			'FA'	=> 'fa fa-file-text',
            'text'  => 'Questions',
            'url'   => $this->di->get('url')->create('Questions'),
            'title' => 'Questions',
        ],
		// This is a menu item
        'tag'  => [
			'FA' 	=> 'fa fa-trophy',
            'text'  => 'Tag',
            'url'   => $this->di->get('url')->create('Tag'),
            'title' => 'Tag',
        ],
		// This is a menu item
        'login'  => [
			'FA' 	=> 'fa fa-trophy',
            'text'  => 'Login/Logout',
            'url'   => $this->di->get('url')->create('Login'),
            'title' => 'Login/Logout',
        ],
		// This is a menu item
        'users'  => [
			'FA'	=> 'fa fa-users',
            'text'  => 'Users',
            'url'   => $this->di->get('url')->create('Users'),
            'title' => 'Users',
        ],
		// This is a menu item
        'about'  => [
			'FA'	=> 'fa fa-users',
            'text'  => 'About',
            'url'   => $this->di->get('url')->create('About'),
            'title' => 'About',
        ],
    ],

    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) 
	{
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) 
	{
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
