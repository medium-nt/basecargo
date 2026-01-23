@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
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

    <form action="{{ route('cargo_shipments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="client_id">Клиент <small>客户群</small></label>
                            <select name="client_id" id="client_id" class="form-control select2 @error('client_id') is-invalid @enderror">
                                <option value="" {{ old('client_id') ? '' : 'selected' }}>---</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="responsible_user_id">Ответственный <small>负责</small></label>
                            <select name="responsible_user_id" id="responsible_user_id" class="form-control select2 @error('responsible_user_id') is-invalid @enderror">
                                <option value="" {{ old('responsible_user_id') ? '' : 'selected' }}>---</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('responsible_user_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
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
                            <label for="contact_phone">телефон получателя <small>电话号码</small></label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="delivery_address">Адрес <small>收件人地址</small></label>
                            <textarea name="delivery_address" id="delivery_address" rows="2" class="form-control @error('delivery_address') is-invalid @enderror">{{ old('delivery_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="china_tracking_number">трек-номер по Китаю <small>中国的轨道号码</small></label>
                            <input type="text" name="china_tracking_number" id="china_tracking_number" class="form-control @error('china_tracking_number') is-invalid @enderror" value="{{ old('china_tracking_number') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="china_cost">стоимость по Китаю <small>中国的成本</small></label>
                            <input type="number" name="china_cost" id="china_cost" class="form-control @error('china_cost') is-invalid @enderror" value="{{ old('china_cost') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="crate">Обрешетка <small>箱子</small></label>
                            <input type="number" step="0.01" name="crate" id="crate" class="form-control @error('crate') is-invalid @enderror" value="{{ old('crate') }}">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="cargo_number">номер груза <small>货物编号</small> <span class="text-danger">*</span></label>
                            <input type="text" name="cargo_number" id="cargo_number" class="form-control @error('cargo_number') is-invalid @enderror" value="{{ old('cargo_number') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="product_name">наименование товара <small>产品名称</small> <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" id="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="material">материал <small>材料</small></label>
                            <input type="text" name="material" id="material" class="form-control @error('material') is-invalid @enderror" value="{{ old('material') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="packaging">упаковка <small>包装</small> <span class="text-danger">*</span></label>
                            <input type="text" name="packaging" id="packaging" class="form-control @error('packaging') is-invalid @enderror" value="{{ old('packaging') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="places_count">количество мест <small>座位数目</small> <span class="text-danger">*</span></label>
                            <input type="number" min="1" step="1" name="places_count" id="places_count" class="form-control @error('places_count') is-invalid @enderror" value="{{ old('places_count') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="items_per_place">количество товары/мест <small>产品/地点数目</small></label>
                            <input type="number" min="1" step="1" name="items_per_place" id="items_per_place" class="form-control @error('items_per_place') is-invalid @enderror" value="{{ old('items_per_place') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="total_items_count">общее количество штук <small>件总数</small></label>
                            <input type="number" min="1" step="1" name="total_items_count" id="total_items_count" class="form-control @error('total_items_count') is-invalid @enderror" value="{{ old('total_items_count') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="volume_total">общий обьем кубов <small>立方体的总体积</small> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="volume_total" id="volume_total" class="form-control @error('volume_total') is-invalid @enderror" value="{{ old('volume_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="volume_per_item">обьем 1 места <small>1个座位的体积</small></label>
                            <input type="number" step="0.01" name="volume_per_item" id="volume_per_item" class="form-control @error('volume_per_item') is-invalid @enderror" value="{{ old('volume_per_item') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="length">длина <small>长度</small></label>
                            <input type="number" step="0.01" name="length" id="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="width">ширина <small>阔度</small></label>
                            <input type="number" step="0.01" name="width" id="width" class="form-control @error('width') is-invalid @enderror" value="{{ old('width') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="height">высота <small>身高</small></label>
                            <input type="number" step="0.01" name="height" id="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="gross_weight_total">Общий вес брутто <small>总毛重</small> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="gross_weight_total" id="gross_weight_total" class="form-control @error('gross_weight_total') is-invalid @enderror" value="{{ old('gross_weight_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="gross_weight_per_place">вес брутто 1 места <small>1个座位的毛重</small></label>
                            <input type="number" step="0.01" name="gross_weight_per_place" id="gross_weight_per_place" class="form-control @error('gross_weight_per_place') is-invalid @enderror" value="{{ old('gross_weight_per_place') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="net_weight_total">Общий вес нетто <small>总净重</small></label>
                            <input type="number" step="0.01" name="net_weight_total" id="net_weight_total" class="form-control @error('net_weight_total') is-invalid @enderror" value="{{ old('net_weight_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="net_weight_per_box">Вес нетто 1 коробки <small>净重1箱</small></label>
                            <input type="number" step="0.01" name="net_weight_per_box" id="net_weight_per_box" class="form-control @error('net_weight_per_box') is-invalid @enderror" value="{{ old('net_weight_per_box') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="tare_weight_total">вес всех коробок <small>所有箱子的重量</small></label>
                            <input type="number" step="0.01" name="tare_weight_total" id="tare_weight_total" class="form-control @error('tare_weight_total') is-invalid @enderror" value="{{ old('tare_weight_total') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="tare_weight_per_box">вес 1 тары <small>1皮重</small></label>
                            <input type="number" step="0.01" name="tare_weight_per_box" id="tare_weight_per_box" class="form-control @error('tare_weight_per_box') is-invalid @enderror" value="{{ old('tare_weight_per_box') }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cargo_status">статус груза</label>
                            <select name="cargo_status" id="cargo_status" class="form-control @error('cargo_status') is-invalid @enderror">
                                <option value="" {{ old('cargo_status') ? '' : 'selected' }}>---</option>
                                <option value="wait_payment" {{ old('cargo_status') == 'wait_payment' ? 'selected' : '' }}>Ожидает оплаты</option>
                                <option value="shipping_supplier" {{ old('cargo_status') == 'shipping_supplier' ? 'selected' : '' }}>Отправка поставщиком</option>
                                <option value="china_transit" {{ old('cargo_status') == 'china_transit' ? 'selected' : '' }}>В пути по Китаю</option>
                                <option value="china_warehouse" {{ old('cargo_status') == 'china_warehouse' ? 'selected' : '' }}>На складе в Китае</option>
                                <option value="china_russia_transit" {{ old('cargo_status') == 'china_russia_transit' ? 'selected' : '' }}>В пути Китай–Россия</option>
                                <option value="russia_warehouse" {{ old('cargo_status') == 'russia_warehouse' ? 'selected' : '' }}>На складе в России</option>
                                <option value="russia_transit" {{ old('cargo_status') == 'russia_transit' ? 'selected' : '' }}>В пути по России</option>
                                <option value="received" {{ old('cargo_status') == 'received' ? 'selected' : '' }}>Получен</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="payment_type">Оплата</label>
                            <select name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror">
                                <option value="" {{ old('payment_type') ? '' : 'selected' }}>---</option>
                                <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Наличные</option>
                                <option value="card" {{ old('payment_type') == 'card' ? 'selected' : '' }}>Карта</option>
                                <option value="rs" {{ old('payment_type') == 'rs' ? 'selected' : '' }}>Р/с</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="payment_status">статус оплаты</label>
                            <select name="payment_status" id="payment_status" class="form-control @error('payment_status') is-invalid @enderror">
                                <option value="" {{ old('payment_status') ? '' : 'selected' }}>---</option>
                                <option value="not_paid" {{ old('payment_status') == 'not_paid' ? 'selected' : '' }}>Не оплачен</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Оплачен</option>
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
                            <input type="number" step="0.01" name="insurance_amount" id="insurance_amount" class="form-control @error('insurance_amount') is-invalid @enderror" value="{{ old('insurance_amount') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="insurance_cost">Страховка</label>
                            <input type="number" step="0.01" name="insurance_cost" id="insurance_cost" class="form-control @error('insurance_cost') is-invalid @enderror" value="{{ old('insurance_cost') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="rate_rub">посчитанная ставка в рублях</label>
                            <input type="number" step="0.01" name="rate_rub" id="rate_rub" class="form-control @error('rate_rub') is-invalid @enderror" value="{{ old('rate_rub') }}">
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
                            <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control @error('total_cost') is-invalid @enderror" value="{{ old('total_cost') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="payment_phone">Телефон</label>
                            <input type="text" name="payment_phone" id="payment_phone" class="form-control @error('contact_phone_payment') is-invalid @enderror" value="{{ old('contact_phone_payment') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="bank_name">Банк</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="bank_account_name">Имя</label>
                            <input type="text" name="bank_account_name" id="bank_account_name" class="form-control @error('bank_account_name') is-invalid @enderror" value="{{ old('bank_account_name') }}">
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
                            <label for="factory_shipping_date">дата отправки с завода <small>从工厂装运日期</small></label>
                            <input type="date" name="factory_shipping_date" id="factory_shipping_date" class="form-control @error('factory_shipping_date') is-invalid @enderror" value="{{ old('factory_shipping_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="sunfuihe_warehouse_received_date">дата получения склад Суньфуйхэ <small>收货日期孙福河仓库</small></label>
                            <input type="date" name="sunfuihe_warehouse_received_date" id="sunfuihe_warehouse_received_date" class="form-control @error('sunfuihe_warehouse_received_date') is-invalid @enderror" value="{{ old('sunfuihe_warehouse_received_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="china_shipping_date">дата отправки с Китая <small>从中国发货日期</small></label>
                            <input type="date" name="china_shipping_date" id="china_shipping_date" class="form-control @error('china_shipping_date') is-invalid @enderror" value="{{ old('china_shipping_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="ussuriysk_arrival_date">дата прибытия в Уссурийск <small>到达乌苏里斯克的日期</small></label>
                            <input type="date" name="ussuriysk_arrival_date" id="ussuriysk_arrival_date" class="form-control @error('ussuriysk_arrival_date') is-invalid @enderror" value="{{ old('ussuriysk_arrival_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="ussuriysk_shipping_date">дата отправки с Уссурийска <small>从乌苏里斯克发货日期</small></label>
                            <input type="date" name="ussuriysk_shipping_date" id="ussuriysk_shipping_date" class="form-control @error('ussuriysk_shipping_date') is-invalid @enderror" value="{{ old('ussuriysk_shipping_date') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="client_received_date">дата получения клиентом <small>客户收到日期</small></label>
                            <input type="date" name="client_received_date" id="client_received_date" class="form-control @error('client_received_date') is-invalid @enderror" value="{{ old('client_received_date') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Калькулятор</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="explanations">поясниния <small></small></label>
                            <input type="text" name="explanations" id="explanations" class="form-control @error('explanations') is-invalid @enderror" value="{{ old('explanations') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="TN_VED_code">код ТНВЭД <small></small></label>
                            <input type="text" name="TN_VED_code" id="TN_VED_code" class="form-control @error('TN_VED_code') is-invalid @enderror" value="{{ old('TN_VED_code') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="payment">платеж <small></small></label>
                            <input type="number" step="0.01" name="payment" id="payment" class="form-control @error('payment') is-invalid @enderror" value="{{ old('payment') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="label_name">НАИМЕНОВАНИЕ ДЛЯ БИРКИ <small></small></label>
                            <input type="text" name="label_name" id="label_name" class="form-control @error('label_name') is-invalid @enderror" value="{{ old('label_name') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="label_number">номер бирки <small></small></label>
                            <input type="text" name="label_number" id="label_number" class="form-control @error('label_number') is-invalid @enderror" value="{{ old('label_number') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="marking">Маркировка <small></small></label>
                            <input type="text" name="marking" id="marking" class="form-control @error('marking') is-invalid @enderror" value="{{ old('marking') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="manufacturer">Изготовитель <small></small></label>
                            <input type="text" name="manufacturer" id="manufacturer" class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="SS/DS">СС/ДС <small></small></label>
                            <input type="text" name="SS/DS" id="SS/DS" class="form-control @error('SS/DS') is-invalid @enderror" value="{{ old('SS/DS') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="bet">Ставка <small></small></label>
                            <input type="text" name="bet" id="bet" class="form-control @error('bet') is-invalid @enderror" value="{{ old('bet') }}">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="ITS">ИТС <small></small></label>
                            <input type="number" step="0.01" name="ITS" id="ITS" class="form-control @error('ITS') is-invalid @enderror" value="{{ old('ITS') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="duty">пошлина % <small></small></label>
                            <input type="number" step="0.01" name="duty" id="duty" class="form-control @error('duty') is-invalid @enderror" value="{{ old('duty') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="VAT">НДС % <small></small></label>
                            <input type="number" step="0.01" name="VAT" id="VAT" class="form-control @error('VAT') is-invalid @enderror" value="{{ old('VAT') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="volume_weight">Коэффициент объёмного веса <small></small></label>
                            <input type="number" step="0.01" name="volume_weight" id="volume_weight" class="form-control @error('volume_weight') is-invalid @enderror" value="{{ old('volume_weight') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="customs_clearance_service">Услуга таможенных оформлений <small></small></label>
                            <input type="number" step="0.01" name="customs_clearance_service" id="customs_clearance_service" class="form-control @error('customs_clearance_service') is-invalid @enderror" value="{{ old('customs_clearance_service') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="cost_truck">стоимость фуры <small></small></label>
                            <input type="number" step="0.01" name="cost_truck" id="cost_truck" class="form-control @error('cost_truck') is-invalid @enderror" value="{{ old('cost_truck') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="revenue_per_kg">выручка за кг. <small></small></label>
                            <input type="number" step="0.01" name="revenue_per_kg" id="revenue_per_kg" class="form-control @error('revenue_per_kg') is-invalid @enderror" value="{{ old('revenue_per_kg') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="dollar_rate">курс $ <small></small></label>
                            <input type="number" step="0.01" name="dollar_rate" id="dollar_rate" class="form-control @error('dollar_rate') is-invalid @enderror" value="{{ old('dollar_rate') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="yuan_rate">курс ¥ <small></small></label>
                            <input type="number" step="0.01" name="yuan_rate" id="yuan_rate" class="form-control @error('yuan_rate') is-invalid @enderror" value="{{ old('yuan_rate') }}">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="estimated_value_cargo_ITS">стоимость груза по ИТС $ <small></small></label>
                            <input type="number" step="0.01" id="estimated_value_cargo_ITS" class="form-control @error('estimated_value_cargo_ITS') is-invalid @enderror" value="{{ old('estimated_value_cargo_ITS') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="total_payment">Общий платеж $ <small></small></label>
                            <input type="number" step="0.01" id="total_payment" class="form-control @error('total_payment') is-invalid @enderror" value="{{ old('total_payment') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="importer_services">услуги импортера $ <small></small></label>
                            <input type="number" step="0.01" id="importer_services" class="form-control @error('importer_services') is-invalid @enderror" value="{{ old('importer_services') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="delivery_to_Ussuriysk">доставка общая до Уссурийска ₽ <small></small></label>
                            <input type="number" step="0.01" id="delivery_to_Ussuriysk" class="form-control @error('delivery_to_Ussuriysk') is-invalid @enderror" value="{{ old('delivery_to_Ussuriysk') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="revenue">выручка ₽ <small></small></label>
                            <input type="number" step="0.01" id="revenue" class="form-control @error('revenue') is-invalid @enderror" value="{{ old('revenue') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="total">Итого ₽ <small></small></label>
                            <input type="number" step="0.01" id="total" class="form-control @error('total') is-invalid @enderror" value="{{ old('total') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="total_per_kg">Итого за КГ ¥ <small></small></label>
                            <input type="number" step="0.01" id="total_per_kg" class="form-control @error('total_per_kg') is-invalid @enderror" value="{{ old('total_per_kg') }}" readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
            padding: 0.375rem 0.75rem;
        }
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
            padding: 0.375rem 0.75rem;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
        }

    </style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
    });

    function calculateGrossWeightPerPlace() {
        console.log('calc...');
        const grossWeightTotal = parseFloat(document.getElementById('gross_weight_total').value) || 0;
        const placesCount = parseInt(document.getElementById('places_count').value) || 0;

        if (placesCount > 0 && grossWeightTotal > 0) {
            const result = grossWeightTotal / placesCount;
            document.getElementById('gross_weight_per_place').value = result.toFixed(2);
        } else {
            document.getElementById('gross_weight_per_place').value = '';
        }

        calculateTareWeightPerBox();
    }

    function calculateVolumePerItem() {
        const volumeTotal = parseFloat(document.getElementById('volume_total').value) || 0;
        const placesCount = parseInt(document.getElementById('places_count').value) || 0;

        if (placesCount > 0 && volumeTotal > 0) {
            const result = volumeTotal / placesCount;
            document.getElementById('volume_per_item').value = result.toFixed(2);
        } else {
            document.getElementById('volume_per_item').value = '';
        }
    }

    function calculateTareWeightTotal() {
        const grossWeightTotal = parseFloat(document.getElementById('gross_weight_total').value) || 0;
        const netWeightTotal = parseFloat(document.getElementById('net_weight_total').value) || 0;

        if (grossWeightTotal > 0 && netWeightTotal > 0) {
            const result = grossWeightTotal - netWeightTotal;
            document.getElementById('tare_weight_total').value = result.toFixed(2);
        } else {
            document.getElementById('tare_weight_total').value = '';
        }
    }

    function calculateNetWeightPerBox() {
        const netWeightTotal = parseFloat(document.getElementById('net_weight_total').value) || 0;
        const placesCount = parseInt(document.getElementById('places_count').value) || 0;

        if (placesCount > 0 && netWeightTotal > 0) {
            const result = netWeightTotal / placesCount;
            document.getElementById('net_weight_per_box').value = result.toFixed(2);
        } else {
            document.getElementById('net_weight_per_box').value = '';
        }

        calculateTareWeightPerBox();
    }

    function calculateTareWeightPerBox() {
        const grossWeightPerPlace = parseFloat(document.getElementById('gross_weight_per_place').value) || 0;
        const netWeightPerBox = parseFloat(document.getElementById('net_weight_per_box').value) || 0;

        if (grossWeightPerPlace > 0 && netWeightPerBox > 0) {
            const result = grossWeightPerPlace - netWeightPerBox;
            document.getElementById('tare_weight_per_box').value = result.toFixed(2);
        } else {
            document.getElementById('tare_weight_per_box').value = '';
        }
    }

    document.getElementById('gross_weight_total').addEventListener('input', calculateGrossWeightPerPlace);
    document.getElementById('places_count').addEventListener('input', calculateGrossWeightPerPlace);

    document.getElementById('volume_total').addEventListener('input', calculateVolumePerItem);
    document.getElementById('places_count').addEventListener('input', calculateVolumePerItem);

    document.getElementById('gross_weight_total').addEventListener('input', calculateTareWeightTotal);
    document.getElementById('net_weight_total').addEventListener('input', calculateTareWeightTotal);

    document.getElementById('net_weight_total').addEventListener('input', calculateNetWeightPerBox);
    document.getElementById('places_count').addEventListener('input', calculateNetWeightPerBox);

    // ========== Автоматический расчёт вычисляемых полей ==========

    function calculateEstimatedValue() {
        const netWeight = parseFloat(document.getElementById('net_weight_total').value) || 0;
        const its = parseFloat(document.getElementById('ITS').value) || 0;
        const result = netWeight * its;
        document.getElementById('estimated_value_cargo_ITS').value = result.toFixed(2);
        calculateTotalPayment();
    }

    function calculateTotalPayment() {
        const A = parseFloat(document.getElementById('estimated_value_cargo_ITS').value) || 0;
        const duty = parseFloat(document.getElementById('duty').value) / 100 || 0;
        const vat = parseFloat(document.getElementById('VAT').value) / 100 || 0;
        const result = (A * duty) + (A + (A * duty)) * vat;
        document.getElementById('total_payment').value = result.toFixed(2);
        calculateImporterServices();
    }

    function calculateImporterServices() {
        const volume = parseFloat(document.getElementById('volume_total').value) || 0;
        const volumeWeight = parseFloat(document.getElementById('volume_weight').value) || 0;
        const service = parseFloat(document.getElementById('customs_clearance_service').value) || 0;

        if (volumeWeight > 0) {
            const result = (volume / volumeWeight) * service;
            document.getElementById('importer_services').value = result.toFixed(2);
        } else {
            document.getElementById('importer_services').value = '0.00';
        }
        calculateDeliveryToUssuriysk();
    }

    function calculateDeliveryToUssuriysk() {
        const costTruck = parseFloat(document.getElementById('cost_truck').value) || 0;
        const volumeWeight = parseFloat(document.getElementById('volume_weight').value) || 0;
        const volume = parseFloat(document.getElementById('volume_total').value) || 0;

        if (volumeWeight > 0) {
            const result = (costTruck / volumeWeight) * volume;
            document.getElementById('delivery_to_Ussuriysk').value = result.toFixed(2);
        } else {
            document.getElementById('delivery_to_Ussuriysk').value = '0.00';
        }
        calculateRevenue();
    }

    function calculateRevenue() {
        const revenuePerKg = parseFloat(document.getElementById('revenue_per_kg').value) || 0;
        const grossWeight = parseFloat(document.getElementById('gross_weight_total').value) || 0;
        const result = revenuePerKg * grossWeight;
        document.getElementById('revenue').value = result.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        const B = parseFloat(document.getElementById('total_payment').value) || 0;
        const C = parseFloat(document.getElementById('importer_services').value) || 0;
        const D = parseFloat(document.getElementById('delivery_to_Ussuriysk').value) || 0;
        const E = parseFloat(document.getElementById('revenue').value) || 0;
        const dollarRate = parseFloat(document.getElementById('dollar_rate').value) || 0;
        const result = ((B + C) * dollarRate) + D + E;
        document.getElementById('total').value = result.toFixed(2);
        calculateTotalPerKg();
    }

    function calculateTotalPerKg() {
        const G = parseFloat(document.getElementById('total').value) || 0;
        const grossWeight = parseFloat(document.getElementById('gross_weight_total').value) || 0;
        const yuanRate = parseFloat(document.getElementById('yuan_rate').value) || 0;

        if (grossWeight > 0 && yuanRate > 0) {
            const result = G / grossWeight / yuanRate;
            document.getElementById('total_per_kg').value = result.toFixed(2);
        } else {
            document.getElementById('total_per_kg').value = '0.00';
        }
    }

    // Event listeners для вычисляемых полей
    document.getElementById('net_weight_total').addEventListener('input', calculateEstimatedValue);
    document.getElementById('ITS').addEventListener('input', calculateEstimatedValue);
    document.getElementById('duty').addEventListener('input', calculateEstimatedValue);
    document.getElementById('VAT').addEventListener('input', calculateEstimatedValue);

    document.getElementById('volume_total').addEventListener('input', calculateEstimatedValue);
    document.getElementById('volume_weight').addEventListener('input', calculateEstimatedValue);
    document.getElementById('customs_clearance_service').addEventListener('input', calculateEstimatedValue);

    document.getElementById('cost_truck').addEventListener('input', calculateEstimatedValue);

    document.getElementById('revenue_per_kg').addEventListener('input', calculateEstimatedValue);
    document.getElementById('gross_weight_total').addEventListener('input', calculateEstimatedValue);

    document.getElementById('dollar_rate').addEventListener('input', calculateEstimatedValue);
    document.getElementById('yuan_rate').addEventListener('input', calculateEstimatedValue);
</script>
@endpush
