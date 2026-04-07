<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $pluralModelLabel = 'Users';

    //FORM
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel(),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3),

                Forms\Components\FileUpload::make('photo')
                    ->label('Foto')
                    ->image()
                    ->directory('users'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->label('Password'),
            ]);
    }

    //TABLE
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
