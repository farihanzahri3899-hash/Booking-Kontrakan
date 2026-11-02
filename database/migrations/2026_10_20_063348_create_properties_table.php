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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('property_type')->default('rumah'); // rumah, petakan, paviliun, kost
            $table->text('description');
            $table->unsignedBigInteger('price_per_month');
            $table->unsignedBigInteger('price_per_year')->nullable();
            $table->unsignedBigInteger('deposit_amount')->default(0);
            $table->string('address');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('map_url')->nullable();
            $table->unsignedSmallInteger('bedrooms')->default(1);
            $table->unsignedSmallInteger('bathrooms')->default(1);
            $table->unsignedSmallInteger('building_size')->nullable();
            $table->unsignedSmallInteger('land_size')->nullable();
            $table->string('electricity_type')->default('token'); // token, pascabayar, gratis
            $table->string('water_source')->default('sumur_bor'); // sumur_bor, pam
            $table->json('facilities')->nullable();
            $table->json('rules')->nullable();
            $table->string('main_image');
            $table->json('images')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
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
