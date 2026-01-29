@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

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
                <div class="col-md-2">
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
                    @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
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
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Фото</th>
                            <th>Клиент 客户群</th>
                            <th>Ответственный 负责</th>
                            <th>Номер груза 货物编号</th>
                            <th>Количество мест 座位数目</th>
                            <th>Общий вес брутто 总毛重</th>
                            <th>Общий обьем кубов 立方体的总体积</th>
                            <th>Статус груза 货物状况</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            <tr>
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
                                <td colspan="8" class="text-center text-muted">
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
@endsection

@push('js')
    <script src="{{ asset('js/PageQueryParam.js') }}"></script>
@endpush
