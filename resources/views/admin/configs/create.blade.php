@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.configs.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.configs.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('key', trans('quickadmin.configs.fields.key').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('key', old('key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('key'))
                        <p class="help-block">
                            {{ $errors->first('key') }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-12 form-group">
                    {!! Form::label('key', trans('quickadmin.configs.fields.key').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('key', old('key'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('key'))
                        <p class="help-block">
                            {{ $errors->first('key') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

