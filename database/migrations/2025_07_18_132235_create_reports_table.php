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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_id')
            ->constrained('order_details')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('staff_id');
            $table->text('title');
            $table->text('report');
            $table->string('image1')
            ->nullable();
            $table->string('image2')
            ->nullable();
            $table->string('image3')
            ->nullable();
            $table->string('image4')
            ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
