<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('color_light_bg')->default('#ECF39E')->after('youtube_url');
            $table->string('color_dark_bg')->default('#132A13')->after('color_light_bg');
            $table->string('color_light_text')->default('#132A13')->after('color_dark_bg');
            $table->string('color_dark_text')->default('#ECF39E')->after('color_light_text');
            $table->string('color_accent')->default('#4F772D')->after('color_dark_text');
            $table->string('color_accent_dark')->default('#90A955')->after('color_accent');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'color_light_bg', 'color_dark_bg',
                'color_light_text', 'color_dark_text',
                'color_accent', 'color_accent_dark',
            ]);
        });
    }
};
