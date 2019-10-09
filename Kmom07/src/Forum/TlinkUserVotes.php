<?php
namespace Anax\Forum;

/**
* Trait for shared questions(q), answers(a) and comments(c) functionality.
*
* Contains interactions with the database.
*/
trait TlinkUserVotes
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
        return empty(parent::query()->where('questionId = ?')
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
        return !empty(parent::query()->where('questionId = ?')
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
        return parent::create([
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
        $row = parent::query()->where('questionId = ?')
            ->andWhere('userId = ?')
            ->execute([$questionId, $userId])[0];

        return parent::delete($row->id);
    }
}
