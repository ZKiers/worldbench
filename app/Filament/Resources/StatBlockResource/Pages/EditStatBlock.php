<?php

namespace App\Filament\Resources\StatBlockResource\Pages;

use App\Filament\Resources\StatBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatBlock extends EditRecord
{
    protected static string $resource = StatBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
