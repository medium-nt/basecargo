@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Информация о рейсе #{{ $trip->id }}</h3>
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="card-tools">
                    <a href="{{ route('trips.edit', ['trip' => $trip->id]) }}" class="btn btn-warning btn-sm">
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
                </div>
            @endif
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
                                <th>Объём</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trip->cargoShipments as $cargo)
                                <tr>
                                    <td>{{ $cargo->id }}</td>
                                    <td>{{ $cargo->cargo_number ?? '-' }}</td>
                                    <td>{{ $cargo->client?->name ?? '-' }}</td>
                                    <td>{{ $cargo->agent?->name ?? '-' }}</td>
                                    <td>{{ $cargo->places_count ?? '-' }}</td>
                                    <td>{{ $cargo->gross_weight_total ?? '-' }}</td>
                                    <td>{{ $cargo->volume_total ?? '-' }}</td>
                                    <td>{{ $cargo->cargo_status_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">На этот рейс еще не добавлены грузы.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ $trip->type === 'domestic' ? route('trips.domestic') : route('trips.international') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i>
            Вернуться к списку
        </a>
    </div>
@endsection
