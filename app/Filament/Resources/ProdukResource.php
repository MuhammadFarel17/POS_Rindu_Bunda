<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $pluralModelLabel = 'Produks';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Nama Produk
                TextInput::make('nama_produk')
                    ->required()
                    ->maxLength(255),

                // Gambar
                FileUpload::make('gambar')
                    ->directory('produk')
                    ->image()
                    ->nullable(),

                // Harga
                TextInput::make('harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                // Stok
                TextInput::make('stok')
                    ->numeric()
                    ->required(),

                // Kategori
                Select::make('kategori')
                    ->label('Kategori')
                    ->options(
                        \App\Models\Kategori::all()
                            ->pluck('nama_kategori', 'nama_kategori')
                    )
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_produk')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('gambar'),

                TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('stok')
                    ->sortable(),

                TextColumn::make('kategori'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}