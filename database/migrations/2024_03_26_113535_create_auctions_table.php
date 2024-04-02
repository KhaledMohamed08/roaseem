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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->string('desc');
            $table->boolean('is_offline')->default(false);
            $table->string('link');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->decimal('opening_price');
            $table->decimal('subscription_fee');
            $table->decimal('minimum_bid');
            $table->enum('admin_status', ['bending', 'confirmed', 'rejected'])->default('bending');
            $table->enum('status', ['coming', 'ongoing', 'ended'])->default('coming');
            $table->string('auctioneer_name');
            // $table->string('id_image');
            $table->string('id_number');
            $table->string('auction_license_number');
            $table->foreignId('region_id')->constrained('regions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
