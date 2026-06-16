<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToppingResource\Pages;
use App\Models\Topping;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class ToppingResource extends Resource
{
    protected static ?string $model = Topping::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Masterdata';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('cost')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->action(function () {
                        $topping = Topping::all();
                        $pdf = Pdf::loadView('pdf.topping_list', ['topping' => $topping]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'topping-list.pdf');
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
            'index' => Pages\ListToppings::route('/'),
            'create' => Pages\CreateTopping::route('/create'),
            'edit' => Pages\EditTopping::route('/{record}/edit'),
        ];
    }
}