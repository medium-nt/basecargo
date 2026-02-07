@extends('adminlte::page')

@section('title', 'Панель')

@section('content_header')
    <h1>Панель управления</h1>
@endsection

@section('content')
    {{-- Статистика по грузам --}}
    <div class="row">
        {{-- Всего --}}
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Всего грузов</span>
                    <span class="info-box-number">{{ $stats['total_count'] }} / {{ number_format($stats['total_weight'], 2, ',', ' ') }} кг</span>
                </div>
            </div>
        </div>

        {{-- В пути --}}
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-truck"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">В пути</span>
                    <span class="info-box-number">{{ $stats['in_transit_count'] }} / {{ number_format($stats['in_transit_weight'], 2, ',', ' ') }} кг</span>
                </div>
            </div>
        </div>

        {{-- Доставлено --}}
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Доставлено</span>
                    <span class="info-box-number">{{ $stats['delivered_count'] }} / {{ number_format($stats['delivered_weight'], 2, ',', ' ') }} кг</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(auth()->user()->isAdmin())
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
            @else
                <p>Добро пожаловать, {{ auth()->user()->name }}!</p>
            @endif
        </div>
    </div>
@endsection
