@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }} #{{ $request->id }}</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Информация о заявке</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($request->photo_path)
                            <div class="col-md-4 text-center">
                                <img src="{{ $request->photo_url }}" alt="Фото" style="max-width: 200px; max-height: 200px; object-fit: contain; border-radius: 4px;">
                            </div>
                        @endif
                        <div class="col-md-{{ $request->photo_path ? '8' : '12' }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">Клиент:</th>
                                    <td>{{ $request->client?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Ответственный:</th>
                                    <td>{{ $request->agent?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Статус:</th>
                                    <td>
                                        @include('cargo_rate_requests._status_badge', ['status' => $request->request_status])
                                    </td>
                                </tr>
                                <tr>
                                    <th>Наименование:</th>
                                    <td>{{ $request->product_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Материал:</th>
                                    <td>{{ $request->material ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Вес брутто:</th>
                                    <td>{{ $request->gross_weight_total ?? '-' }} кг</td>
                                </tr>
                                <tr>
                                    <th>Объём:</th>
                                    <td>{{ $request->volume_total ?? '-' }} м³</td>
                                </tr>
                                <tr>
                                    <th>Вес нетто:</th>
                                    <td>{{ $request->net_weight_total ?? '-' }} кг</td>
                                </tr>
                                <tr>
                                    <th>Адрес доставки:</th>
                                    <td>{{ $request->delivery_address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Рассчитанная ставка:</th>
                                    <td>
                                        @if($request->calculated_rate)
                                            <strong>{{ number_format($request->calculated_rate, 2, '.', ' ') }} ¥</strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @if($request->manager_notes)
                                    <tr>
                                        <th>Заметки менеджера:</th>
                                        <td>{{ $request->manager_notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Файлы --}}
            @if($request->files->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Файлы</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Размер</th>
                                    <th>Загружен</th>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || ((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id && $request->isPending()))
                                        <th style="width: 50px;"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($request->files as $file)
                                    <tr>
                                        <td>
                                            <a href="{{ $file->url }}" target="_blank" download="{{ $file->file_name }}">
                                                {{ $file->file_name }}
                                            </a>
                                        </td>
                                        <td>{{ $file->human_readable_size }}</td>
                                        <td>{{ $file->uploadedBy?->name ?? '-' }}</td>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isManager() || ((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id && $request->isPending()))
                                            <td>
                                                <form method="POST" action="{{ route('cargo_rate_requests.files.destroy', ['cargoRateRequest' => $request->id, 'fileId' => $file->id]) }}" onsubmit="return confirm('Удалить файл?');">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            {{-- Действия --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Действия</h3>
                </div>
                <div class="card-body">
                    {{-- Для менеджера --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        @if($request->isPending() || $request->isAwaitingApproval())
                            <a href="{{ route('cargo_rate_requests.edit', ['cargoRateRequest' => $request->id]) }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-edit"></i> Редактировать
                            </a>
                        @endif
                    @endif

                    {{-- Для клиента (владельца) --}}
                    @if((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id)
                        @if($request->isPending())
                            <a href="{{ route('cargo_rate_requests.edit', ['cargoRateRequest' => $request->id]) }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-edit"></i> Редактировать
                            </a>

                            <form method="POST" action="{{ route('cargo_rate_requests.destroy', ['cargoRateRequest' => $request->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Удалить заявку?');">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            </form>
                        @endif

                        @if($request->isAwaitingApproval())
                            <form method="POST" action="{{ route('cargo_rate_requests.approve', ['cargoRateRequest' => $request->id]) }}" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Принять ставку {{ number_format($request->calculated_rate, 2, '.', ' ') }} ₽ и создать груз?');">
                                    <i class="fas fa-check"></i> Принять
                                </button>
                            </form>

                            <form method="POST" action="{{ route('cargo_rate_requests.reject', ['cargoRateRequest' => $request->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Отклонить заявку?');">
                                    <i class="fas fa-times"></i> Отклонить
                                </button>
                            </form>
                        @endif
                    @endif

                    {{-- Связь с грузом --}}
                    @if($request->cargo_shipment_id)
                        <a href="{{ route('cargo_shipments.show', ['cargoShipment' => $request->cargo_shipment_id]) }}" class="btn btn-info btn-block">
                            <i class="fas fa-box"></i> Перейти к грузу
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
