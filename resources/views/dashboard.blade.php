@extends('adminlte::page')

@section('title', 'Панель')

@section('content_header')
    <h1>Панель управления</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p>Добро пожаловать в админку.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p>Тест QR-кода</p>

            {!! QrCode::format('svg')->size(300)->generate(
                route('cargo_shipments.qr')
            ) !!}
        </div>
    </div>
@endsection
