<?php

namespace App\Models;

use Database\Factories\CargoShipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CargoShipment extends Model
{
    /** @use HasFactory<CargoShipmentFactory> */
    use HasFactory;

    protected $table = 'cargo_shipments';

    protected $fillable = [
        'public_id',
        'responsible_user_id',
        'client_id',
        'client_name',
        'client_messenger',
        'client_messenger_number',
        'client_phone',
        'recipient_address',
        'china_tracking_number',
        'china_cost',
        'cargo_status',
        'payment_type',
        'payment_status',
        'crate',
        'cargo_type',
        'cargo_weight',
        'cargo_volume',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($shipment) {
            $shipment->public_id = (string) Str::uuid();
        });
    }
}
