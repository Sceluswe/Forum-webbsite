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
        'me'  => [
            'text'  => 'Me',
            'url'   => $this->di->get('url')->create('Theme'),
            'title' => 'Me-page',
        ],
		// This is a menu item
        'Regioner'  => [
            'text'  => 'Regioner',
            'url'   => $this->di->get('url')->create('Regioner'),
            'title' => 'Regioner',
        ],
		//This is a menu item.
		'Fa Test' => [
			'text'	=> 'Fa Test',
			'url'	=> $this->di->get('url')->create('FATest'),
			'title' => 'Fa Test',
		],
		//This is a menu item.
		'Rutnat' => [
			'text'	=> 'Rutnät',
			'url'	=> $this->di->get('url')->create('Rutnat'),
			'title' => 'Rutnat',
		],
		//This is a menu item.
		'Typografi' => [
			'text'	=> 'Typografi',
			'url'	=> $this->di->get('url')->create('Typografi'),
			'title' => 'Typografi',
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
