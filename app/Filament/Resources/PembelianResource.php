<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianResource\Pages;
use App\Models\Pembelian;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;

// Tambahan untuk PDF
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // STEP 1: Data Utama
                    Wizard\Step::make('Data Pembelian')
                        ->schema([
                            Forms\Components\Section::make('Faktur Pembelian')
                                ->schema([ 
                                    TextInput::make('no_faktur')
                                        ->default(fn () => Pembelian::getKodeFakturBeli())
                                        ->label('Nomor Faktur')
                                        ->required()
                                        ->readonly(),
                                    
                                    Select::make('kode_suplayer')
                                        ->label('Supplier')
                                        ->relationship('suplayer', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    DateTimePicker::make('tgl')
                                        ->label('Tanggal Pembelian')
                                        ->default(now())
                                        ->required(),

                                    Select::make('status')
                                        ->options([
                                            'pesan' => 'Pesan',
                                            'pending' => 'Pending',
                                        ])
                                        ->default('pesan')
                                        ->required(),
                                    
                                    Forms\Components\Hidden::make('tagihan')
                                        ->default(0),
                                ])->columns(2),
                        ]),

                    // STEP 2: Input Produk
                    Wizard\Step::make('Input Produk Masuk')
                        ->schema([
                            Repeater::make('pembelianProduk')
                                ->relationship('pembelianProduk') 
                                ->schema([
                                    Select::make('produk_id')
                                        ->label('Pilih Produk')
                                        ->options(Produk::pluck('nama_produk', 'id')->toArray())
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            $produk = Produk::find($state);
                                            $set('harga', $produk ? $produk->harga : 0); 
                                        }),

                                    TextInput::make('harga') 
                                        ->label('Harga Satuan')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->readonly(),

                                    TextInput::make('jml')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),
                                ])->columns(3),
                        ]),

                    // STEP 3: Ringkasan
                    Wizard\Step::make('Selesai')
                        ->schema([
                            Placeholder::make('Ringkasan Produk')
                                ->label('Detail Transaksi yang akan disimpan:')
                                ->content(function (Get $get) {
                                    $pembelianProduk = $get('pembelianProduk');
                                    
                                    if (empty($pembelianProduk)) {
                                        return 'Belum ada produk yang dipilih.';
                                    }

                                    $html = '<table style="width:100%; text-align:left; border-collapse: collapse;">';
                                    $html .= '<thead><tr style="border-bottom: 1px solid #ccc;"><th style="padding: 8px 0;">Nama Produk</th><th style="padding: 8px 0; text-align:center;">Jumlah</th><th style="padding: 8px 0; text-align:right;">Harga Satuan</th><th style="padding: 8px 0; text-align:right;">Subtotal</th></tr></thead>';
                                    $html .= '<tbody>';

                                    $totalSemua = 0;
                                    foreach ($pembelianProduk as $item) {
                                        $produk = Produk::find($item['produk_id']);
                                        $nama = $produk?->nama_produk ?? 'Tidak Diketahui';
                                        $harga = (int) ($item['harga'] ?? 0);
                                        $jumlah = (int) ($item['jml'] ?? 1);
                                        $subtotal = $harga * $jumlah;
                                        $totalSemua += $subtotal;

                                        $html .= "<tr><td style='padding: 8px 0;'>{$nama}</td><td style='padding: 8px 0; text-align:center;'>{$jumlah}</td><td style='padding: 8px 0; text-align:right;'>Rp " . number_format($harga, 0, ',', '.') . "</td><td style='padding: 8px 0; text-align:right;'>Rp " . number_format($subtotal, 0, ',', '.') . "</td></tr>";
                                    }

                                    $html .= '</tbody><tfoot><tr style="font-weight: bold; border-top: 2px solid #eee;"><td colspan="3" style="padding: 15px 0; text-align:right;">Total Tagihan:</td><td style="padding: 15px 0; text-align:right; color: #f59e0b;">Rp ' . number_format($totalSemua, 0, ',', '.') . '</td></tr></tfoot></table>';
                                    return new HtmlString($html);
                                }),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                TextColumn::make('suplayer.name')->label('Supplier'),
                TextColumn::make('tgl')->dateTime()->label('Tanggal'),
                TextColumn::make('tagihan')->money('idr')->label('Total'),
                TextColumn::make('status')->badge(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                // ✅ Tombol Cetak Nota Per Baris (Ini yang tadinya error)
                Action::make('downloadPdf')
                    ->label('Cetak Nota')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Pembelian $record) {
                        $pdf = Pdf::loadView('pdf.pembelian', $record->toArray());
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "Nota-Pembelian-{$record->no_faktur}.pdf"
                        );
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                // ✅ Tombol Cetak SEMUA Data (Laporan)
                Action::make('downloadAllPdf')
                    ->label('Cetak Semua Laporan')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->action(function () {
                        $pembelian = Pembelian::all();
                        // Gunakan array untuk mengirim data list
                        $pdf = Pdf::loadView('pdf.pembelian_semua', ['data' => $pembelian]); 
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-pembelian.pdf'
                        );
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}