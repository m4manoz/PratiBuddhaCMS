<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected const SOCIAL_NETWORKS = [
        'facebook' => ['label' => 'Facebook', 'icon' => 'fa-brands fa-facebook-f'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'fa-brands fa-youtube'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'fa-brands fa-tiktok'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fa-brands fa-linkedin-in'],
        'twitter_x' => ['label' => 'X', 'icon' => 'fa-brands fa-x-twitter'],
        'viber' => ['label' => 'Viber', 'icon' => 'fa-solid fa-comment-sms'],
        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fa-brands fa-whatsapp'],
        'telegram' => ['label' => 'Telegram', 'icon' => 'fa-brands fa-telegram'],
    ];

    protected $fillable = [
        'site_name',
        'hero_title',
        'hero_subtitle',
        'about_text',
        'hero_cta_products',
        'hero_cta_dealers',
        'contact_address',
        'contact_phone',
        'contact_email',
        'warehouse_location',
        'seo_title',
        'seo_description',
        'canonical_url',
        'og_image',
        'analytics_script',
        'about_page',
        'gallery_items',
        'events_items',
        'careers_items',
        'contact_page',
    ];

    protected $casts = [
        'about_page' => 'array',
        'gallery_items' => 'array',
        'events_items' => 'array',
        'careers_items' => 'array',
        'contact_page' => 'array',
    ];

    public static function getSettings(): self
    {
        return static::first() ?? new static([
            'site_name' => 'PRATIBUDDHA',
            'hero_title' => 'Nepal\'s Premier Electronics Importer',
            'hero_subtitle' => 'Elevating Your Home with Quality Electronics',
            'about_text' => 'At Pratibuddha Enterprises, we are dedicated to transforming your home experience with cutting-edge technology and global expertise.',
            'hero_cta_products' => 'Explore Products',
            'hero_cta_dealers' => 'Authorized Dealers',
        ]);
    }

    public static function getContactSocialLinks(?array $contactPage = null): array
    {
        $socialRaw = [];
        if (is_array($contactPage)) {
            $socialRaw = $contactPage['social_links'] ?? [];
        }

        if (! is_array($socialRaw)) {
            return [];
        }

        $normalized = [];

        foreach (self::SOCIAL_NETWORKS as $key => $meta) {
            $value = $socialRaw[$key] ?? null;
            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);
            if ($value === '') {
                continue;
            }

            if (! preg_match('/^(https?:\/\/|mailto:|tel:|viber:|whatsapp:|telegram:|tg:)/i', $value)) {
                if (str_starts_with($value, 'www.') || str_contains($value, '.')) {
                    $value = 'https://' . ltrim($value);
                } else {
                    continue;
                }
            }

            $isWeb = str_starts_with($value, 'http://') || str_starts_with($value, 'https://');
            if (! $isWeb && ! preg_match('/^(viber:|whatsapp:|telegram:|tg:)/i', $value)) {
                continue;
            }

            if ($isWeb && ! filter_var($value, FILTER_VALIDATE_URL)) {
                continue;
            }

            $normalized[] = [
                'key' => $key,
                'label' => $meta['label'],
                'icon' => $meta['icon'],
                'url' => $value,
                'is_web' => $isWeb,
            ];
        }

        return $normalized;
    }
}
