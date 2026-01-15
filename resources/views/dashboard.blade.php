@extends('adminlte::page')

@section('title', 'Панель')

@section('content_header')
    <h1>Панель управления</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Тестовые пользователи:</p>

            1@1.ru | 111111 - админ
            <br>
            <br>
            2@2.ru | 222222
            <br>
            22@22.ru | 222222 - агенты
            <br>
            <br>
            3@3.ru | 333333
            <br>
            33@33.ru | 333333 - клиенты.
            <br>
            <br>
            4@4.ru | 444444 - менеджер
            <br>
        </div>
    </div>
@endsection
