<?php

namespace App\Models;

use Database\Factories\CargoShipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CargoShipment extends Model
{
    /** @use HasFactory<CargoShipmentFactory> */
    use HasFactory;

    protected $table = 'cargo_shipments';

    protected $fillable = [
        'public_id',
        'client_id',
        'responsible_user_id',
        'delivery_address',
        'contact_phone',
        'china_tracking_number',
        'china_cost',
        'cargo_status',
        'payment_type',
        'payment_status',
        'crate',
        'photo_path',
        'cargo_number',
        'product_name',
        'material',
        'packaging',
        'places_count',
        'items_per_place',
        'total_items_count',
        'gross_weight_per_place',
        'gross_weight_total',
        'length',
        'width',
        'height',
        'volume_per_item',
        'volume_total',
        'tare_weight_per_box',
        'tare_weight_total',
        'net_weight_per_box',
        'net_weight_total',
        'insurance_amount',
        'insurance_cost',
        'rate_rub',
        'payment_phone',
        'total_cost',
        'bank_name',
        'bank_account_name',
        'factory_shipping_date',
        'sunfuihe_warehouse_received_date',
        'china_shipping_date',
        'ussuriysk_arrival_date',
        'ussuriysk_shipping_date',
        'client_received_date',
        'explanations',
        'TN_VED_code',
        'payment',
        'label_name',
        'label_number',
        'marking',
        'manufacturer',
        'SS_DS',
        'bet',
        'ITS',
        'duty',
        'VAT',
        'volume_weight',
        'customs_clearance_service',
        'cost_truck',
        'revenue_per_kg',
        'dollar_rate',
        'yuan_rate'
    ];

    protected $appends = [
        'estimated_value_cargo_ITS',
        'total_payment',
        'importer_services',
        'delivery_to_Ussuriysk',
        'revenue',
        'total',
        'total_per_kg',
    ];

    public function getEstimatedValueCargoITSAttribute(): float
    {
        $result = ($this->net_weight_total ?? 0) * ($this->ITS ?? 0);
        return round($result, 2);
    }

    public function getTotalPaymentAttribute(): float
    {
        $A = $this->estimated_value_cargo_ITS;
        $duty = ($this->duty ?? 0) / 100;
        $vat = ($this->VAT ?? 0) / 100;
        $result = ($A * $duty) + ($A + ($A * $duty)) * $vat;
        return round($result, 2);
    }

    public function getImporterServicesAttribute(): float
    {
        $volume = $this->volume_total ?? 0;
        $volumeWeight = $this->volume_weight ?? 0;
        $service = $this->customs_clearance_service ?? 0;

        if ($volumeWeight > 0) {
            $result = ($volume / $volumeWeight) * $service;
            return round($result, 2);
        }
        return 0;
    }

    public function getDeliveryToUssuriyskAttribute(): float
    {
        $costTruck = $this->cost_truck ?? 0;
        $volumeWeight = $this->volume_weight ?? 0;
        $volume = $this->volume_total ?? 0;

        if ($volumeWeight > 0) {
            $result = ($costTruck / $volumeWeight) * $volume;
            return round($result, 2);
        }
        return 0;
    }

    public function getRevenueAttribute(): float
    {
        $result = ($this->revenue_per_kg ?? 0) * ($this->gross_weight_total ?? 0);
        return round($result, 2);
    }

    public function getTotalAttribute(): float
    {
        $B = $this->total_payment;
        $C = $this->importer_services;
        $D = $this->delivery_to_Ussuriysk;
        $E = $this->revenue;
        $dollarRate = $this->dollar_rate ?? 0;

        $result = (($B + $C) * $dollarRate) + $D + $E;
        return round($result, 2);
    }

    public function getTotalPerKgAttribute(): float
    {
        $G = $this->total;
        $grossWeight = $this->gross_weight_total ?? 0;
        $yuanRate = $this->yuan_rate ?? 0;

        if ($grossWeight > 0 && $yuanRate > 0) {
            $result = $G / $grossWeight / $yuanRate;
            return round($result, 2);
        }
        return 0;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($shipment) {
            $shipment->public_id = (string) Str::uuid();
        });
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CargoShipmentFile::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_path) {
            return Storage::url($this->photo_path);
        }

        return null;
    }
}
