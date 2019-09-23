<?php

namespace Mos\PlayerDice;

/**
* A CDice class to play around with a dice.
*/
class CPlayerDice
{



	/**
	* Properties
	*/
	private $value;



	/**
	* Constructor
	*/
	public function __construct()
	{

	}



	/**
	* Rolls the dice
	*/
	public function Roll()
	{
		$this->value = rand(1, 6);
	}



	/**
	* Get the value
	*
    * @return integer, the rolled value of the dice.
	*/
	public function GetValue()
	{
		return $this->value;
	}
}
