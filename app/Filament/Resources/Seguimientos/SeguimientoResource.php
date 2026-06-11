<?php

namespace App\Filament\Resources\Seguimientos;

use App\Filament\Resources\Seguimientos\Pages\CreateSeguimiento;
use App\Filament\Resources\Seguimientos\Pages\EditSeguimiento;
use App\Filament\Resources\Seguimientos\Pages\ListSeguimientos;
use App\Filament\Resources\Seguimientos\Schemas\SeguimientoForm;
use App\Filament\Resources\Seguimientos\Tables\SeguimientosTable;
use App\Models\Seguimiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return SeguimientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeguimientosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeguimientos::route('/'),
            'create' => CreateSeguimiento::route('/create'),
            'edit' => EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
