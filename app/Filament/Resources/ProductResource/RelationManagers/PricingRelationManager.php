<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductPricing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class PricingRelationManager extends RelationManager
{
    protected static string $relationship = 'pricing'; // tên relation trong Product model

    protected static ?string $title = 'Bảng giá';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('')
                    ->label('Chu kỳ')
                    ->options([
                        'monthly' => 'Tháng',
                        'quarterly' => 'Quý',
                        'semiannually' => '6 tháng',
                        'annually' => 'Năm',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Giá')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('setup_fee')
                    ->label('Setup fee')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('currency')
                    ->label('Tiền tệ')
                    ->default('VND')
                    ->maxLength(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Chu kỳ'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá'),
                Tables\Columns\TextColumn::make('setup_fee')
                    ->label('Setup fee'),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Tiền tệ'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
