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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->unsignedSmallInteger('duration_months');
            $table->date('end_date');
            $table->unsignedSmallInteger('occupants_count')->default(1);
            $table->unsignedBigInteger('monthly_price');
            $table->unsignedBigInteger('total_rent_price');
            $table->unsignedBigInteger('deposit_price')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->string('status')->default('pending'); // pending, confirmed, active, rejected, cancelled, completed
            $table->string('tenant_name');
            $table->string('tenant_phone');
            $table->string('tenant_id_card')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('payment_method')->default('transfer_bca');
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, verified
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
