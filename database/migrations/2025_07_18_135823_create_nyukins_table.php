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
        Schema::create('nyukins', function (Blueprint $table) {
            $table->id();
            $table->dateTime('nyukin_date');
            $table->foreignId('sales_id')
                ->constrained('sales')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('shop_id')
                ->constrained('shops')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('pay_method'); // 1: 現金, 2: クレジットカード, 3: 振込
            $table->integer('nyukin_kingaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nyukins');
    }
};
