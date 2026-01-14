<?php

namespace Database\Seeders;

use App\Models\CargoShipment;
use Illuminate\Database\Seeder;

class CargoShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CargoShipment::factory()->create([
            'responsible_user_id' => 2,
            'client_id' => 4,
            'delivery_address' => 'г. Москва, ул. Пушкинская, 1',
            'contact_phone' => '+7 (999) 999-99-99',

            'china_tracking_number' => '112385240292',
            'china_cost' => 1000,
            'cargo_status' => 'wait_payment',
            'payment_type' => 'cash',
            'payment_status' => 'paid',
            'crate' => 1500,

            'cargo_number' => '',
            'product_name' => 'компьютерные кресла',
            'material' => '',
            'packaging' => 'коробки',

            'places_count' => 6,
            'items_per_place' => 1,
            'total_items_count' => 6,
            'gross_weight_per_place' => 210,
            'gross_weight_total' => 210,

            'insurance_amount' => 15000,
            'insurance_cost' => 1000,
            'rate_rub' => 160,

            'total_cost' => 21000,
            'payment_phone' => '+7 (999) 999-99-99',
            'bank_name' => 'Сбербанк',
            'bank_account_name' => 'Иванов Иван Иванович',
        ]);

        CargoShipment::factory()->create([
            'responsible_user_id' => 2,
            'client_id' => 5,
            'delivery_address' => 'г. Санкт-Петербург, ул. Московская, 10/1',
            'contact_phone' => '+7 (888) 888-88-88',

            'china_tracking_number' => 'DPK381116553045',
            'china_cost' => 2000,
            'cargo_status' => 'shipping_supplier',
            'payment_type' => 'card',
            'payment_status' => 'not_paid',
            'crate' => 1600,

            'cargo_number' => '54321',
            'product_name' => 'Усилитель звука',
            'material' => '',
            'packaging' => 'коробки',

            'places_count' => 18,
            'items_per_place' => 211,
            'total_items_count' => 3800,
            'gross_weight_per_place' => 34.24,
            'gross_weight_total' => 659,

            'insurance_amount' => 20000,
            'insurance_cost' => 2000,
            'rate_rub' => 190,

            'total_cost' => 121000,
            'payment_phone' => '+7 (888) 999-99-99',
            'bank_name' => 'ВТБ',
            'bank_account_name' => 'Петров Петр Петрович',
        ]);

        CargoShipment::factory()->create([
            'responsible_user_id' => 3,
            'client_id' => 4,
            'delivery_address' => 'г. СПб, ул. Мира, д.156',
            'contact_phone' => '+7 (777) 777-77-77',

            'china_tracking_number' => '961878074',
            'china_cost' => 3000,
            'cargo_status' => 'china_transit',
            'payment_type' => 'cash',
            'payment_status' => 'paid',
            'crate' => 0,

            'cargo_number' => 'M5552-22',
            'product_name' => 'Столовые приборы',
            'material' => 'сталь',
            'packaging' => 'коробки',

            'places_count' => 34,
            'items_per_place' => 14.71,
            'total_items_count' => 500,
            'gross_weight_per_place' => 19.04,
            'gross_weight_total' => 647.50,

            'insurance_amount' => 1000,
            'insurance_cost' => 100,
            'rate_rub' => 50,

            'total_cost' => 17345,
            'payment_phone' => '+7 (777) 999-99-99',
            'bank_name' => 'Тинькофф',
            'bank_account_name' => 'Смирнов Сергей Сергеевич',
        ]);

        CargoShipment::factory()->create([
            'responsible_user_id' => 3,
            'client_id' => 5,
            'delivery_address' => 'г. Владивосток, пр. Ленина, 2',
            'contact_phone' => '+7 (666) 666-66-66',

            'china_tracking_number' => 'Леша-Гуанчжоу',
            'china_cost' => 4000,
            'cargo_status' => 'china_warehouse',
            'payment_type' => 'rs',
            'payment_status' => 'not_paid',
            'crate' => 100,

            'cargo_number' => 'SAMsmk-28',
            'product_name' => 'Аромамашина с маслами',
            'material' => '',
            'packaging' => 'мешки',

            'places_count' => 7,
            'items_per_place' => 142,
            'total_items_count' => 1000,
            'gross_weight_per_place' => 38.57,
            'gross_weight_total' => 270,

            'insurance_amount' => 190000,
            'insurance_cost' => 9000,
            'rate_rub' => 986,

            'total_cost' => 128763,
            'payment_phone' => '+7 (666) 999-99-99',
            'bank_name' => '',
            'bank_account_name' => '',
        ]);
    }
}
