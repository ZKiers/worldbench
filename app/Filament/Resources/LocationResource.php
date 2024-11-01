<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Character;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $activeNavigationIcon = 'heroicon-s-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->required(),
                Forms\Components\Select::make('location_id')
                    ->relationship('location')
                    ->options(Location::all()->pluck('name', 'id'))
                    ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->whereNull('location_id'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                Split::make([
                    Group::make([
                        TextEntry::make('description')
                            ->html()
                            ->label(fn(Location $location): string => $location->name),
                        RepeatableEntry::make('locations')
                            ->schema([
                                TextEntry::make('description')
                                    ->html()
                                    ->label(fn(Location $location): string => $location->name)
                                    ->hintAction(
                                        function (Location $location): Action {
                                            return Action::make('view')
                                                ->url(self::getUrl('view', ['record' => $location]))
                                                ->icon('heroicon-o-eye')
                                                ->label(false);
                                        }
                                    )
                            ])
                            ->label('Points of interest')
                            ->hidden(fn(Location $location): bool => $location->locations->count() == 0),
                        RepeatableEntry::make('characters')
                            ->schema([
                                TextEntry::make('background')
                                    ->label(fn(Character $character): string => $character->name)
                                    ->columnSpan(3)
                                    ->html(),
                                ImageEntry::make('portrait')
                                    ->columnSpan(1)
                                    ->size('100%')
                                    ->label(false)
                            ])
                            ->columns(4)
                            ->hidden(fn(Location $location): bool => $location->characters->count() == 0)
                    ]),
                    ImageEntry::make('map')
                        ->label(false)
                        ->size('100%'),
                ])
            ])
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getSubLocations(Location $location): array
    {
        $entries = [];
        $location->loadMissing('locations');
        foreach ($location->locations as $pointOfInterest) {
            $entries[] = TextEntry::make($pointOfInterest->name)
                ->state($pointOfInterest->description)
                ->html();
            $entries[] = ImageEntry::make($pointOfInterest->name . '_map')
                ->label(false)
                ->state($pointOfInterest->map);
        }
        return $entries;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
            'view' => Pages\ViewLocation::route('/{record}')
        ];
    }
}
