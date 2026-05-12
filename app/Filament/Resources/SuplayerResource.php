<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuplayerResource\Pages;
use App\Models\Suplayer; 
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class SuplayerResource extends Resource
{
    protected static ?string $model = Suplayer::class;
    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationLabel = 'Suplayer';
    protected static ?string $navigationGroup = 'Masterdata';

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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
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