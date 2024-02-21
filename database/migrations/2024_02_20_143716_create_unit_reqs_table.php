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
            $table->string('area');
            $table->decimal('price',8,2);
            $table->longText('description');
            $table->dateTime('ad_period');
            $table->enum('entity_type',['companies','marketers','all']);
            $table->unsignedBigInteger('city_id');
            $table->timestamps();

            //foregin Key
            $table->foreign('city_id')->references('id')->on('cities');
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
