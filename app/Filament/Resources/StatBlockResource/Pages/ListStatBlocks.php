<?php

namespace App\Filament\Resources\StatBlockResource\Pages;

use App\Filament\Resources\StatBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatBlocks extends ListRecords
{
    protected static string $resource = StatBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
