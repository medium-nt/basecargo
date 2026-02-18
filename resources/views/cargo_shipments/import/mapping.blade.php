@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Файл: <strong>{{ $fileName }}</strong> |
                Найдено строк: <strong>{{ $totalRows }}</strong> (максимум 100)
            </div>

            <form action="{{ route('cargo_shipments.import.validate') }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 100px;">Колонка</th>
                                <th>Заголовок в файле</th>
                                <th>Поле в системе</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fileColumns as $column)
                            <tr>
                                <td><strong>{{ $column }}</strong></td>
                                <td>{{ $headers[$column] ?? '-' }}</td>
                                <td>
                                    <select name="mapping[{{ $column }}]" class="form-control form-control-sm">
                                        <option value="skip" {{ ($detectedMapping[$column] ?? 'skip') === 'skip' ? 'selected' : '' }} style="color: #dc3545;">
                                            -- Пропустить колонку --
                                        </option>
                                        @foreach($columnDefinitions as $field => $label)
                                            <option value="{{ $field }}"
                                                {{ ($detectedMapping[$column] ?? 'skip') === $field ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check mr-1"></i>
                        Далее: Валидация
                    </button>
                    <a href="{{ route('cargo_shipments.import.cancel') }}"
                       class="btn btn-secondary ml-2"
                       onclick="return confirm('Отменить импорт? Все данные будут потеряны.');">
                        <i class="fas fa-times mr-1"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select[name^="mapping"]');

            function updateSelectStyle(select) {
                if (select.value === 'skip') {
                    select.classList.add('skip-column');
                } else {
                    select.classList.remove('skip-column');
                }
            }

            selects.forEach(function(select) {
                updateSelectStyle(select);

                select.addEventListener('change', function() {
                    updateSelectStyle(this);
                });
            });
        });
    </script>

    <style>
        .skip-column {
            background-color: #ffe6e6 !important;
            border: 2px solid #dc3545 !important;
        }

        /* Сбрасываем фон для всех option в списке */
        .skip-column option {
            background-color: #fff !important;
            color: #000 !important;
        }

        /* Опция "skip" в списке - красный текст */
        .skip-column option[value="skip"] {
            color: #dc3545 !important;
            font-weight: bold !important;
        }
    </style>
@endsection
