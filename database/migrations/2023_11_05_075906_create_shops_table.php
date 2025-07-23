<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('company_id');
            $table->string('shop_name');
            $table->text('shop_info')->nullable();
            $table->foreignId('company_id')->constrained();
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
            $table->string('shop_postcode')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('shop_tel')->nullable();
            $table->integer('rate')->default(0);
            $table->boolean('is_selling')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
