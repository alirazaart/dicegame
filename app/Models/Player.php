<?php

namespace App\Models;

class Player
{
    public $name;
    protected $diceCup;
    protected $diceCountToAddInCup;

    public function __construct($name, DiceCup $diceCup)
    {
        $this->name = $name;
        $this->diceCup = $diceCup;
        $this->diceToAddInCup = collect([]);
    }

    public function getDiceCup()
    {
        return $this->diceCup;
    }

    public function setDiceCup(DiceCup $diceCup)
    {
        $this->diceCup = $diceCup;
    }

    public function setDiceToAddInCup($diceToAddInCup)
    {
        $this->diceToAddInCup = $diceToAddInCup;
    }

    public function getDiceToAddInCup()
    {
        return $this->diceToAddInCup;
    }
}
