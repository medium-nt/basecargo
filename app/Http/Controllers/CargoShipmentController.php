<?php

namespace App\Http\Controllers;

use App\Models\CargoShipment;
use App\Http\Controllers\Controller;
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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoShipment $cargoShipment)
    {
        //
    }

    public function qr(CargoShipment $cargoShipment)
    {
        echo 'тут будут данные по грузу';
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
}
