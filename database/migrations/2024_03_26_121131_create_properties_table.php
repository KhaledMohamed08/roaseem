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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions');
            $table->string('title');
            $table->string('desc');
            $table->foreignId('region_id')->constrained('regions');
            // $table->foreignId('property_type_id')->constrained('unit_types');
            $table->unsignedBigInteger('property_type_id');
            $table->foreign('property_type_id')->references('id')->on('unit_types');
            $table->string('address');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('license_name');
            $table->date('license_end_date');
            $table->string('brokerage_contract_number');
            $table->string('license_number');
            $table->date('license_creation_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
