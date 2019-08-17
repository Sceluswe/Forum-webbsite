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
    * Calculates the sum of an array of objects rating property.
    *
    * @param array of objects, the objects containing the property.
    *
    * @return int, the sum of all ratings.
    */
    public function ratingSum($arrObj)
    {
        $ratingSum = 0;

        foreach($arrObj as $item)
        {
            $ratingSum += $item->rating;
        }

        return $ratingSum;
    }
}
