<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table): void {
            $table->renameColumn('front_title', '_front_title_old');
            $table->renameColumn('back_title', '_back_title_old');
            $table->renameColumn('position', '_position_old');
        });

        Schema::table('team_members', function (Blueprint $table): void {
            $table->json('front_title')->nullable()->after('name');
            $table->json('back_title')->nullable()->after('front_title');
            $table->json('position')->nullable()->after('back_title');
        });

        DB::table('team_members')->orderBy('id')->each(function (object $row): void {
            DB::table('team_members')->where('id', $row->id)->update([
                'front_title' => json_encode(['id' => (string) ($row->_front_title_old ?? ''), 'en' => '']),
                'back_title' => json_encode(['id' => (string) ($row->_back_title_old ?? ''),  'en' => '']),
                'position' => json_encode(['id' => (string) ($row->_position_old ?? ''),    'en' => '']),
            ]);
        });

        Schema::table('team_members', function (Blueprint $table): void {
            $table->dropColumn(['_front_title_old', '_back_title_old', '_position_old']);
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table): void {
            $table->renameColumn('front_title', '_front_title_json');
            $table->renameColumn('back_title', '_back_title_json');
            $table->renameColumn('position', '_position_json');
        });

        Schema::table('team_members', function (Blueprint $table): void {
            $table->string('front_title')->nullable()->after('name');
            $table->string('back_title')->nullable()->after('front_title');
            $table->string('position')->nullable()->after('back_title');
        });

        DB::table('team_members')->orderBy('id')->each(function (object $row): void {
            $front = json_decode($row->_front_title_json, true);
            $back = json_decode($row->_back_title_json, true);
            $position = json_decode($row->_position_json, true);
            DB::table('team_members')->where('id', $row->id)->update([
                'front_title' => $front['id'] ?? null,
                'back_title' => $back['id'] ?? null,
                'position' => $position['id'] ?? null,
            ]);
        });

        Schema::table('team_members', function (Blueprint $table): void {
            $table->dropColumn(['_front_title_json', '_back_title_json', '_position_json']);
        });
    }
};
