<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    /* --- STEP 1: PILIH PRODUK --- */
                    Wizard\Step::make('Pilih Produk')
                        ->icon('heroicon-m-cube')
                        ->schema([
                            Section::make('Daftar Produk')
                                ->schema([
                                    Repeater::make('detailPenjualan')
                                        ->relationship()
                                        ->label('')
                                        ->defaultItems(1)
                                        ->live()
                                        ->schema([
                                            Select::make('produk_id')
                                                ->label('Produk')
                                                ->options(Produk::pluck('nama_produk', 'id'))
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $produk = Produk::find($state);
                                                    if (!$produk) return;
                                                    
                                                    $hargaProduk = (int) $produk->harga;
                                                    $topping = Topping::find($get('topping_id'));
                                                    $hargaTopping = (int) ($topping->cost ?? 0);
                                                    $harga = $hargaProduk + $hargaTopping;
                                                    
                                                    $set('harga', $harga);
                                                    $set('qty', 1);
                                                    $set('subtotal', $harga * 1);
                                                    self::hitungTotal($get, $set);
                                                })->columnSpan(2),

                                            Select::make('topping_id')
                                                ->label('Topping')
                                                ->options(Topping::pluck('name', 'id'))
                                                ->searchable()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $produk = Produk::find($get('produk_id'));
                                                    if (!$produk) return;

                                                    $hargaTopping = (int) (Topping::find($state)->cost ?? 0);
                                                    $hargaTotal = (int) $produk->harga + $hargaTopping;
                                                    $qty = (int) ($get('qty') ?? 1);

                                                    $set('harga', $hargaTotal);
                                                    $set('subtotal', $hargaTotal * $qty);
                                                    self::hitungTotal($get, $set);
                                                }),

                                            TextInput::make('harga')->label('Harga')->numeric()->prefix('Rp')->readOnly(),
                                            TextInput::make('qty')->label('Qty')->numeric()->default(1)->live(debounce: 500)
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $harga = (int) $get('harga');
                                                    $set('subtotal', $harga * max((int)$state, 1));
                                                    self::hitungTotal($get, $set);
                                                }),
                                            TextInput::make('subtotal')->label('Subtotal')->numeric()->prefix('Rp')->readOnly(),
                                        ])->columns(6)
                                        ->deleteAction(fn ($action) => $action->after(fn (callable $set, callable $get) => self::hitungTotal($get, $set)))
                                        ->required(),
                                ])
                        ]),

                    /* --- STEP 2: PEMBAYARAN --- */
                    Wizard\Step::make('Pembayaran')
                        ->icon('heroicon-m-credit-card')
                        ->schema([
                            Section::make('Informasi Penjualan')
                                ->schema([
                                    Hidden::make('user_id')->default(Auth::id()),
                                    TextInput::make('no_faktur')->label('No Faktur')->default(fn () => Penjualan::getKodeFaktur())->readOnly(),
                                    DateTimePicker::make('tanggal_penjualan')->default(now())->required(),
                                    TextInput::make('nama_pembeli')->label('Nama Pembeli')->required(),

                                    Select::make('metode_pembayaran')
                                        ->label('Metode Pembayaran')
                                        ->options([
                                            'Cash' => 'Cash (Manual)',
                                            'Midtrans' => 'Midtrans (QRIS/Transfer/VA)',
                                        ])
                                        ->native(false)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // Reset bayar/kembalian jika pindah ke Midtrans agar tidak error truncated
                                            if ($state === 'Midtrans') {
                                                $set('status_penjualan', 'Pending');
                                                $set('bayar', 0);
                                                $set('kembalian', 0);
                                            } else {
                                                $set('status_penjualan', 'Selesai');
                                            }
                                        }),
                                ])->columns(2),

                            Section::make('Total Pembayaran')
                                ->schema([
                                    Placeholder::make('grand_total_view')
                                        ->label('Grand Total')
                                        ->content(fn ($get) => 'Rp ' . number_format((int)($get('grand_total') ?? 0), 0, ',', '.')),

                                    // Input Bayar hanya muncul jika METODE = CASH
                                    TextInput::make('bayar')
                                        ->label('Bayar')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->live()
                                        ->visible(fn ($get) => $get('metode_pembayaran') === 'Cash')
                                        ->required(fn ($get) => $get('metode_pembayaran') === 'Cash')
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $grandTotal = (int) ($get('grand_total') ?? 0);
                                            $set('kembalian', max((int)$state - $grandTotal, 0));
                                        }),

                                    TextInput::make('kembalian')
                                        ->label('Kembalian')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->visible(fn ($get) => $get('metode_pembayaran') === 'Cash'),

                                    Hidden::make('total_penjualan')->default(0),
                                    Hidden::make('grand_total')->default(0),
                                    Hidden::make('status_penjualan'), // Nilai diisi via afterStateUpdated
                                ])->columns(3)
                        ])
                ])->columnSpanFull()->persistStepInQueryString()
            ]);
    }

    protected static function hitungTotal(callable $get, callable $set): void
    {
        // Mendukung pencarian data baik di dalam repeater maupun root form
        $items = $get('../../detailPenjualan') ?? $get('detailPenjualan') ?? [];
        $grandTotal = collect($items)->sum(fn ($item) => (int) ($item['subtotal'] ?? 0));

        $set('../../grand_total', $grandTotal);
        $set('../../total_penjualan', $grandTotal);
        $set('grand_total', $grandTotal);
        $set('total_penjualan', $grandTotal);

        $bayar = (int) ($get('../../bayar') ?? $get('bayar') ?? 0);
        $set('../../kembalian', max($bayar - $grandTotal, 0));
        $set('kembalian', max($bayar - $grandTotal, 0));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable()->weight('bold'),
                TextColumn::make('nama_pembeli')->label('Pembeli')->searchable(),
                TextColumn::make('grand_total')->money('IDR')->sortable()->weight('bold'),
                TextColumn::make('metode_pembayaran')->badge(),
                TextColumn::make('status_penjualan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Pending' => 'warning',
                        'Batal' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_penjualan')->dateTime('d M Y H:i'),
            ])
            ->actions([
                // Tombol Bayar Midtrans (Muncul jika status masih Pending)
                Action::make('bayar_sekarang')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('warning')
                    ->visible(fn ($record) => $record->metode_pembayaran === 'Midtrans' && $record->status_penjualan === 'Pending')
                    // Menggunakan route yang sudah kita siapkan di web.php
                    ->url(fn ($record) => route('penjualan.bayar', $record->id))
                    ->openUrlInNewTab(),
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $penjualan = Penjualan::all();
                        $pdf = Pdf::loadView('pdf.penjualan', ['penjualan' => $penjualan]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'penjualan-list.pdf');
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}