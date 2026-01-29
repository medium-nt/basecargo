@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('trips.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Тип рейса <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Выберите тип</option>
                                <option value="domestic">По России</option>
                                <option value="international">Китай-Россия</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="truck_number">Номер фуры</label>
                            <input type="text" name="truck_number" id="truck_number"
                                   class="form-control"
                                   value="{{ old('truck_number') }}"
                                   placeholder="А123БВ777">
                            @error('truck_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driver_name">ФИО водителя</label>
                            <input type="text" name="driver_name" id="driver_name"
                                   class="form-control"
                                   value="{{ old('driver_name') }}"
                                   placeholder="Иванов Иван Иванович">
                            @error('driver_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="invoice_number">Номер инвойса</label>
                            <input type="text" name="invoice_number" id="invoice_number"
                                   class="form-control"
                                   value="{{ old('invoice_number') }}"
                                   placeholder="INV-2024-001">
                            @error('invoice_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Статус рейса</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Не выбран</option>
                                <option value="planned">Запланирован</option>
                                <option value="loading">Погрузка</option>
                                <option value="in_transit">В пути</option>
                                <option value="unloading">Разгрузка</option>
                                <option value="completed">Завершен</option>
                                <option value="cancelled">Отменен</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_status">Статус оплаты</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="">Не выбран</option>
                                <option value="unpaid">Не оплачен</option>
                                <option value="partial">Частично оплачен</option>
                                <option value="paid">Оплачен</option>
                            </select>
                            @error('payment_status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="loading_date">Дата погрузки</label>
                            <input type="date" name="loading_date" id="loading_date"
                                   class="form-control"
                                   value="{{ old('loading_date') }}">
                            @error('loading_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unloading_date">Дата выгрузки</label>
                            <input type="date" name="unloading_date" id="unloading_date"
                                   class="form-control"
                                   value="{{ old('unloading_date') }}">
                            @error('unloading_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cost">Стоимость (₽)</label>
                            <input type="number" name="cost" id="cost"
                                   class="form-control"
                                   value="{{ old('cost') }}"
                                   step="0.01" min="0"
                                   placeholder="0.00">
                            @error('cost')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="loading_address">Адрес погрузки</label>
                    <textarea name="loading_address" id="loading_address"
                              class="form-control"
                              rows="2">{{ old('loading_address') }}</textarea>
                    @error('loading_address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unloading_address">Адрес выгрузки</label>
                    <textarea name="unloading_address" id="unloading_address"
                              class="form-control"
                              rows="2">{{ old('unloading_address') }}</textarea>
                    @error('unloading_address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="details">Реквизиты</label>
                    <textarea name="details" id="details"
                              class="form-control"
                              rows="3">{{ old('details') }}</textarea>
                    @error('details')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>
                        Создать рейс
                    </button>
                    <a href="{{ url()->previous() ?: route('trips.domestic') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i>
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
