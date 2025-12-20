<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketDepartmentResource\Pages;
use App\Models\TicketDepartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\tables\Table;
use Filament\Tables;

class TicketDepartmentResource extends Resource
{
    protected static ?string $model = TicketDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationGroup = 'Support';
    protected static ?string $navigationLabel = 'Phòng ban ticket';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Tên phòng ban')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email nhận ticket')
                ->email()
                ->maxLength(255)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('tickets_count')
                    ->counts('tickets')
                    ->label('Số ticket'),
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
            'index'  => Pages\ListTicketDepartments::route('/'),
            'create' => Pages\CreateTicketDepartment::route('/create'),
            'edit'   => Pages\EditTicketDepartment::route('/{record}/edit'),
        ];
    }
}
