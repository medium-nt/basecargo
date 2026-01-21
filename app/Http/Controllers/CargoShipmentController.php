<?php

namespace App\Http\Controllers;

use App\Http\Requests\CargoShipmentRequest;
use App\Models\CargoShipment;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Label\Font\NotoSans;
use Illuminate\Http\Request;

class CargoShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        return view('cargo_shipments.index', [
            'title' => 'Грузы',
            'shipments' => CargoShipment::query()
                ->when(auth()->user()->isAgent(), function ($query) use ($user) {
                    $query->where('responsible_user_id', $user->id);
                })
                ->when(auth()->user()->isClient(), function ($query) use ($user) {
                    $query->where('client_id', $user->id);
                })
                ->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cargo_shipments.create', [
            'title' => 'Создание груза',
            'agents' => User::agents_and_managers(),
            'clients' => User::clients(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoShipmentRequest $request)
    {
        CargoShipment::query()->create($request->validated());

        return redirect()
            ->route('cargo_shipments.index')
            ->with('success', 'Груз успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoShipment $cargoShipment)
    {
        return view('cargo_shipments.show', [
            'title' => 'Детали груза',
            'shipment' => $cargoShipment,
        ]);
    }

    public function qr($uuid)
    {
        $cargoShipment = CargoShipment::query()->where('public_id', $uuid)->firstOrFail();

        return view('cargo_shipments.qr', [
            'title' => 'Детали груза',
            'shipment' => $cargoShipment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CargoShipment $cargoShipment)
    {
        return view('cargo_shipments.edit', [
            'title' => 'Редактирование груза',
            'shipment' => $cargoShipment,
            'clients' => User::clients(),
            'agents' => User::agents_and_managers(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoShipmentRequest $request, CargoShipment $cargoShipment)
    {
        $cargoShipment->update($request->validated());

        return redirect()
            ->route('cargo_shipments.show', $cargoShipment)
            ->with('success', 'Груз успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CargoShipment $cargoShipment)
    {
        //
    }

    public function showQr(CargoShipment $cargoShipment)
    {
        $result = Builder::create()
            ->data(route('cargo_shipments.qr', ['uuid' => $cargoShipment->public_id]))
            ->size(300)
            ->labelText($cargoShipment->cargo_number ?? '')
            ->labelFont(new NotoSans(30))
            ->build();

        $png = $result->getString();

        return response()->streamDownload(function () use ($png) {
            echo $png;
        }, 'qr-code.png', [
            'Content-Type' => 'image/png',
        ]);

    }
}
