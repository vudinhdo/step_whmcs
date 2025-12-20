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
            {{ $isInternal ? 'Có đơn hàng mới được tạo.' : 'Cảm ơn bạn! Chúng tôi đã nhận được đơn hàng của bạn.' }}
        </p>

        <p style="margin:0 0 12px;">
            <strong>Đơn hàng:</strong> #{{ $order->id }}<br>
            <strong>Tổng tiền tạm tính:</strong> {{ number_format($order->total, 0, ',', '.') }} {{ $order->currency }}
        </p>

        <div style="margin:16px 0;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
            <strong>Chi tiết:</strong>
            <ul style="margin:8px 0 0 18px;">
                @foreach($order->items as $it)
                    <li>{{ $it->description }} (x{{ $it->quantity }})</li>
                @endforeach
            </ul>
        </div>

        @if(!empty($createdNewUser) && !empty($defaultPassword))
            <p style="margin:12px 0;">
                Chúng tôi đã tạo tài khoản Client Portal cho bạn:
                <br><b>Email:</b> {{ $order->guest_email ?? '' }}
                <br><b>Mật khẩu tạm:</b> {{ $defaultPassword }}
                <br><i>Lần đăng nhập đầu tiên hệ thống sẽ yêu cầu bạn đổi mật khẩu.</i>
            </p>
        @endif

        <p style="margin: 0 0 12px;">
            Bạn có thể xem trạng thái đơn tại đây:
            <a href="{{ route('checkout.thankyou', ['token' => $order->public_token]) }}">Xem đơn hàng</a>
        </p>


        <p style="margin:16px 0 0;color:#6b7280;font-size:12px;">
            {{ setting('footer_text', 'Cảm ơn bạn đã sử dụng dịch vụ.') }}
        </p>
    </div>
</div>
</body>
</html>
