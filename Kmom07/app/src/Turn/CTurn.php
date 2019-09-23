<?php

namespace Mos\Turn;

/**
* Class for one game round (turn).
*/
class CTurn
{



    /**
    * Properties
    */
    private $PlayerDice;
	private $turnScore;



    /**
	* Constructor
	*
	*/
	public function __construct($playerDice)
	{
		$this->PlayerDice = $playerDice;
	}



	/**
	* Do one roll for this turn.
	*
    * @return string, the HTML code for everything that happened this turn.
	*/
	public function doRoll()
	{
		$returnValue = "";
		$this->PlayerDice->Roll();

		$roll = $this->PlayerDice->GetValue();

		if($roll == 1)
		{
			$this->turnScore = 0;
			$returnValue .= "<h3>Du förlorade alla dina poäng! Börja en ny runda.</h3>";
		}
		else
		{
			$this->turnScore = $this->turnScore + $roll;
			$returnValue .= "<p>Kom ihåg att om du slår en 1:a förlorar du alla insammlade poäng!</p>";
		}

		$returnValue .=  "<p>Du slog en:</p> <ul class='dice'><li class='dice-" . $roll . "'></li></ul>";
		$returnValue .= "<p>Dina poäng för denna runda är: {$this->turnScore}</p>";

		return $returnValue;
	}



    /**
	* A blackjack game with dice.
	*
    * @return string, the HTML code for everything that happened this turn.
	*/
	public function play21()
	{
		$returnValue = "";
		if(isAuthenticated())
		{
			$this->PlayerDice->Roll();

			$this->turnScore = $this->turnScore + $this->PlayerDice->GetValue();

			$returnValue .= "<p>You rolled: {$this->PlayerDice->GetValue()}</p>";

			if($this->turnScore > 21)
			{
				$returnValue .= "<p class='red'>You lost, your score exceeded 21. Try again!</p>";
				$this->turnScore = 0;
			}

			if($this->turnScore == 21)
			{
				$this->turnScore = 0;

				$returnValue .= "<p class='green'>You have won! 5 points added to your account.</p>";
			}

			$returnValue .= "<p>Your current score is: {$this->turnScore}</p>";
		}
		else
		{
			$returnValue .= "<p class='red'>Login or create an account to play.</p>";
		}

		return $returnValue;
	}



	/**
	* Get the turnScore
	*
    * @return integer, the score gained this turn.
	*/
	public function GetTurnScore()
	{
		return $this->turnScore;
	}



    /**
	* Reset the score gained this turn. (Used to reset the turn object).
	*/
	public function ResetTurnScore()
	{
		$this->turnScore = 0;
	}
}
