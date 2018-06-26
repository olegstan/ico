@extends('emails.layout')
@section('title', 'Регистрация')
@section('content')
    Вы успешно зарегистрировались
    <br>
    Ваш логин/почта для входа: {{ $login }}
    <br>
    Ваш пароль для входа: {{ $password }}
@endsection