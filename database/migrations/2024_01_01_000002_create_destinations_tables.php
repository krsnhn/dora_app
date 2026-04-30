<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->string('location')->nullable();
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->string('tags')->nullable(); // comma-separated
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('weather_location')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();

            $table->index('created_by');
            $table->index('is_approved');
        });

        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('duration'); // e.g., "3 days / 2 nights"
            $table->text('inclusions')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->timestamps();

            $table->index('agency_id');
            $table->index('destination_id');
        });

        Schema::create('destination_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->string('name');
            $table->string('country');
            $table->string('location');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->string('tags')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('agency_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_requests');
        Schema::dropIfExists('tour_packages');
        Schema::dropIfExists('destinations');
    }
};
