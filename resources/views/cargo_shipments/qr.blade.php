<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-box"></i> Груз
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Трек-номер -->
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-barcode"></i> Трек-номер по Китаю
                            </h5>
                            <p class="mb-0 fs-4">{{ $shipment->china_tracking_number }}</p>
                        </div>

                        <div class="row">
                            <!-- Клиент -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-user"></i> Клиент
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="fw-bold" style="width: 120px;">Имя:</td>
                                                <td>{{ $shipment->client_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Телефон:</td>
                                                <td>{{ $shipment->client_phone }}</td>
                                            </tr>
                                            @if($shipment->client_messenger)
                                                <tr>
                                                    <td class="fw-bold">{{ $shipment->client_messenger }}:</td>
                                                    <td>{{ $shipment->client_messenger_number }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="fw-bold">Адрес:</td>
                                                <td>{{ $shipment->recipient_address }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Груз -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-box"></i> Груз
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="fw-bold" style="width: 100px;">Тип:</td>
                                                <td>{{ $shipment->cargo_type }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Вес:</td>
                                                <td>{{ $shipment->cargo_weight }} кг</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Объём:</td>
                                                <td>{{ $shipment->cargo_volume }} м³</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Ящик:</td>
                                                <td>{{ $shipment->crate }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Статус:</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $shipment->cargo_status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Оплата -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-credit-card"></i> Оплата
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Тип оплаты:</strong></p>
                                        <p>{{ $shipment->payment_type }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Статус оплаты:</strong></p>
                                        <p>
                                            <span class="badge bg-success">
                                                {{ $shipment->payment_status }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Стоимость:</strong></p>
                                        <p class="fs-5 text-primary">¥{{ number_format($shipment->china_cost, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
