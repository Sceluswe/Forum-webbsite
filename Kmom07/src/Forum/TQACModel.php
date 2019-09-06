<?php
namespace Anax\Forum;

trait TQACModel {

    /**
    * Function finds the correct dataobject and updates its rating.
    *
    * @param object, the data in which the targeted dataobject exists.
    * @param string, the unique id of the row to use in the table/data.
    * @param string, a positive or negative number to add to the rating score.
    *
    * @return void.
    */
    public function editVote($id, $number)
    {
        // Update it with an increase of 1.
        parent::update([
            'rating' => parent::find($id)->rating + $number
        ]);
    }

    public function findByUserId($userId)
    {
        return parent::query()->where("userid = ?")->execute([$userId]);
    }

    /**
    * Calculates the sum of an array of objects rating property.
    *
    * @param array of objects, the objects containing the property.
    *
    * @return int, the sum of all ratings.
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
