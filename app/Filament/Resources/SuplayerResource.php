<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuplayerResource\Pages;
use App\Models\Suplayer; 
use App\Models\Suplayer;
use App\Models\Suplayer; 
use App\Models\Suplayer; // Pastikan nama file Model juga sudah diubah menjadi Suplayer.php
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Columns\TextColumn;

class SuplayerResource extends Resource
{
    // Menggunakan model Suplayer
    protected static ?string $model = Suplayer::class;
    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationLabel = 'Suplayer';
    protected static ?string $navigationGroup = 'Masterdata';

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    protected static ?string $navigationLabel = 'Suplayer';

    protected static ?string $navigationGroup = 'Masterdata';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Suplayer')
                    ->schema([
                        Select::make('user_id')
                            ->label('Admin Penginput')
                            ->relationship('user', 'name')
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        TextInput::make('kode_suplayer')
                            ->label('Kode Suplayer')
                            ->default(fn () => Suplayer::getKodeSuplayer()) 
                            ->required()
                            ->readonly(),

                        TextInput::make('name')
                            ->label('Nama Suplayer')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Suplayer')
                            ->email()
                            ->required(),

                        TextInput::make('city')
                            ->label('Kota'),

                        TextInput::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('is_active')
                            ->label('Status Aktif')
                            ->options(['1' => 'Aktif', '0' => 'Non-Aktif'])
                            ->default('1')
                            ->required(),
                    ])->columns(2),
                // Relasi ke tabel users
                Select::make('user_id')
                    ->label('Pilih User')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);
                            if ($user) {
                                $set('name', $user->name);
                                $set('email', $user->email);
                            }
                        }
                    }),

                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);

                            if ($user) {
                                $set('name', $user->name);
                                $set('email', $user->email);
                            }
                        }
                    }),

                TextInput::make('kode_suplayer')
                    ->label('Kode Suplayer')
                    ->default(fn () => Suplayer::getKodeSuplayer())
                    ->required()
                    ->readonly(),

                // Field User ID untuk mencatat Admin yang menginput
                Select::make('user_id')
                    ->label('Admin Penginput')
                    ->relationship('user', 'name')
                    ->default(auth()->id()) // Otomatis ambil ID admin yang login
                    ->disabled() // Dikunci agar tidak bisa diubah manual
                    ->dehydrated() // Tetap dikirim ke database saat simpan
                    ->required(),

                TextInput::make('kode_suplayer')
                    ->label('Kode Suplayer')
                    ->default(fn () => Suplayer::getKodeSuplayer()) 
                    ->required()
                    ->readonly(),

                TextInput::make('name')
                    ->label('Nama Suplayer')
                    ->required()
                    ->placeholder('Isikan nama suplayer'),

                // Relasi ke tabel users
                Select::make('user_id')
                    ->label('Pilih User')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);
                            if ($user) {
                                $set('name', $user->name);
                                $set('email', $user->email);
                            }
                        }
                    }),

                TextInput::make('kode_suplayer')
                    ->label('Kode Suplayer')
                    ->default(fn () => Suplayer::getKodeSuplayer()) 
                    ->required()
                    ->readonly(),

                TextInput::make('name')
                    ->label('Nama Suplayer')
                    ->required()
                    ->readonly()
                    ->placeholder('Otomatis dari User'),

                TextInput::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->placeholder('Masukkan alamat suplayer'),


                TextInput::make('name')
                    ->label('Nama Suplayer')
                    ->required()
                    ->readonly()
                    ->placeholder('Otomatis dari User'),

                TextInput::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->placeholder('Masukkan alamat suplayer'),

                TextInput::make('city')
                    ->label('Kota')
                    ->placeholder('Masukkan kota suplayer'),

                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required()
                    ->placeholder('Contoh: 08123456789'),


                TextInput::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->placeholder('Masukkan alamat suplayer'),

                TextInput::make('city')
                    ->label('Kota')
                    ->placeholder('Masukkan kota suplayer'),

                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required()
                    ->placeholder('Contoh: 08123456789'),

                TextInput::make('email')
                    ->label('Email Suplayer')
                    ->email()
                    ->required()
                    ->placeholder('Masukkan Email Suplayer'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_suplayer')->label('Kode')->sortable()->searchable(),
                TextColumn::make('name')->label('Nama Suplayer')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Admin Input'),
                TextColumn::make('phone')->label('Telepon'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === '1' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === '1' ? 'Aktif' : 'Non-Aktif'),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $suplayer = Suplayer::all();
                        $pdf = Pdf::loadView('pdf.suplayer', ['suplayer' => $suplayer, 'title' => 'LAPORAN SUPLAYER']);
                        return response()->streamDownload(fn () => print($pdf->output()), 'laporan-suplayer.pdf');
                    })
                TextColumn::make('kode_suplayer')
                    ->label('Kode Suplayer')
                    ->sortable()
                    ->searchable(),
                

                TextColumn::make('name')
                    ->label('Nama Suplayer')
                    ->sortable()
                    ->searchable(),
                

                TextColumn::make('phone')
                    ->label('Telepon'),

                    ->searchable(),
                
                TextColumn::make('name')
                    ->label('Nama Suplayer')
                    ->sortable()
                    ->searchable(),
                
                // Menampilkan nama admin yang melakukan penginputan
                TextColumn::make('user.name')
                    ->label('Admin Input')
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Telepon'),
                
                TextColumn::make('email')
                    ->label('Email'),

                TextColumn::make('city')
                    ->label('Kota')
                    ->toggleable(),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => $state === '1' ? 'Aktif' : 'Non-Aktif'
                    ),
                    })
                    ->formatStateUsing(fn (string $state): string => $state === '1' ? 'Aktif' : 'Non-Aktif'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function () {
                        $suplayer = Suplayer::all();
                        $pdf = Pdf::loadView('pdf.suplayer_list', ['suplayer' => $suplayer]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'suplayer-list.pdf');
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
            // SINKRONKAN DENGAN NAMA CLASS DI FOLDER PAGES (Jika ListSuplayers pakai S, gunakan S)
            'index' => Pages\ListSuplayer::route('/'),
            'create' => Pages\CreateSuplayer::route('/create'),
            'edit' => Pages\EditSuplayer::route('/{record}/edit'),
        ];
    }
}