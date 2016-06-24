<?php
namespace Anax\Forum;

/**
 * Model for Users.
 * 
 * Contains interactions with the database.
 */
class Question extends \Anax\MVC\CDatabaseModel
{
	/**
	*	Save the previous question in session.
	*/
	public function setQuestion($question)
	{
		$this->session->set('savedQuestion', htmlentities($question));
	}
	
	/**
	*	Get the previous object from session.
	*/
	public function getQuestion()
	{
		return $this->session->get('savedQuestion');
	}
	
	/*
	* Set route to redirect to in session.
	*
	* @param string, route to redirect to. 
	*
	* @return string.
	*/
	public function setRedirect($key)
	{
		$this->session->set('redirect', $key);
	}
	
	/*
	* Get route to redirect to from session.
	*
	* @return string.
	*/
	public function getRedirect()
	{
		return $this->session->get('redirect');	
	}
}