@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.configs.title')</h3>
    @can('config_create')
    <p>
        <a href="{{ route('admin.configs.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($configs) > 0 ? 'datatable' : '' }} @can('config_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('config_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.configs.fields.name')</th>
                                                <th>&nbsp;
                                                </th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($configs) > 0)
                        @foreach ($configs as $config)
                            <tr data-entry-id="{{ $config->id }}">
                                @can('config_delete')
                                    <td></td>
                                @endcan

                                <td field-key='name'>{{ $config->name }}</td>
                                                                <td>
                                    @can('config_view')
                                    <a href="{{ route('admin.configs.show',[$config->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('config_edit')
                                    <a href="{{ route('admin.configs.edit',[$config->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('config_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.configs.destroy', $config->id])) !!}
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
        @can('config_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.configs.mass_destroy') }}';
        @endcan

    </script>
@endsection