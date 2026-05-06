<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('agency_id');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->integer('pax')->default(1);
            $table->text('message')->nullable();
            $table->date('travel_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('tour_packages')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('user_id');
            $table->index('agency_id');
            $table->index('package_id');
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('destination_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'destination_id']);
            $table->index('user_id');
            $table->index('destination_id');
        });

        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->date('travel_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('set null');
            $table->timestamps();

            $table->index('user_id');
            $table->index('destination_id');
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('tour_packages')->onDelete('set null');
            $table->timestamps();

            $table->index('user_id');
            $table->index('agency_id');
        });

        Schema::create('backpack_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('item_name');
            $table->string('group_name')->default('default');
            $table->enum('category', ['essentials', 'clothing', 'toiletries', 'electronics', 'documents', 'other'])->default('essentials');
            $table->boolean('is_checked')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['user_id', 'group_name', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backpack_items');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('memories');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('inquiries');
    }
};
