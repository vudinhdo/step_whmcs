<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers\InvoiceItemsRelationManager;
use App\Filament\Resources\InvoiceResource\RelationManagers\TransactionsRelationManager;
use App\Models\Invoice;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Thanh toán';
    protected static ?string $navigationLabel = 'Hoá đơn';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Khách hàng')
                ->options(User::pluck('email', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'draft'    => 'Bản nháp',
                    'unpaid'   => 'Chưa thanh toán',
                    'paid'     => 'Thanh toán',
                    'cancelled'=> 'Đã hủy',
                    'refunded' => 'Đã hoàn tiền',
                ])
                ->default('unpaid'),

            Forms\Components\DatePicker::make('issue_date')
                ->label('Ngày xuất')
                ->required(),

            Forms\Components\DatePicker::make('due_date')
                ->label('Đến hạn')
                ->required(),

            Forms\Components\TextInput::make('subtotal')
                ->numeric()
                ->label('Subtotal'),

            Forms\Components\TextInput::make('tax')
                ->numeric()
                ->label('Thuế'),

            Forms\Components\TextInput::make('total')
                ->numeric()
                ->label('Tổng'),

            Forms\Components\TextInput::make('currency')
                ->label('Tiền tệ')
                ->default('VND')
                ->maxLength(3),

            Forms\Components\TextInput::make('payment_gateway')
                ->label('Cổng thanh toán')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Khách hàng')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Tổng')
                    ->money('VND'),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Ngày xuất')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Đến hạn')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'unpaid'   => 'Unpaid',
                        'paid'     => 'Paid',
                        'draft'    => 'Draft',
                        'cancelled'=> 'Cancelled',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InvoiceItemsRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit'   => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
