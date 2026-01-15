<?php

namespace App\Models;

use Database\Factories\CargoShipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'total_cost',
        'bank_name',
        'bank_account_name',
        'factory_shipping_date',
        'sunfuihe_warehouse_received_date',
        'china_shipping_date',
        'ussuriysk_arrival_date',
        'ussuriysk_shipping_date',
        'client_received_date'
    ];

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
}
