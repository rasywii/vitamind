<?php

namespace App\Filament\Resources\Seguimientos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SeguimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'name'),
                TextInput::make('tipo')
                    ->required(),
                Textarea::make('nota')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
