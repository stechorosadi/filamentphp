<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_categories', function (Blueprint $table): void {
            $table->renameColumn('name', '_name_old');
            $table->renameColumn('description', '_description_old');
        });

        Schema::table('content_categories', function (Blueprint $table): void {
            $table->json('name')->after('id');
            $table->json('description')->nullable()->after('slug');
        });

        DB::table('content_categories')->orderBy('id')->each(function (object $row): void {
            DB::table('content_categories')->where('id', $row->id)->update([
                'name' => json_encode(['id' => (string) ($row->_name_old ?? ''),        'en' => '']),
                'description' => json_encode(['id' => (string) ($row->_description_old ?? ''), 'en' => '']),
            ]);
        });

        Schema::table('content_categories', function (Blueprint $table): void {
            $table->dropColumn(['_name_old', '_description_old']);
        });
    }

    public function down(): void
    {
        Schema::table('content_categories', function (Blueprint $table): void {
            $table->renameColumn('name', '_name_json');
            $table->renameColumn('description', '_description_json');
        });

        Schema::table('content_categories', function (Blueprint $table): void {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('slug');
        });

        DB::table('content_categories')->orderBy('id')->each(function (object $row): void {
            $name = json_decode($row->_name_json, true);
            $description = json_decode($row->_description_json, true);
            DB::table('content_categories')->where('id', $row->id)->update([
                'name' => $name['id'] ?? '',
                'description' => $description['id'] ?? null,
            ]);
        });

        Schema::table('content_categories', function (Blueprint $table): void {
            $table->dropColumn(['_name_json', '_description_json']);
        });
    }
};
