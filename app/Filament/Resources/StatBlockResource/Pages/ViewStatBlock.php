<?php

namespace App\Filament\Resources\StatBlockResource\Pages;

use App\Filament\Resources\StatBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatBlock extends ViewRecord
{
    protected static string $resource = StatBlockResource::class;

    /**
     * @return string|null
     */
    public function getHeading(): string
    {
        return $this->getRecord()->name;
    }
}
