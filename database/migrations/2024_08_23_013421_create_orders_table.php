<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('customer_id')->nullable();
            $table->integer('delivery_person_id')->nullable();
            $table->decimal('total_price', 8, 2);
            $table->foreignId('status_id')->constrained('statuses');
            $table->string('payment_status');
            $table->string('last_status_id')->nullable();
            $table->string('last_payment_status')->nullable();
            $table->foreignId('order_type_id')->constrained('order_types');
            $table->string('location')->nullable();
            $table->foreignId('order_origin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
