@extends('emails.layout')
@section('title', 'Регистрация')
@section('content')
    Вы успешно зарегистрировались
    <br>
    Ваш логин для входа: {{ $login }}
    <br>
    Ваш пароль для входа: {{ $password }}
    <br>
    Для подтверждения почты перейдите по
    <a href="{{ route('get.user.confirm', ['token' => $token]) }}" target="_blank" style="color:#0186BE;"><font color="#0186BE">этой ссылке</font></a>
@endsection