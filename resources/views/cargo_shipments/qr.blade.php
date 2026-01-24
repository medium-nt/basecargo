<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .field-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .field-label-cn {
            font-size: 0.75rem;
            color: #adb5bd;
        }
        .field-value {
            font-weight: 500;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .photo-placeholder {
            width: 100%;
            height: 200px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        .cargo-photo {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 0.375rem;
        }
        .photo-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-box"></i> Груз / 货物
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Трек-номер -->
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-barcode"></i> Трек-номер / 追踪号码
                            </h5>
                            <p class="mb-0 fs-4">{{ $shipment->china_tracking_number }}</p>
                        </div>

                        <!-- Главная фотография -->
                        @if($shipment->photo_path)
                        <div class="card mb-3">
                            <div class="card-body p-2 photo-section">
                                <img src="{{ Storage::url($shipment->photo_path) }}" alt="Фото груза" class="cargo-photo">
                            </div>
                        </div>
                        @else
                        <div class="card mb-3">
                            <div class="card-body p-2 photo-section">
                                <div class="alert alert-secondary mb-0" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <div class="text-muted">Фото не загружено</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Основная информация -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 section-title">
                                    <i class="fas fa-info-circle"></i> Основная информация / 基本信息
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @if($shipment->agent)
                                    <div class="col-md-6">
                                        <div class="field-label">Ответственный</div>
                                        <div class="field-label-cn">负责</div>
                                        <div class="field-value">{{ $shipment->agent->name }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Ответственный</div>
                                        <div class="field-label-cn">负责</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->delivery_address)
                                    <div class="col-md-6">
                                        <div class="field-label">Адрес получателя</div>
                                        <div class="field-label-cn">收件人地址</div>
                                        <div class="field-value">{{ $shipment->delivery_address }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Адрес получателя</div>
                                        <div class="field-label-cn">收件人地址</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->cargo_status)
                                    <div class="col-md-6">
                                        <div class="field-label">Статус груза</div>
                                        <div class="field-label-cn">货物状况</div>
                                        <div class="field-value">
                                            <span class="badge bg-info">{{ $shipment->cargo_status }}</span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Статус груза</div>
                                        <div class="field-label-cn">货物状况</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->payment_status)
                                    <div class="col-md-6">
                                        <div class="field-label">Статус оплаты</div>
                                        <div class="field-label-cn">付款状态</div>
                                        <div class="field-value">
                                            <span class="badge bg-success">{{ $shipment->payment_status }}</span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Статус оплаты</div>
                                        <div class="field-label-cn">付款状态</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->crate)
                                    <div class="col-md-6">
                                        <div class="field-label">Обрешетка</div>
                                        <div class="field-label-cn">箱子</div>
                                        <div class="field-value">{{ $shipment->crate }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Обрешетка</div>
                                        <div class="field-label-cn">箱子</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <!-- Информация о грузе -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 section-title">
                                    <i class="fas fa-box"></i> Информация о грузе / 货物信息
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @if($shipment->cargo_number)
                                    <div class="col-md-6">
                                        <div class="field-label">Номер груза</div>
                                        <div class="field-label-cn">货物编号</div>
                                        <div class="field-value">{{ $shipment->cargo_number }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Номер груза</div>
                                        <div class="field-label-cn">货物编号</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->product_name)
                                    <div class="col-md-6">
                                        <div class="field-label">Наименование товара</div>
                                        <div class="field-label-cn">产品名称</div>
                                        <div class="field-value">{{ $shipment->product_name }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Наименование товара</div>
                                        <div class="field-label-cn">产品名称</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->material)
                                    <div class="col-md-6">
                                        <div class="field-label">Материал</div>
                                        <div class="field-label-cn">材料</div>
                                        <div class="field-value">{{ $shipment->material }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Материал</div>
                                        <div class="field-label-cn">材料</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->packaging)
                                    <div class="col-md-6">
                                        <div class="field-label">Упаковка</div>
                                        <div class="field-label-cn">包装</div>
                                        <div class="field-value">{{ $shipment->packaging }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="field-label">Упаковка</div>
                                        <div class="field-label-cn">包装</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Количество и вес -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 section-title">
                                    <i class="fas fa-weight-hanging"></i> Количество и вес / 数量和重量
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @if($shipment->places_count)
                                    <div class="col-md-4">
                                        <div class="field-label">Количество мест</div>
                                        <div class="field-label-cn">座位数目</div>
                                        <div class="field-value">{{ $shipment->places_count }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Количество мест</div>
                                        <div class="field-label-cn">座位数目</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->items_per_place)
                                    <div class="col-md-4">
                                        <div class="field-label">Кол-во товара/мест</div>
                                        <div class="field-label-cn">产品/地点数目</div>
                                        <div class="field-value">{{ $shipment->items_per_place }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Кол-во товара/мест</div>
                                        <div class="field-label-cn">产品/地点数目</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->total_items_count)
                                    <div class="col-md-4">
                                        <div class="field-label">Общее кол-во штук</div>
                                        <div class="field-label-cn">件总数</div>
                                        <div class="field-value">{{ $shipment->total_items_count }}</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Общее кол-во штук</div>
                                        <div class="field-label-cn">件总数</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->gross_weight_per_place)
                                    <div class="col-md-4">
                                        <div class="field-label">Вес брутто 1 места</div>
                                        <div class="field-label-cn">1个座位的毛重</div>
                                        <div class="field-value">{{ number_format($shipment->gross_weight_per_place, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Вес брутто 1 места</div>
                                        <div class="field-label-cn">1个座位的毛重</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->gross_weight_total)
                                    <div class="col-md-4">
                                        <div class="field-label">Общий вес брутто</div>
                                        <div class="field-label-cn">总毛重</div>
                                        <div class="field-value">{{ number_format($shipment->gross_weight_total, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Общий вес брутто</div>
                                        <div class="field-label-cn">总毛重</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->length || $shipment->width || $shipment->height)
                                    <div class="col-md-4">
                                        <div class="field-label">Габариты (ДхШхВ)</div>
                                        <div class="field-label-cn">尺寸 (长x宽x高)</div>
                                        <div class="field-value">
                                            {{ $shipment->length }} x {{ $shipment->width }} x {{ $shipment->height }} см
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Габариты (ДхШхВ)</div>
                                        <div class="field-label-cn">尺寸 (长x宽x高)</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->volume_per_item)
                                    <div class="col-md-4">
                                        <div class="field-label">Объем 1 места</div>
                                        <div class="field-label-cn">1个座位的体积</div>
                                        <div class="field-value">{{ number_format($shipment->volume_per_item, 3) }} м³</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Объем 1 места</div>
                                        <div class="field-label-cn">1个座位的体积</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->volume_total)
                                    <div class="col-md-4">
                                        <div class="field-label">Общий объем кубов</div>
                                        <div class="field-label-cn">立方体的总体积</div>
                                        <div class="field-value">{{ number_format($shipment->volume_total, 3) }} м³</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Общий объем кубов</div>
                                        <div class="field-label-cn">立方体的总体积</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->tare_weight_per_box)
                                    <div class="col-md-4">
                                        <div class="field-label">Вес 1 тары</div>
                                        <div class="field-label-cn">1皮重</div>
                                        <div class="field-value">{{ number_format($shipment->tare_weight_per_box, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Вес 1 тары</div>
                                        <div class="field-label-cn">1皮重</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->tare_weight_total)
                                    <div class="col-md-4">
                                        <div class="field-label">Вес всех коробок</div>
                                        <div class="field-label-cn">所有箱子的重量</div>
                                        <div class="field-value">{{ number_format($shipment->tare_weight_total, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Вес всех коробок</div>
                                        <div class="field-label-cn">所有箱子的重量</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->net_weight_per_box)
                                    <div class="col-md-4">
                                        <div class="field-label">Вес нетто 1 коробки</div>
                                        <div class="field-label-cn">净重1箱</div>
                                        <div class="field-value">{{ number_format($shipment->net_weight_per_box, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Вес нетто 1 коробки</div>
                                        <div class="field-label-cn">净重1箱</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif

                                    @if($shipment->net_weight_total)
                                    <div class="col-md-4">
                                        <div class="field-label">Общий вес нетто</div>
                                        <div class="field-label-cn">总净重</div>
                                        <div class="field-value">{{ number_format($shipment->net_weight_total, 3) }} кг</div>
                                    </div>
                                    @else
                                    <div class="col-md-4">
                                        <div class="field-label">Общий вес нетто</div>
                                        <div class="field-label-cn">总净重</div>
                                        <div class="field-value text-muted">—</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @auth
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('cargo_shipments.show', ['cargoShipment' => $shipment->id]) }}"
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-external-link-alt"></i> Просмотр в системе / 在系统中查看
                                </a>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
