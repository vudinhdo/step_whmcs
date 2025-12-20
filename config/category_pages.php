<?php

return [
    // cấu hình mặc định cho mọi danh mục
    'default' => [
        'theme' => [
            'accent' => 'indigo',
            'badge'  => 'Dịch vụ',
            'emoji'  => '✨',
        ],
        'hero' => [
            'title' => null, // null => dùng $group->name
            'subtitle' => 'Giải pháp hạ tầng phù hợp cho doanh nghiệp.',
            'cta_primary' => ['text' => 'Xem sản phẩm', 'route' => 'products.index', 'params' => []],
            'cta_secondary'=> ['text' => 'Liên hệ tư vấn', 'route' => 'contact', 'params' => []],
        ],
        'features' => [
            'title' => 'Lợi ích nổi bật',
            'items' => [
                ['title' => 'Triển khai nhanh', 'desc' => 'Tối ưu thời gian triển khai dự án.'],
                ['title' => 'Linh hoạt mở rộng', 'desc' => 'Scale theo nhu cầu thực tế.'],
                ['title' => 'Hỗ trợ kỹ thuật', 'desc' => 'Hỗ trợ qua ticket/email.'],
                ['title' => 'Bảo mật & Backup', 'desc' => 'Bảo vệ và khôi phục dữ liệu.'],
            ],
        ],
        'layout' => [
            'products_style' => 'cards', // cards | table | pricing
            'show_filters'   => true,
            'show_faq'       => true,
        ],
        'faq' => [
            ['q' => 'Tôi có thể nâng cấp gói sau khi mua không?', 'a' => 'Có. Bạn có thể nâng cấp cấu hình theo nhu cầu.'],
            ['q' => 'Có hỗ trợ kỹ thuật không?', 'a' => 'Có. Chúng tôi hỗ trợ theo ticket và email.'],
        ],
    ],

    // chỉ override những phần khác default
    'cloud' => [
        'theme' => [
            'accent' => 'blue',
            'badge'  => 'Cloud Infrastructure',
            'emoji'  => '☁️',
        ],
        'hero' => [
            'title' => 'Cloud Server cho doanh nghiệp',
            'subtitle' => 'Tuỳ chọn CPU/RAM/SSD. Giá dự tính realtime. Thanh toán linh hoạt.',
        ],
        'features' => [
            'title' => 'Tại sao chọn Cloud',
            'items' => [
                ['title' => 'Pay-as-you-go', 'desc' => 'Chi phí theo nhu cầu sử dụng.'],
                ['title' => 'Auto-scale', 'desc' => 'Mở rộng tài nguyên linh hoạt.'],
                ['title' => 'Backup tự động', 'desc' => 'Snapshot/backup định kỳ.'],
                ['title' => 'SLA rõ ràng', 'desc' => 'Cam kết vận hành ổn định.'],
            ],
        ],
        'layout' => [
            'products_style' => 'pricing',
        ],
        'faq' => [
            ['q' => 'Cloud có chọn cấu hình được không?', 'a' => 'Có. Bạn có thể chọn CPU/RAM/SSD và hệ thống sẽ tính giá dự tính.'],
            ['q' => 'Có hỗ trợ chuyển dữ liệu không?', 'a' => 'Có. Hỗ trợ theo nhu cầu dự án.'],
        ],
        'presets' => [
            [
                'key' => 'starter',
                'name' => 'Starter',
                'desc' => 'Cho website/ứng dụng nhỏ',
                'config' => ['cpu' => 2, 'ram' => 4, 'storage' => 40],
                'badge' => 'Phổ biến',
            ],
            [
                'key' => 'business',
                'name' => 'Business',
                'desc' => 'Cho doanh nghiệp vừa',
                'config' => ['cpu' => 4, 'ram' => 8, 'storage' => 80],
                'badge' => null,
            ],
            [
                'key' => 'enterprise',
                'name' => 'Enterprise',
                'desc' => 'Tải cao / nhiều dịch vụ',
                'config' => ['cpu' => 8, 'ram' => 16, 'storage' => 160],
                'badge' => 'Hiệu năng',
            ],
        ],
    ],

    'email-hybrid' => [
        'theme' => [
            'accent' => 'purple',
            'badge'  => 'Email Enterprise',
            'emoji'  => '✉️',
        ],
        'hero' => [
            'title' => 'Email Hybrid doanh nghiệp',
            'subtitle' => 'Chống spam mạnh, bảo mật MFA, tích hợp AD/Outlook, lưu trữ mail.',
        ],
        'layout' => [
            'products_style' => 'table',
            'show_filters'   => false,
        ],
    ],


];
