<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasiResource\Pages;
use App\Models\Reservasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

// Wizard
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;

// PDF
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // =========================
    // FORM (WIZARD)
    // =========================
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([

                    // STEP 1
                    Wizard\Step::make('Data Reservasi')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Informasi')
                                ->schema([

                                    Forms\Components\TextInput::make('kode_reservasi')
                                        ->default(fn () => Reservasi::getKodeReservasi())
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    Forms\Components\TextInput::make('nama_pelanggan')
                                        ->required(),

                                    Forms\Components\TextInput::make('nama_petugas')
                                        ->default(fn () => Auth::user()->name ?? 'Admin')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),

                                    Forms\Components\TextInput::make('no_hp')
                                        ->required(),

                                ])->columns(2),
                        ]),

                    // STEP 2
                    Wizard\Step::make('Jadwal')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Section::make('Waktu Reservasi')
                                ->schema([

                                    Forms\Components\DatePicker::make('tanggal_reservasi')
                                        ->required(),

                                    Forms\Components\TimePicker::make('jam_reservasi')
                                        ->required(),

                                    Forms\Components\TextInput::make('jumlah_orang')
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\TextInput::make('meja'),

                                ])->columns(2),
                        ]),

                    // STEP 3
                    Wizard\Step::make('Konfirmasi')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Section::make('Final')
                                ->schema([

                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed',
                                            'cancel' => 'Cancel',
                                        ])
                                        ->default('pending')
                                        ->required(),

                                    Forms\Components\Textarea::make('catatan'),

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
                Tables\Columns\TextColumn::make('kode_reservasi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('nama_petugas'),
                Tables\Columns\TextColumn::make('tanggal_reservasi')->date(),
                Tables\Columns\TextColumn::make('jam_reservasi'),
                Tables\Columns\TextColumn::make('jumlah_orang'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'cancel',
                    ]),

                Tables\Columns\TextColumn::make('created_at')->since(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancel' => 'Cancel',
                    ]),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // PRINT STRUK
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function ($record) {

                        $pdf = Pdf::loadView('pdf.struk-reservasi', [
                            'data' => $record
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'struk-'.$record->kode_reservasi.'.pdf'
                        );
                    }),
            ])

            // EXPORT SEMUA
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {

                        $reservasi = Reservasi::all();

                        $pdf = Pdf::loadView('pdf.reservasi', [
                            'reservasi' => $reservasi
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'data-reservasi.pdf'
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
            'index' => Pages\ListReservasis::route('/'),
            'create' => Pages\CreateReservasi::route('/create'),
            'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }
}