<?php
namespace Anax\Forum;

/**
* Trait for shared questions(q), answers(a) and comments(c) functionality.
*
* Contains interactions with the database.
*/
trait TQACModel {



    /**
    * Find correct q, a or c row and update its rating with input number.
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
    * Find q, a or c by column userId.
    *
    * @param string, the id number of the q a or c rows to find.
    *
    * @return array with resultset.
    */
    public function findByUserId($userId)
    {
        return parent::query()->where("userid = ?")->execute([$userId]);
    }



    /**
    * Calculate: total nr of q/a/c, respective rating and total score.
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
