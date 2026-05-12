<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturPenjualanResource\Pages;
use App\Models\ReturPenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReturPenjualanResource extends Resource
{
    protected static ?string $model = ReturPenjualan::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Retur Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    // STEP 1: Pilih Transaksi
                    Forms\Components\Wizard\Step::make('Pilih Penjualan')
                        ->description('Pilih nomor faktur yang akan diretur')
                        ->icon('heroicon-m-shopping-cart')
                        ->schema([
                            Forms\Components\Select::make('id_penjualan')
                                ->label('No. Faktur Penjualan')
                                ->options(function () {
                                    if (class_exists('App\Models\Penjualan')) {
                                        return \App\Models\Penjualan::all()->pluck('no_faktur', 'id');
                                    }
                                    return ['' => 'Model Penjualan Belum Ada'];
                                })
                                ->searchable()
                                ->preload() // Menambah kelancaran saat memilih
                                ->required(),
                            
                            Forms\Components\DateTimePicker::make('tanggal_retur')
                                ->label('Tanggal Retur')
                                ->default(now())
                                ->required(),
                        ])->columns(2),

                    // STEP 2: Detail Retur
                    Forms\Components\Wizard\Step::make('Detail Barang & Alasan')
                        ->description('Masukkan nominal dan alasan pengembalian')
                        ->icon('heroicon-m-clipboard-document-check')
                        ->schema([
                            Forms\Components\TextInput::make('total_retur')
                                ->label('Total Nominal Retur')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->helperText('Masukkan total uang yang akan dikembalikan.'),

                            Forms\Components\Textarea::make('alasan_retur')
                                ->label('Alasan Retur')
                                ->placeholder('Contoh: Barang cacat produksi / Salah ukuran')
                                ->required()
                                ->columnSpanFull(),

                            // Hidden user ID tetap di dalam step terakhir
                            Forms\Components\Hidden::make('id_user')
                                ->default(auth()->id()),
                        ]),
                ])
                ->columnSpanFull() // Agar Wizard tampil lebar dan cantik
                ->skippable() // Membolehkan admin klik langkah sebelumnya tanpa validasi ulang
                ->persistStepInQueryString('retur-wizard-step'), // Menyimpan langkah di URL
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_retur_penjualan')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan ID agar rapi

                Tables\Columns\TextColumn::make('penjualan.no_faktur')
                    ->label('No. Faktur')
                    ->badge() // Membuat tampilan faktur lebih menonjol
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_retur')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_retur')
                    ->label('Total Retur')
                    ->money('idr')
                    ->color('danger') // Warna merah karena ini uang keluar/pengembalian
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Admin')
                    ->icon('heroicon-m-user')
                    ->sortable(),
            ])
            ->filters([
                // Tambahkan filter tanggal jika perlu
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Tambahkan View agar bisa lihat detail tanpa edit
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturPenjualans::route('/'),
            'create' => Pages\CreateReturPenjualan::route('/create'),
            'edit' => Pages\EditReturPenjualan::route('/{record}/edit'),
        ];
    }
}