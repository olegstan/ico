<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\Lobby\OpenSessionRequest;
use App\Http\Requests\Lobby\CloseSessionRequest;
use App\Models\GameSession;
use Auth;
use Session;

class ApiController extends Controller
{
    /**
     * @param OpenSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOpenSession(OpenSessionRequest $request)
    {
        Session::set('bet_id', $request->input('bet_id'));
        $id = GameSession::open($request->input('bet_id'), Auth::id());


        //TODO open game


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
        GameSession::close($request->input('session_id'), $request->input('session_id'));

        return response()->json([
            'result' => 'success'
        ]);
    }

    public function listSessions()
    {

    }


}
