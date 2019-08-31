<?php
namespace Anax\Forum;

/**
 * Model for Questions.
 *
 * Contains interactions with the database.
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TForumModel;



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
}
