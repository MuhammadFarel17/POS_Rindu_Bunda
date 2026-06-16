<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriExport;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 1;

    protected static ?string $pluralModelLabel = 'Kategoris';

    protected static ?string $navigationGroup = 'Masterdata';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        TextInput::make('id_kategori')
                            ->label('ID Kategori')
                            ->placeholder('Otomatis...')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Masukkan nama kategori...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kategori')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])

            // Tombol PDF & Excel
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $kategori = Kategori::all();

                        $pdf = Pdf::loadView('pdf.kategori', [
                            'kategori' => $kategori,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'data-kategori.pdf'
                        );
                    }),

                Action::make('downloadExcel')
                    ->label('Unduh Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('primary')
                    ->action(function () {
                        return Excel::download(
                            new KategoriExport(),
                            'data-kategori.xlsx'
                        );
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        return [];
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