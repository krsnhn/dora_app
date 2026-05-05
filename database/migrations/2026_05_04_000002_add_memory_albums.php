<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('memory_albums')) {
            Schema::create('memory_albums', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('name');
                $table->string('description')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'name']);
                $table->index('user_id');
            });
        }

        if (!Schema::hasColumn('memories', 'album_id')) {
            Schema::table('memories', function (Blueprint $table) {
                $table->unsignedBigInteger('album_id')->nullable()->after('user_id');
                $table->foreign('album_id')->references('id')->on('memory_albums')->onDelete('set null');
                $table->index('album_id');
            });
        }

        $userIds = DB::table('memories')->whereNull('album_id')->distinct()->pluck('user_id');

        foreach ($userIds as $userId) {
            $albumId = DB::table('memory_albums')->where('user_id', $userId)->where('name', 'Recents')->value('id');

            if (!$albumId) {
                $albumId = DB::table('memory_albums')->insertGetId([
                    'user_id' => $userId,
                    'name' => 'Recents',
                    'description' => 'Photos not yet sorted into a custom album.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('memories')->where('user_id', $userId)->whereNull('album_id')->update([
                'album_id' => $albumId,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('memories', 'album_id')) {
            Schema::table('memories', function (Blueprint $table) {
                $table->dropForeign(['album_id']);
                $table->dropIndex(['album_id']);
                $table->dropColumn('album_id');
            });
        }

        Schema::dropIfExists('memory_albums');
    }
};
