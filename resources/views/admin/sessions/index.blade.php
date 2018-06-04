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
                        @can('game_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.sessions.fields.name')</th>
                                                <th>&nbsp;
                                                </th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($sessions) > 0)
                        @foreach ($sessions as $game)
                            <tr data-entry-id="{{ $game->id }}">
                                <td field-key='name'>{{ $game->name }}</td>
                                                                <td>
                                    @can('game_view')
                                    <a href="{{ route('admin.sessions.show',[$game->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan

                                    @can('game_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.sessions.destroy', $game->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
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