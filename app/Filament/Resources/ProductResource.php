<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\PricingRulesRelationManager;

use App\Models\Product;
use App\Models\ProductGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sản phẩm';
    protected static ?string $navigationLabel = 'Sản phẩm';
    protected static ?string $pluralModelLabel = 'Sản phẩm';
    protected static ?string $modelLabel = 'Sản phẩm';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_group_id')
                    ->label('Nhóm sản phẩm')
                    ->options(ProductGroup::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(4),

                Forms\Components\Select::make('type')
                    ->label('Loại')
                    ->options([
                        'hosting' => 'Hosting',
                        'cloud' => 'Cloud',
                        'vps' => 'VPS',
                        'email' => 'Email',
                        'domain' => 'Domain',
                        'other' => 'Khác',
                    ])
                    ->default('other'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Kích hoạt')
                    ->default(true),

                Forms\Components\TextInput::make('provisioning_module')
                    ->label('Module provisioning')
                    ->helperText('Để trống nếu chưa dùng auto provisioning.'),

                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta title')
                    ->maxLength(255),

                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta description')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group.name')
                    ->label('Nhóm'),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Loại'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Kích hoạt'),
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
            PricingRulesRelationManager::class,
            RelationManagers\PricingRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
