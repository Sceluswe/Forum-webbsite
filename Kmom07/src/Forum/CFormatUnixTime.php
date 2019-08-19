<?php
namespace Anax\Forum;

/**
 * To format unix time into human readable format.
 *
 */
class CFormatUnixTime
{
    /**
    * Function to format the unix timestamp property of one object.
    *
    * @param array, object with timestamps to be converted.
    *
    * @return array, array of human readable timestamps.
    */
    function formatUnixProperty($obj)
    {
        $obj->timestamp = $this->humanUnixTime($obj->timestamp);
        return $obj;
    }



    /**
    * Function to format the unix timestamp property of several objects.
    *
    * @param array, array of objects with timestamps to be converted.
    *
    * @return array, array of human readable timestamps.
    */
    function formatUnixProperties($arrOfObj)
    {
        foreach($arrOfObj as $item)
        {
            $item->timestamp = $this->humanUnixTime($item->timestamp);
        }

        return $arrOfObj;
    }



    /**
    * Format a unix timestamp to display its age (5 days ago, 1 day ago, just now etc.).
    *
    * @param int, unix timestamp.
    *
    * @return string, a unix timestamp in human readable format.
    */
    public function humanUnixTime($timestamp)
    {
        $elapsedtime;
        $ret = array();
        $secs = time() - $timestamp;
        if($secs == 0)
        {
            $elapsedtime = "Just now.";
        }
        else
        {
            $bit = array(
                'y' => $secs / 31556926 % 12,
                'd' => $secs / 86400 % 7,
                'h' => $secs / 3600 % 24,
                'm' => $secs / 60 % 60,
                's' => $secs % 60
                );

            foreach($bit as $k => $v)
            {
                if($v > 0)
                {
                    $ret[] = $v . $k;
                }
            }

            $elapsedtime = join(' ', $ret);
        }

        return $elapsedtime;
    }
}
