<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->renameColumn('name', '_name_old');
        });

        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->json('name')->after('id');
        });

        DB::table('content_classifications')->orderBy('id')->each(function (object $row): void {
            DB::table('content_classifications')->where('id', $row->id)->update([
                'name' => json_encode(['id' => (string) ($row->_name_old ?? ''), 'en' => '']),
            ]);
        });

        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->dropColumn('_name_old');
        });
    }

    public function down(): void
    {
        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->renameColumn('name', '_name_json');
        });

        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->string('name')->after('id');
        });

        DB::table('content_classifications')->orderBy('id')->each(function (object $row): void {
            $name = json_decode($row->_name_json, true);
            DB::table('content_classifications')->where('id', $row->id)->update([
                'name' => $name['id'] ?? '',
            ]);
        });

        Schema::table('content_classifications', function (Blueprint $table): void {
            $table->dropColumn('_name_json');
        });
    }
};
