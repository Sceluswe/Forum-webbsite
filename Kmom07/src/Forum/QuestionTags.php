<?php
namespace Anax\Forum;

/**
* Model for questions and tags link table.
*
* Contains interactions with the database.
*/
class QuestionTags extends \Anax\MVC\CDatabaseModel
{



    /**
    * Checks if a question has a tag.
    *
    * @param string, the id of the question.
    * @param string, the id of the tag to look for.
    *
    * @return boolean, true of false.
    */
    public function questionHasTag($questionId, $tagId)
    {
        return !empty($this->query()->where('questionId = ?')
            ->andWhere('tagId = ?')
            ->execute([$questionId, $tagId]));
    }



    /**
    * Checks if a question has a tag.
    *
    * @param string, the id of the tag to select by.
    *
    * @return array, a resultset.
    */
    public function selectByTag($tagId)
    {
        $source = $this->getSource();

        $this->db->select()->from("{$source}, Question")
            ->where("{$source}.tagId = ?")
            ->andWhere("{$source}.questionId = Question.id");

        $this->db->execute([$tagId]);
        return $this->db->fetchAll();
    }
}
