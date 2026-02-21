@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@push('css')
    <style>
        .cell-error {
            background-color: #ffcccc !important;
            border: 2px solid #ff0000 !important;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .tab-btn {
            cursor: pointer;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
        }
        .tab-btn.active {
            background-color: #fff;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
        }
        .tabs-container {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Статистика --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="alert alert-info mb-0">
                        <strong>Всего строк:</strong> {{ $totalRows }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-success mb-0">
                        <strong>Валидных:</strong> {{ $validRowsCount }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-{{ $hasErrors ? 'danger' : 'success' }} mb-0">
                        <strong>С ошибками:</strong> {{ $errorsCount }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-warning mb-0">
                        <strong>Предупреждений:</strong> {{ $validationResult->getWarningsCount() }}
                    </div>
                </div>
            </div>

            {{-- Предупреждения --}}
            @if($validationResult->hasWarnings())
                <div class="alert alert-warning">
                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Внимание:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($validationResult->warnings as $index => $warning)
                            <li>Строка {{ $mappedData[$index]['_row_index'] ?? ($index + 2) }}: {{ $warning }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Табы --}}
            <div class="tabs-container">
                <button class="tab-btn active" data-tab="all">
                    Все строки ({{ $totalRows }})
                </button>
                <button class="tab-btn" data-tab="errors">
                    С ошибками ({{ $errorsCount }})
                </button>
                <button class="tab-btn" data-tab="valid">
                    Валидные ({{ $validRowsCount }})
                </button>
            </div>

            {{-- Таблица с данными --}}
            <div id="tab-all" class="tab-content active">
                @include('cargo_shipments.import._preview_table', ['showAll' => true])
            </div>

            <div id="tab-errors" class="tab-content">
                @include('cargo_shipments.import._preview_table', ['showOnlyErrors' => true])
            </div>

            <div id="tab-valid" class="tab-content">
                @include('cargo_shipments.import._preview_table', ['showOnlyValid' => true])
            </div>

            {{-- Кнопки действий --}}
            <div class="form-group mt-3">
                @if($validRowsCount > 0)
                <form action="{{ route('cargo_shipments.import.store') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i>
                        Импортировать валидные ({{ $validRowsCount }})
                    </button>
                </form>
                @endif

                <button type="button" onclick="history.back()" class="btn btn-warning ml-2">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Назад (изменить маппинг)
                </button>

                <a href="{{ route('cargo_shipments.import.cancel') }}"
                   class="btn btn-secondary ml-2"
                   onclick="return confirm('Отменить импорт? Все данные будут потеряны.');">
                    <i class="fas fa-times mr-1"></i>
                    Отмена
                </a>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Убираем active у всех
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));

                    // Добавляем active нажатому
                    this.classList.add('active');
                    document.getElementById('tab-' + targetTab).classList.add('active');
                });
            });
        });
    </script>
@endpush
