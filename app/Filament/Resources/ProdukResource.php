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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $pluralModelLabel = 'Produks';

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Produks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Nama Produk
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
                            ->directory('produk-images') // Akan disimpan di storage/app/public/produk-images
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
                        // INI BAGIAN YANG MENARIK DATA KATEGORI
                        Select::make('id_kategori')
                            ->label('Kategori')
                            ->relationship('kategoriRelasi', 'nama_kategori') // Pakai nama fungsi relasi di Model
                            ->searchable()
                            ->preload() // Supaya data langsung muncul saat diklik
                            ->required(),
                    ])->columns(2),
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
                // Di bagian form()
                Select::make('kategori') 
                    ->label('Kategori')
                    // Arahkan ke nama fungsi baru: 'kategoriRelasi'
                    ->relationship('kategoriRelasi', 'nama_kategori') 
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
                TextColumn::make('nama_produk')->searchable()->sortable(),
                ImageColumn::make('gambar'),
                TextColumn::make('harga')->money('IDR')->sortable(),
                TextColumn::make('stok')->sortable(),
                // Mengakses relasi 'kategori' dan kolom 'nama_kategori'
                TextColumn::make('kategori.nama_kategori')->label('Kategori')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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