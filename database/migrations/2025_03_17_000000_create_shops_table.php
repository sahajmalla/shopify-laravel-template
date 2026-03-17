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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop')->unique()->comment('Shop domain e.g. test-shop.myshopify.com');
            $table->string('access_mode', 16)->default('offline');
            $table->string('user_id')->nullable()->comment('Merchant user ID for online tokens');
            $table->text('token');
            $table->string('scope')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('refresh_token_expires_at')->nullable();
            $table->json('user')->nullable()->comment('User details for online tokens');
            $table->timestamps();

            $table->index(['shop', 'access_mode', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
