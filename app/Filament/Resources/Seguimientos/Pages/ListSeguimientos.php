<?php

namespace App\Filament\Resources\Seguimientos\Pages;

use App\Filament\Resources\Seguimientos\SeguimientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeguimientos extends ListRecords
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
