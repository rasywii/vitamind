<?php

namespace App\Filament\Resources\Seguimientos\Pages;

use App\Filament\Resources\Seguimientos\SeguimientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeguimiento extends EditRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
