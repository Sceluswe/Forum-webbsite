<?php
namespace Anax\Forum;

/**
 * Model for Users.
 *
 * Contains interactions with the database.
 */
class Comment extends \Anax\MVC\CDatabaseModel
{
	/*
	* Set route to redirect to in session.
	*
	* @param string, route to redirect to.
	*
	* @return string.
	*/
	public function setRedirect($key)
	{
		$this->session->set('redirectc', $key);
	}

	/*
	* Get route to redirect to from session.
	*
	* @return string.
	*/
	public function getRedirect()
	{
		return $this->session->get('redirectc');
	}

    /**
    * Find the comments belonging to a question.
    *
    * @param int, the database ID of the question.
    *
    * @return array, the resultset containing the questions comments.
    */
	public function findQuestionComments($id)
	{
		$this->query()->where("commentparent='Q'")->andWhere("qaid = ?");
		$result = $this->execute([$id]);

		return $result;
	}

    /**
    * Find the comments belonging to an answer.
    *
    * @param int, the database ID of the answer.
    *
    * @return array, the resultset containing the answers comments.
    */
	public function findAnswerComments($id)
	{
		$this->query()->where("commentparent='A'")->andWhere("qaid = ?");
		$result = $this->execute([$id]);

		return $result;
	}
}
