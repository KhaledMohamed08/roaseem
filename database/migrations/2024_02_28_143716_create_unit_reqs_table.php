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
            $table->unsignedBigInteger('unit_types_id');
            $table->unsignedBigInteger('unit_status_id')->nullable();
            $table->unsignedBigInteger('unit_purpose_id')->nullable();
            $table->unsignedDecimal('max_area',10,2);
            $table->unsignedDecimal('min_area',8,2);
            $table->unsignedDecimal('max_price',10,2);
            $table->unsignedDecimal('min_price',10,2);
            $table->longText('description')->nullable();
            $table->enum('bed_rooms',['1','2','3','4',"5+"])->nullable();
            $table->enum('bath_rooms',['1','2','3','4',"5+"])->nullable();
            $table->dateTime('ad_period');
            $table->enum('entity_type',['companies','marketers','all']);
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            //foregin Keys
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unit_types_id')->references('id')->on('unit_types');
            $table->foreign('unit_status_id')->references('id')->on('unit_statuses');
            $table->foreign('unit_purpose_id')->references('id')->on('unit_purposes');
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
