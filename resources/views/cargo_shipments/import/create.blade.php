@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cargo_shipments.import.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Выберите Excel файл (.xlsx, .xls)</label>
                    <input type="file" name="file" id="file" class="form-control-file" accept=".xlsx,.xls" required>
                    <small class="form-text text-muted">
                        Максимум 10MB, максимум 100 строк данных. Первая строка должна содержать заголовки.
                    </small>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Совет:</strong> Сначала скачайте шаблон, нажав кнопку "Скачать шаблон" на странице грузов,
                    затем заполните его данными и загрузите здесь.
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload mr-1"></i>
                        Загрузить файл
                    </button>
                    <a href="{{ route('cargo_shipments.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Назад
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
