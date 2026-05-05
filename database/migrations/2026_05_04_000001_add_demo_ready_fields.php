<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_packages', 'image_url')) {
                $table->string('image_url')->nullable()->after('image_path');
            }
        });

        Schema::table('destination_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('destination_requests', 'image_url')) {
                $table->string('image_url')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('destination_requests', 'weather_location')) {
                $table->string('weather_location')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('destination_requests', function (Blueprint $table) {
            if (Schema::hasColumn('destination_requests', 'weather_location')) {
                $table->dropColumn('weather_location');
            }
            if (Schema::hasColumn('destination_requests', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });

        Schema::table('tour_packages', function (Blueprint $table) {
            if (Schema::hasColumn('tour_packages', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
};
