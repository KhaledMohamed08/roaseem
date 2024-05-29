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
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('ad_title');
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->foreignId('unit_status_id')->constrained('unit_statuses');
            $table->foreignId('unit_purpose_id')->constrained('unit_purposes');
            $table->foreignId('unit_interface_id')->constrained('unit_interfaces');
            $table->year('created_year');
            $table->date('license_start');
            $table->date('license_end');
            $table->integer('floor_number');
            $table->decimal('area', 8, 2);
            $table->decimal('street_width', 8, 2);
            $table->foreignId('unit_payment_id')->constrained('unit_payments');
            $table->decimal('price', 11, 2);
            $table->longText('descreption')->nullable();
            // $table->longText('services');
            $table->integer('bedrooms');
            $table->integer('living_rooms');
            $table->integer('bathrooms');
            $table->integer('kitchens');
            $table->enum('unit_category',['F', 'S']);
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
