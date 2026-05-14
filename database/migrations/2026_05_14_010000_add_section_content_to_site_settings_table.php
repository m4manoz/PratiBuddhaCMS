<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('site_settings', 'about_page')) {
                $table->json('about_page')->nullable()->after('analytics_script');
            }

            if (! Schema::hasColumn('site_settings', 'gallery_items')) {
                $table->json('gallery_items')->nullable()->after('about_page');
            }

            if (! Schema::hasColumn('site_settings', 'events_items')) {
                $table->json('events_items')->nullable()->after('gallery_items');
            }

            if (! Schema::hasColumn('site_settings', 'careers_items')) {
                $table->json('careers_items')->nullable()->after('events_items');
            }

            if (! Schema::hasColumn('site_settings', 'contact_page')) {
                $table->json('contact_page')->nullable()->after('careers_items');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_page',
                'gallery_items',
                'events_items',
                'careers_items',
                'contact_page',
            ]);
        });
    }
};
