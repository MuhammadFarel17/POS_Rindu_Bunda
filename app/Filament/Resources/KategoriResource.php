<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masterdata';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_kategori')
                    ->default(function () {
                        $latest = \App\Models\Kategori::latest('id')->first();
                        if (! $latest) {
                            return 'KAT-001';
                        }
                        $string = preg_replace("/[^0-9\.]/", '', $latest->id_kategori);
                        return 'KAT-' . sprintf('%03d', (int)$string + 1);
                    })
                    ->disabled()
                    ->dehydrated(false)
                    ->label('ID Kategori'),
                TextInput::make('nama_kategori')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kategori')
                    ->searchable()
                    ->sortable()
                    ->label('ID Kategori'),
                TextColumn::make('nama_kategori')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kategori'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function () {
                        $kategori = Kategori::all();
                        $pdf = Pdf::loadView('pdf.kategori', ['kategori' => $kategori]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'kategori-list.pdf');
                    }),
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
            'index' => Pages\ListKategori::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}
