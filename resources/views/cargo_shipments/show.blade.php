@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="client_id">Клиент</label>
                        <input type="text" id="client_id" class="form-control" value="{{ $shipment->client->name ?? '---' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="responsible_user_id">Ответственный</label>
                        <input type="text" id="responsible_user_id" class="form-control" value="{{ $shipment->agent->name ?? '---' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Информация о товаре</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="contact_phone">телефон получателя</label>
                        <input type="text" id="contact_phone" class="form-control" value="{{ $shipment->contact_phone ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label for="delivery_address">Адрес</label>
                        <textarea id="delivery_address" rows="2" class="form-control" readonly>{{ $shipment->delivery_address ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="china_tracking_number">трек-номер по Китаю</label>
                        <input type="text" id="china_tracking_number" class="form-control" value="{{ $shipment->china_tracking_number ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="china_cost">стоимость по Китаю</label>
                        <input type="number" id="china_cost" class="form-control" value="{{ $shipment->china_cost ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="crate">Обрешетка</label>
                        <input type="text" id="crate" class="form-control" value="{{ $shipment->crate ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="cargo_number">номер груза</label>
                        <input type="text" id="cargo_number" class="form-control" value="{{ $shipment->cargo_number ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="product_name">наименование товара</label>
                        <input type="text" id="product_name" class="form-control" value="{{ $shipment->product_name ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="material">материал</label>
                        <input type="text" id="material" class="form-control" value="{{ $shipment->material ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="packaging">упаковка</label>
                        <input type="text" id="packaging" class="form-control" value="{{ $shipment->packaging ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="places_count">количество мест</label>
                        <input type="number" id="places_count" class="form-control" value="{{ $shipment->places_count ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="items_per_place">количество товары/мест</label>
                        <input type="number" id="items_per_place" class="form-control" value="{{ $shipment->items_per_place ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="total_items_count">общее количество штук</label>
                        <input type="number" id="total_items_count" class="form-control" value="{{ $shipment->total_items_count ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="length">длина</label>
                        <input type="number" id="length" class="form-control" value="{{ $shipment->length ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="width">ширина</label>
                        <input type="number" id="width" class="form-control" value="{{ $shipment->width ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="height">высота</label>
                        <input type="number" id="height" class="form-control" value="{{ $shipment->height ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="volume_per_item">обьем 1 места</label>
                        <input type="number" id="volume_per_item" class="form-control" value="{{ $shipment->volume_per_item ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="volume_total">общий обьем кубов</label>
                        <input type="number" id="volume_total" class="form-control" value="{{ $shipment->volume_total ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="weight">вес</label>
                        <input type="number" id="weight" class="form-control" value="{{ $shipment->weight ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="gross_weight_per_place">вес брутто 1 места</label>
                        <input type="number" id="gross_weight_per_place" class="form-control" value="{{ $shipment->gross_weight_per_place ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="gross_weight_total">Общий вес брутто</label>
                        <input type="number" id="gross_weight_total" class="form-control" value="{{ $shipment->gross_weight_total ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="tare_weight_per_box">вес 1 тары</label>
                        <input type="number" id="tare_weight_per_box" class="form-control" value="{{ $shipment->tare_weight_per_box ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="tare_weight_total">вес всех коробок</label>
                        <input type="number" id="tare_weight_total" class="form-control" value="{{ $shipment->tare_weight_total ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="net_weight_per_box">Вес нетто 1 коробки</label>
                        <input type="number" id="net_weight_per_box" class="form-control" value="{{ $shipment->net_weight_per_box ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label for="net_weight_total">Общий вес нетто</label>
                        <input type="number" id="net_weight_total" class="form-control" value="{{ $shipment->net_weight_total ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="cargo_status">статус груза</label>
                        <select id="cargo_status" class="form-control" disabled>
                            <option value="">---</option>
                            <option value="wait_payment" {{ $shipment->cargo_status === 'wait_payment' ? 'selected' : '' }}>Ожидает оплаты</option>
                            <option value="shipping_supplier" {{ $shipment->cargo_status === 'shipping_supplier' ? 'selected' : '' }}>Отправка поставщиком</option>
                            <option value="china_transit" {{ $shipment->cargo_status === 'china_transit' ? 'selected' : '' }}>В пути по Китаю</option>
                            <option value="china_warehouse" {{ $shipment->cargo_status === 'china_warehouse' ? 'selected' : '' }}>На складе в Китае</option>
                            <option value="china_russia_transit" {{ $shipment->cargo_status === 'china_russia_transit' ? 'selected' : '' }}>В пути Китай–Россия</option>
                            <option value="russia_warehouse" {{ $shipment->cargo_status === 'russia_warehouse' ? 'selected' : '' }}>На складе в России</option>
                            <option value="russia_transit" {{ $shipment->cargo_status === 'russia_transit' ? 'selected' : '' }}>В пути по России</option>
                            <option value="received" {{ $shipment->cargo_status === 'received' ? 'selected' : '' }}>Получен</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="payment_type">Оплата</label>
                        <select id="payment_type" class="form-control" disabled>
                            <option value="">---</option>
                            <option value="cash" {{ $shipment->payment_type === 'cash' ? 'selected' : '' }}>Наличные</option>
                            <option value="card" {{ $shipment->payment_type === 'card' ? 'selected' : '' }}>Карта</option>
                            <option value="rs" {{ $shipment->payment_type === 'rs' ? 'selected' : '' }}>Р/с</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="payment_status">статус оплаты</label>
                        <select id="payment_status" class="form-control" disabled>
                            <option value="">---</option>
                            <option value="not_paid" {{ $shipment->payment_status === 'not_paid' ? 'selected' : '' }}>Не оплачен</option>
                            <option value="paid" {{ $shipment->payment_status === 'paid' ? 'selected' : '' }}>Оплачен</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Страховка</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="insurance_amount">Страховая сумма</label>
                        <input type="number" id="insurance_amount" class="form-control" value="{{ $shipment->insurance_amount ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="insurance_cost">Страховка</label>
                        <input type="number" id="insurance_cost" class="form-control" value="{{ $shipment->insurance_cost ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="rate_rub">посчитанная ставка в рублях</label>
                        <input type="number" id="rate_rub" class="form-control" value="{{ $shipment->rate_rub ?? '' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Оплата</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="total_cost">Сумма</label>
                        <input type="number" id="total_cost" class="form-control" value="{{ $shipment->total_cost ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="contact_phone_payment">Телефон</label>
                        <input type="text" id="contact_phone_payment" class="form-control" value="{{ $shipment->contact_phone_payment ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="bank_name">Банк</label>
                        <input type="text" id="bank_name" class="form-control" value="{{ $shipment->bank_name ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="bank_account_name">Имя</label>
                        <input type="text" id="bank_account_name" class="form-control" value="{{ $shipment->bank_account_name ?? '' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Даты</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="factory_shipping_date">дата отправки с завода</label>
                        <input type="date" id="factory_shipping_date" class="form-control" value="{{ $shipment->factory_shipping_date ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="sunfuihe_warehouse_received_date">дата получения склад Суньфуйхэ</label>
                        <input type="date" id="sunfuihe_warehouse_received_date" class="form-control" value="{{ $shipment->sunfuihe_warehouse_received_date ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="china_shipping_date">дата отправки с Китая</label>
                        <input type="date" id="china_shipping_date" class="form-control" value="{{ $shipment->china_shipping_date ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="ussuriysk_arrival_date">дата прибытия в Уссурийск</label>
                        <input type="date" id="ussuriysk_arrival_date" class="form-control" value="{{ $shipment->ussuriysk_arrival_date ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="ussuriysk_shipping_date">дата отправки с Уссурийска</label>
                        <input type="date" id="ussuriysk_shipping_date" class="form-control" value="{{ $shipment->ussuriysk_shipping_date ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="client_received_date">дата получения клиентом</label>
                        <input type="date" id="client_received_date" class="form-control" value="{{ $shipment->client_received_date ?? '' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a href="{{ route('cargo_shipments.edit', $shipment) }}" class="btn btn-primary">Редактировать</a>
            <form action="{{ route('cargo_shipments.destroy', $shipment) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить груз?')">Удалить</button>
            </form>
        </div>
    </div>
@endsection
