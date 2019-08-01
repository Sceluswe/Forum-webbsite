<?php

namespace Anax\Utility;

/**
 * Class containing functions for commonly occuring code.
 *
 */
class CUtility implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;
        
	/**
	* Render a default page with title and content.
	*
	* @param, string, the title to display on the default page.
    * @param, string, a string containing HTML to display on the default page. 
	*/
    public function renderDefaultPage($title, $content)
    {
        $this->theme->setTitle($title);
		$this->views->add('default/page', [
            'title' => $title,
			'content' => $content
        ]);
    }
    
    /**
	* Creates a URL and redirects the user to it.
	*
	* @param, string, the final part of the adress.
	*/
    public function createRedirect($redirectAdress)
    {
        // Create the URL to redirect to.
        $url = $this->url->create($redirectAdress);
        // Redirect user to URL.
        $this->response->redirect($url);
    }
}
