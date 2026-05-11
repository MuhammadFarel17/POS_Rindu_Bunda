<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pegawai')
                    ->schema([
                        Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $user = User::find($state);
                                    
                                    if ($user) {
                                        $set('nama_pegawai', $user->name);
                                        // Menggunakan kolom sesuai screenshot database kamu
                                        $set('telepon', $user->phone); 
                                        $set('alamat', $user->address);
                                    }
                                } else {
                                    $set('nama_pegawai', null);
                                    $set('telepon', null);
                                    $set('alamat', null);
                                }
                            }),

                        TextInput::make('kode_pegawai')
                            ->label('Kode Pegawai')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(function () {
                                $latestPegawai = Pegawai::latest('id')->first();
                                $nextNumber = $latestPegawai ? $latestPegawai->id + 1 : 1;
                                return 'PGW-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                            })
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('nama_pegawai')
                            ->label('Nama Lengkap')
                            ->required(),

                        TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->required(),

                        TextInput::make('telepon')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->placeholder('628...')
                            ->required(),

                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'paid' => 'Paid',
                                'aktif' => 'Aktif',
                                'tidak aktif' => 'Tidak Aktif',
                            ])
                            ->default('aktif')
                            ->required(),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pegawai')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_pegawai')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jabatan')
                    ->searchable(),

                TextColumn::make('telepon')
                    ->label('WhatsApp'),

                TextColumn::make('alamat')
                    ->limit(30),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif', 'paid' => 'success',
                        'tidak aktif' => 'danger',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->actions([
                // Tambahan Action Bayar agar status otomatis berubah
                Tables\Actions\Action::make('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'draft') // Hanya muncul kalau statusnya masih draft
                    ->action(function ($record) {
                    // Gunakan save() untuk memastikan data benar-benar tersimpan
                    $record->status = 'paid'; // sesuaikan kalau di DB kamu pakai 'Paid'
                    $record->save();
                    
                    Notification::make()
                        ->title('Pembayaran Berhasil')
                        ->success()
                        ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}