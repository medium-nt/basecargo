@php
    $columnDefinitions = app(\App\Services\CargoShipmentImportService::class)->getColumnDefinitions();
    $mainFields = ['cargo_number', 'product_name', 'packaging', 'places_count', 'gross_weight_total', 'volume_total'];

    // Определяем режим фильтрации (по умолчанию - показывать все)
    $filterMode = $showOnlyErrors ?? false ? 'errors' : ($showOnlyValid ?? false ? 'valid' : 'all');
@endphp

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm">
        <thead class="thead-dark">
            <tr>
                <th style="width: 50px;">#</th>
                @foreach($mainFields as $field)
                    <th>{{ $columnDefinitions[$field] ?? $field }}</th>
                @endforeach
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mappedData as $index => $row)
                @php
                    $hasErrors = isset($validationResult->errors[$index]);
                    $hasWarning = !$hasErrors && $validationResult->hasWarningForRow($index);
                    $rowNumber = $row['_row_index'] ?? ($index + 2);

                    // Фильтрация по режиму
                    $shouldShow = match($filterMode) {
                        'errors' => $hasErrors,
                        'valid' => !$hasErrors && !$hasWarning,
                        default => true,
                    };
                @endphp

                @if(!$shouldShow)
                    @continue
                @endif

                <tr class="{{ $hasErrors ? 'table-danger' : ($hasWarning ? 'table-warning' : '') }}">
                    <td>{{ $rowNumber }}</td>
                    @foreach($mainFields as $field)
                        @php
                            $hasFieldError = $hasErrors && isset($validationResult->errors[$index][$field]);
                            $cellClass = $hasFieldError ? 'cell-error' : '';
                        @endphp
                        <td class="{{ $cellClass }}">
                            {{ $row[$field] ?? '-' }}
                            @if($hasFieldError)
                                <small class="d-block text-danger">
                                    {{ implode(', ', $validationResult->errors[$index][$field]) }}
                                </small>
                            @endif
                        </td>
                    @endforeach
                    <td>
                        @if($hasErrors)
                            <span class="badge badge-danger">Ошибка</span>
                        @elseif($hasWarning)
                            <span class="badge badge-warning">Предупреждение</span>
                        @else
                            <span class="badge badge-success">ОК</span>
                        @endif
                    </td>
                </tr>

                {{-- Показать все ошибки для строки с ошибками (только в режиме "Все") --}}
                @if($hasErrors && $filterMode === 'all')
                    <tr>
                        <td colspan="{{ count($mainFields) + 2 }}" class="bg-light">
                            <strong>Ошибки в строке {{ $rowNumber }}:</strong>
                            <ul class="mb-0 pl-3">
                                @foreach($validationResult->errors[$index] as $field => $messages)
                                    <li>
                                        <strong>{{ $columnDefinitions[$field] ?? $field }}:</strong>
                                        {{ implode(', ', $messages) }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif

                {{-- Показать предупреждение для строки с предупреждением (только в режиме "Все") --}}
                @if($hasWarning && $filterMode === 'all')
                    <tr>
                        <td colspan="{{ count($mainFields) + 2 }}" class="bg-light">
                            <strong class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Предупреждение для строки {{ $rowNumber }}:</strong>
                            <span class="text-warning">{{ $validationResult->getWarningForRow($index) }}</span>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($mainFields) + 2 }}" class="text-center text-muted">
                        Нет данных для отображения
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
