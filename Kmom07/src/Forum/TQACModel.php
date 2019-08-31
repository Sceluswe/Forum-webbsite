<?php
namespace Anax\Forum;

trait TQACModel {



    /**
    * Calculates the sum of an array of objects rating property.
    *
    * @param array of objects, the objects containing the property.
    *
    * @return int, the sum of all ratings.
    */
    public function calculateScore($userid)
    {
        $arrObj = parent::findByColumn("userid", $userid);
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
