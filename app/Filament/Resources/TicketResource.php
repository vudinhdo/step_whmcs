<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\RepliesRelationManager;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationGroup = 'Support';
    protected static ?string $navigationLabel = 'Tickets';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Khách hàng')
                ->options(User::pluck('email', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('department_id')
                ->label('Phòng ban')
                ->options(TicketDepartment::pluck('name', 'id'))
                ->required(),

            Forms\Components\TextInput::make('subject')
                ->label('Chủ đề')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'open' => 'Open',
                    'answered' => 'Answered',
                    'customer-reply' => 'Customer reply',
                    'closed' => 'Closed',
                ])
                ->default('open'),

            Forms\Components\Select::make('priority')
                ->label('Độ ưu tiên')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ])
                ->default('medium'),

            Forms\Components\Select::make('assigned_to')
                ->label('Giao cho staff')
                ->options(
                    User::whereIn('role', ['admin', 'staff'])->pluck('email', 'id')
                )
                ->searchable()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Chủ đề')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Khách hàng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Phòng ban'),
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Ưu tiên'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->since(), // hiển thị "2 hours ago"
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
            RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
