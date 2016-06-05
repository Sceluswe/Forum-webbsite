<?php
namespace Anax\Forum;

/**
 * Model for Users.
 * 
 * Contains interactions with the database.
 */
class Question extends \Anax\MVC\CDatabaseModel
{
	/*
	* Set table to store question in.
	*
	* @param string, table to use in the database. 
	*
	* @return string.
	*/
	public function setSource($key)
	{
		$this->session->set('question', strtolower($key));
	}
	
	/*
	* Get table to store question in from session.
	*
	* @return string.
	*/
	public function getSource()
	{
		return $this->session->get('question');
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