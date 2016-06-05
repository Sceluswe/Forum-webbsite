<?php

namespace Mos\DiceGame;

class CDiceGame 
{
	private $turn;
	private $playerScore;
	
	 public function __construct($turn) 
	{
		$this->turn = $turn;
	}

	public function StartGame()
	{
		$returnValue = "<h2>Välkommen till tärningsspelet.</h2>
		<p><a href='?roll'>Starta spelet?</a></p>";
		
		$this->playerScore = 0;
		$this->turn->ResetTurnScore();
		
		return $returnValue;
	}
	
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
	
	public function RestartGame()
	{
		$returnValue = "<h3>Du har valt att börja om</h3><p><a href='?roll'>Börja!</a></p>";
		$this->playerScore = 0;
		$this->turn->ResetTurnScore();
		
		return $returnValue;
	}
}