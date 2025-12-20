<?php

namespace App\Filament\Resources\AutomationLogResource\Pages;

use App\Filament\Resources\AutomationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAutomationLog extends EditRecord
{
    protected static string $resource = AutomationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
