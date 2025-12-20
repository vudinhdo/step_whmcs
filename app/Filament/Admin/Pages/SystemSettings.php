<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;

class SystemSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Cấu hình hệ thống';
    protected static ?string $navigationLabel = 'Cài đặt chung';

    protected static string $view = 'filament.pages.system-settings';

    public array $data = [];

    public function mount()
    {
        $this->form->fill(
            Setting::pluck('value', 'key')->toArray()
        );
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->tabs([
                    Tabs\Tab::make('Thông tin công ty')->schema([
                        TextInput::make('company_name')->label('Tên công ty'),
                        TextInput::make('company_website')->label('Website'),
                        TextInput::make('support_email')->label('Email hỗ trợ'),
                        TextInput::make('hotline')->label('Hotline'),
                        Textarea::make('company_address')->label('Địa chỉ'),
                    ]),

                    Tabs\Tab::make('Logo')->schema([
                        FileUpload::make('logo')
                            ->label('Logo chính')
                            ->directory('logos')
                            ->image(),

                        FileUpload::make('email_logo')
                            ->label('Logo email')
                            ->directory('logos')
                            ->image(),
                    ]),

                    Tabs\Tab::make('Hóa đơn')->schema([
                        TextInput::make('default_currency')
                            ->label('Tiền tệ mặc định')
                            ->default('VND'),

                        TextInput::make('vat_percent')
                            ->label('% VAT')
                            ->numeric(),
                    ]),

                    Tabs\Tab::make('Footer')->schema([
                        Textarea::make('footer_text')
                            ->label('Footer tùy chỉnh'),
                    ]),
                ]),
            Tabs::make('Email')->schema([
                TextInput::make('mail_host')->label('SMTP Host'),
                TextInput::make('mail_port')->label('SMTP Port')->numeric(),
                TextInput::make('mail_username')->label('SMTP Username'),
                TextInput::make('mail_password')->password()->label('SMTP Password'),
                TextInput::make('mail_encryption')->label('Encryption (tls/ssl)'),
                TextInput::make('mail_from_address')->label('From Email'),
                TextInput::make('mail_from_name')->label('From Name'),
            ]),

        ];
    }

    public function submit()
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->notify('success', 'Đã lưu cấu hình');
    }
}
