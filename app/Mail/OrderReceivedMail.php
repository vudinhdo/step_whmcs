<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public bool $isInternal = false,
        public bool $isForSupport = false,
        public bool $createdNewUser = false,
        public ?string $defaultPassword = null,

    ) {
        $this->subject(($isInternal ? '[INTERNAL] ' : '') . 'Đã nhận đơn hàng #' . $order->id);
    }

    public function build(): OrderReceivedMail
    {
        return $this->subject('Xác nhận đơn hàng #' . $this->order->id)
            ->view('emails.order-received')
            ->with([
                'order' => $this->order,
                'isForSupport' => $this->isForSupport,
                'createdNewUser' => $this->createdNewUser,
                'defaultPassword' => $this->defaultPassword,
            ]);
    }
}
