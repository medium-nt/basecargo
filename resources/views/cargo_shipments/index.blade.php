@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <a href="{{ route('cargo_shipments.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i>
                Добавить груз
            </a>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Номер</th>
                            <th>Клиент</th>
                            <th>Трек-номер по Китаю</th>
                            <th>Статус груза</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>{{ $shipment->client_name }}</td>
                                <td>{{ $shipment->china_tracking_number }}</td>
                                <td>{{ $shipment->cargo_status }}</td>
                                <td>
                                    <a href="{{ route('cargo_shipments.show_qr', ['cargoShipment' => $shipment->id]) }}"
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa fa-qrcode"></i>
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
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
