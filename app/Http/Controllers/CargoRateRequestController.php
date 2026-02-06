<?php

namespace App\Http\Controllers;

use App\Http\Requests\CargoRateRequestRequest;
use App\Models\CargoRateRequest;
use App\Models\CargoRateRequestFile;
use App\Models\CargoShipment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CargoRateRequestController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('cargo_rate_requests.index', [
            'title' => 'Заявки на расчет',
            'clients' => User::clients(),
            'requests' => CargoRateRequest::query()
//                ->when(auth()->user()->isManager(), function ($query) use ($user) {
//                    $query->where('responsible_user_id', $user->id);
//                })
                ->when(auth()->user()->isClient() || auth()->user()->isAgent(), function ($query) use ($user) {
                    $query->where('client_id', $user->id);
                })
                ->when(request('archive') == '1', function ($query) {
                    $query->where('request_status', 'rejected');
                })
                ->when(! request('archive') || request('archive') == '0', function ($query) {
                    $query->whereNot('request_status', 'rejected');
                })
                ->when(request('request_status') && request('archive') != '1', function ($query) {
                    $query->where('request_status', request('request_status'));
                })
                ->when(request('client_id'), function ($query) {
                    $query->where('client_id', request('client_id'));
                })
                ->orderBy('created_at', 'desc')
                ->with('client', 'agent', 'files')
                ->paginate(50)
                ->withQueryString(),
        ]);
    }

    public function store(CargoRateRequestRequest $request): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                // Обработка фото
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $this->uploadPhoto($request->file('photo'));
                }

                // Создаём заявку с явным указанием полей (защита от Mass Assignment)
                $cargoRateRequest = CargoRateRequest::query()->create([
                    'client_id' => auth()->id(),
                    'request_status' => 'pending',
                    'photo_path' => $photoPath,
                    'product_name' => $data['product_name'] ?? null,
                    'material' => $data['material'] ?? null,
                    'gross_weight_total' => $data['gross_weight_total'] ?? null,
                    'volume_total' => $data['volume_total'] ?? null,
                    'net_weight_total' => $data['net_weight_total'] ?? null,
                    'delivery_address' => $data['delivery_address'] ?? null,
                ]);

                // Обработка файлов
                if ($requestFile = $request->file('files')) {
                    $this->uploadFiles($requestFile, $cargoRateRequest);
                }

                return redirect()
                    ->route('cargo_rate_requests.index')
                    ->with('success', 'Заявка успешно создана');
            });
        } catch (Throwable $e) {
            Log::error('Ошибка создания заявки', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Не удалось создать заявку. Попробуйте ещё раз.']);
        }
    }

    public function show(CargoRateRequest $cargoRateRequest): View
    {
        $cargoRateRequest->load('files.uploadedBy');

        return view('cargo_rate_requests.show', [
            'title' => 'Детали заявки',
            'request' => $cargoRateRequest,
        ]);
    }

    public function edit(CargoRateRequest $cargoRateRequest): View
    {
        $cargoRateRequest->load('files.uploadedBy');

        return view('cargo_rate_requests.edit', [
            'title' => 'Редактирование заявки',
            'request' => $cargoRateRequest,
        ]);
    }

    public function update(CargoRateRequestRequest $request, CargoRateRequest $cargoRateRequest): RedirectResponse
    {
        $data = $request->validated();

        // Менеджер не может менять фото и файлы клиента
        if (auth()->user()->isAdmin() || auth()->user()->isManager()) {
            unset($data['photo'], $data['files']);
        }

        // Если менеджер указал ставку и заявка в статусе "pending" - автоматически на согласование
        if (isset($data['calculated_rate']) && $data['calculated_rate'] && $cargoRateRequest->isPending() && (auth()->user()->isAdmin() || auth()->user()->isManager())) {
            $data['request_status'] = 'awaiting_approval';
            $data['calculated_at'] = now();
        }

        // Обработка фото (для клиента)
        if ($request->hasFile('photo') && (auth()->user()->isClient() || auth()->user()->isAgent()) && $cargoRateRequest->isPending()) {
            if ($cargoRateRequest->photo_path) {
                Storage::disk('public')->delete($cargoRateRequest->photo_path);
            }
            $data['photo_path'] = $this->uploadPhoto($request->file('photo'));
        }

        $cargoRateRequest->update($data);

        // Обработка файлов (для клиента)
        if ($request->hasFile('files') && (auth()->user()->isClient() || auth()->user()->isAgent()) && $cargoRateRequest->isPending()) {
            $this->uploadFiles($request->file('files'), $cargoRateRequest);
        }

        return redirect()
            ->route('cargo_rate_requests.show', $cargoRateRequest)
            ->with('success', 'Заявка успешно обновлена');
    }

    public function approve(CargoRateRequest $cargoRateRequest): RedirectResponse
    {
        // Создаем груз только если нет EXCEL файлов и все поля заполнены
        $canCreateShipment = ! $cargoRateRequest->hasExcelFiles() && $cargoRateRequest->isComplete();

        $shipment = null;
        if ($canCreateShipment) {
            $shipment = CargoShipment::query()->create([
                'client_id' => $cargoRateRequest->client_id,
                'responsible_user_id' => $cargoRateRequest->responsible_user_id,
                'photo_path' => $cargoRateRequest->photo_path,
                'product_name' => $cargoRateRequest->product_name,
                'material' => $cargoRateRequest->material,
                'gross_weight_total' => $cargoRateRequest->gross_weight_total,
                'volume_total' => $cargoRateRequest->volume_total,
                'net_weight_total' => $cargoRateRequest->net_weight_total,
                'delivery_address' => $cargoRateRequest->delivery_address,
                'cargo_status' => 'wait_payment',
                'rate_rub' => $cargoRateRequest->calculated_rate,
            ]);
        }

        $cargoRateRequest->update([
            'request_status' => 'approved',
            'cargo_shipment_id' => $shipment?->id,
        ]);

        return $shipment
            ? redirect()->route('cargo_shipments.show', $shipment)->with('success', 'Заявка согласована. Груз создан.')
            : redirect()->route('cargo_rate_requests.index')->with('success', 'Заявка согласована.');
    }

    public function reject(CargoRateRequest $cargoRateRequest): RedirectResponse
    {
        $cargoRateRequest->update([
            'request_status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return redirect()
            ->route('cargo_rate_requests.index')
            ->with('success', 'Заявка отклонена');
    }

    public function destroy(CargoRateRequest $cargoRateRequest): RedirectResponse
    {
        // Удаляем фото
        if ($cargoRateRequest->photo_path) {
            Storage::disk('public')->delete($cargoRateRequest->photo_path);
        }

        // Удаляем файлы
        foreach ($cargoRateRequest->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $cargoRateRequest->delete();

        return redirect()
            ->route('cargo_rate_requests.index')
            ->with('success', 'Заявка удалена');
    }

    public function destroyFile(CargoRateRequest $cargoRateRequest, int $fileId): RedirectResponse
    {
        $file = CargoRateRequestFile::where('cargo_rate_request_id', $cargoRateRequest->id)
            ->findOrFail($fileId);

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Файл удален');
    }

    private function uploadPhoto(UploadedFile $file): string
    {
        $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

        return $file->storeAs('rate-request-photos', $filename, 'public');
    }

    private function uploadFiles(array $files, CargoRateRequest $cargoRateRequest): void
    {
        $user = auth()->user();

        foreach ($files as $file) {
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                .'_'.Str::random(6)
                .'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs("rate-request-files/{$cargoRateRequest->id}", $filename, 'public');

            CargoRateRequestFile::create([
                'cargo_rate_request_id' => $cargoRateRequest->id,
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

        if (in_array($mimeType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return 'excel';
        }

        return 'other';
    }
}
