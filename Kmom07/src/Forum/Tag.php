<?php
namespace Anax\Forum;

/**
 * Model for Users.
 *
 * Contains interactions with the database.
 */
class Tag extends \Anax\MVC\CDatabaseModel
{
    use \Anax\Forum\TForumModel;

    public function getPopularTags()
    {
        return $this->query('name, COUNT(1) AS num')
            ->groupBy('name')
            ->orderBy('num DESC LIMIT 12')
            ->execute();
    }
}
