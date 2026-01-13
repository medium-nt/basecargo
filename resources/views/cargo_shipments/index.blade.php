@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Тут будет таблица с грузами</p>
        </div>
    </div>
@endsection
