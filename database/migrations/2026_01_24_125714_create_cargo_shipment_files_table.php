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
        Schema::create('cargo_shipment_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cargo_shipment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 50);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_category', 20)->nullable();

            $table->foreignId('uploaded_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Индексы для оптимизации
            $table->index(['cargo_shipment_id', 'file_category']);
            $table->index('uploaded_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_shipment_files');
    }
};
