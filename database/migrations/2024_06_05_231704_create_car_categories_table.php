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
        Schema::create('makes', function (Blueprint $table) {
            $table->id();
            $table->string('makes_name');
            $table->text('makes_info')->nullable();
            $table->integer('sort_order');
            $table->timestamps();
        });

        Schema::create('car_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makes_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('car_name');
            $table->text('car_info')->nullable();
            $table->integer('sort_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_sub_categories');
        Schema::dropIfExists('work_categories');
    }
};
