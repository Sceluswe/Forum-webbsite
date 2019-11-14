<?php
namespace Anax\Forum;

/**
* Trait for shared questions(Q), answers(A) and comments(C) functionality.
*
* Contains interactions with the database.
*/
trait TQACModel
{



    /**
    * Find correct Q, A or C row and update its rating with input number.
    *
    * @param object, the data in which the targeted dataobject exists.
    * @param string, the unique id of the row to use in the table/data.
    * @param string, a positive or negative number to add to the rating score.
    *
    * @return void.
    */
    public function editVote($id, $number)
    {
        parent::update([
            'rating' => parent::find($id)->rating + $number
        ]);
    }



    /**
    * Find Q, A or C by column userId.
    *
    * @param string, the id number of the Q, A or C rows to find.
    *
    * @return array with resultset.
    */
    public function findByUserId($userId)
    {
        return parent::query()->where("userid = ?")->execute([$userId]);
    }



    /**
    * Calculate: total nr of Q/A/C, respective rating and total score.
    *
    * @param string, the user id to find by.
    *
    * @return int, all sums.
    */
    public function calculateScore($userId)
    {
        $arrObj = $this->findByUserId($userId);
        $count = count($arrObj);
        $ratingSum = 0;

        foreach($arrObj as $item)
        {
            $ratingSum += $item->rating;
        }

        return [
            "count" => $count,
            "rating" => $ratingSum,
            "sum" => $count + $ratingSum
        ];
    }
}
