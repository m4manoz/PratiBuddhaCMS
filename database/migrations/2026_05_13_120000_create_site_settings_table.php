<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('PRATIBUDDHA');
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('about_text')->nullable();
            $table->string('hero_cta_products', 120)->nullable();
            $table->string('hero_cta_dealers', 120)->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_phone', 40)->nullable();
            $table->string('contact_email', 140)->nullable();
            $table->string('warehouse_location')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('analytics_script')->nullable();
            $table->timestamps();
        });

        if (! DB::table('site_settings')->exists()) {
            DB::table('site_settings')->insert([
                'site_name' => 'PRATIBUDDHA',
                'hero_title' => 'Nepal\'s Premier Electronics Importer',
                'hero_subtitle' => 'Elevating Your Home with Quality Electronics',
                'about_text' => 'At Pratibuddha Enterprises, we are dedicated to transforming your home experience with cutting-edge technology and global expertise.',
                'hero_cta_products' => 'Explore Products',
                'hero_cta_dealers' => 'Authorized Dealers',
                'contact_address' => 'Janabahal, Kathmandu, Nepal',
                'contact_phone' => '+977-9849124657',
                'contact_email' => 'info@pratibuddha.com',
                'warehouse_location' => 'Sitapaila, Kathmandu',
                'seo_title' => 'PRATIBUDDHA | Premium Quality Products',
                'seo_description' => 'Explore premium quality electronics and authorized dealer support across Nepal.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
