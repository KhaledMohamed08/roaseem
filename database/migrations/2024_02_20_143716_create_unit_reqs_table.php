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
        Schema::create('unit_reqs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->enum('unit_types',['land','villa', 'Apartment', 'fair', 'building','office','station']);
            $table->enum('status',['sale','rent'])->nullable();
            $table->enum('purpose',['residential','companies','Agricultural'])->nullable();
            $table->unsignedDecimal('max_area',10,2);
            $table->unsignedDecimal('min_area',8,2);
            $table->unsignedDecimal('max_price',10,2);
            $table->unsignedDecimal('min_price',8,2);
            $table->longText('description');
            $table->enum('bed_rooms',['1','2','3','4',"5+"]);
            $table->enum('bath_rooms',['1','2','3','4',"5+"]);
            $table->dateTime('ad_period');
            $table->enum('entity_type',['companies','marketers','all']);
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            //foregin Keys
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_reqs');
    }
};
