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
}
