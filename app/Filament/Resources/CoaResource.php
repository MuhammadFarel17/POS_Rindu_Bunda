<?php

namespace App\Filament\Resources;

// Tambahan
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\TextInput; //kita menggunakan textinput
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

// ✅ Import tambahan untuk PDF (DomPDF) dan Action
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

// Import untuk Export Excel
use App\Filament\Exports\CoaExporter;
use Filament\Tables\Actions\ExportAction;

use App\Filament\Resources\CoaResource\Pages;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $pluralModelLabel = 'Coas';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Masterdata';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('header_akun')
                            ->required()
                            ->placeholder('Masukkan header akun'),

                        TextInput::make('kode_akun')
                            ->required()
                            ->placeholder('Masukkan kode akun'),

                        TextInput::make('nama_akun')
                            ->autocapitalize('words')
                            ->label('Nama Akun')
                            ->required()
                            ->placeholder('Masukkan nama akun'),
                    ]),
                ->schema([
                    TextInput::make('header_akun')
                        ->required()
                        ->placeholder('Masukkan header akun'),
                    TextInput::make('kode_akun')
                        ->required()
                        ->placeholder('Masukkan kode akun'),
                    TextInput::make('nama_akun')
                        ->autocapitalize('words')
                        ->label('Nama akun')
                        ->required()
                        ->placeholder('Masukkan nama akun'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('header_akun')
                    ->label('Header Akun'),

                TextColumn::make('kode_akun')
                    ->label('Kode Akun'),

                TextColumn::make('nama_akun')
                    ->label('Nama Akun'),
                TextColumn::make('header_akun'),
                TextColumn::make('kode_akun'),
                TextColumn::make('nama_akun'), 
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('header_akun')
                    ->options([
                        1 => 'Aset/Aktiva',
                        2 => 'Utang',
                        3 => 'Modal',
                        4 => 'Pendapatan',
                        5 => 'Beban',
                    ]),
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
                        $coa = Coa::all();
                        $pdf = Pdf::loadView('pdf.coa_list', ['coa' => $coa]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'coa-list.pdf');
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}