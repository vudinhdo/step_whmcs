<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $services = Service::with('product')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.services.index', compact('services'));
    }

    public function show(Service $service): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        // Chặn user khác xem dịch vụ không phải của mình
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->load('product', 'order', 'invoiceItems.invoice');

        return view('client.services.show', compact('service'));
    }
}
