<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CampaignTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Campaign::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form($this->campaignForm())
                    ->hidden(!auth()->user()->admin)
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form($this->campaignForm())
                    ->hidden(!auth()->user()->admin)
            ]);
    }

    public function campaignForm(): array
    {
        return [
            TextInput::make('title')
                ->required(),
            Select::make('users')
                ->multiple()
                ->relationship('users', 'name')
                ->options(User::all()->pluck('name', 'id'))
        ];
    }
}
