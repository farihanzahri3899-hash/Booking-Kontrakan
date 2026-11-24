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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_status')->default('unverified')->after('avatar'); // unverified, pending, verified, rejected
            $table->string('kyc_id_type')->nullable()->after('kyc_status'); // ktp, ktm, sim, paspor
            $table->string('kyc_id_number')->nullable()->after('kyc_id_type');
            $table->string('kyc_document_path')->nullable()->after('kyc_id_number');
            $table->string('kyc_selfie_path')->nullable()->after('kyc_document_path');
            $table->text('kyc_rejection_reason')->nullable()->after('kyc_selfie_path');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_status',
                'kyc_id_type',
                'kyc_id_number',
                'kyc_document_path',
                'kyc_selfie_path',
                'kyc_rejection_reason',
                'kyc_verified_at',
            ]);
        });
    }
};
