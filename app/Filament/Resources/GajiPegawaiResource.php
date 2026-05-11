<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiPegawaiResource\Pages;
use App\Models\GajiPegawai;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection; // Tambahkan ini di atas

class GajiPegawaiResource extends Resource
{
    protected static ?string $model = GajiPegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // STEP 1
                    Wizard\Step::make('Informasi Slip')
                        ->schema([
                            Section::make('Header Penggajian')
                                ->schema([
                                    TextInput::make('no_slip_gaji')
                                        ->label('Nomor Slip')
                                        ->default('SLP-' . date('YmdHis'))
                                        ->readonly()
                                        ->required(),

                                    Select::make('pegawai_id')
                                        ->label('Nama Pegawai')
                                        ->options(Pegawai::all()->pluck('nama_pegawai', 'id'))
                                        ->searchable()
                                        ->required()
                                        ->reactive(),

                                    DatePicker::make('tanggal_gaji')
                                        ->label('Tanggal Input')
                                        ->default(now())
                                        ->required(),

                                    Select::make('bulan')
                                        ->label('Bulan')
                                        ->options([
                                            'Januari' => 'Januari', 'Februari' => 'Februari', 'Maret' => 'Maret',
                                            'April' => 'April', 'Mei' => 'Mei', 'Juni' => 'Juni',
                                            'Juli' => 'Juli', 'Agustus' => 'Agustus', 'September' => 'September',
                                            'Oktober' => 'Oktober', 'November' => 'November', 'Desember' => 'Desember',
                                        ])->required(),

                                    TextInput::make('tahun')
                                        ->label('Tahun')
                                        ->default(date('Y'))
                                        ->numeric()
                                        ->required(),
                                ])->columns(2),
                        ]),

                    // STEP 2
                    Wizard\Step::make('Rincian Gaji')
                        ->schema([
                            Section::make('Komponen Gaji')
                                ->schema([
                                    TextInput::make('gaji_pokok')
                                        ->label('Gaji Pokok')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),

                                    Repeater::make('details')
                                        ->label('Rincian Komponen')
                                        ->relationship('details')
                                        ->schema([
                                            TextInput::make('nama_komponen')
                                                ->label('Nama Komponen')
                                                ->placeholder('Contoh: Bonus')
                                                ->required(),
                                            Select::make('jenis')
                                                ->label('Jenis')
                                                ->options([
                                                    'tunjangan' => 'Tunjangan (+)',
                                                    'potongan' => 'Potongan (-)',
                                                ])->required()
                                                ->live(),
                                            TextInput::make('nominal')
                                                ->label('Nominal')
                                                ->numeric()
                                                ->prefix('Rp')
                                                ->required()
                                                ->live(),
                                        ])
                                        ->columns(3)
                                        ->addActionLabel('Tambah Komponen')
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),
                                ]),
                        ]),

                    // STEP 3
                    Wizard\Step::make('Konfirmasi')
                        ->schema([
                            Section::make('Hasil Perhitungan')
                                ->schema([
                                    Placeholder::make('total_tunjangan_disp')
                                        ->label('Total Tunjangan')
                                        ->content(fn (Get $get) => 'Rp ' . number_format($get('total_tunjangan') ?? 0, 0, ',', '.')),

                                    Placeholder::make('total_potongan_disp')
                                        ->label('Total Potongan')
                                        ->content(fn (Get $get) => 'Rp ' . number_format($get('total_potongan') ?? 0, 0, ',', '.')),

                                    Placeholder::make('total_diterima_disp')
                                        ->label('Gaji Bersih (Net)')
                                        ->content(fn (Get $get) => 'Rp ' . number_format($get('total_diterima') ?? 0, 0, ',', '.')),

                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'paid' => 'Lunas / Terbayar',
                                        ])->default('draft')->required(),

                                    Hidden::make('total_tunjangan'),
                                    Hidden::make('total_potongan'),
                                    Hidden::make('total_diterima'),
                                ])->columns(3),
                        ]),

                ])->columnSpanFull()
                  ->submitAction(new \Illuminate\Support\HtmlString(
                      '<button type="submit" class="fi-btn fi-btn-size-md fi-btn-color-primary fi-btn-style-filled px-4 py-2 rounded-lg text-white bg-primary-600 hover:bg-primary-500">
                          Simpan
                      </button>'
                  )),
            ]);
    }

    public static function updateTotals(Set $set, Get $get): void
    {
        $gajiPokok = (float) $get('gaji_pokok');
        $details = collect($get('details') ?? []);

        $tunjangan = $details->where('jenis', 'tunjangan')->sum('nominal');
        $potongan = $details->where('jenis', 'potongan')->sum('nominal');

        $totalBersih = ($gajiPokok + $tunjangan) - $potongan;

        $set('total_tunjangan', $tunjangan);
        $set('total_potongan', $potongan);
        $set('total_diterima', $totalBersih);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_slip_gaji')
                    ->label('No Slip')
                    ->searchable(),

                TextColumn::make('pegawai.nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable(),

                TextColumn::make('bulan')->label('Periode'),
                TextColumn::make('tahun')->label('Tahun'),

                TextColumn::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_tunjangan')
                    ->label('Tunjangan')
                    ->money('IDR'),

                TextColumn::make('total_potongan')
                    ->label('Potongan')
                    ->money('IDR'),

                TextColumn::make('total_diterima')
                    ->label('Gaji Bersih')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tanggal_gaji')
                    ->label('Tanggal Input')
                    ->date('d M Y'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'paid' => 'Lunas / Terbayar',
                    ]),
                Tables\Filters\SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari', 'Februari' => 'Februari',
                        'Maret' => 'Maret', 'April' => 'April',
                        'Mei' => 'Mei', 'Juni' => 'Juni',
                        'Juli' => 'Juli', 'Agustus' => 'Agustus',
                        'September' => 'September', 'Oktober' => 'Oktober',
                        'November' => 'November', 'Desember' => 'Desember',
                    ]),
            ])
            // BAGIAN INI DIHAPUS/DIKOSONGKAN agar tidak bingung
            ->headerActions([
                // Header action dihapus agar tidak muncul tombol "Unduh PDF" yang mengambil semua data
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // TAMBAHAN: Tombol Bayar via Midtrans (hanya muncul kalau status masih draft)
                    Tables\Actions\Action::make('bayar')
                        ->label('Bayar')
                        ->icon('heroicon-o-credit-card')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === 'draft')
                        ->url(fn ($record) => url('/bayar-gaji/' . $record->id))
                        ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // TOMBOL BARU: OTOMATIS KIRIM EMAIL & DOWNLOAD
                    Tables\Actions\BulkAction::make('sendAndDownloadPdf')
                        ->label('Kirim Email & Unduh PDF')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $dataGajiKeseluruhan = [];

                            foreach ($records as $item) {
                                $slipData = [
                                    'no_slip_gaji'    => $item->no_slip_gaji,
                                    'nama_pegawai'    => $item->pegawai->nama_pegawai ?? '-',
                                    'email_pegawai'   => $item->pegawai->user->email ?? null, // Pastikan kolom 'email' ada di tabel Pegawai
                                    'jabatan'         => $item->pegawai->jabatan ?? '-',
                                    'bulan'           => $item->bulan,
                                    'tahun'           => $item->tahun,
                                    'gaji_pokok'      => $item->gaji_pokok,
                                    'total_tunjangan' => $item->total_tunjangan,
                                    'total_potongan'  => $item->total_potongan,
                                    'total_diterima'  => $item->total_diterima,
                                ];

                                // 1. Buat PDF khusus untuk individu ini (untuk lampiran email)
                                $pdfIndividu = Pdf::loadView('pdf.slip-gaji', ['data' => [$slipData]]);
                                
                                // 2. Kirim Email Otomatis jika email pegawai ditemukan
                                if ($slipData['email_pegawai']) {
                                    \Illuminate\Support\Facades\Mail::to($slipData['email_pegawai'])
                                        ->send(new \App\Mail\SlipGajiMail($slipData, $pdfIndividu->output()));
                                }

                                $dataGajiKeseluruhan[] = $slipData;
                            }

                            // 3. Buat PDF gabungan untuk didownload Admin sebagai arsip
                            $pdfFinal = Pdf::loadView('pdf.slip-gaji', ['data' => $dataGajiKeseluruhan]);

                            return response()->streamDownload(
                                fn () => print($pdfFinal->output()),
                                'slip-gaji-terkirim.pdf'
                            );
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGajiPegawais::route('/'),
            'create' => Pages\CreateGajiPegawai::route('/create'),
            'edit' => Pages\EditGajiPegawai::route('/{record}/edit'),
        ];
    }
}