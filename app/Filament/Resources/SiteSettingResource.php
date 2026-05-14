<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettingResource\Pages\ListSiteSettings;
use App\Models\SiteSetting;
use Filament\Actions\EditAction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $recordTitleAttribute = 'site_name';
    protected static ?string $slug = 'site-settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand & Header')
                    ->schema([
                        TextInput::make('site_name')->required(),
                        TextInput::make('hero_title')->required(),
                        TextInput::make('hero_subtitle'),
                        Textarea::make('about_text')->columnSpanFull(),
                        TextInput::make('hero_cta_products')->label('Hero CTA (Products)'),
                        TextInput::make('hero_cta_dealers')->label('Hero CTA (Dealers)'),
                        Textarea::make('analytics_script')->label('Analytics Script (optional)')->rows(4)->columnSpanFull(),
                    ]),
                Section::make('Navigation & Contact')
                    ->schema([
                        TextInput::make('contact_address'),
                        TextInput::make('contact_phone'),
                        TextInput::make('contact_email')->email(),
                        TextInput::make('warehouse_location'),
                        TextInput::make('contact_page.social_links.facebook')->label('Facebook')->url()->nullable(),
                        TextInput::make('contact_page.social_links.instagram')->label('Instagram')->url()->nullable(),
                        TextInput::make('contact_page.social_links.youtube')->label('YouTube')->url()->nullable(),
                        TextInput::make('contact_page.social_links.tiktok')->label('TikTok')->url()->nullable(),
                        TextInput::make('contact_page.social_links.linkedin')->label('LinkedIn')->url()->nullable(),
                        TextInput::make('contact_page.social_links.twitter_x')->label('X')->url()->nullable(),
                        TextInput::make('contact_page.social_links.whatsapp')->label('WhatsApp')->nullable()
                            ->helperText('Use WhatsApp URL such as https://wa.me/<number>'),
                        TextInput::make('contact_page.social_links.viber')->label('Viber')->nullable()
                            ->helperText('Use viber app link such as viber://chat?number=+977... (optional)'),
                        TextInput::make('contact_page.social_links.telegram')->label('Telegram')->nullable(),
                    ]),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title'),
                        Textarea::make('seo_description')->maxLength(300)->columnSpanFull(),
                        TextInput::make('canonical_url'),
                        TextInput::make('og_image')->label('OG Image URL'),
                    ]),
                Section::make('About page')
                    ->schema([
                        self::jsonEditor('about_page', 'About page JSON', 'Use valid JSON. Supports keys like hero_title, hero_subtitle, hero_text, mission_points, metrics, milestones.'),
                    ]),
                Section::make('Gallery / Events / Careers / Contact pages')
                    ->schema([
                        self::jsonEditor('gallery_items', 'Gallery cards', 'Array JSON expected.'),
                        self::jsonEditor('events_items', 'Events list', 'Array JSON expected.'),
                        self::jsonEditor('careers_items', 'Career roles', 'Array JSON expected.'),
                        self::jsonEditor('contact_page', 'Contact page', 'Object JSON expected.'),
                    ]),
            ]);
    }

    protected static function jsonEditor(string $field, string $label, string $helperText): Textarea
    {
        return Textarea::make($field)
            ->label($label)
            ->rows(10)
            ->columnSpanFull()
            ->helperText($helperText)
            ->nullable()
            ->rule('json')
            ->formatStateUsing(fn ($state) => self::encodeJsonState($state))
            ->dehydrateStateUsing(fn ($state) => self::decodeJsonState($state));
    }

    protected static function encodeJsonState($state): string
    {
        if ($state === null || $state === '') {
            return '';
        }

        if (is_string($state)) {
            $decoded = json_decode($state, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }

            return $state;
        }

        return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected static function decodeJsonState($state)
    {
        if ($state === null || trim((string) $state) === '') {
            return null;
        }

        if (is_array($state)) {
            return $state;
        }

        $decoded = json_decode((string) $state, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('site_name')->searchable()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteSettings::route('/'),
            'edit' => EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
