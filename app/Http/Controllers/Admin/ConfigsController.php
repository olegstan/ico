<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConfigsRequest;
use App\Http\Requests\Admin\UpdateConfigsRequest;

class ConfigsController extends Controller
{
    /**
     * Display a listing of Config.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('config_access')) {
            return abort(401);
        }

        $configs = Config::all();

        return view('admin.configs.index', compact('configs'));
    }

    /**
     * Show the form for creating new Config.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('config_create')) {
            return abort(401);
        }

        return view('admin.configs.create');
    }

    /**
     * Store a newly created Config in storage.
     *
     * @param  \App\Http\Requests\StoreConfigsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConfigsRequest $request)
    {
        if (! Gate::allows('config_create')) {
            return abort(401);
        }
        $config = Config::create($request->all());



        return redirect()->route('admin.configs.index');
    }


    /**
     * Show the form for editing Config.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('config_edit')) {
            return abort(401);
        }

        $config = Config::findOrFail($id);

        return view('admin.configs.edit');
    }

    /**
     * Update Config in storage.
     *
     * @param  \App\Http\Requests\UpdateConfigsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConfigsRequest $request, $id)
    {
        if (! Gate::allows('config_edit')) {
            return abort(401);
        }
        $config = Config::findOrFail($id);
        $config->update($request->all());



        return redirect()->route('admin.configs.index');
    }


    /**
     * Display Config.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('config_view')) {
            return abort(401);
        }
        $config = Config::findOrFail($id);

        return view('admin.configs.show', compact('config'));
    }


    /**
     * Remove Config from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('config_delete')) {
            return abort(401);
        }
        $config = Config::findOrFail($id);
        $config->delete();

        return redirect()->route('admin.configs.index');
    }

    /**
     * Delete all selected Config at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('config_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Config::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
