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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('message');
            $table->boolean('is_read');
            $table->enum('event',['unit_viewed', 'added to', 'unit_added_to_fav','new_unit']);
            $table->string('url')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            //foregin Keys
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
