<?php

namespace Mos\DiceGame;

/**
* CDiceGame with dice game functionality.
*/
class CDiceGame
{
	private $turn;
	private $playerScore;



    /**
    * Constructor for CDiceGame
    *
    * @param object, the turn oject used in the game.
    */
	public function __construct($turn)
	{
		$this->turn = $turn;
	}



    /**
    * Creates a "start the game?" menu.
    *
    * @return string, string containing the menu in HTML code.
    */
	public function StartGame()
	{
		$returnValue = "<h2>Välkommen till tärningsspelet.</h2>
		<p><a href='?roll'>Starta spelet?</a></p>";

		$this->playerScore = 0;
		$this->turn->ResetTurnScore();

		return $returnValue;
	}



    /**
    * Rolls a die and returns new menu options to carry the game forward.
    *
    * @return string, string containing the forwarding menu in HTML code.
    */
	public function RollDice()
	{
		$returnValue = $this->turn->doRoll();

		$returnValue .= "<p><a href='?turn'>Spara runda?</a> Eller <a href='?roll'>Kasta igen?</a></p>";

		if($this->playerScore > 0)
		{
			$returnValue .= "<p>Din sammanlagda poäng är {$this->playerScore}</p>";
		}

		return $returnValue;
	}



    /**
    * Ends a game round and displays the total score.
    *
    * @return string, string containing the total score in HTML code.
    */
	public function EndTurn()
	{
		$returnValue = "";
		$newScore = $this->turn->GetTurnScore();
		$this->playerScore = $this->playerScore + $newScore;

		if($this->playerScore < 100)
		{
			$returnValue .= "<p>Din sammanlagda poäng är {$this->playerScore}</p><p>Du sammlade in: {$this->turn->GetTurnScore()} poäng denna runda.</p><p>Du har en bit kvar!</p> <p><a href='?roll'>Kasta igen?</a> Eller <a href='?restart'>Börja om?</p>";
		}
		else
		{
			$returnValue .= "<p>Din sammanlagda poäng är {$this->playerScore}</p><h3>Du har vunnit!</h3><a href='?roll'>Spela igen?</a>";
			$this->playerScore = 0;
		}

		$this->turn->ResetTurnScore();

		return $returnValue;
	}



    /**
    * Creates and returns a menu for starting the game over.
    *
    * @return string, string containing the restart menu in HTML code.
    */
	public function RestartGame()
	{
		$returnValue = "<h3>Du har valt att börja om</h3><p><a href='?roll'>Börja!</a></p>";
		$this->playerScore = 0;
		$this->turn->ResetTurnScore();

		return $returnValue;
	}
}
