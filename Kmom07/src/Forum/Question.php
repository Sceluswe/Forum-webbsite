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

    public function selectByTag($tagId)
    {
        $this->db->select()
            ->from($this->getSource() . ", questions_tags")
            ->where("questions_tags.tagId = ?")
            ->andWhere("questions_tags.questionId = Question.id");

        $this->db->execute([$tagId]);
        return $this->db->fetchAll();
    }
}
