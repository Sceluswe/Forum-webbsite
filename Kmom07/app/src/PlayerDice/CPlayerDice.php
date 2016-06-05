<?php
/**
* A CDice class to play around with a dice.
*
*/

namespace Mos\PlayerDice;

class CPlayerDice
{
	/**
	* Properties
	*
	*/
	private $value;
	/**
	* Constructor
	*
	*/
	public function __construct() 
	{
	;
	}
	/**
	* Roll the dice
	*
	*/
	public function Roll()
	{
		$this->value = rand(1, 6);
	}
	/**
	* Get the value
	*
	*/
	public function GetValue()
	{
		return $this->value;
	}
}