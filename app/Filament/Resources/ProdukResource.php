<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Exports\ProdukExporter;
use Filament\Tables\Actions\ExportBulkAction;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Produks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        TextInput::make('nama_produk')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('gambar')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('produk-images') 
                            ->required(),

                        TextInput::make('harga')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('stok')
                            ->label('Stok')
                            ->numeric()
                            ->required(),

                        Select::make('id_kategori')
                            ->label('Kategori')
                            ->relationship('kategoriRelasi', 'nama_kategori') 
                            ->searchable()
                            ->preload() 
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Foto'),

                TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategoriRelasi.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // --- BAGIAN TOMBOL ATAS (COLORFUL) ---
            ->headerActions([
                // Tombol PDF Warna Hijau Solid
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-s-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $produk = \App\Models\Produk::all();
                        $pdf = Pdf::loadView('pdf.produk', ['produk' => $produk]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'produk-list.pdf'
                        );
                    }),

                // Tombol Export Excel Warna Biru (Info) + Efek Pop Up
                ExportAction::make()
                    ->label('Export Excel')
                    ->exporter(ProdukExporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info') 
                    ->extraAttributes([
                        'class' => 'font-bold shadow-md hover:scale-105 transition-all',
                    ]),
            ])
            // --- BAGIAN BULK ACTION (FITUR CENTANG) ---
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // Tambahan Export Pilihan agar muncul saat data dicentang
                    ExportBulkAction::make()
                        ->label('Export Pilihan')
                        ->exporter(ProdukExporter::class)
                        ->icon('heroicon-o-check-circle')
                        ->color('info'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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