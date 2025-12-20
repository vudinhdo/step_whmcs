<!doctype html>
<html lang="vi">
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;background:#f4f4f5;padding:24px;">
<div style="max-width:640px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb;">
    <div style="background:#111827;color:#fff;padding:16px 20px;">
        <strong>{{ setting('company_name', config('app.name')) }}</strong>
    </div>

    <div style="padding:20px;color:#111827;font-size:14px;">
        <p style="margin:0 0 12px;">
            Ticket <strong>#{{ $ticket->id }}</strong> — {{ $ticket->subject }}
        </p>

        <p style="margin:0 0 12px;color:#374151;">
            Người trả lời: <strong>{{ $reply->user->email ?? 'N/A' }}</strong>
            @if($reply->is_staff) (Staff) @endif
        </p>

        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;white-space:pre-line;">
            {{ $reply->message }}
        </div>

        <p style="margin:16px 0 0;">
            @if($isForClient)
                Bạn có thể xem và trả lời tại:
                <a href="{{ route('tickets.show', $ticket) }}">mở ticket</a>.
            @else
                Bạn có thể xem trong admin hoặc trang ticket.
            @endif
        </p>

        <p style="margin:16px 0 0;color:#6b7280;font-size:12px;">
            {{ setting('footer_text', 'Cảm ơn bạn đã sử dụng dịch vụ.') }}
        </p>
    </div>
</div>
</body>
</html>
