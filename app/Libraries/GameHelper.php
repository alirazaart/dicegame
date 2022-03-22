<?php

namespace App\Libraries;

use App\Models\Game;
use \App\Models\DiceCup;
use \App\Models\Player;

class GameHelper
{
    public function initializeGame()
    {
        $game = Game::truncate();
        $players = $this->createPlayers([
            'Player 1',
            'Player 2',
            'Player 3',
            'Player 4',
        ]);

        $roundNumber = 1;
        $hasWinner = ['',false];
        $print ="<table border='1px' style='text-align: center'>";
        $print .=" <tr>";
        $print .="     <th></th>";
        $print .="     <th>Player 1</th>";
        $print .="     <th>Player 2</th>";
        $print .="     <th>Player 3</th>";
        $print .="     <th>Player 4</th>";
        $print .=" </tr>";
        while (!$hasWinner[1]) {
            $roundScoreSheet = [];

            # Run through all the players and get
            # their score for current round
                // $game = Game::truncate();
            foreach ($players as $playerPosition => $player) {   
                Game::create([
                    'player' => $player->name,
                    'score' => '',
                    'type' => 1,
                    'winning' => 0,
                ]);
                $playerRoundScore = $this->playRound($player, $players, $playerPosition);
                $roundScoreSheet = $this->addScoreInRoundScoreSheet(
                    $playerRoundScore, 
                    $roundScoreSheet, 
                    $player
                );
            }
            $print .=" <tr>";
            $print .="     <th>ROUND $roundNumber After Dice Rolled:</th>";
            # Display the Score after dice rolled
            // echo "ROUND $roundNumber\n\n";
            // echo "After Dice Rolled:\n";
            $print .= $this->displayRoundScore($roundScoreSheet);
        
            # Pass all the dice to the players beside
            $this->passDiceAcrossPlayers($players); 

            # Setup the Score after the dice moved/removed
            $diceMovedRoundScore = [];
            $diceMovedRoundScore = $this->populateMovedRoundScore(
                $players
            ); 

            # Display the Score after dice moved/removed
            $print .=" </tr>";
            $print .=" <tr>";
            $print .="     <th>After Dice Moved/Removed:</th>";
            // echo "After Dice Moved/Removed: \n";
            $print .= $this->displayRoundScore($diceMovedRoundScore);
            $print .=" </tr>";
            // $print .=" <tr></tr>";
            # Check if there is a valid winner

            $hasWinner = $this->checkIfHaveWinner($players);
            
            $roundNumber++;
        }
        if ($hasWinner[1]) {

            $print .='<tr><td colspan="'.(count($players)+1).'"><h2>'.$hasWinner[0].' has Victory Points '.$hasWinner[2].'</h2></td></tr>';
            $print .="</table>";
            echo $print;

        }
        else
        {
            $print .="</table>";
            echo $print;
        }
    }

    public function createPlayers(array $names)
    {   
        $players = collect([]);

        foreach ($names as $name) {
            $player = new Player($name, new DiceCup(4));
            $players->push($player);
        }

        return $players;
    }

    public function checkIfHaveWinner($players)
    {
        $hasWinner = ['',false];
        $winPlayer = [];
        $count = 1;
        // foreach ($players as $key => $player) {
        //     $cup = $player->getDiceCup();
            $emptyCups = [];
            $playersCups = [];
        //     if ($cup->isEmptyCup()) {
        //         // unset($players[$key]);
        //         $count++;
        //         continue;
        //     }            
        //     // if (count($players) > 3) {
                
        //     //     $game = Game::where('player',$player->name)->get();
        //     //     if (count($game) > 1) {
        //     //         echo "Match Draw";
        //     //         exit;
        //     //     }
        //     //     // $winPlayer[$player->name] = $game->winning;
        //     // }


        // }
        
        // if (!empty($winPlayer)) {
        //     $winningPlayer = array_search(max($winPlayer), $winPlayer);
        //     $hasWinner = [$winningPlayer,true];
        // }

        for ($i=1; $i < count($players); $i++) { 
            $playersCups[$i] = $players[$i]->getDiceCup();
            if ($playersCups[$i]->isEmptyCup()) {
                $emptyCups[$i] = $players[$i];
            }
        }

        if (count($emptyCups) == 3) {
            $Winner = Game::max('winning');
            $game = Game::where('winning',$Winner)->first();
            $hasWinner[0] = $game->player;
            $hasWinner[1] = true;
            $hasWinner[2] = $game->winning;

        }
        return $hasWinner;
    }

    public function playRound(Player $player, $players, $playerPosition)
    {
        $cup = $player->getDiceCup();
        $allDice = $cup->getAllDice();

        $rolledDice = collect([]);
        foreach ($allDice as $dice) {
            $dice->roll();
            $rolledDice->push($dice); 
        }
        foreach($rolledDice as $rolledDiceSingle){
            if ($rolledDiceSingle->topValue == 6) {
                $game = Game::where("player", $player->name)->first();
                $game->winning = $game->winning+1;
                $game->update();
            }
        }
        $diceCountShouldBeRemoved = $this->diceByTopValue($rolledDice, 6);
        $diceShouldBePassed = $this->diceByTopValue($rolledDice, 1);
        
        $diceToReturn = $this->diceToReturn(
            $rolledDice
        );

        $diceRollRoundScore = $this->getRolledDiceScores($rolledDice);

        $cup->addMultipleDice($diceToReturn);
        $player->setDiceCup(
            $cup
        );
        // dd($player);
        $this->passDiceToPlayerToTheRight(
            $players,
            $diceShouldBePassed,
            $playerPosition
        );

        return $diceRollRoundScore;
    }

    public function populateMovedRoundScore($players)
    {
        $roundScoreSheet = [];

        foreach ($players as $player) {
            $cup = $player->getDiceCup();
            $allDice = $cup->peekAtAllDice();

            $playerRoundScore = $this->getRolledDiceScores($allDice);
            $roundScoreSheet = $this->addScoreInRoundScoreSheet(
                $playerRoundScore, 
                $roundScoreSheet, 
                $player
            );
        }

        return $roundScoreSheet;
    }

    public function displayRoundScore($roundScore)
    {
        $value = '';
        foreach ($roundScore as $playerName => $scores) {
            
            $rolingValues = implode(',', $scores);
            $value .="<td>$playerName Score: ".implode(',', $scores)."</td>";
            
            Game::where("player", $playerName)->update(["score" => $rolingValues]);
        }
        return $value;
    }

    public function passDiceAcrossPlayers($players)
    {
        foreach ($players as $player) {
            if ($player->getDiceToAddInCup()->count() != 0) {
                $cup = $player->getDiceCup();
                $cup->addMultipleDice($player->getDiceToAddInCup());   
                $player->setDiceCup(
                    $cup
                ); 
            }
        }
    }

    public function addScoreInRoundScoreSheet($playerRoundScore, $roundScoreSheet, $player)
    {
        if (!isset($roundScoreSheet[$player->name])) {
            $roundScoreSheet[$player->name] = [];
        }

        $roundScoreSheet[$player->name] = $playerRoundScore;
            
        return $roundScoreSheet;
    }

    public function getRolledDiceScores($rolledDice)
    {
        $scores = [];
        foreach ($rolledDice as $dice) {
            $scores[] = $dice->getTopValue();
        }

        return $scores;
    }

    public function passDiceToPlayerToTheRight(
        $players,
        $diceShouldBePassed,
        $currentPlayerPosition
    ) {
        $nextPlayerPosition = $currentPlayerPosition+1;
        $playerToPassDice = $players->get($nextPlayerPosition);

        if (is_null($playerToPassDice)) {
            $playerToPassDice = $players->first();
        }

        $playerToPassDice->setDiceToAddInCup($diceShouldBePassed);
    }

    public function diceByTopValue($rolledDice, $topValue)
    {   
        $filtered = $rolledDice->filter(function ($dice) use ($topValue) {
            return $dice->getTopValue() == $topValue;
        });

        return $filtered;
    }

    public function diceToReturn($rolledDice) 
    {
    	$filtered = $rolledDice->reject(function ($dice) {
            return (in_array($dice->getTopValue(), [1,6]));
        });

        return $filtered;
    }
}
