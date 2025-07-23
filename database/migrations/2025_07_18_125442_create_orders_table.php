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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('car_id')
                ->constrained('cars')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('shop_id')
                ->constrained('shops')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('staff_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->tinyInteger('order_status')->default(1);
            $table->text('order_info')->nullable();
            $table->dateTime('pitin_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
