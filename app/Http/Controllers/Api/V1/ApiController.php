<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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

        return response()->redirectTo(env('GAME_HOST', '') . '/open?session_io=' . $id . '&user_id=' . Auth::id());
    }

    /**
     * @param CloseSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postStartSession(CloseSessionRequest $request)
    {
        GameSession::start($request->input('session_id'));

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
