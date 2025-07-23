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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')
                ->constrained('item_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('prod_code')->nullable();
            $table->string('item_name')->nullable();
            $table->integer('item_price')->nullable();
            $table->integer('item_cost')->nullable();
            $table->text('item_info')->nullable();
            $table->string('item_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
