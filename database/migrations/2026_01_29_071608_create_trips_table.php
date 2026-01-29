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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // domestic | international
            $table->string('truck_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('status')->nullable();
            $table->string('invoice_number')->nullable();
            $table->text('loading_address')->nullable();
            $table->text('unloading_address')->nullable();
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
