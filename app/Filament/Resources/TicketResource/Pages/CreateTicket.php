<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // user đang login trong admin
        $data['created_by'] = Auth::id();

        // nếu bạn muốn mặc định user mở ticket là chính staff hiện tại
        // khi không chọn trong form (tùy bạn)
        if (empty($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        return $data;
    }
}
