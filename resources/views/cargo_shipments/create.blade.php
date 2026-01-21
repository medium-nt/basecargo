@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@endsection

@section('content')
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
                            <input type="text" name="crate" id="crate" class="form-control @error('crate') is-invalid @enderror" value="{{ old('crate') }}">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="cargo_number">номер груза <small>货物编号</small></label>
                            <input type="text" name="cargo_number" id="cargo_number" class="form-control @error('cargo_number') is-invalid @enderror" value="{{ old('cargo_number') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="product_name">наименование товара <small>产品名称</small></label>
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
                            <label for="packaging">упаковка <small>包装</small></label>
                            <input type="text" name="packaging" id="packaging" class="form-control @error('packaging') is-invalid @enderror" value="{{ old('packaging') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="places_count">количество мест <small>座位数目</small></label>
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
                            <label for="volume_total">общий обьем кубов <small>立方体的总体积</small></label>
                            <input type="number" name="volume_total" id="volume_total" class="form-control @error('volume_total') is-invalid @enderror" value="{{ old('volume_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="volume_per_item">обьем 1 места <small>1个座位的体积</small></label>
                            <input type="number" name="volume_per_item" id="volume_per_item" class="form-control @error('volume_per_item') is-invalid @enderror" value="{{ old('volume_per_item') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="length">длина <small>长度</small></label>
                            <input type="number" name="length" id="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="width">ширина <small>阔度</small></label>
                            <input type="number" name="width" id="width" class="form-control @error('width') is-invalid @enderror" value="{{ old('width') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="height">высота <small>身高</small></label>
                            <input type="number" name="height" id="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="gross_weight_total">Общий вес брутто <small>总毛重</small></label>
                            <input type="number" name="gross_weight_total" id="gross_weight_total" class="form-control @error('gross_weight_total') is-invalid @enderror" value="{{ old('gross_weight_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="gross_weight_per_place">вес брутто 1 места <small>1个座位的毛重</small></label>
                            <input type="number" name="gross_weight_per_place" id="gross_weight_per_place" class="form-control @error('gross_weight_per_place') is-invalid @enderror" value="{{ old('gross_weight_per_place') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="net_weight_total">Общий вес нетто <small>总净重</small></label>
                            <input type="number" name="net_weight_total" id="net_weight_total" class="form-control @error('net_weight_total') is-invalid @enderror" value="{{ old('net_weight_total') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="net_weight_per_box">Вес нетто 1 коробки <small>净重1箱</small></label>
                            <input type="number" name="net_weight_per_box" id="net_weight_per_box" class="form-control @error('net_weight_per_box') is-invalid @enderror" value="{{ old('net_weight_per_box') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="tare_weight_total">вес всех коробок <small>所有箱子的重量</small></label>
                            <input type="number" name="tare_weight_total" id="tare_weight_total" class="form-control @error('tare_weight_total') is-invalid @enderror" value="{{ old('tare_weight_total') }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="tare_weight_per_box">вес 1 тары <small>1皮重</small></label>
                            <input type="number" name="tare_weight_per_box" id="tare_weight_per_box" class="form-control @error('tare_weight_per_box') is-invalid @enderror" value="{{ old('tare_weight_per_box') }}" readonly>
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
                            <input type="number" name="insurance_amount" id="insurance_amount" class="form-control @error('insurance_amount') is-invalid @enderror" value="{{ old('insurance_amount') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="insurance_cost">Страховка</label>
                            <input type="number" name="insurance_cost" id="insurance_cost" class="form-control @error('insurance_cost') is-invalid @enderror" value="{{ old('insurance_cost') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="rate_rub">посчитанная ставка в рублях</label>
                            <input type="number" name="rate_rub" id="rate_rub" class="form-control @error('rate_rub') is-invalid @enderror" value="{{ old('rate_rub') }}">
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
                            <input type="number" name="total_cost" id="total_cost" class="form-control @error('total_cost') is-invalid @enderror" value="{{ old('total_cost') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="contact_phone_payment">Телефон</label>
                            <input type="text" name="contact_phone_payment" id="contact_phone_payment" class="form-control @error('contact_phone_payment') is-invalid @enderror" value="{{ old('contact_phone_payment') }}">
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
</script>
@endpush
