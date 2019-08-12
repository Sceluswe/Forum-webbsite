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
    *
    * @return void.
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
    *
    * @return void.
	*/
    public function createRedirect($redirectAdress)
    {
        // Create the URL to redirect to.
        $url = $this->url->create($redirectAdress);
        // Redirect user to URL.
        $this->response->redirect($url);
    }
    
    /**
	* Format a unix timestamp to display its age (5 days ago, 1 day ago, just now etc.).
	*
	* @param int, unix timestamp.
    *
	* @return string, a unix timestamp in human readable format.
	*/
	public function humanUnixTime($timestamp)
	{
		$elapsedtime;
		$ret = array();
		$secs = time() - $timestamp;
		if($secs == 0)
		{
			$elapsedtime = "Just now.";
		}
		else
		{
			$bit = array(
				'y' => $secs / 31556926 % 12,
				'd' => $secs / 86400 % 7,
				'h' => $secs / 3600 % 24,
				'm' => $secs / 60 % 60,
				's' => $secs % 60
				);
				
			foreach($bit as $k => $v)
			{
				if($v > 0)
				{
					$ret[] = $v . $k;
				}
			}
			
			$elapsedtime = join(' ', $ret);
		}
			
		return $elapsedtime;
    }
}
