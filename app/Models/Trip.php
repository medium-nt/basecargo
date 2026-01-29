<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trip extends Model
{
    protected $fillable = [
        'type',
        'truck_number',
        'driver_name',
        'status',
        'invoice_number',
        'loading_address',
        'unloading_address',
        'loading_date',
        'unloading_date',
        'cost',
        'payment_status',
        'details',
    ];

    protected $casts = [
        'loading_date' => 'date',
        'unloading_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'domestic' => 'По России',
            'international' => 'Китай-Россия',
            default => '---',
        };
    }

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'Запланирован',
            'loading' => 'Погрузка',
            'in_transit' => 'В пути',
            'unloading' => 'Разгрузка',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
            default => '---',
        };
    }

    public function getPaymentStatusNameAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Не оплачен',
            'partial' => 'Частично оплачен',
            'paid' => 'Оплачен',
            default => '---',
        };
    }

    public function cargoShipments(): BelongsToMany
    {
        return $this->belongsToMany(CargoShipment::class, 'cargo_shipment_trip')
            ->withTimestamps()
            ->withPivot('id');
    }
}
