<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatBlockResource\Pages;
use App\Filament\Resources\StatBlockResource\RelationManagers;
use App\Models\StatBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
                ->columnSpan(2)
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
