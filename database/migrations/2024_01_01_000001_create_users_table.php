<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['traveler', 'agency', 'admin'])->default('traveler');
            $table->enum('status', ['pending', 'active', 'suspended'])->default('active');
            // Agency-specific fields
            $table->string('business_name')->nullable();
            $table->string('facebook_page')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('valid_id_path')->nullable();
            $table->text('verification_notes')->nullable();
            $table->enum('agency_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
