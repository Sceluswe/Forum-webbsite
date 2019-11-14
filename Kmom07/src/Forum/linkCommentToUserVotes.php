<?php
namespace Anax\Forum;

/**
* Model for linking comments and user votes link table.
*
*/
class linkCommentToUserVotes extends \Anax\Forum\ACUserVotes
{



    /**
    * Checks if a user has voted on a comment.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasNotVoted($qacId, $userId)
    {
        return empty($this->query()->where('commentId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId]));
    }



    /**
    * Checks if a user has voted on a comment.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function userHasVoted($qacId, $userId)
    {
        return !empty($this->query()->where('commentId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId]));
    }



    /**
    * Checks if a user has voted on a comment.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    * @param integer, the type of vote placed (1 or -1).
    *
    * @return boolean, true or false.
    */
    public function addUserVote($qacId, $userId, $voteType)
    {
        return $this->create([
            "commentId"    => $qacId,
            "userId"       => $userId,
            "voteType"     => $voteType
        ]);
    }



    /**
    * Checks if a user has voted on a comment.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    *
    * @return boolean, true or false.
    */
    public function removeUserVote($qacId, $userId)
    {
        $row = $this->query()->where('commentId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId])[0];

        return $this->delete($row->id);
    }



    /**
    * Get the voteType of a placed vote.
    *
    * @param string, the id of the comment.
    * @param string, the id of the user to look for.
    *
    * @return integer, the number indicating the type of vote placed.
    */
    public function getVoteType($qacId, $userId)
    {
        $row = $this->query()->where('commentId = ?')
            ->andWhere('userId = ?')
            ->execute([$qacId, $userId])[0];

        return $row->voteType;
    }
}
