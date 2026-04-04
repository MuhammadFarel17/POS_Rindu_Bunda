<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuplayerResource\Pages;
use App\Filament\Resources\SuplayerResource\RelationManagers;
use App\Models\Suplayer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuplayerResource extends Resource
{
    protected static ?string $model = Suplayer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Textarea::make('address')
                    ->rows(3),

                Forms\Components\TextInput::make('city')
                    ->maxLength(100),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSuplayer::route('/'),
            'create' => Pages\CreateSuplayer::route('/create'),
            'edit' => Pages\EditSuplayer::route('/{record}/edit'),
        ];
    }
}
