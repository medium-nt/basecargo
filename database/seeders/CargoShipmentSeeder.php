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
            'client_name' => 'новый клиент',
            'client_messenger' => 'ТГ',
            'client_messenger_number' => '@username',
            'client_phone' => '+7 (999) 999-99-99',
            'recipient_address' => 'г. Москва, ул. Пушкинская, 1',
            'china_tracking_number' => '123456789',
            'china_cost' => '10000',
        ]);
    }
}
