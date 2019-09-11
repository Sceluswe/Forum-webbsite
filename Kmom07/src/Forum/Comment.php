<?php
namespace Anax\Forum;

/**
 * Model for Comments.
 *
 * Contains interactions with the database.
 */
class Comment extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TForumModel,
        \Anax\Forum\TQACModel;



    /**
    * Find the comments belonging to a question.
    *
    * @param int, the database ID of the question.
    *
    * @return array, the resultset containing the questions comments.
    */
	public function findQuestionComments($id)
	{
		return $this->query()->where("commentparent='Q'")
            ->andWhere("qaid = ?")
            ->execute([$id]);
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
		return $this->query()->where("commentparent='A'")
            ->andWhere("qaid = ?")
            ->execute([$id]);
	}
}
