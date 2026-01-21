@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isManager())
            <a href="{{ route('cargo_shipments.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i>
                Добавить груз
            </a>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Клиент 客户群</th>
                            <th>Ответственный 负责</th>
                            <th>Трек-номер по Китаю 中国的轨道号码</th>
                            <th>Номер груза 货物编号</th>
                            <th>Статус груза 货物状况</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>{{ $shipment->client?->name ?? '' }}</td>
                                <td>{{ $shipment->agent?->name ?? '' }}</td>
                                <td>{{ $shipment->china_tracking_number }}</td>
                                <td>{{ $shipment->cargo_number }}</td>
                                <td>{{ $shipment->cargo_status }}</td>
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
                                <td colspan="7" class="text-center text-muted">
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
