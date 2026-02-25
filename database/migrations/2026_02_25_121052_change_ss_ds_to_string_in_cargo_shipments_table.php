<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // 1. Создаём временную таблицу с новой структурой
            DB::statement('
                CREATE TABLE cargo_shipments_temp AS
                SELECT * FROM cargo_shipments
            ');

            // 2. Удаляем старую таблицу
            Schema::drop('cargo_shipments');

            // 3. Создаём новую таблицу с правильным типом SS_DS
            Schema::create('cargo_shipments', function (Blueprint $table) {
                $table->id();
                $table->uuid('public_id')->unique();

                $table->foreignId('responsible_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->foreignId('client_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->text('delivery_address')->nullable();
                $table->string('contact_phone', 100)->nullable();
                $table->string('china_tracking_number')->nullable();
                $table->decimal('china_cost')->nullable();
                $table->string('cargo_status')->nullable();
                $table->string('payment_type')->nullable();
                $table->string('payment_status')->nullable();
                $table->decimal('crate')->nullable();
                $table->string('photo_path')->nullable();
                $table->string('cargo_number')->nullable();
                $table->text('product_name')->nullable();
                $table->string('material')->nullable();
                $table->string('packaging')->nullable();
                $table->unsignedSmallInteger('places_count')->nullable();
                $table->unsignedSmallInteger('items_per_place')->nullable();
                $table->unsignedInteger('total_items_count')->nullable();
                $table->decimal('gross_weight_per_place', 10, 3)->nullable();
                $table->decimal('gross_weight_total', 10, 3)->nullable();
                $table->decimal('length')->nullable();
                $table->decimal('width')->nullable();
                $table->decimal('height')->nullable();
                $table->decimal('volume_per_item', 10, 3)->nullable();
                $table->decimal('volume_total', 10, 3)->nullable();
                $table->decimal('tare_weight_per_box', 10, 3)->nullable();
                $table->decimal('tare_weight_total', 10, 3)->nullable();
                $table->decimal('net_weight_per_box', 10, 3)->nullable();
                $table->decimal('net_weight_total', 10, 3)->nullable();
                $table->decimal('insurance_amount')->nullable();
                $table->decimal('insurance_cost')->nullable();
                $table->decimal('rate_rub')->nullable();
                $table->decimal('total_cost')->nullable();
                $table->string('payment_phone', 100)->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_account_name')->nullable();
                $table->date('factory_shipping_date')->nullable();
                $table->date('sunfuihe_warehouse_received_date')->nullable();
                $table->date('china_shipping_date')->nullable();
                $table->date('ussuriysk_arrival_date')->nullable();
                $table->date('ussuriysk_shipping_date')->nullable();
                $table->date('client_received_date')->nullable();
                $table->string('explanations')->nullable();
                $table->string('TN_VED_code')->nullable();
                $table->decimal('ITS')->nullable();
                $table->decimal('payment')->nullable();
                $table->decimal('VAT')->nullable();
                $table->string('label_name')->nullable();
                $table->string('label_number')->nullable();
                $table->string('marking')->nullable();
                $table->string('manufacturer')->nullable();
                $table->string('SS_DS', 255)->nullable(); // ИЗМЕНЕНО: string(255) вместо decimal
                $table->decimal('bet')->nullable();
                $table->decimal('duty')->nullable();
                $table->decimal('volume_weight')->nullable();
                $table->decimal('customs_clearance_service')->nullable();
                $table->decimal('cost_truck')->nullable();
                $table->decimal('revenue_per_kg')->nullable();
                $table->decimal('dollar_rate')->nullable();
                $table->decimal('yuan_rate')->nullable();
                $table->timestamps();
            });

            // 4. Копируем данные обратно
            DB::statement('
                INSERT INTO cargo_shipments SELECT * FROM cargo_shipments_temp
            ');

            // 5. Удаляем временную таблицу
            Schema::drop('cargo_shipments_temp');

        } else {
            // MySQL/PostgreSQL — сначала конвертируем данные, потом меняем тип
            DB::statement("UPDATE cargo_shipments SET SS_DS = NULL WHERE SS_DS = 0");
            DB::statement("UPDATE cargo_shipments SET SS_DS = CAST(SS_DS AS CHAR) WHERE SS_DS IS NOT NULL");

            Schema::table('cargo_shipments', function (Blueprint $table) {
                $table->string('SS_DS', 255)->nullable()->change();
            });
        }
    }

    public function down()
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite — пересоздаём таблицу
            DB::statement('
                CREATE TABLE cargo_shipments_temp AS
                SELECT * FROM cargo_shipments
            ');
            Schema::drop('cargo_shipments');

            Schema::create('cargo_shipments', function (Blueprint $table) {
                $table->id();
                $table->uuid('public_id')->unique();
                $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('delivery_address')->nullable();
                $table->string('contact_phone', 100)->nullable();
                $table->string('china_tracking_number')->nullable();
                $table->decimal('china_cost')->nullable();
                $table->string('cargo_status')->nullable();
                $table->string('payment_type')->nullable();
                $table->string('payment_status')->nullable();
                $table->decimal('crate')->nullable();
                $table->string('photo_path')->nullable();
                $table->string('cargo_number')->nullable();
                $table->text('product_name')->nullable();
                $table->string('material')->nullable();
                $table->string('packaging')->nullable();
                $table->unsignedSmallInteger('places_count')->nullable();
                $table->unsignedSmallInteger('items_per_place')->nullable();
                $table->unsignedInteger('total_items_count')->nullable();
                $table->decimal('gross_weight_per_place', 10, 3)->nullable();
                $table->decimal('gross_weight_total', 10, 3)->nullable();
                $table->decimal('length')->nullable();
                $table->decimal('width')->nullable();
                $table->decimal('height')->nullable();
                $table->decimal('volume_per_item', 10, 3)->nullable();
                $table->decimal('volume_total', 10, 3)->nullable();
                $table->decimal('tare_weight_per_box', 10, 3)->nullable();
                $table->decimal('tare_weight_total', 10, 3)->nullable();
                $table->decimal('net_weight_per_box', 10, 3)->nullable();
                $table->decimal('net_weight_total', 10, 3)->nullable();
                $table->decimal('insurance_amount')->nullable();
                $table->decimal('insurance_cost')->nullable();
                $table->decimal('rate_rub')->nullable();
                $table->decimal('total_cost')->nullable();
                $table->string('payment_phone', 100)->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_account_name')->nullable();
                $table->date('factory_shipping_date')->nullable();
                $table->date('sunfuihe_warehouse_received_date')->nullable();
                $table->date('china_shipping_date')->nullable();
                $table->date('ussuriysk_arrival_date')->nullable();
                $table->date('ussuriysk_shipping_date')->nullable();
                $table->date('client_received_date')->nullable();
                $table->string('explanations')->nullable();
                $table->string('TN_VED_code')->nullable();
                $table->decimal('ITS')->nullable();
                $table->decimal('payment')->nullable();
                $table->decimal('VAT')->nullable();
                $table->string('label_name')->nullable();
                $table->string('label_number')->nullable();
                $table->string('marking')->nullable();
                $table->string('manufacturer')->nullable();
                $table->decimal('SS_DS')->nullable(); // ВОССТАНОВЛЕНО: decimal
                $table->decimal('bet')->nullable();
                $table->decimal('duty')->nullable();
                $table->decimal('volume_weight')->nullable();
                $table->decimal('customs_clearance_service')->nullable();
                $table->decimal('cost_truck')->nullable();
                $table->decimal('revenue_per_kg')->nullable();
                $table->decimal('dollar_rate')->nullable();
                $table->decimal('yuan_rate')->nullable();
                $table->timestamps();
            });

            DB::statement('INSERT INTO cargo_shipments SELECT * FROM cargo_shipments_temp');
            Schema::drop('cargo_shipments_temp');

        } else {
            Schema::table('cargo_shipments', function (Blueprint $table) {
                $table->decimal('SS_DS')->nullable()->change();
            });
        }
    }
};
