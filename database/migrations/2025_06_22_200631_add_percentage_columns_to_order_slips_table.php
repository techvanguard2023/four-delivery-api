<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_slips', function (Blueprint $table) {
            $table->decimal('couvert', 8, 2)->nullable()->after('discount');
            $table->string('percentage_tax')->nullable()->after('couvert');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_slips', function (Blueprint $table) {
            $table->dropColumn(['couvert', 'percentage_tax']);
        });
    }
};
