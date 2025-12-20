<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';
    protected static ?string $title = 'Giao dịch thanh toán';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->label('Số tiền'),

            Forms\Components\TextInput::make('currency')
                ->label('Tiền tệ')
                ->default('VND')
                ->maxLength(3),

            Forms\Components\TextInput::make('payment_gateway')
                ->label('Cổng thanh toán')
                ->required(),

            Forms\Components\TextInput::make('transaction_id')
                ->label('Mã giao dịch')
                ->nullable(),

            Forms\Components\Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'failed'  => 'Failed',
                ])
                ->default('success'),

            Forms\Components\DateTimePicker::make('paid_at')
                ->label('Thanh toán lúc')
                ->nullable(),
        ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_gateway')
                    ->label('Gateway'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Số tiền'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Thanh toán lúc')
                    ->dateTime('d/m/Y H:i'),
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
