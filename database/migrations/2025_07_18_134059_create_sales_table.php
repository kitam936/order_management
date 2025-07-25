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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->dateTime('sales_date');
            $table->foreignId('shop_id')
                ->constrained('shops')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('car_id')
                ->constrained('cars')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('staff_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('order_kingaku');
            $table->integer('adjust');
            $table->integer('total_kingaku');
            $table->text('seikyu_info')->nullable();
            $table->dateTime('nyukin_limit')->nullable();
            $table->tinyInteger('seikyu_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
