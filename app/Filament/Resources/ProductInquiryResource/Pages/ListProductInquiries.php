<?php

namespace App\Filament\Resources\ProductInquiryResource\Pages;

use App\Filament\Resources\ProductInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductInquiries extends ListRecords
{
    protected static string $resource = ProductInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
