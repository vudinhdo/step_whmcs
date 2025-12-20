<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dịch vụ đã kích hoạt</title>
</head>
<body style="background:#f4f4f5; padding:30px; font-family:Arial, sans-serif;">
<div style="max-width:600px;margin:auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,.1);">
    <div style="background:#1f2937;padding:20px;text-align:center;">
        <h2 style="color:#ffffff;margin:0;">
            {{ setting('company_name', 'Dịch vụ hạ tầng') }}
        </h2>
    </div>

    <div style="padding:24px;font-size:14px;color:#111827;">
        <p>Xin chào {{ $service->user->name }},</p>

        <p>Dịch vụ của bạn đã được <strong>kích hoạt thành công</strong>:</p>

        <ul>
            <li><strong>ID dịch vụ:</strong> #{{ $service->id }}</li>
            <li><strong>Sản phẩm:</strong> {{ $service->product->name ?? 'N/A' }}</li>
            <li><strong>Trạng thái:</strong> {{ ucfirst($service->status) }}</li>
            @if($service->start_date)
                <li><strong>Ngày bắt đầu:</strong> {{ $service->start_date->format('d/m/Y') }}</li>
            @endif
            @if($service->next_due_date)
                <li><strong>Ngày đến hạn tiếp theo:</strong> {{ $service->next_due_date->format('d/m/Y') }}</li>
            @endif
            <li><strong>Chu kỳ thanh toán:</strong> {{ $service->billing_cycle }}</li>
        </ul>

        @if(is_array($service->custom_fields) && count($service->custom_fields))
            <p>Các thông tin kỹ thuật:</p>
            <ul>
                @foreach($service->custom_fields as $key => $value)
                    <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        @endif

        <p>
            Bạn có thể xem chi tiết dịch vụ tại:
            <a href="{{ route('client.services.show', $service) }}">
                trang quản lý dịch vụ
            </a>.
        </p>

        <p>Trân trọng,<br>
            {{ setting('company_name', 'Đội ngũ hỗ trợ') }}
        </p>
    </div>

    <div style="padding:16px;background:#f9fafb;text-align:center;font-size:12px;color:#6b7280;">
        {{ setting('footer_text', 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.') }}
    </div>
</div>
</body>
</html>
