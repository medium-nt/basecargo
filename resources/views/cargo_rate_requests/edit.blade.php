@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }} #{{ $request->id }}</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cargo_rate_requests.update', ['cargoRateRequest' => $request->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                {{-- Основные поля --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Информация о заявке</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Клиент</label>
                            <input type="text" class="form-control" value="{{ $request->client?->name ?? '-' }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="product_name">Наименование товара</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name', $request->product_name) }}">
                        </div>

                        <div class="form-group">
                            <label for="material">Материал</label>
                            <input type="text" name="material" id="material" class="form-control" value="{{ old('material', $request->material) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gross_weight_total">Вес брутто (кг)</label>
                                    <input type="number" step="0.001" name="gross_weight_total" id="gross_weight_total" class="form-control" value="{{ old('gross_weight_total', $request->gross_weight_total) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="volume_total">Объём (м³)</label>
                                    <input type="number" step="0.001" name="volume_total" id="volume_total" class="form-control" value="{{ old('volume_total', $request->volume_total) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="net_weight_total">Вес нетто (кг)</label>
                                    <input type="number" step="0.001" name="net_weight_total" id="net_weight_total" class="form-control" value="{{ old('net_weight_total', $request->net_weight_total) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="delivery_address">Адрес доставки</label>
                            <textarea name="delivery_address" id="delivery_address" rows="2" class="form-control">{{ old('delivery_address', $request->delivery_address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Расчёт ставки (только для менеджера) --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="card card-success mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Расчёт ставки</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="calculated_rate">Рассчитанная ставка (¥)</label>
                                        <input type="number" step="0.01" name="calculated_rate" id="calculated_rate" class="form-control" value="{{ old('calculated_rate', $request->calculated_rate) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="manager_notes">Заметки менеджера</label>
                                <textarea name="manager_notes" id="manager_notes" rows="3" class="form-control">{{ old('manager_notes', $request->manager_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Фото и файлы --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Фото и файлы</h3>
                    </div>
                    <div class="card-body">
                        @if($request->photo_path)
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{ $request->photo_url }}" alt="Фото" style="max-width: 150px; border-radius: 4px;">
                                </div>
                                <div class="col-md-9">
                                    <p class="form-text text-muted">Текущее фото</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Фото не загружено</p>
                        @endif

                        {{-- Для клиента - можно редактировать фото и файлы --}}
                        @if((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id && $request->isPending())
                            <div class="form-group mt-3">
                                <label for="photo">Заменить фото</label>
                                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                                <small class="form-text text-muted">JPG, PNG, WebP. Макс. 5MB</small>
                            </div>

                            <div class="form-group">
                                <label for="files">Добавить файлы</label>
                                <input type="file" name="files[]" id="files" class="form-control-file" multiple accept=".xlsx,.xls,.csv">
                                <small class="form-text text-muted">XLS, XLSX, CSV. Максимум 10 файлов, до 10MB каждый</small>
                            </div>
                        @endif

                        @if($request->files->count() > 0)
                            <table class="table table-sm table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Файл</th>
                                        <th>Размер</th>
                                        @if((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id && $request->isPending())
                                            <th style="width: 50px;"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($request->files as $file)
                                        <tr>
                                            <td><a href="{{ $file->url }}" target="_blank">{{ $file->file_name }}</a></td>
                                            <td>{{ $file->human_readable_size }}</td>
                                            @if((auth()->user()->isClient() || auth()->user()->isAgent()) && auth()->id() === $request->client_id && $request->isPending())
                                                <td>
                                                    <a href="#" onclick="deleteFile({{ $file->id }}, '{{ route('cargo_rate_requests.files.destroy', ['cargoRateRequest' => $request->id, 'fileId' => $file->id]) }}'); return false;" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Действия</h3>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save"></i> Сохранить
                        </button>

                        <a href="{{ route('cargo_rate_requests.show', ['cargoRateRequest' => $request->id]) }}" class="btn btn-default btn-block">
                            <i class="fas fa-arrow-left"></i> Назад
                        </a>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5>Статус: <strong>{{ $request->request_status_name }}</strong></h5>
                        @if($request->calculated_at)
                            <p class="text-muted mb-0">Рассчитано: {{ $request->calculated_at?->format('d.m.Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2();
        });

        function deleteFile(fileId, url) {
            if (!confirm('Удалить файл?')) {
                return false;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
            .then(response => {
                if (response.ok) {
                    toastr.success('Файл удален');
                    location.reload();
                } else {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Ошибка при удалении файла');
            });
        }
    </script>
@endpush
