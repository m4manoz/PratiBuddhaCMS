<?php

namespace App\Filament\Resources\Zones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('District Name')
                    ->required(),
                Select::make('province_id')
                    ->label('Province')
                    ->relationship('province', 'name')
                    ->required(),
            ]);
    }
}
