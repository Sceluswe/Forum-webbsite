<?php
namespace Anax\Forum;

/**
 * Model for Answers.
 *
 * Contains interactions with the database.
 */
class Answer extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TForumModel,
        \Anax\Forum\TQACModel;



    /**
    * Find rows by column questionid.
    *
    * @param string, $questionid the questionid of the tag.
    *
    * @return array, returns a resultset.
    */
    public function findByQuestionId($questionid)
    {
        return $this->query()->where("questionid = ?")->execute([$questionid]);
    }
}
