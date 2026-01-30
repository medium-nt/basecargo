@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Информация о рейсе #{{ $trip->id }}</h3>
            <div class="card-tools">
                <a href="{{ $trip->type === 'domestic' ? route('trips.domestic') : route('trips.international') }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left"></i>
                    Вернуться к списку
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('trips.edit', ['trip' => $trip->id]) }}" class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-edit"></i>
                        Редактировать
                    </a>
                    @if(auth()->user()->isAdmin())
                        <form action="{{ route('trips.destroy', ['trip' => $trip->id]) }}"
                              method="POST"
                              style="display: inline;"
                              onsubmit="return confirm('Вы уверены, что хотите удалить этот рейс?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                                Удалить
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th style="width: 40%;">Тип рейса</th>
                            <td>{{ $trip->type_name }}</td>
                        </tr>
                        <tr>
                            <th>Номер фуры</th>
                            <td>{{ $trip->truck_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>ФИО водителя</th>
                            <td>{{ $trip->driver_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Статус рейса</th>
                            <td>
                                <span class="badge badge-info">
                                    {{ $trip->status_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Номер инвойса</th>
                            <td>{{ $trip->invoice_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Статус оплаты</th>
                            <td>
                                <span class="badge badge-{{ $trip->payment_status === 'paid' ? 'success' : ($trip->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                                    {{ $trip->payment_status_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Стоимость</th>
                            <td>{{ $trip->cost ? number_format($trip->cost, 2, '.', ' ') . ' ₽' : '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th style="width: 40%;">Дата погрузки</th>
                            <td>{{ $trip->loading_date?->format('d.m.Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Дата выгрузки</th>
                            <td>{{ $trip->unloading_date?->format('d.m.Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Адрес погрузки</th>
                            <td>{{ $trip->loading_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Адрес выгрузки</th>
                            <td>{{ $trip->unloading_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Реквизиты</th>
                            <td>{{ $trip->details ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Дата создания</th>
                            <td>{{ $trip->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Дата обновления</th>
                            <td>{{ $trip->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Грузы на рейсе --}}
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Грузы на рейсе ({{ $trip->cargoShipments->count() }})</h3>
        </div>

        <div class="card-body">
            @if($trip->cargoShipments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Номер груза</th>
                                <th>Клиент</th>
                                <th>Ответственный</th>
                                <th>Количество мест</th>
                                <th>Вес брутто</th>
                                <th>Нетто</th>
                                <th>Объём</th>
                                <th>Статус</th>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <th style="width: 50px;"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trip->cargoShipments as $cargo)
                                <tr>
                                    <td><a href="{{ route('cargo_shipments.show', ['cargoShipment' => $cargo->id]) }}">{{ $cargo->id }}</a></td>
                                    <td>{{ $cargo->cargo_number ?? '-' }}</td>
                                    <td>{{ $cargo->client?->name ?? '-' }}</td>
                                    <td>{{ $cargo->agent?->name ?? '-' }}</td>
                                    <td>{{ $cargo->places_count ?? '-' }}</td>
                                    <td>{{ $cargo->gross_weight_total ?? '-' }}</td>
                                    <td>{{ $cargo->net_weight_total ?? '-' }}</td>
                                    <td>{{ $cargo->volume_total ?? '-' }}</td>
                                    <td>{{ $cargo->cargo_status_name }}</td>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <td>
                                        <form action="{{ route('trips.detach_cargo', ['trip' => $trip->id, 'cargoShipment' => $cargo->id]) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Вы уверены, что хотите открепить груз от рейса?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr class="font-weight-bold bg-light">
                                <td colspan="4" class="text-right">ИТОГО:</td>
                                <td>{{ $trip->cargoShipments->sum('places_count') }}</td>
                                <td>{{ $trip->cargoShipments->sum('gross_weight_total') }}</td>
                                <td>{{ $trip->cargoShipments->sum('net_weight_total') }}</td>
                                <td>{{ $trip->cargoShipments->sum('volume_total') }}</td>
                                <td></td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <td></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">На этот рейс еще не добавлены грузы.</p>
            @endif
        </div>
    </div>
@endsection
