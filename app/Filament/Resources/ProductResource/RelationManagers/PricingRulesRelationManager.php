<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PricingRulesRelationManager extends RelationManager
{
    protected static string $relationship = 'pricingRules';

    protected static ?string $title = 'Pricing Rules';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('key')
                ->label('Key')
                ->options([
                    'cpu' => 'CPU (vCPU)',
                    'ram' => 'RAM (GB)',
                    'storage' => 'Storage (GB)',
                    'gpu' => 'GPU (optional)',
                ])
                ->required()
                ->searchable(),

            Forms\Components\Select::make('billing_cycle')
                ->label('Billing cycle')
                ->options([
                    'monthly' => 'Monthly',
                    'annually' => 'Annually',
                ])
                ->default('monthly')
                ->required(),

            Forms\Components\TextInput::make('price_per_unit')
                ->label('Price per unit')
                ->numeric()
                ->default(0)
                ->required(),

            Forms\Components\TextInput::make('min')
                ->label('Min')
                ->numeric()
                ->nullable(),

            Forms\Components\TextInput::make('max')
                ->label('Max')
                ->numeric()
                ->nullable(),

            Forms\Components\TextInput::make('step')
                ->label('Step')
                ->numeric()
                ->default(1)
                ->required(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Key')->badge(),
                Tables\Columns\TextColumn::make('billing_cycle')->label('Cycle')->badge(),
                Tables\Columns\TextColumn::make('price_per_unit')->label('Price/unit')->numeric(2),
                Tables\Columns\TextColumn::make('min')->label('Min'),
                Tables\Columns\TextColumn::make('max')->label('Max'),
                Tables\Columns\TextColumn::make('step')->label('Step'),
                Tables\Columns\TextColumn::make('updated_at')->since()->label('Updated'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('key');
    }
}
