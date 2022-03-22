<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\GameHelper;

class DiceGameController extends Controller
{

    public function index()
    {
        $game = new GameHelper;
        $game->initializeGame();
    }

}
