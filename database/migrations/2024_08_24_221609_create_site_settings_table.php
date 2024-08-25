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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->text('logo_url')->nullable();
            $table->text('menu_dishes')->nullable();
            $table->text('about_us_title')->nullable();
            $table->text('about_us_content')->nullable();
            $table->string('whatsapp_bot_number')->nullable();
            $table->string('email')->nullable();
            $table->text('social')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
