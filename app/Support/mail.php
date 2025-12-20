<?php

use Illuminate\Support\Facades\Config;

if (! function_exists('applyMailSettings')) {
    function applyMailSettings(): void
    {
        Config::set('mail.default', 'smtp');

        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => setting('mail_host'),
            'port'       => (int) setting('mail_port', 587),
            'encryption' => setting('mail_encryption') ?: null,
            'username'   => setting('mail_username'),
            'password'   => setting('mail_password'),
            'timeout'    => null,
            'auth_mode'  => null,
        ]);

        Config::set('mail.from.address', setting('mail_from_address'));
        Config::set('mail.from.name', setting('mail_from_name', config('app.name')));
    }
}
