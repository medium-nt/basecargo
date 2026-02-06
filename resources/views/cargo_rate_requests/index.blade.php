@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }} @if(request('archive') == '1') <small>(Архив)</small>@endif</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Форма создания заявки (только для клиентов) --}}
    @if(auth()->user()->isClient() || auth()->user()->isAgent())
        <div class="card card-primary mb-3">
            <div class="card-header">
                <h3 class="card-title">Создать заявку</h3>
            </div>
            <form action="{{ route('cargo_rate_requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Наименование товара</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material">Материал</label>
                                <input type="text" name="material" id="material" class="form-control" value="{{ old('material') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gross_weight_total">Вес брутто (кг)</label>
                                <input type="number" step="0.001" name="gross_weight_total" id="gross_weight_total" class="form-control" value="{{ old('gross_weight_total') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="volume_total">Объём (м³)</label>
                                <input type="number" step="0.001" name="volume_total" id="volume_total" class="form-control" value="{{ old('volume_total') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="net_weight_total">Вес нетто (кг)</label>
                                <input type="number" step="0.001" name="net_weight_total" id="net_weight_total" class="form-control" value="{{ old('net_weight_total') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="delivery_address">Адрес доставки</label>
                        <textarea name="delivery_address" id="delivery_address" rows="2" class="form-control">{{ old('delivery_address') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo">Фото</label>
                                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                                <small class="form-text text-muted">JPG, PNG, WebP. Макс. 5MB</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="files">Файлы (включая Excel)</label>
                                <input type="file" name="files[]" id="files" class="form-control-file" multiple accept=".xlsx,.xls,.csv">
                                <small class="form-text text-muted">XLS, XLSX, CSV. Максимум 10 файлов, до 10MB каждый</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Создать заявку
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Фильтры --}}
    <div class="row mb-3">
        @if(request('archive') != '1')
            <div class="col-md-3">
                <select name="request_status" id="request_status" onchange="updatePageWithQueryParam(this)" class="form-control">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('request_status') == 'pending' ? 'selected' : '' }}>Неразобрано</option>
                    <option value="awaiting_approval" {{ request('request_status') == 'awaiting_approval' ? 'selected' : '' }}>На согласовании</option>
                    <option value="approved" {{ request('request_status') == 'approved' ? 'selected' : '' }}>Согласовано</option>
                </select>
            </div>
        @else
            <div class="col-md-3">
                <span class="form-text text-muted">Показываются отклонённые заявки</span>
            </div>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            @if(request('archive') != '1')
                <div class="col-md-3">
                    <select name="client_id" id="client_id" onchange="updatePageWithQueryParam(this)" class="form-control">
                        <option value="">Все клиенты</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('cargo_rate_requests.index') }}" class="btn btn-default w-100">
                        <i class="fas fa-undo"></i> Сбросить фильтры
                    </a>
                </div>
            @else
                <div class="col-md-3">
                    <select name="client_id" id="client_id" onchange="updatePageWithQueryParam(this)" class="form-control">
                        <option value="">Все клиенты</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @else
            @if(request('archive') != '1')
                <div class="col-md-6">
                    <a href="{{ route('cargo_rate_requests.index') }}" class="btn btn-default w-100">
                        <i class="fas fa-undo"></i> Сбросить фильтры
                    </a>
                </div>
            @endif
        @endif

        <div class="col-md-3 ml-auto">
            @if(request('archive') == '1')
                <a href="{{ route('cargo_rate_requests.index', ['archive' => '0']) }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-list mr-1"></i> Активные
                </a>
            @else
                <a href="{{ route('cargo_rate_requests.index', ['archive' => '1']) }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-archive mr-1"></i> Архив
                </a>
            @endif
        </div>
    </div>

    {{-- Таблица заявок --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Фото</th>
                            <th>Клиент</th>
                            <th>Наименование</th>
                            <th>Вес брутто</th>
                            <th>Объём</th>
                            <th>Адрес доставки</th>
                            <th>Статус</th>
                            <th>Ставка</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>
                                    @if($request->photo_path)
                                        <a href="{{ route('cargo_rate_requests.show', ['cargoRateRequest' => $request->id]) }}">
                                            <img src="{{ $request->photo_url }}" alt="Фото" style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px;">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $request->client?->name ?? '' }}</td>
                                <td>{{ Str::limit($request->product_name ?? '-', 50) }}</td>
                                <td>{{ $request->gross_weight_total ?? '-' }}</td>
                                <td>{{ $request->volume_total ?? '-' }}</td>
                                <td>{{ Str::limit($request->delivery_address ?? '-', 30) }}</td>
                                <td>@include('cargo_rate_requests._status_badge', ['status' => $request->request_status])</td>
                                <td>{{ $request->calculated_rate ? number_format($request->calculated_rate, 2, '.', ' ') . ' ¥' : '-' }}</td>
                                <td>
                                    <a href="{{ route('cargo_rate_requests.show', ['cargoRateRequest' => $request->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Нет заявок, соответствующих выбранным параметрам
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination-component :collection="$requests" />
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/PageQueryParam.js') }}"></script>
@endpush
