<?php
namespace Anax\Forum;

/**
 * Model for Users.
 *
 * Contains interactions with the database.
 */
class Tag extends \Anax\MVC\CDatabaseModel
{



    /**
    * Find rows by column name.
    *
    * @param string, $name the name of the tag.
    *
    * @return array, returns a resultset.
    */
    public function findByName($name)
    {
        return $this->query()->where("name = ?")->execute([$name]);
    }

    public function getPopularTags()
    {
        return $this->query("name, COUNT(1) AS num")
            ->groupBy("name")
            ->orderBy("num DESC LIMIT 12")
            ->execute();
    }
}
