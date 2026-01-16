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
            'agents' => User::agents(),
            'clients' => User::clients(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'delivery_address' => [],
            'contact_phone' => [],
            'china_tracking_number' => [],
            'china_cost' => [],
            'cargo_status' => [],
            'payment_type' => [],
            'payment_status' => [],
            'crate' => [],
            'cargo_number' => [],
            'product_name' => [],
            'material' => [],
            'packaging' => [],
            'places_count' => [],
            'items_per_place' => [],
            'total_items_count' => [],
            'gross_weight_per_place' => [],
            'gross_weight_total' => [],
            'length' => [],
            'width' => [],
            'height' => [],
            'volume_per_item' => [],
            'volume_total' => [],
            'tare_weight_per_box' => [],
            'tare_weight_total' => [],
            'net_weight_per_box' => [],
            'net_weight_total' => [],
            'insurance_amount' => [],
            'insurance_cost' => [],
            'rate_rub' => [],
            'total_cost' => [],
            'bank_name' => [],
            'bank_account_name' => [],
            'factory_shipping_date' => [],
            'sunfuihe_warehouse_received_date' => [],
            'china_shipping_date' => [],
            'ussuriysk_arrival_date' => [],
            'ussuriysk_shipping_date' => [],
            'client_received_date' => [],
        ]);

        CargoShipment::query()->create([
            'client_id' => $request->client_id,
            'responsible_user_id' => $request->responsible_user_id,
            'delivery_address' => $request->delivery_address,
            'contact_phone' => $request->contact_phone,
            'china_tracking_number' => $request->china_tracking_number,
            'china_cost' => $request->china_cost,
            'cargo_status' => $request->cargo_status,
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
            'crate' => $request->crate,
            'cargo_number' => $request->cargo_number,
            'product_name' => $request->product_name,
            'material' => $request->material,
            'packaging' => $request->packaging,
            'places_count' => $request->places_count,
            'items_per_place' => $request->items_per_place,
            'total_items_count' => $request->total_items_count,
            'gross_weight_per_place' => $request->gross_weight_per_place,
            'gross_weight_total' => $request->gross_weight_total,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume_per_item' => $request->volume_per_item,
            'volume_total' => $request->volume_total,
            'tare_weight_per_box' => $request->tare_weight_per_box,
            'tare_weight_total' => $request->tare_weight_total,
            'net_weight_per_box' => $request->net_weight_per_box,
            'net_weight_total' => $request->net_weight_total,
            'insurance_amount' => $request->insurance_amount,
            'insurance_cost' => $request->insurance_cost,
            'rate_rub' => $request->rate_rub,
            'total_cost' => $request->total_cost,
            'bank_name' => $request->bank_name,
            'bank_account_name' => $request->bank_account_name,
            'factory_shipping_date' => $request->factory_shipping_date,
            'sunfuihe_warehouse_received_date' => $request->sunfuihe_warehouse_received_date,
            'china_shipping_date' => $request->china_shipping_date,
            'ussuriysk_arrival_date' => $request->ussuriysk_arrival_date,
            'ussuriysk_shipping_date' => $request->ussuriysk_shipping_date,
            'client_received_date' => $request->client_received_date,
        ]);

        return redirect()->route('cargo_shipments.index');
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
            ->labelText($cargoShipment->cargo_number)
            ->build();

        $png = $result->getString();

        return response()->streamDownload(function () use ($png) {
            echo $png;
        }, 'qr-code.png', [
            'Content-Type' => 'image/png',
        ]);

    }
}
