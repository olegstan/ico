@extends('emails.layout')
@section('title', 'Смена email')
@section('content')
    Для подтверждения почты перейдите по
    <a href="{{ route('get.user.confirm', ['token' => $token]) }}" target="_blank" style="color:#0186BE;"><font color="#0186BE">этой ссылке</font></a>
@endsection