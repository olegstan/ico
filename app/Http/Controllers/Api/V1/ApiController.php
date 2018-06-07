<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Lobby\OpenSessionRequest;
use App\Http\Requests\Lobby\ExitSessionRequest;
use App\Http\Requests\Lobby\CloseSessionRequest;
use App\Models\GameSession;
use Auth;
use DB;
use Session;

class ApiController extends Controller
{
    /**
     * @param OpenSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOpenSession(OpenSessionRequest $request)
    {
        Session::put('bet_id', $request->input('bet_id'));
        $id = GameSession::open($request->input('bet_id'), Auth::id());


        return response()->json([
            'result' => 'success',
            'id' => $id
        ]);

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
        GameSession::close($request->input('session_id'), $request->input('user_id'));

        return response()->json([
            'result' => 'success'
        ]);
    }

    /**
     * @param ExitSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postExitSession(ExitSessionRequest $request)
    {
        GameSession::exit($request->input('session_id'), $request->input('user_id'));

        return response()->json([
            'result' => 'success'
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSessions()
    {
        $sessions = GameSession::select([
            'gs.id',
            'gs.started_at',
            'gs.ended_at',
            'gb.bet',
            DB::raw('(SELECT COUNT(`gsu2`.`id`) FROM `game_sessions` as `gs2`
                left join `game_sessions_users` as `gsu2` on `gs2`.`id` = `gsu2`.`session_id` 
                WHERE `gs2`.`id` = `gs`.`id`
                GROUP BY `gs2`.`id`
                ) as count'),
            'u.email'
        ])
            ->from('game_sessions as gs')
            ->leftJoin('users as u', function ($j){
                $j->on('gs.winner_id', '=', 'u.id');
            })->leftJoin('game_bets as gb', function ($j){
                $j->on('gs.bet_id', '=', 'gb.id');
            })->leftJoin('games as g', function ($j){
                $j->on('gb.game_id', '=', 'g.id');
            })
            ->get()
            ->all();

        return response()->json([
            'result' => 'success',
            'sessions' => $sessions
        ]);
    }
}
