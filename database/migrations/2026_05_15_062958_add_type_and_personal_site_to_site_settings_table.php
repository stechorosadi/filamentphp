<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('type')->default('organization')->after('id');
            $table->boolean('is_personal_site')->default(false)->after('vision');
            $table->foreignId('personal_member_id')
                ->nullable()
                ->after('is_personal_site')
                ->constrained('team_members')
                ->nullOnDelete();
        });

        // Seed the personal row as a copy of the org row
        $org = DB::table('site_settings')->where('type', 'organization')->first();
        if ($org) {
            $row = (array) $org;
            unset($row['id']);
            $row['type'] = 'personal';
            $row['is_personal_site'] = false;
            $row['personal_member_id'] = null;
            $row['created_at'] = now();
            $row['updated_at'] = now();
            DB::table('site_settings')->insert($row);
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->where('type', 'personal')->delete();

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropForeign(['personal_member_id']);
            $table->dropColumn(['type', 'is_personal_site', 'personal_member_id']);
        });
    }
};
