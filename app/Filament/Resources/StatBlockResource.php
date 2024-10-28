<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatBlockResource\Pages;
use App\Filament\Resources\StatBlockResource\RelationManagers;
use App\Models\StatBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatBlockResource extends Resource
{
    protected static ?string $model = StatBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('armor_class')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('hit_points')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('speed')
                    ->numeric()
                    ->required(),
                Forms\Components\Split::make(function() {
                    $schema = [];
                    foreach(config('worldbench.stats') as $key => $name) {
                        $schema[] = Forms\Components\TextInput::make('stats.' . $key)
                            ->label(ucfirst($name))
                            ->numeric()
                            ->required();
                    }
                    return $schema;
                })
                ->columns(count(config('worldbench.stats')))
                ->columnSpan(2),
                Forms\Components\Select::make('features')
                    ->relationship('features', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make([
                    Fieldset::make('Survivability')
                        ->schema([
                            TextEntry::make('armor_class')
                                ->inlineLabel(),
                            TextEntry::make('hit_points')
                                ->inlineLabel(),
                            TextEntry::make('speed')
                                ->suffix(' ft.')
                                ->inlineLabel(),
                        ])
                        ->label(false)
                        ->columns(1),
                    Fieldset::make('Ability Scores')
                        ->schema(function(StatBlock $statBlock): array {
                            $schema = [];
                            foreach($statBlock->stats as $key => $value) {
                                $schema[] = TextEntry::make('stats.' . $key)
                                    ->state($statBlock->getStatString($key))
                                    ->label(ucfirst($key));
                            }
                            return $schema;
                        })
                        ->columns(6)
                ])
                ->columns(1)
                ->columnSpan(1)
            ]);
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
            'index' => Pages\ListStatBlocks::route('/'),
            'create' => Pages\CreateStatBlock::route('/create'),
            'edit' => Pages\EditStatBlock::route('/{record}/edit'),
            'view' => Pages\ViewStatBlock::route('/{record}'),
        ];
    }
}
