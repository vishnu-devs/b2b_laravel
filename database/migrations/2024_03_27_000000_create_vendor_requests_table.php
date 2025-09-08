<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('gst_number');
            $table->string('pan_number');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('contact_person_name');
            $table->string('contact_person_phone');
            $table->string('alternate_phone')->nullable();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('branch_name');

            $table->tinyInteger('status')->default(0); // 0 for pending, 1 for approved, 2 for rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_requests');
    }
};