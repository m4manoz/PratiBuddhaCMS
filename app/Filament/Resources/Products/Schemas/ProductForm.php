<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                
                \Filament\Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(\App\Models\Product::class, 'slug', ignoreRecord: true),
                
                \Filament\Forms\Components\TextInput::make('category')
                    ->placeholder('e.g. Electronics, Home, etc.'),
                
                \Filament\Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('NPR'),
                
                \Filament\Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                
                \Filament\Forms\Components\FileUpload::make('image_path')
                    ->label('Product Image')
                    ->image()
                    ->directory('products'),
                
                \Filament\Forms\Components\KeyValue::make('specifications')
                    ->addActionLabel('Add Specification')
                    ->columnSpanFull(),
            ]);
    }
}
