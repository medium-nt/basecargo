<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'messenger',
        'messenger_number',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public static function clients()
    {
        return Role::where('name', 'client')
            ->first()
            ->users;
    }

    public static function clients_and_agents(): Collection
    {
        return User::whereHas('role', function ($q) {
            $q->whereIn('name', ['agent', 'client']);
        })->get();
    }

    public static function agents_and_managers(): Collection
    {
        return User::whereHas('role', function ($q) {
            $q->whereIn('name', ['agent', 'manager']);
        })->get();
    }

    public static function agents()
    {
        return Role::where('name', 'agent')
            ->first()
            ->users;
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role?->name === 'client';
    }

    public function isAgent(): bool
    {
        return $this->role?->name === 'agent';
    }

    public function isManager(): bool
    {
        return $this->role?->name === 'manager';
    }

    public function isStaff(): bool
    {
        return in_array($this->role?->name, ['admin', 'manager'], true);
    }

    public function roleName(): Attribute
    {
        return Attribute::get(function () {
            return match ($this->role?->name) {
                'client' => 'Клиент',
                'agent' => 'Агент',
                'admin' => 'Админ',
                'manager' => 'Менеджер',
                default => '---',
            };
        });
    }
}
