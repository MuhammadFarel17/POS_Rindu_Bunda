<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturPembelianResource\Pages;
use App\Models\ReturPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ReturPembelianResource extends Resource
{
    protected static ?string $model = ReturPembelian::class;

    // Sidebar
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Retur Pembelian';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?int $navigationSort = 1;

    // Label
    protected static ?string $modelLabel = 'Retur Pembelian';
    protected static ?string $pluralModelLabel = 'Retur Pembelian';

    // =========================
    // FORM (WIZARD)
    // =========================
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([

                    // STEP 1
                    Wizard\Step::make('Data Retur')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Informasi Retur')
                                ->schema([

                                    Forms\Components\TextInput::make('no_retur')
                                        ->label('Nomor Retur')
                                        ->placeholder('Otomatis')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    Forms\Components\Select::make('id_pembelian')
                                        ->relationship('pembelian', 'id')
                                        ->label('ID Pembelian')
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\DatePicker::make('tanggal')
                                        ->label('Tanggal Retur')
                                        ->default(now())
                                        ->required(),

                                    Forms\Components\TextInput::make('nama_petugas')
                                        ->label('Petugas')
                                        ->default(fn () => Auth::user()->name ?? 'Admin')
                                        ->disabled()
                                        ->dehydrated(false),

                                ])->columns(2),
                        ]),

                    // STEP 2
                    Wizard\Step::make('Detail Retur')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Section::make('Daftar Produk')
                                ->schema([

                                    Forms\Components\Repeater::make('detailReturPembelian')
                                        ->relationship('detailReturPembelian')
                                        ->label('Detail Produk')
                                        ->schema([
                                            Forms\Components\Select::make('id_produk')
                                                ->relationship('produk', 'nama_produk')
                                                ->label('Produk')
                                                ->required()
                                                ->searchable()
                                                ->preload(),

                                            Forms\Components\TextInput::make('qty')
                                                ->label('Qty')
                                                ->numeric()
                                                ->required()
                                                ->default(1)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $set(
                                                        'subtotal',
                                                        ((float) $get('qty')) * ((float) $get('harga'))
                                                    );
                                                }),

                                            Forms\Components\TextInput::make('harga')
                                                ->label('Harga')
                                                ->numeric()
                                                ->required()
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $set(
                                                        'subtotal',
                                                        ((float) $get('qty')) * ((float) $get('harga'))
                                                    );
                                                }),

                                            Forms\Components\TextInput::make('subtotal')
                                                ->label('Subtotal')
                                                ->numeric()
                                                ->readOnly()
                                                ->dehydrated(),
                                        ])
                                        ->columns(4)
                                        ->defaultItems(1)
                                        ->addActionLabel('Tambah Produk')
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $total = 0;

                                            if (is_array($state)) {
                                                foreach ($state as $item) {
                                                    $total += (float) ($item['subtotal'] ?? 0);
                                                }
                                            }

                                            $set('total_retur', $total);
                                        }),

                                ]),
                        ]),

                    // STEP 3
                    Wizard\Step::make('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Section::make('Ringkasan')
                                ->schema([

                                    Forms\Components\TextInput::make('total_retur')
                                        ->label('Total Retur')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->default(0),

                                    Forms\Components\Textarea::make('keterangan')
                                        ->label('Keterangan')
                                        ->rows(4),

                                ]),
                        ]),

                ])->columnSpanFull(),
            ]);
    }

    // =========================
    // TABLE
    // =========================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_retur')
                    ->label('Nomor Retur')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pembelian.id')
                    ->label('ID Pembelian')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_retur')
                    ->label('Total Retur')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $returPembelian = ReturPembelian::all();

                        $pdf = Pdf::loadView('pdf.retur-pembelian', [
                            'returPembelian' => $returPembelian,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'retur-pembelian.pdf'
                        );
                    }),
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
            'index' => Pages\ListReturPembelians::route('/'),
            'create' => Pages\CreateReturPembelian::route('/create'),
            'edit' => Pages\EditReturPembelian::route('/{record}/edit'),
        ];
    }
}