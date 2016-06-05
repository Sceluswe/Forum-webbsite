<?php
/**
 * Bootstrapping functions, essential and needed for Anax to work together with some common helpers. 
 *
 */



/**
 * Utility for debugging.
 *
 * @param mixed $array values to print out
 *
 * @return void
 */
function dump($array) 
{
    echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}



/**
 * Sort array but maintain index when compared items are equal.
 * http://www.php.net/manual/en/function.usort.php#38827
 *
 * @param array    &$array       input array
 * @param callable $cmp_function custom function to compare values
 *
 * @return void
 *
 */
function mergesort(&$array, $cmp_function) 
{
    // Arrays of size < 2 require no action.
    if (count($array) < 2) return;
    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);
    // Recurse to sort the two halves
    mergesort($array1, $cmp_function);
    mergesort($array2, $cmp_function);
    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmp_function, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }
    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmp_function, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        } else {
            $array[] = $array2[$ptr2++];
        }
    }
    // Merge the remainder
    while ($ptr1 < count($array1)) $array[] = $array1[$ptr1++];
    while ($ptr2 < count($array2)) $array[] = $array2[$ptr2++];
    return;
}

/**
* Format a unix timestamp to display its age (5 days ago, 1 day ago, just now etc.).
*
* @param   int     $timestamp,  unix timestamp
* @return  string
*/
function elapsedTime($timestamp) 
{
	$elapsedTime = ""; // returnvalue
	
	$time = time() - $timestamp;
	$years = ($time / 31556926) >= 1 ? floor($time / 31556926) : 0;
	if($years > 1)
	{
		$time = $time - $years * 31556926;
		$elapsedTime .= "{$years} years ";
	}
	else if($years == 1)
	{
		$time = $time - 31556926;
		$elapsedTime .= "{$years} year ";
	}
	
	$months = ($time / 2629743) >= 1 ? floor($time / 2629743) : 0;
	if($months > 1)
	{
		$time = $time - $months * 2629743;
		$elapsedTime .= "{$months} months";
	}
	else if($months == 1)
	{
		$time = $time - 2629743;
		$elapsedTime .= "{$months} month";
	}
	
	$days =	($time / 86400) >= 1 ? floor($time / 86400) : 0;
	if($days > 1)
	{
		$time = $time - $days * 86400;
		$elapsedTime .= "{$days} days ";
	}
	else if($days == 1)
	{
		$time = $time - 86400;
		$elapsedTime .= "{$days} day ";
	}
	
	$hours = floor($time / 3600) >= 1 ? floor($time / 3600) : 0;
	if($hours > 1)
	{
		$time = $time - $hours * 3600;
		$elapsedTime .= "{$hours} hours ";
	}
	else if($hours == 1)
	{
		$time = $time - 3600;
		$elapsedTime .= "{$hours} hour ";
	}
	
	$minutes = ($time / 60) >= 1 ? floor($time / 60) : 0;
	if($minutes > 1)
	{
		$elapsedTime .= "{$minutes} minutes ";
	}
	else if($minutes == 1)
	{
		$time = $time - 60;
		$elapsedTime .= "{$minutes} minute ";
	}
	
	if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0)
	{
		$elapsedTime = "Just now.";
	}
	else
	{
		$elapsedTime .= "ago.";
	}
	return $elapsedTime;
}
