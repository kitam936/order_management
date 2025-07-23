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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('work_id');
            $table->integer('item_id');
            $table->integer('item_price')->nullable();
            $table->integer('item_pcs')->default(0);
            $table->integer('work_fee')->default(0);
            $table->tinyInteger('detail_status')->default(1);
            $table->text('detail_info')->nullable();
            $table->integer('workingtime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
