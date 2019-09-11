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
    * Finds one row by column name (since name is UNIQUE) and load it into the module.
    *
    * @param string, $name the unique name of the tag.
    *
    * @return array, returns a resultset.
    */
    public function findByName($name)
    {
        $this->query()->where("name = ?");
        $this->db->execute([$name]);
        return $this->db->fetchInto($this);
    }

    public function getPopularTags()
    {
        return $this->query("name, COUNT(1) AS num")
            ->groupBy("name")
            ->orderBy("num DESC LIMIT 12")
            ->execute();
    }
}
