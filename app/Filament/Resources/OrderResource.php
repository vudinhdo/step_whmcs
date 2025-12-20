<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
//use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'Khách hàng & Đơn hàng';
    protected static ?string $navigationLabel = 'Đơn hàng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Khách hàng')
                    ->options(User::pluck('email', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending'  => 'Chờ kích hoạt',
                        'active'   => 'Kích hoạt',
                        'cancelled'=> 'Đã hủy',
                        'fraud'    => 'Fraud',
                    ])
                    ->default('pending'),

                Forms\Components\TextInput::make('total')
                    ->label('Tổng tiền')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('currency')
                    ->label('Tiền tệ')
                    ->default('VND'),

                Forms\Components\TextInput::make('payment_gateway')
                    ->label('Cổng thanh toán')
                    ->placeholder('vnpay, momo, bank-transfer...'),

                Forms\Components\Textarea::make('notes')
                    ->label('Ghi chú')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Khách hàng')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'warning',
                        'success' => 'active',
                        'danger'  => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tổng')
                    ->money('VND'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('activate')
                    ->label('Activate')
                    ->color('success')
                    ->icon('heroicon-o-bolt')
                    ->requiresConfirmation()
                    ->modalHeading('Kích hoạt đơn hàng?')
                    ->modalDescription('Hệ thống sẽ tạo Service + tạo Invoice mẫu và gửi email cho khách.')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'active']);

                        Notification::make()
                            ->title('Đã kích hoạt đơn hàng')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
