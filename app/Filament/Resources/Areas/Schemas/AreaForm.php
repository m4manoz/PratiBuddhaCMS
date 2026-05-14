<?php

namespace App\Filament\Resources\Areas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Local Level Name')
                    ->required(),
                Select::make('zone_id')
                    ->label('District')
                    ->relationship('zone', 'name')
                    ->required(),
            ]);
    }
}
