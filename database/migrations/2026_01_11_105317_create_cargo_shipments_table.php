<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
            $table->string('client_name')->default('');
            $table->string('client_messenger')->default(''); // TO_DO какой формат поля?
            $table->string('client_messenger_number', 100)->default('');
            $table->string('client_phone', 100)->default('');
            $table->text('recipient_address')->nullable();

            $table->string('china_tracking_number')->default('');
            $table->decimal('china_cost')->default(0);
            $table->string('cargo_status')->default(''); // TO_DO какой формат поля?
            $table->string('payment_type')->default(''); // TO_DO какой формат поля?
            $table->string('payment_status')->default(''); // TO_DO какой формат поля?
            $table->string('crate')->default(''); // TO_DO какой формат поля?

            $table->string('photo_path')->default('');
            $table->string('cargo_number')->default('');
            $table->text('product_name')->nullable();
            $table->string('material')->default('');
            $table->string('packaging_type')->default('');


            $table->unsignedSmallInteger('places_count')->default(0);
            $table->unsignedSmallInteger('items_per_place')->default(0);
            $table->unsignedInteger('total_items_count')->default(0);
            $table->decimal('gross_weight_per_place', 10, 3)->default(0);
            $table->decimal('gross_weight_total', 10, 3)->default(0);

            $table->decimal('length')->default(0);
            $table->decimal('width')->default(0);
            $table->decimal('height')->default(0);

            $table->decimal('volume_per_item', 10, 3)->default(0);
            $table->decimal('volume_total', 10, 3)->default(0);

            $table->decimal('tare_weight_per_box', 10, 3)->default(0);
            $table->decimal('tare_weight_total', 10, 3)->default(0);

            $table->decimal('net_weight_per_box', 10, 3)->default(0);
            $table->decimal('net_weight_total', 10, 3)->default(0);


            $table->decimal('insurance_amount')->default(0);
            $table->decimal('insurance_cost')->default(0);
            $table->decimal('rate_rub')->default(0);

            $table->decimal('total_cost')->default(0);
            $table->string('contact_phone', 100)->default('');
            $table->string('bank_name')->default('');
            $table->string('bank_account_name')->default('');

            $table->date('factory_shipping_date')->nullable();
            $table->date('sunfuihe_warehouse_received_date')->nullable();
            $table->date('china_shipping_date')->nullable();
            $table->date('ussuriysk_arrival_date')->nullable();
            $table->date('ussuriysk_shipping_date')->nullable();
            $table->date('client_received_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_shipments');
    }
};
