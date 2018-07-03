<?php

namespace App\Http\Controllers\Gamer;

use App\Models\GameSession;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class SessionsController extends Controller
{
    /**
     * Display a listing of Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('session_access')) {
            return abort(401);
        }

        $sessions = GameSession::select([
            'gs.id',
            'gs.win',
            'gs.winner_id',
            'gs.started_at',
            'gs.ended_at',
            'gb.bet',
            'g.name',
            'gsu.credits_before',
            'gsu.credits_after',
            DB::raw('(SELECT COUNT(`gsu2`.`id`) FROM `game_sessions` as `gs2`
                left join `game_sessions_users` as `gsu2` on `gs2`.`id` = `gsu2`.`session_id` 
                WHERE `gs2`.`id` = `gs`.`id`
                GROUP BY `gs2`.`id`
                ) as count'),
            'u.email'
        ])
        ->from('game_sessions as gs')
        ->where('gsu.user_id', Auth::id())
        ->leftJoin('game_sessions_users as gsu', function ($j){
            $j->on('gs.id', '=', 'gsu.session_id');
        })->leftJoin('users as u', function ($j){
            $j->on('gs.winner_id', '=', 'u.id');
        })->leftJoin('game_bets as gb', function ($j){
            $j->on('gs.bet_id', '=', 'gb.id');
        })->leftJoin('games as g', function ($j){
            $j->on('gb.game_id', '=', 'g.id');
        })
            ->get()
            ->all();


        return view('gamer.sessions.index', compact('sessions'));
    }
}
