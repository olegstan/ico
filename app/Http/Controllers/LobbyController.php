<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Lobby\OpenSessionRequest;
use App\Http\Requests\Lobby\CloseSessionRequest;
use App\Models\GameBet;
use App\Models\GameSession;
use Auth;
use Session;

class LobbyController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $bets = GameBet::with(['game'])
            ->get()
            ->all();


        return view('lobby.index', compact('bets'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGames()
    {
        return view('lobby.games');
    }



    public function removeUserFromSession()
    {

    }


}
