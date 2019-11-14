<?php
namespace Anax\Forum;

abstract class ACUserVotes extends \Anax\MVC\CDatabaseModel
{
    abstract public function userHasNotVoted($qacId, $userId);
    abstract public function userHasVoted($qacId, $userId);
    abstract public function addUserVote($qacId, $userId);
    abstract public function removeUserVote($qacId, $userId);



    /**
    * Checks if a user has voted on a comment.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    *
    * @return void.
    */
    public function updateUserVote($qacId, $userId)
    {
        $bool = false;
        if($this->userHasNotVoted($qacId, $userId))
        {
            $this->addUserVote($qacId, $userId);
        }
        elseif($this->userHasVoted($qacId, $userId))
        {
            $this->removeUserVote($qacId, $userId);
        }

        return $bool;
    }
}
