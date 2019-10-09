<?php
namespace Anax\Forum;

/**
* Model for questions and user votes link table.
*
* Contains interactions with the database.
*/
class linkQuestionToUserVotes extends \Anax\MVC\CDatabaseModel
{



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasNotVoted($questionId, $userId)
    {
        return empty($this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$questionId, $userId]));
    }



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasVoted($questionId, $userId)
    {
        return !empty($this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$questionId, $userId]));
    }



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function addUserVote($questionId, $userId)
    {
        return $this->create([
            "questionId"    => $questionId,
            "userId"        => $userId
        ]);
    }



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function removeUserVote($questionId, $userId)
    {
        $row = $this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$questionId, $userId])[0];

        return $this->delete($row->id);
    }
}
