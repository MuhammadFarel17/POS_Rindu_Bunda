<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuplayerResource\Pages;
use App\Models\Suplayer; // Pastikan nama file Model juga sudah diubah menjadi Suplayer.php
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class SuplayerResource extends Resource
{
    // Menggunakan model Suplayer
    protected static ?string $model = Suplayer::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    protected static ?string $navigationLabel = 'Suplayer';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuplayer::route('/'),
            'create' => Pages\CreateSuplayer::route('/create'),
            'edit' => Pages\EditSuplayer::route('/{record}/edit'),
        ];
    }
}