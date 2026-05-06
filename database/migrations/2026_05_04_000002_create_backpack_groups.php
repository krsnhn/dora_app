<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backpack_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->date('travel_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'title']);
        });

        Schema::table('backpack_items', function (Blueprint $table) {
            if (!Schema::hasColumn('backpack_items', 'group_id')) {
                $table->unsignedBigInteger('group_id')->nullable()->after('user_id');
                $table->foreign('group_id')->references('id')->on('backpack_groups')->onDelete('cascade');
                $table->index('group_id');
            }
        });

        // Only modify column for MySQL (SQLite doesn't support MODIFY syntax)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE backpack_items MODIFY category VARCHAR(100) NOT NULL DEFAULT "essentials"');
        }

        $legacyGroups = DB::table('backpack_items')
            ->select('user_id', 'group_name')
            ->whereNotNull('group_name')
            ->distinct()
            ->get();

        foreach ($legacyGroups as $legacyGroup) {
            $groupId = DB::table('backpack_groups')->insertGetId([
                'user_id' => $legacyGroup->user_id,
                'title' => $legacyGroup->group_name === 'default' ? 'Default Trip' : $legacyGroup->group_name,
                'start_date' => null,
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('backpack_items')
                ->where('user_id', $legacyGroup->user_id)
                ->where('group_name', $legacyGroup->group_name)
                ->update(['group_id' => $groupId]);
        }
    }

    public function down(): void
    {
        Schema::table('backpack_items', function (Blueprint $table) {
            if (Schema::hasColumn('backpack_items', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropIndex(['group_id']);
                $table->dropColumn('group_id');
            }
        });

        Schema::dropIfExists('backpack_groups');
    }
};
