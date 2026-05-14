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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
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
                        KeyValue::make('about_page')
                            ->label('About page JSON')
                            ->helperText('Use keys hero_title, hero_subtitle, hero_text, mission_points, metrics, milestones')
                            ->columnSpanFull(),
                    ]),
                Section::make('Gallery / Events / Careers / Contact pages')
                    ->schema([
                        KeyValue::make('gallery_items')->label('Gallery cards')->columnSpanFull(),
                        KeyValue::make('events_items')->label('Events list')->columnSpanFull(),
                        KeyValue::make('careers_items')->label('Career roles')->columnSpanFull(),
                        KeyValue::make('contact_page')->label('Contact page')->columnSpanFull(),
                    ]),
            ]);
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
