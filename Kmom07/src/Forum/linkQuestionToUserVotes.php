<?php
namespace Anax\Forum;

/**
* Model for linking questions and user votes link table.
*
*/
class linkQuestionToUserVotes extends \Anax\Forum\ACUserVotes
{



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasNotVoted($qacId, $userId)
    {
        return empty($this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId]));
    }



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasVoted($qacId, $userId)
    {
        return !empty($this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId]));
    }



    /**
    * Checks if a user has voted on a question.
    *
    * @param string, the id of the question.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function addUserVote($qacId, $userId)
    {
        return $this->create([
            "questionId"    => $qacId,
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
    public function removeUserVote($qacId, $userId)
    {
        $row = $this->query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId])[0];

        return $this->delete($row->id);
    }
}
