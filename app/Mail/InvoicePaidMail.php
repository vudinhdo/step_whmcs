<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable
{
    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->subject('Xác nhận thanh toán cho hóa đơn #' . $invoice->id);
    }

    public function build()
    {
        return $this->view('emails.invoice-paid')
            ->with(['invoice' => $this->invoice]);
    }
}
