<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
                TextInput::make('estado')
                    ->required()
                    ->default('pendiente'),
                TextInput::make('canal')
                    ->required()
                    ->default('tienda'),
                TextInput::make('metodo_pago'),
            ]);
    }
}
