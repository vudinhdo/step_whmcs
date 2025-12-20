<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Mail\TicketReplyMail;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';
    protected static ?string $title = 'Trao đổi';

    public function form(Form $form): Form
    {
        return $form->schema([
            // user_id sẽ tự set là staff hiện tại khi tạo reply
            Forms\Components\Textarea::make('message')
                ->label('Nội dung')
                ->rows(4)
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Người trả lời'),
                Tables\Columns\IconColumn::make('is_staff')
                    ->label('Staff')
                    ->boolean(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Nội dung')
                    ->limit(80),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        $data['is_staff'] = in_array(Auth::user()->role ?? 'client', ['admin', 'staff']);
                        return $data;
                    })
                    ->after(function ($record, RelationManager $livewire) {
                        // $record = TicketReply vừa tạo
                        $ticket = $livewire->getOwnerRecord(); // Ticket hiện tại

                        // staff trả lời -> set status
                        $ticket->update(['status' => 'answered']);

                        // gửi mail cho khách
                        $clientEmail = $ticket->user?->email;
                        if ($clientEmail) {
                            Mail::to($clientEmail)->send(new TicketReplyMail($ticket, $record, true));
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
