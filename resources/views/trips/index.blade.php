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
                {{-- Фильтр по статусу --}}
                <div class="col-md-3">
                    <select name="status" id="status"
                            onchange="updatePageWithQueryParam(this)"
                            class="form-control mb-3">
                        <option value="">Все статусы</option>
                        <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Запланирован</option>
                        <option value="loading" {{ request('status') == 'loading' ? 'selected' : '' }}>Погрузка</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>В пути</option>
                        <option value="unloading" {{ request('status') == 'unloading' ? 'selected' : '' }}>Разгрузка</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершен</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ $currentType === 'domestic' ? route('trips.domestic') : route('trips.international') }}" class="btn btn-default mb-3">
                        <i class="fas fa-undo mr-1"></i>
                        Сбросить фильтры
                    </a>
                </div>

                <div class="ml-auto mr-2">
                    {{-- Кнопка добавления рейса --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                        <a href="{{ route('trips.create') }}" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i>
                            Добавить рейс
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Номер фуры</th>
                            <th>Водитель</th>
                            <th>Статус</th>
                            <th>Дата погрузки</th>
                            <th>Дата выгрузки</th>
                            <th>Стоимость</th>
                            <th>Статус оплаты</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trips as $trip)
                            <tr>
                                <td>{{ $trip->id }}</td>
                                <td>{{ $trip->truck_number ?? '-' }}</td>
                                <td>{{ $trip->driver_name ?? '-' }}</td>
                                <td>{{ $trip->status_name }}</td>
                                <td>{{ $trip->loading_date?->format('d.m.Y') ?? '-' }}</td>
                                <td>{{ $trip->unloading_date?->format('d.m.Y') ?? '-' }}</td>
                                <td>{{ $trip->cost ? number_format($trip->cost, 2, '.', ' ') . ' ₽' : '-' }}</td>
                                <td>{{ $trip->payment_status_name }}</td>
                                <td>
                                    <div class="row">
                                        <a href="{{ route('trips.show', ['trip' => $trip->id]) }}"
                                           class="btn btn-primary mr-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('trips.destroy', ['trip' => $trip->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить этот рейс?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Нет рейсов, соответствующих выбранным параметрам
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <x-pagination-component :collection="$trips" />

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/PageQueryParam.js') }}"></script>
@endpush
