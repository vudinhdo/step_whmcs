<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AutomationLogResource\Pages;
use App\Models\AutomationLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class AutomationLogResource extends Resource
{
    protected static ?string $model = AutomationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Cấu hình';
    protected static ?string $navigationLabel = 'Automation logs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('type')
                ->label('Loại')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Mô tả')
                ->rows(3),

            Forms\Components\Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'success' => 'Success',
                    'failed'  => 'Failed',
                ])
                ->default('success'),

            Forms\Components\DateTimePicker::make('run_at')
                ->label('Chạy lúc')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('run_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả')
                    ->limit(60),
            ])
            ->defaultSort('run_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(), // chỉ xem, không cần sửa
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAutomationLogs::route('/'),
            'create' => Pages\CreateAutomationLog::route('/create'),
            'edit'   => Pages\EditAutomationLog::route('/{record}/edit'),
        ];
    }
}
