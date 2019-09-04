<?php
namespace Anax\Forum;

/**
 * Model for Questions.
 *
 * Contains interactions with the database.
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TForumModel,
        \Anax\Forum\TQACModel;



    /**
    * Find rows by column userid.
    *
    * @param string, $userid the userid of the tag.
    *
    * @return array, returns a resultset.
    */
    public function findByUserId($userid)
    {
        return $this->query()->where("userid = ?")->execute([$userid]);
    }



	/**
	*	Save the previous question in session.
	*/
	public function setQuestionId($question)
	{
		$this->session->set('savedQuestion', htmlentities($question));
	}



	/**
	*	Get the previous object from session.
	*/
	public function getQuestionId()
	{
		return $this->session->get('savedQuestion');
	}



    /**
	*	Get the 6 most recent questions.
    *
    *   @return array with resultset.
	*/
    public function getRecentQuestions()
    {
        return $this->query()->orderBy('timestamp DESC LIMIT 6')->execute();
    }
}
