<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('categoria_id')
                    ->relationship('categoria', 'nombre')
                    ->required(),
                TextInput::make('nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('tipo')
                    ->required()
                    ->default('fisico'),
                TextInput::make('precio')
                    ->required()
                    ->numeric(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('controla_stock')
                    ->required(),
                TextInput::make('archivo_url')
                    ->url(),
                TextInput::make('imagen'),
                Toggle::make('activo')
                    ->required(),
            ]);
    }
}
