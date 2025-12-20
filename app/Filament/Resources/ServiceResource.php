<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationGroup = 'Khách hàng & Đơn hàng';
    protected static ?string $navigationLabel = 'Dịch vụ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Khách hàng')
                    ->options(User::pluck('email', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->label('Sản phẩm')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('order_id')
                    ->label('Đơn hàng')
                    ->options(Order::pluck('id', 'id'))
                    ->searchable()
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending'    => 'Đang chờ kích hoạt',
                        'active'     => 'Kích hoạt',
                        'suspended'  => 'Tạm ngưng',
                        'terminated' => 'Đã chấm dứt',
                        'cancelled'  => 'Đã hủy',
                    ])
                    ->default('pending'),

                Forms\Components\Select::make('billing_cycle')
                    ->label('Chu kỳ thanh toán')
                    ->options([
                        'monthly'       => 'Tháng',
                        'quarterly'     => 'Quý',
                        'semiannually'  => '6 tháng',
                        'annually'      => 'Năm',
                    ]),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Ngày bắt đầu'),

                Forms\Components\DatePicker::make('next_due_date')
                    ->label('Ngày đến hạn'),

                Forms\Components\DatePicker::make('terminate_date')
                    ->label('Ngày kết thúc'),

                Forms\Components\keyValue::make('custom_fields')
                    ->label('Custom fields')
                    ->helperText('VD: domain, IP, username...'),

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
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Sản phẩm')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Chu kỳ'),
                Tables\Columns\TextColumn::make('next_due_date')
                    ->label('Đến hạn')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending'    => 'Đang chờ kích hoạt',
                        'active'     => 'Kích hoạt',
                        'suspended'  => 'Tạm ngưng',
                        'terminated' => 'Đã chấm dứt',
                        'cancelled'  => 'Đã hủy',
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
