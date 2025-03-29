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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('position'); // ex: "Mesa 1"
            $table->string('customer_name')->nullable(); // ou um relacionamento se tiver login
            $table->string('contact_phone')->nullable(); // opcional
            $table->text('observation')->nullable(); // opcional
            $table->dateTime('reserved_at'); // data/hora da reserva
            $table->integer('duration_minutes')->default(90); // tempo estimado da reserva
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
