<?php

namespace App\Http\Controllers;

use App\Http\Requests\CargoShipmentRequest;
use App\Models\CargoShipment;
use App\Models\CargoShipmentFile;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Label\Font\NotoSans;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $data = $request->validated();

        // Обработка главной фотографии
        if ($request->hasFile('photo')) {
            $uuid = (string) Str::uuid();
            $data['photo_path'] = $this->uploadPhoto($request->file('photo'), $uuid);
            $data['public_id'] = $uuid;
        }

        $shipment = CargoShipment::query()->create($data);

        // Обработка дополнительных файлов
        if ($request->hasFile('files')) {
            $this->uploadFiles($request->file('files'), $shipment);
        }

        return redirect()
            ->route('cargo_shipments.index')
            ->with('success', 'Груз успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(CargoShipment $cargoShipment)
    {
        $cargoShipment->load('files.uploadedBy');

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
        $cargoShipment->load('files.uploadedBy');

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
        $data = $request->validated();

        // Обработка главной фотографии
        if ($request->hasFile('photo')) {
            // Удалить старую фотографию
            if ($cargoShipment->photo_path) {
                Storage::disk('public')->delete($cargoShipment->photo_path);
            }

            $data['photo_path'] = $this->uploadPhoto(
                $request->file('photo'),
                $cargoShipment->public_id
            );
        }

        $cargoShipment->update($data);

        // Обработка дополнительных файлов
        if ($request->hasFile('files')) {
            $this->uploadFiles($request->file('files'), $cargoShipment);
        }

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

    /**
     * Remove the specified file from storage.
     */
    public function destroyFile(CargoShipment $cargoShipment, $fileId)
    {
        $file = CargoShipmentFile::where('cargo_shipment_id', $cargoShipment->id)
            ->findOrFail($fileId);

        // Удаление файла из storage
        Storage::disk('public')->delete($file->file_path);

        // Удаление записи из БД
        $file->delete();

        return back()->with('success', 'Файл удален');
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

    private function uploadPhoto(UploadedFile $file, string $uuid): string
    {
        $filename = $uuid . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('cargo-photos', $filename, 'public');
    }

    private function uploadFiles(array $files, CargoShipment $shipment): void
    {
        $user = auth()->user();

        foreach ($files as $file) {
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs("cargo-files/{$shipment->public_id}", $filename, 'public');

            CargoShipmentFile::create([
                'cargo_shipment_id' => $shipment->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_category' => $this->determineFileCategory($file),
                'uploaded_by_user_id' => $user->id,
            ]);
        }
    }

    private function determineFileCategory(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            return 'photo';
        }

        if ($mimeType === 'application/pdf') {
            return 'document';
        }

        return 'other';
    }
}
