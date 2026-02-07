<?php

namespace App\Http\Controllers;

use App\Models\CargoShipment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Базовый запрос с фильтрацией по ролям
        $query = CargoShipment::query()
            ->when($user->isAdmin(), function ($q) {
                // Admin видит все грузы - фильтр не нужен
            })
            ->when($user->isManager(), function ($q) use ($user) {
                $q->where('responsible_user_id', $user->id);
            })
            ->when($user->isAgent(), function ($q) use ($user) {
                $q->where('responsible_user_id', $user->id)
                    ->orWhere('client_id', $user->id);
            })
            ->when($user->isClient(), function ($q) use ($user) {
                $q->where('client_id', $user->id);
            });

        // Статусы для "В пути" (все кроме wait_payment и received)
        $inTransitStatuses = [
            'shipping_supplier',
            'china_transit',
            'china_warehouse',
            'china_russia_transit',
            'russia_warehouse',
            'russia_transit',
            'wait_receiving',
        ];

        // Статистика
        $stats = [
            'total_count' => (clone $query)->count(),
            'total_weight' => (clone $query)->sum('gross_weight_total') ?? 0,

            'in_transit_count' => (clone $query)->whereIn('cargo_status', $inTransitStatuses)->count(),
            'in_transit_weight' => (clone $query)->whereIn('cargo_status', $inTransitStatuses)->sum('gross_weight_total') ?? 0,

            'delivered_count' => (clone $query)->where('cargo_status', 'received')->count(),
            'delivered_weight' => (clone $query)->where('cargo_status', 'received')->sum('gross_weight_total') ?? 0,
        ];

        return view('dashboard', compact('stats'));
    }
}
