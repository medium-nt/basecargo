<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargoShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],

            'contact_phone' => ['nullable', 'string', 'max:255'],
            'delivery_address' => ['nullable', 'string', 'max:1000'],

            'china_tracking_number' => ['nullable', 'string', 'max:255'],
            'china_cost' => ['nullable', 'numeric', 'min:0'],
            'crate' => ['nullable', 'string', 'max:255'],

            'cargo_number' => ['required', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:500'],
            'material' => ['nullable', 'string', 'max:255'],
            'packaging' => ['required', 'string', 'max:255'],

            'places_count' => ['required', 'integer', 'min:1'],
            'items_per_place' => ['nullable', 'integer', 'min:1'],
            'total_items_count' => ['nullable', 'integer', 'min:1'],

            'volume_total' => ['required', 'numeric', 'min:0'],
            'volume_per_item' => ['nullable', 'numeric', 'min:0'],

            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],

            'gross_weight_total' => ['required', 'numeric', 'min:0'],
            'gross_weight_per_place' => ['nullable', 'numeric', 'min:0'],
            'net_weight_total' => ['nullable', 'numeric', 'min:0'],
            'net_weight_per_box' => ['nullable', 'numeric', 'min:0'],
            'tare_weight_total' => ['nullable', 'numeric', 'min:0'],
            'tare_weight_per_box' => ['nullable', 'numeric', 'min:0'],

            'cargo_status' => ['in:wait_payment,shipping_supplier,china_transit,china_warehouse,china_russia_transit,russia_warehouse,russia_transit,wait_receiving,received'],
            'payment_type' => ['nullable', 'in:cash,card,rs'],
            'payment_status' => ['nullable', 'in:not_paid,paid'],

            'insurance_amount' => ['nullable', 'numeric', 'min:0'],
            'insurance_cost' => ['nullable', 'numeric', 'min:0'],
            'rate_rub' => ['nullable', 'numeric', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],

            'contact_phone_payment' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],

            'factory_shipping_date' => ['nullable', 'date'],
            'sunfuihe_warehouse_received_date' => ['nullable', 'date'],
            'china_shipping_date' => ['nullable', 'date'],
            'ussuriysk_arrival_date' => ['nullable', 'date'],
            'ussuriysk_shipping_date' => ['nullable', 'date'],
            'client_received_date' => ['nullable', 'date'],

            // Калькулятор - string поля
            'explanations' => ['nullable', 'string', 'max:500'],
            'TN_VED_code' => ['nullable', 'string', 'max:50'],
            'label_name' => ['nullable', 'string', 'max:500'],
            'label_number' => ['nullable', 'string', 'max:100'],
            'marking' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'SS_DS' => ['nullable', 'string', 'max:255'],
            'bet' => ['nullable', 'string', 'max:50'],

            // Калькулятор - numeric поля
            'payment' => ['nullable', 'numeric'],
            'ITS' => ['nullable', 'numeric'],
            'duty' => ['nullable', 'numeric'],
            'VAT' => ['nullable', 'numeric'],
            'volume_weight' => ['nullable', 'numeric'],
            'customs_clearance_service' => ['nullable', 'numeric'],
            'cost_truck' => ['nullable', 'numeric'],
            'revenue_per_kg' => ['nullable', 'numeric'],
            'dollar_rate' => ['nullable', 'numeric'],
            'yuan_rate' => ['nullable', 'numeric'],

            // Файлы
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.exists' => 'Указанный клиент не существует',
            'client_id.integer' => 'Неверный формат клиента',

            'responsible_user_id.exists' => 'Указанный ответственный не существует',
            'responsible_user_id.integer' => 'Неверный формат ответственного',

            'contact_phone.max' => 'Телефон получателя не должен превышать :max символов',
            'delivery_address.max' => 'Адрес не должен превышать :max символов',

            'china_tracking_number.max' => 'Трек-номер не должен превышать :max символов',
            'china_cost.numeric' => 'Стоимость по Китаю должна быть числом',
            'china_cost.min' => 'Стоимость по Китаю не может быть отрицательной',

            'crate.max' => 'Поле обрешетки не должно превышать :max символов',
            'cargo_number.required' => 'Поле номера груза обязательно для заполнения',
            'cargo_number.max' => 'Номер груза не должен превышать :max символов',
            'product_name.required' => 'Поле наименования товара обязательно для заполнения',
            'product_name.max' => 'Наименование товара не должно превышать :max символов',
            'material.max' => 'Материал не должен превышать :max символов',
            'packaging.required' => 'Поле упаковки обязательно для заполнения',
            'packaging.max' => 'Упаковка не должна превышать :max символов',

            'places_count.required' => 'Поле количества мест обязательно для заполнения',
            'places_count.integer' => 'Количество мест должно быть целым числом',
            'places_count.min' => 'Количество мест должно быть не менее :min',
            'items_per_place.integer' => 'Количество товаров/мест должно быть целым числом',
            'items_per_place.min' => 'Количество товаров/мест должно быть не менее :min',
            'total_items_count.integer' => 'Общее количество штук должно быть целым числом',
            'total_items_count.min' => 'Общее количество штук должно быть не менее :min',

            'volume_total.required' => 'Поле общего объёма обязательно для заполнения',
            'volume_total.numeric' => 'Общий объём должен быть числом',
            'volume_total.min' => 'Общий объём не может быть отрицательным',
            'volume_per_item.numeric' => 'Объём 1 места должен быть числом',
            'volume_per_item.min' => 'Объём 1 места не может быть отрицательным',

            'length.numeric' => 'Длина должна быть числом',
            'length.min' => 'Длина не может быть отрицательной',
            'width.numeric' => 'Ширина должна быть числом',
            'width.min' => 'Ширина не может быть отрицательной',
            'height.numeric' => 'Высота должна быть числом',
            'height.min' => 'Высота не может быть отрицательной',

            'gross_weight_total.required' => 'Поле общего веса брутто обязательно для заполнения',
            'gross_weight_total.numeric' => 'Общий вес брутто должен быть числом',
            'gross_weight_total.min' => 'Общий вес брутто не может быть отрицательным',
            'gross_weight_per_place.numeric' => 'Вес брутто 1 места должен быть числом',
            'gross_weight_per_place.min' => 'Вес брутто 1 места не может быть отрицательным',

            'net_weight_total.numeric' => 'Общий вес нетто должен быть числом',
            'net_weight_total.min' => 'Общий вес нетто не может быть отрицательным',
            'net_weight_per_box.numeric' => 'Вес нетто 1 коробки должен быть числом',
            'net_weight_per_box.min' => 'Вес нетто 1 коробки не может быть отрицательным',

            'tare_weight_total.numeric' => 'Вес всех коробок должен быть числом',
            'tare_weight_total.min' => 'Вес всех коробок не может быть отрицательным',
            'tare_weight_per_box.numeric' => 'Вес 1 тары должен быть числом',
            'tare_weight_per_box.min' => 'Вес 1 тары не может быть отрицательным',

            'cargo_status.in' => 'Неверный статус груза',
            'payment_type.in' => 'Неверный тип оплаты',
            'payment_status.in' => 'Неверный статус оплаты',

            'insurance_amount.numeric' => 'Страховая сумма должна быть числом',
            'insurance_amount.min' => 'Страховая сумма не может быть отрицательной',
            'insurance_cost.numeric' => 'Страховка должна быть числом',
            'insurance_cost.min' => 'Страховка не может быть отрицательной',
            'rate_rub.numeric' => 'Ставка в рублях должна быть числом',
            'rate_rub.min' => 'Ставка в рублях не может быть отрицательной',
            'total_cost.numeric' => 'Сумма должна быть числом',
            'total_cost.min' => 'Сумма не может быть отрицательной',

            'contact_phone_payment.max' => 'Телефон не должен превышать :max символов',
            'bank_name.max' => 'Банк не должен превышать :max символов',
            'bank_account_name.max' => 'Имя не должно превышать :max символов',

            'factory_shipping_date.date' => 'Неверный формат даты отправки с завода',
            'sunfuihe_warehouse_received_date.date' => 'Неверный формат даты получения складом',
            'china_shipping_date.date' => 'Неверный формат даты отправки из Китая',
            'ussuriysk_arrival_date.date' => 'Неверный формат даты прибытия в Уссурийск',
            'ussuriysk_shipping_date.date' => 'Неверный формат даты отправки из Уссурийска',
            'client_received_date.date' => 'Неверный формат даты получения клиентом',

            // Файлы
            'photo.image' => 'Файл должен быть изображением',
            'photo.mimes' => 'Фотография должна быть в формате: JPEG, PNG, JPG, WebP',
            'photo.max' => 'Размер фотографии не должен превышать 5MB',

            'files.max' => 'Максимум 10 файлов за одну загрузку',
            'files.*.file' => 'Загруженный файл не является валидным файлом',
            'files.*.mimes' => 'Файл должен быть в формате: JPEG, PNG, JPG, PDF, DOC, DOCX, XLS, XLSX',
            'files.*.max' => 'Размер файла не должен превышать 10MB',
        ];
    }
}
