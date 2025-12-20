<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->subject('Hóa đơn mới #' . $invoice->id);
    }

    public function build()
    {
        return $this->view('emails.invoice-created')
            ->with(['invoice' => $this->invoice]);
    }
}
