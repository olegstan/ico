@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.sessions.title')</h3>
    @can('game_create')
        <p>
            <a href="{{ route('admin.sessions.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>

        </p>
    @endcan



    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($sessions) > 0 ? 'datatable' : '' }} @can('game_delete') dt-select @endcan">
                <thead>
                <tr>
                    <th>@lang('quickadmin.sessions.fields.id')</th>
                    <th>@lang('quickadmin.sessions.fields.game_name')</th>
                    <th>@lang('quickadmin.sessions.fields.credits_before')</th>
                    <th>@lang('quickadmin.sessions.fields.bet')</th>
                    <th>@lang('quickadmin.sessions.fields.result')</th>
                    <th>@lang('quickadmin.sessions.fields.credits_after')</th>
                    <th>@lang('quickadmin.sessions.fields.started_at')</th>
                    <th>@lang('quickadmin.sessions.fields.ended_at')</th>
                </tr>
                </thead>

                <tbody>
                    @if (count($sessions) > 0)
                        @foreach ($sessions as $game)
                            <tr data-entry-id="{{ $game->id }}">
                                <td field-key='id'>{{ $game->id }}</td>
                                <td field-key='game_name'>{{ $game->name }}</td>
                                <td field-key='credits_before'>{{ $game->credits_before }}</td>
                                <td field-key='bet'>{{ $game->bet }}</td>
                                <td field-key='result'>{{ (Auth::id() == $game->winner_id ? 'win' : (isset($game->ended_at) ? 'lose' : '')) }}</td>
                                <td field-key='credits_after'>{{ $game->credits_after }}</td>
                                <td field-key='started_at'>{{ $game->started_at }}</td>
                                <td field-key='ended_at'>{{ $game->ended_at }}</td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="10">@lang('quickadmin.qa_no_entries_in_table')</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        @can('game_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.sessions.mass_destroy') }}';
        @endcan

    </script>
@endsection