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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_category_id')
            ->constrained('car_categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->integer('user_id');
            $table->date('regist_date')->nullable();
            $table->date('inspect_date')->nullable();
            $table->text('car_info')->nullable();
            $table->string('car_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
