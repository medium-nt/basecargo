<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\CargoShipment;
use App\Models\Trip;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type = null)
    {
        $type = $type ?? request('type');

        $title = match ($type) {
            'domestic' => 'Рейсы по России',
            'international' => 'Рейсы Китай-Россия',
            default => 'Рейсы',
        };

        return view('trips.index', [
            'title' => $title,
            'currentType' => $type,
            'trips' => Trip::query()
                ->when($type, function ($query) use ($type) {
                    $query->where('type', $type);
                })
                ->when(request('status'), function ($query) {
                    $query->where('status', request('status'));
                })
                ->orderBy('created_at', 'desc')
                ->paginate(50)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create', [
            'title' => 'Создание рейса',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TripRequest $request)
    {
        $trip = Trip::query()->create($request->validated());

        return redirect()
            ->route($trip->type === 'domestic' ? 'trips.domestic' : 'trips.international')
            ->with('success', 'Рейс успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load(['cargoShipments' => function ($query) {
            $query->orderBy('id');
        }]);

        return view('trips.show', [
            'title' => 'Детали рейса',
            'trip' => $trip,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        return view('trips.edit', [
            'title' => 'Редактирование рейса',
            'trip' => $trip,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TripRequest $request, Trip $trip)
    {
        $trip->update($request->validated());

        return redirect()
            ->route('trips.show', $trip)
            ->with('success', 'Рейс успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        if ($trip->cargoShipments()->count() > 0) {
            return back()->with('error', 'Невозможно удалить рейс с прикреплёнными грузами');
        }

        $type = $trip->type;
        $trip->delete();

        return redirect()
            ->route($type === 'domestic' ? 'trips.domestic' : 'trips.international')
            ->with('success', 'Рейс успешно удален');
    }

    /**
     * Detach cargo from trip.
     */
    public function detachCargo(Trip $trip, CargoShipment $cargoShipment)
    {
        $trip->cargoShipments()->detach($cargoShipment->id);

        return back()->with('success', 'Груз откреплён от рейса');
    }
}
