<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConfigsRequest;

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

        $sessions = GameSession::all();

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
