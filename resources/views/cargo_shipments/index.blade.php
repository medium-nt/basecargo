@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body" style="position: relative;">
            <div id="loading-overlay" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; border-radius: 0.25rem;"></div>

            @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isManager())
            <div class="row">
                @if(request('archive') != '1')
                    {{-- Фильтр по статусу только для активных грузов --}}
                    <div class="col-md-3">
                        <select name="cargo_status" id="cargo_status"
                                onchange="updatePageWithQueryParam(this)"
                                class="form-control mb-3">
                            <option value="">Все статусы</option>
                            <option value="wait_payment" {{ request('cargo_status') == 'wait_payment' ? 'selected' : '' }}>Ожидает оплаты</option>
                            <option value="shipping_supplier" {{ request('cargo_status') == 'shipping_supplier' ? 'selected' : '' }}>Отправка поставщиком</option>
                            <option value="china_transit" {{ request('cargo_status') == 'china_transit' ? 'selected' : '' }}>В пути по Китаю</option>
                            <option value="china_warehouse" {{ request('cargo_status') == 'china_warehouse' ? 'selected' : '' }}>На складе в Китае</option>
                            <option value="china_russia_transit" {{ request('cargo_status') == 'china_russia_transit' ? 'selected' : '' }}>В пути Китай–Россия</option>
                            <option value="russia_warehouse" {{ request('cargo_status') == 'russia_warehouse' ? 'selected' : '' }}>На складе в России</option>
                            <option value="russia_transit" {{ request('cargo_status') == 'russia_transit' ? 'selected' : '' }}>В пути по России</option>
                            <option value="wait_receiving" {{ request('cargo_status') == 'wait_receiving' ? 'selected' : '' }}>Ожидает получения клиентом</option>
                        </select>
                    </div>
                @endif
                {{-- Фильтры по клиенту и ответственному — всегда --}}
                <div class="col-md-{{ request('archive') == '1' ? '4' : '3' }}">
                    <select name="client_id" id="client_id"
                            onchange="updatePageWithQueryParam(this)"
                            class="form-control mb-3">
                        <option value="">Все клиенты</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="responsible_user_id" id="responsible_user_id"
                            onchange="updatePageWithQueryParam(this)"
                            class="form-control mb-3">
                        <option value="">Все ответственные</option>
                        @foreach($responsibleUsers as $responsibleUser)
                            <option value="{{ $responsibleUser->id }}" {{ request('responsible_user_id') == $responsibleUser->id ? 'selected' : '' }}>{{ $responsibleUser->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="col-md-1">
                    <select name="trip_status" id="trip_status"
                            onchange="updatePageWithQueryParam(this)"
                            class="form-control mb-3">
                        <option value="">Все</option>
                        <option value="with_trip" {{ request('trip_status') == 'with_trip' ? 'selected' : '' }}>С рейсом</option>
                        <option value="without_trip" {{ request('trip_status') == 'without_trip' ? 'selected' : '' }}>Без рейса</option>
                    </select>
                </div>
                <div class="col-md-{{ request('archive') == '1' ? '3' : '2' }}">
                @else
                <div class="col-md-{{ request('archive') == '1' ? '4' : '3' }}">
                @endif
                    <a href="{{ route('cargo_shipments.index', ['archive' => request('archive') ?? '0']) }}" class="btn btn-default mb-3">
                        <i class="fas fa-undo mr-1"></i>
                        Сбросить фильтры
                    </a>
                </div>
                <div class="ml-auto mr-2">
                    @if(request('archive') == '1')
                        {{-- Кнопка возврата к активным --}}
                        <a href="{{ route('cargo_shipments.index', ['archive' => '0']) }}" class="btn btn-outline-success mb-3">
                            <i class="fas fa-box mr-1"></i>
                            Активные
                        </a>
                    @else
                        {{-- Кнопка перехода в архив --}}
                        <a href="{{ route('cargo_shipments.index', ['archive' => '1']) }}" class="btn btn-outline-secondary mb-3">
                            <i class="fas fa-archive mr-1"></i>
                            Архив
                        </a>
                    @endif
                </div>
            </div>

                @if(request('archive') != '1')
                    {{-- Кнопка добавления груза --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isAgent())
                        <a href="{{ route('cargo_shipments.create') }}" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i>
                            Добавить груз
                        </a>
                    @endif
                @endif
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            @if((auth()->user()->isAdmin() || auth()->user()->isManager()) && request('archive') != '1')
                            <th style="width: 40px; text-align: center; vertical-align: middle;">
                                <input type="checkbox" id="select-all-cargos" class="form-check-input" style="margin-left: 1px;">
                            </th>
                            @endif
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Фото</th>
                            <th>Клиент<br><span style="font-size: 0.85em;">客户群</span></th>
                            <th>Ответственный<br><span style="font-size: 0.85em;">负责</span></th>
                            <th>Номер груза<br><span style="font-size: 0.85em;">货物编号</span></th>
                            <th>Количество мест<br><span style="font-size: 0.85em;">座位数目</span></th>
                            <th>Общий вес брутто<br><span style="font-size: 0.85em;">总毛重</span></th>
                            <th>Общий обьем кубов<br><span style="font-size: 0.85em;">立方体的总体积</span></th>
                            <th>Статус груза<br><span style="font-size: 0.85em;">货物状况</span></th>
                            @if(!auth()->user()->isClient())
                            <th>Рейс</th>
                            @endif
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            <tr>
                                @if((auth()->user()->isAdmin() || auth()->user()->isManager()) && request('archive') != '1')
                                <td style="text-align: center; vertical-align: middle; cursor: pointer;"
                                    onclick="this.querySelector('.cargo-checkbox').click()">
                                    <input type="checkbox" class="cargo-checkbox form-check-input"
                                           data-cargo-id="{{ $shipment->id }}"
                                           style="margin-left: 1px; pointer-events: none;">
                                </td>
                                @endif
                                <td>{{ $shipment->id }}</td>
                                <td>
                                    @if($shipment->photo_path)
                                        <a href="{{ route('cargo_shipments.show', ['cargoShipment' => $shipment->id]) }}">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($shipment->photo_path) }}"
                                                 alt="Фото"
                                                 style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px;">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $shipment->client?->name ?? '' }}</td>
                                <td>{{ $shipment->agent?->name ?? '' }}</td>
                                <td>{{ $shipment->cargo_number }}</td>
                                <td>{{ $shipment->places_count }}</td>
                                <td>{{ $shipment->gross_weight_total }}</td>
                                <td>{{ $shipment->volume_total }}</td>
                                <td>{{ $shipment->cargo_status_name }}</td>
                                @if(!auth()->user()->isClient())
                                <td>
                                    @if($shipment->trips->count() > 0)
                                        @foreach($shipment->trips as $trip)
                                            @if($trip->type === 'domestic')
                                                <a href="{{ route('trips.show', ['trip' => $trip->id]) }}" class="badge badge-success mr-1">{{ $trip->truck_number ?? 'Рейс #' . $trip->id }}</a>
                                            @elseif($trip->type === 'international')
                                                <a href="{{ route('trips.show', ['trip' => $trip->id]) }}" class="badge badge-danger mr-1">{{ $trip->truck_number ?? 'Рейс #' . $trip->id }}</a>
                                            @else
                                                <a href="{{ route('trips.show', ['trip' => $trip->id]) }}" class="badge badge-info mr-1">{{ $trip->truck_number ?? 'Рейс #' . $trip->id }}</a>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <div class="row">
                                        <a href="{{ route('cargo_shipments.show', ['cargoShipment' => $shipment->id]) }}"
                                           class="btn btn-primary mr-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @isset($shipment->cargo_number)
                                        <a href="{{ route('cargo_shipments.show_qr', ['cargoShipment' => $shipment->id]) }}"
                                           class="btn btn-secondary">
                                            <i class="fas fa fa-qrcode"></i>
                                        </a>
                                        @endisset
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="
                                    @if(auth()->user()->isClient())
                                        10
                                    @elseif(auth()->user()->isAgent())
                                        11
                                    @elseif(request('archive') == '1')
                                        10
                                    @else
                                        11
                                    @endif
                                " class="text-center text-muted">
                                    Нет грузов, соответствующих выбранным параметрам
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <x-pagination-component :collection="$shipments" />

        </div>
    </div>

    {{-- Bulk actions panel --}}
    @if((auth()->user()->isAdmin() || auth()->user()->isManager()) && request('archive') != '1')
    <div id="bulk-actions-panel" class="card card-primary card-outline fixed-bottom"
         style="display: none; max-width: 600px; left: 50%; transform: translateX(-50%); bottom: 20px; z-index: 1000;">
        <div class="card-body">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <span class="selected-count font-weight-bold">Выбрано: 0 грузов</span>
                </div>
                <div class="col">
                    <select name="trip_id" id="bulk-trip-select" class="form-control">
                        <option value="">Выберите рейс</option>
                        <optgroup label="По России">
                            @foreach($trips->where('type', 'domestic') as $trip)
                                <option value="{{ $trip->id }}">
                                    {{ $trip->truck_number ?: 'Рейс #' . $trip->id }} ({{ $trip->status_name }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Китай-Россия">
                            @foreach($trips->where('type', 'international') as $trip)
                                <option value="{{ $trip->id }}">
                                    {{ $trip->truck_number ?: 'Рейс #' . $trip->id }} ({{ $trip->status_name }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="button" id="attach-to-trip-btn" class="btn btn-primary" disabled>
                        Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('js/PageQueryParam.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-cargos');
            const cargoCheckboxes = document.querySelectorAll('.cargo-checkbox');
            const bulkActionsPanel = document.getElementById('bulk-actions-panel');
            const selectedCountSpan = document.querySelector('.selected-count');
            const bulkTripSelect = document.getElementById('bulk-trip-select');
            const attachToTripBtn = document.getElementById('attach-to-trip-btn');
            const loadingOverlay = document.getElementById('loading-overlay');

            // Выделить все
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    cargoCheckboxes.forEach(function(checkbox) {
                        if (!checkbox.disabled) {
                            checkbox.checked = selectAllCheckbox.checked;
                        }
                    });
                    updateBulkActions();
                });
            }

            // Отслеживание изменений чекбоксов
            cargoCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateBulkActions();
                });
            });

            // Обновление панели действий
            function updateBulkActions() {
                const selectedCount = Array.from(cargoCheckboxes)
                    .filter(cb => cb.checked).length;

                selectedCountSpan.textContent = 'Выбрано: ' + selectedCount + ' грузов';

                if (selectedCount > 0) {
                    bulkActionsPanel.style.display = 'block';
                } else {
                    bulkActionsPanel.style.display = 'none';
                }

                // Включить/выключить кнопку добавления
                attachToTripBtn.disabled = !bulkTripSelect.value || selectedCount === 0;
            }

            // Выбор рейса
            if (bulkTripSelect) {
                bulkTripSelect.addEventListener('change', function() {
                    attachToTripBtn.disabled = !this.value;
                });
            }

            // Кнопка добавления
            if (attachToTripBtn) {
                attachToTripBtn.addEventListener('click', function() {
                    const selectedCargoIds = Array.from(cargoCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.dataset.cargoId);

                    const tripId = bulkTripSelect.value;

                    if (!tripId || selectedCargoIds.length === 0) {
                        return;
                    }

                    const formData = new FormData();
                    selectedCargoIds.forEach(id => formData.append('cargo_ids[]', id));
                    formData.append('trip_id', tripId);
                    formData.append('_token', '{{ csrf_token() }}');

                    attachToTripBtn.disabled = true;
                    attachToTripBtn.textContent = 'Добавление...';
                    loadingOverlay.style.display = 'block';

                    fetch('{{ route('cargo_shipments.attach_to_trip') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message || Object.values(err.errors || {})[0]?.[0] || 'Ошибка');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            if (data.success) {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        }
                        setTimeout(() => location.reload(), 1000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error(error.message || 'Ошибка при добавлении грузов в рейс');
                        attachToTripBtn.disabled = false;
                        attachToTripBtn.textContent = 'Добавить';
                        loadingOverlay.style.display = 'none';
                    });
                });
            }
        });
    </script>
@endpush
