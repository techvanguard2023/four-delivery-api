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
        Schema::create('order_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('customer_name')->nullable();
            $table->string('position')->nullable(); // ex: "Mesa 1"
            $table->decimal('total_price', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('total_price_with_discount', 8, 2)->nullable();
            $table->foreignId('status_id')->constrained('statuses');
            $table->string('payment_status');
            $table->string('last_status_id')->nullable();
            $table->string('last_payment_status')->nullable();
            $table->foreignId('order_type_id')->constrained('order_types');
            $table->foreignId('order_origin_id')->nullable();
            $table->time('duration')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_slip');
    }
};