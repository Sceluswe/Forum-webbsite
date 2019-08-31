<?php
namespace Anax\Forum;

trait TForumModel {



    /*
	* Set route to redirect to in session.
	*
	* @param string, route to redirect to.
	*
	* @return string.
	*/
	public function setRedirect($key)
	{
		$this->session->set('redirect' + parent::getSource(), $key);
	}



	/*
	* Get route to redirect to from session.
	*
	* @return string.
	*/
	public function getRedirect()
	{
		return $this->session->get('redirect' + parent::getSource());
	}



    /**
    * Calculates the sum of an array of objects rating property.
    *
    * @param array of objects, the objects containing the property.
    *
    * @return int, the sum of all ratings.
    */
    public function calculateScore($userid)
    {
        $arrObj = parent::findByColumn("userid", $userid);
        $count = count($arrObj);
        $ratingSum = 0;

        foreach($arrObj as $item)
        {
            $ratingSum += $item->rating;
        }

        return [
            "count" => $count,
            "rating" => $ratingSum,
            "sum" => $count + $ratingSum
        ];
    }
}
