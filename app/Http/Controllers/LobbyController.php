<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Lobby\OpenSessionRequest;
use App\Models\Game;
use App\Models\GameBet;
use App\Models\GameSession;
use App\Models\GameSessionUser;
use Auth;

class LobbyController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('lobby.index');
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

    /**
     * @param OpenSessionRequest $request
     */
    public function getOpenSession(OpenSessionRequest $request)
    {
        GameSession::open($request->input('bet_id'), Auth::id());
    }


}
