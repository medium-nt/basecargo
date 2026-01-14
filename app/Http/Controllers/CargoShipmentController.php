<?php

namespace App\Http\Controllers;

use App\Models\CargoShipment;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Http\Request;

class CargoShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cargo_shipments.index', [
            'title' => 'Грузы',
            'shipments' => CargoShipment::query()
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
            'agents' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'weight' => ['required', 'numeric'],
            'width' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'length' => ['required', 'numeric'],
        ]);

        CargoShipment::query()->create([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            'width' => $request->width,
            'height' => $request->height,
            'length' => $request->length,
        ]);

        return redirect()->route('cargo_shipments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoShipment $cargoShipment)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CargoShipment $cargoShipment)
    {
        //
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
            ->build();

        $png = $result->getString();

        return response()->streamDownload(function () use ($png) {
            echo $png;
        }, 'qr-code.png', [
            'Content-Type' => 'image/png',
        ]);

    }
}
