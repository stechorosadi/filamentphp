<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->renameColumn('site_title', '_site_title_old');
            $table->renameColumn('site_tagline', '_site_tagline_old');
            $table->renameColumn('site_description', '_site_description_old');
        });

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->json('site_title')->after('id');
            $table->json('site_tagline')->nullable()->after('site_title');
            $table->json('site_description')->nullable()->after('site_tagline');
        });

        DB::table('site_settings')->orderBy('id')->each(function (object $row): void {
            DB::table('site_settings')->where('id', $row->id)->update([
                'site_title' => json_encode(['id' => (string) ($row->_site_title_old ?? ''),       'en' => '']),
                'site_tagline' => json_encode(['id' => (string) ($row->_site_tagline_old ?? ''),     'en' => '']),
                'site_description' => json_encode(['id' => (string) ($row->_site_description_old ?? ''), 'en' => '']),
            ]);
        });

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn(['_site_title_old', '_site_tagline_old', '_site_description_old']);
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->renameColumn('site_title', '_site_title_json');
            $table->renameColumn('site_tagline', '_site_tagline_json');
            $table->renameColumn('site_description', '_site_description_json');
        });

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('site_title')->after('id');
            $table->string('site_tagline')->nullable()->after('site_title');
            $table->text('site_description')->nullable()->after('site_tagline');
        });

        DB::table('site_settings')->orderBy('id')->each(function (object $row): void {
            $title = json_decode($row->_site_title_json, true);
            $tagline = json_decode($row->_site_tagline_json, true);
            $description = json_decode($row->_site_description_json, true);
            DB::table('site_settings')->where('id', $row->id)->update([
                'site_title' => $title['id'] ?? '',
                'site_tagline' => $tagline['id'] ?? null,
                'site_description' => $description['id'] ?? null,
            ]);
        });

        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn(['_site_title_json', '_site_tagline_json', '_site_description_json']);
        });
    }
};
