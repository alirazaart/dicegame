<?php

namespace App\Models;

class DiceCup
{
    protected $contents;

    public function __construct($diceCount)
    {
        $this->contents = collect([]);
        $this->fillDice($diceCount);
    }

    public function addMultipleDice($multipleDice)
    {
        $this->contents = $this->contents->merge($multipleDice);
    }

    public function addDice(Dice $dice)
    {
        $this->contents->push($dice);
    }

    public function fillDice($diceCount)
    {   
        $insertCount = 0;
        while ($insertCount < $diceCount) {
            $this->addDice(new Dice());
            $insertCount++;
        }
    }

    public function getAllDice()
    {
        $contents = $this->contents;
        $this->contents = collect([]);
        
        return $contents;
    }

    public function peekAtAllDice()
    {
        return $this->contents;
    }

    public function isEmptyCup()
    {
        return ($this->contents->count() == 0);
    }
}
