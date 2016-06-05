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
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Me-page',
        ],
		// This is a menu item
        'redovisning'  => [
            'text'  => 'Redovisning',
            'url'   => $this->di->get('url')->create('redovisning'),
            'title' => 'Redovisning',
        ],
		//This is a menu item.
		'dice' => [
			'text'	=> 'Dice',
			'url'	=> $this->di->get('url')->create('dice-menu'),
			'title' => 'Dice',
		],
		// This is a menu item
		'comments' 	=> [
			'text' 	=> 'Comments',
			'url' 	=> $this->di->get('url')->create('comments'),
			'title' => 'Comments',
		],
		// This is a menu item
        'source'  => [
            'text'  => 'Källkod',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Källkod',
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
