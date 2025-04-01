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
        Schema::create('order_slip_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_slip_id')->constrained('order_slips')->onDelete('cascade');
            $table->enum('type', ['discount', 'tip', 'cover_charge']);
            $table->enum('value_type', ['fixed', 'percentage']);
            $table->decimal('value', 8, 2);
            $table->string('description')->nullable(); // Ex: "Couvert artístico ao vivo"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_slip_adjustments');
    }
};