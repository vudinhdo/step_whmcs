<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? setting('company_name') }}</title>
</head>
<body style="background-color:#f4f4f5; padding: 30px; font-family: Arial, sans-serif;">
<div style="
        max-width: 600px;
        margin: auto;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,.1);
    ">
    <div style="background: #1f2937; padding: 20px; text-align:center;">
        @if(setting('email_logo'))
            <img src="{{ asset('storage/' . setting('email_logo')) }}" alt="Logo" style="height:40px;">
        @else
            <h2 style="color:white; margin:0;">
                {{ setting('company_name', 'Company') }}
            </h2>
        @endif
    </div>

    <div style="padding: 30px;">
        {{ $slot }}
    </div>

    <div style="padding: 20px; background:#f9fafb; text-align:center; font-size:12px; color:#6b7280;">
        {{ setting('footer_text', 'Thank you for choosing us!') }}
    </div>
</div>
</body>
</html>
