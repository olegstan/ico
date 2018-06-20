<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConfigsRequest;
use DB;

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


        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating new Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(401);
    }

    /**
     *
     */
    public function store()
    {
        return abort(401);
    }


    /**
     * Show the form for editing Game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(401);
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        return abort(401);
    }


    /**
     * Display Game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('session_view')) {
            return abort(401);
        }
        $session = GameSession::findOrFail($id);

        return view('admin.sessions.show', compact('session'));
    }


    /**
     * Remove Game from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(401);
    }

    /**
     * Delete all selected Game at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('session_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = GameSession::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
