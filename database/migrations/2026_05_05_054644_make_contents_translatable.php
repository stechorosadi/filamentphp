<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->renameColumn('title', '_title_old');
            $table->renameColumn('excerpt', '_excerpt_old');
            $table->renameColumn('content', '_content_old');
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->json('title')->after('user_id');
            $table->json('excerpt')->nullable()->after('title');
            $table->json('content')->after('excerpt');
        });

        DB::table('contents')->orderBy('id')->each(function (object $row): void {
            DB::table('contents')->where('id', $row->id)->update([
                'title' => json_encode(['id' => (string) ($row->_title_old ?? ''),   'en' => '']),
                'excerpt' => json_encode(['id' => (string) ($row->_excerpt_old ?? ''), 'en' => '']),
                'content' => json_encode(['id' => (string) ($row->_content_old ?? ''), 'en' => '']),
            ]);
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->dropColumn(['_title_old', '_excerpt_old', '_content_old']);
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table): void {
            $table->renameColumn('title', '_title_json');
            $table->renameColumn('excerpt', '_excerpt_json');
            $table->renameColumn('content', '_content_json');
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->string('title')->after('user_id');
            $table->text('excerpt')->nullable()->after('title');
            $table->longText('content')->after('excerpt');
        });

        DB::table('contents')->orderBy('id')->each(function (object $row): void {
            $title = json_decode($row->_title_json, true);
            $excerpt = json_decode($row->_excerpt_json, true);
            $content = json_decode($row->_content_json, true);
            DB::table('contents')->where('id', $row->id)->update([
                'title' => $title['id'] ?? '',
                'excerpt' => $excerpt['id'] ?? null,
                'content' => $content['id'] ?? '',
            ]);
        });

        Schema::table('contents', function (Blueprint $table): void {
            $table->dropColumn(['_title_json', '_excerpt_json', '_content_json']);
        });
    }
};
