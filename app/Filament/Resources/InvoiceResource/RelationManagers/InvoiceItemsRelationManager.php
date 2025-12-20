<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class InvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Chi tiết hoá đơn';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('description')
                ->label('Mô tả')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->default(1),

            Forms\Components\TextInput::make('unit_price')
                ->numeric()
                ->label('Đơn giá'),

            Forms\Components\TextInput::make('line_total')
                ->numeric()
                ->label('Thành tiền'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->wrap(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('SL'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Đơn giá'),
                Tables\Columns\TextColumn::make('line_total')
                    ->label('Thành tiền'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
