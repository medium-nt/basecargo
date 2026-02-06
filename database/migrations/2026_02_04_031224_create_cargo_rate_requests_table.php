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
        Schema::create('cargo_rate_requests', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Основные поля заявки
            $table->string('photo_path')->nullable();
            $table->text('product_name')->nullable();
            $table->string('material')->nullable();
            $table->decimal('gross_weight_total', 10, 3)->nullable();
            $table->decimal('volume_total', 10, 3)->nullable();
            $table->decimal('net_weight_total', 10, 3)->nullable();
            $table->text('delivery_address')->nullable();

            // Статус и расчеты менеджера
            $table->string('request_status')->default('pending');
            $table->decimal('calculated_rate', 10, 2)->nullable();
            $table->text('manager_notes')->nullable();
            $table->timestamp('calculated_at')->nullable();

            // Связь с созданным грузом
            $table->foreignId('cargo_shipment_id')->nullable()->constrained('cargo_shipments')->nullOnDelete();

            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            // Индексы для фильтрации
            $table->index('request_status');
            $table->index('client_id');
            $table->index('created_at');
            $table->index(['client_id', 'request_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_rate_requests');
    }
};
