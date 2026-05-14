<?php

namespace App\Filament\Resources\Dealers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DealerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('contact_number')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Toggle::make('is_active')
                    ->label('Is Active Dealer')
                    ->default(true)
                    ->required(),
                
                // Location Hierarchy
                Select::make('province_id')
                    ->label('Province')
                    ->options(\App\Models\Province::all()->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->dehydrated(false),
                
                Select::make('district_id')
                    ->label('District')
                    ->options(fn (callable $get) => \App\Models\District::where('province_id', $get('province_id'))->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->dehydrated(false),
                
                Select::make('area_id')
                    ->label('Local Level')
                    ->options(fn (callable $get) => \App\Models\LocalLevel::where('zone_id', $get('district_id'))->pluck('name', 'id'))
                    ->searchable()
                    ->relationship('area', 'name')
                    ->required(),

                TextInput::make('ward')
                    ->label('Ward')
                    ->maxLength(80),

                TextInput::make('street_tole')
                    ->label('Street / Tole')
                    ->maxLength(120),

                Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric()
                    ->placeholder('e.g. 27.7172'),
                TextInput::make('longitude')
                    ->numeric()
                    ->placeholder('e.g. 85.3240'),
            ]);
    }
}
