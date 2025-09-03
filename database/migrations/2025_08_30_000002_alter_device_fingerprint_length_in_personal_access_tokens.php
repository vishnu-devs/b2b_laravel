<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove the existing column with insufficient length
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('device_fingerprint');
        });

        // Re-add the column with a larger capacity to hold long fingerprints
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->mediumText('device_fingerprint')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        // Roll back to the previous definition (string 255)
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('device_fingerprint');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('device_fingerprint', 255)->nullable()->after('ip_address');
        });
    }
};