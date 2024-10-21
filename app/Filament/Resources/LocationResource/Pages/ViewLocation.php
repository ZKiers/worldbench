<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewLocation extends ViewRecord
{
    protected static string $resource = LocationResource::class;

    public function getHeading(): string
    {
        return $this->getRecord()->name;
    }

    public function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->hidden(!auth()->user()->admin)
        ];
    }
}
