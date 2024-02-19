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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('region_id')->constrained('regions');
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('ad_title');
            $table->enum('unit_type',['land', 'apartment', 'exhibition']);
            $table->enum('contract_type', ['sale', 'rent']);
            $table->enum('interface', ['north', 'south', 'east', 'west']);
            $table->integer('floor_number');
            $table->decimal('area', 8, 2);
            $table->decimal('street_width', 8, 2);
            $table->enum('payment_method', ['cash', 'card']);
            $table->decimal('price', 8, 2);
            $table->longText('descreption');
            $table->longText('services');
            $table->integer('bedrooms');
            $table->integer('living_rooms');
            $table->integer('bathrooms');
            $table->integer('kitchens');
            $table->string('licensor_name');
            $table->string('advertising_license_number');
            $table->string('brokerage_documentation_license_number');
            $table->string('rights_and_obligations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
