<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CargoRateRequest extends Model
{
    protected $fillable = [
        'client_id',
        'responsible_user_id',
        'photo_path',
        'product_name',
        'material',
        'gross_weight_total',
        'volume_total',
        'net_weight_total',
        'delivery_address',
        'request_status',
        'calculated_rate',
        'manager_notes',
        'calculated_at',
        'cargo_shipment_id',
        'rejected_at',
    ];

    protected $casts = [
        'gross_weight_total' => 'decimal:3',
        'volume_total' => 'decimal:3',
        'net_weight_total' => 'decimal:3',
        'calculated_rate' => 'decimal:2',
        'calculated_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function getRequestStatusNameAttribute(): string
    {
        return match ($this->request_status) {
            'pending' => 'Неразобрано',
            'awaiting_approval' => 'На согласовании',
            'approved' => 'Согласовано',
            'rejected' => 'Отклонено',
            default => '---',
        };
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function cargoShipment(): BelongsTo
    {
        return $this->belongsTo(CargoShipment::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(CargoRateRequestFile::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_path) {
            return Storage::url($this->photo_path);
        }

        return null;
    }

    public function isPending(): bool
    {
        return $this->request_status === 'pending';
    }

    public function isAwaitingApproval(): bool
    {
        return $this->request_status === 'awaiting_approval';
    }

    public function canBeApprovedBy(User $user): bool
    {
        return $this->client_id === $user->id && $this->isAwaitingApproval();
    }

    public function isComplete(): bool
    {
        return filled($this->product_name)
            && filled($this->material)
            && filled($this->gross_weight_total)
            && filled($this->volume_total)
            && filled($this->net_weight_total)
            && filled($this->delivery_address);
    }

    public function hasExcelFiles(): bool
    {
        return $this->files->contains('file_category', 'excel');
    }
}
