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
        Schema::create('users', static function (Blueprint $table) {
            $table->id();

            // Telegram data
            $table->unsignedBigInteger('telegram_id')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('language_code');
            $table->boolean('is_premium')->default(false);
            $table->boolean('allows_write_to_pm');

            $table->unsignedBigInteger('coins')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
