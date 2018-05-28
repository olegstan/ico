<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Lobby\OpenSessionRequest;
use App\Http\Requests\Lobby\CloseSessionRequest;
use App\Models\GameSession;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOpenSession(OpenSessionRequest $request)
    {
        GameSession::open($request->input('bet_id'), Auth::id());

        return response()->json([
            'result' => 'success'
        ]);
    }

    /**
     * @param CloseSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCloseSession(CloseSessionRequest $request)
    {
        GameSession::close($request->input('session_id'), Auth::id());

        return response()->json([
            'result' => 'success'
        ]);
    }


}
