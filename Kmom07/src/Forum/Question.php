<?php
namespace Anax\Forum;

/**
 * Model for Questions.
 *
 * Contains interactions with the database.
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TQACModel;



    /**
    * Save the previous object in session.
    *
    * @return void.
    */
    public function setQuestionId($question)
    {
        $this->session->set('savedQuestion', htmlentities($question));
    }



    /**
    * Get the previous object from session.
    *
    * @return object, the object stored in session.
    */
    public function getQuestionId()
    {
        return $this->session->get('savedQuestion');
    }



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
    * Get the 6 most recent questions.
    *
    * @return array with resultset.
    */
    public function getRecentQuestions()
    {
        return $this->query()->orderBy('timestamp DESC LIMIT 6')->execute();
    }
}
