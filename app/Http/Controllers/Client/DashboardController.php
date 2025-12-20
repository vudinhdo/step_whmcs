<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'active_services' => Service::where('user_id', $userId)->where('status', 'active')->count(),
            'unpaid_invoices' => Invoice::where('user_id', $userId)->whereIn('status', ['unpaid','draft'])->count(),
            'pending_orders'  => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'open_tickets'    => Ticket::where('user_id', $userId)->whereIn('status', ['open','customer-reply','staff-reply'])->count(),
        ];

        $recent = [
            'invoices' => Invoice::where('user_id', $userId)->latest()->limit(5)->get(),
            'tickets'  => Ticket::where('user_id', $userId)->latest()->limit(5)->get(),
        ];

        return view('dashboard', compact('stats', 'recent'));
    }
}
