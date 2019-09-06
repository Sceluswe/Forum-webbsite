<?php
namespace Anax\Forum;

trait TQACModel {

    public function findByUserId($userId)
    {
        return $this->query()->where("userid = ?")->execute([$userId]);
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
